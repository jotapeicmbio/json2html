<?php

namespace Icmbio\Json2html;

use DOMDocument;
use DOMElement;

abstract class AbstractRenderer
{
    protected DOMDocument $dom;
    protected array $config;
    protected array $attributes;

    public function __construct(DOMDocument $dom, array $config = [], array $attributes = [])
    {
        $this->dom = $dom;
        $this->config = $config;
        $this->attributes = $attributes;
    }

    abstract public function render(array $headers, array $data): \DOMElement;

    protected function isMultidimensionalArray(array $array): bool
    {
        return count(array_filter($array, 'is_array')) > 0;
    }

    protected function shouldTranspose(array $array): bool
    {
        // Check if all values are arrays and have numeric keys (parallel arrays)
        $arrayValues = array_filter($array, 'is_array');
        if (count($arrayValues) !== count($array)) {
            return false; // Mix of arrays and non-arrays - use nested tables
        }
        
        // Check if first array has numeric keys (list/vector)
        $firstArray = reset($arrayValues);
        $hasNumericKeys = array_keys($firstArray) === range(0, count($firstArray) - 1);
        
        // Additional check: if the array contains associative arrays (objects), don't transpose
        if ($hasNumericKeys) {
            // Check if elements inside the first array are associative arrays  
            foreach ($firstArray as $item) {
                if (is_array($item) && !empty($item) && !is_numeric(key($item))) {
                    return false; // This is an array of objects, not parallel arrays
                }
            }
        }
        
        return $hasNumericKeys;
    }

    protected function getNestedOrientation(): TableOrientation
    {
        return $this->config['nested'] ?? TableOrientation::HORIZONTAL;
    }

    protected function createNestedRenderer(): AbstractRenderer
    {
        $nestedOrientation = $this->getNestedOrientation();
        
        // Para nested renderers, os atributos 'nested' se tornam 'root'
        $nestedAttributes = [
            'root' => $this->attributes['nested'] ?? [],
            'nested' => $this->attributes['nested'] ?? []
        ];
        
        return match ($nestedOrientation) {
            TableOrientation::HORIZONTAL => new HorizontalRenderer($this->dom, $this->config, $nestedAttributes),
            TableOrientation::VERTICAL => new VerticalRenderer($this->dom, $this->config, $nestedAttributes),
        };
    }

    protected function applyAttributes(\DOMElement $table, bool $isNested = false): void
    {
        $attributeSet = $isNested ? $this->attributes['nested'] ?? [] : $this->attributes['root'] ?? [];
        
        foreach ($attributeSet as $name => $value) {
            if ($name === 'class' && is_array($value)) {
                if (!empty($value)) {
                    $table->setAttribute('class', implode(' ', $value));
                }
            } elseif (!empty($value) && $value !== null) {
                $table->setAttribute($name, (string)$value);
            }
        }
    }

    protected function appendValueToCell(DOMElement $cell, mixed $value): void
    {
        if (is_array($value)) {
            $cell->appendChild($this->createNestedTable($value));
            return;
        }

        $cell->textContent = (string)$value;
    }

    protected function createNestedTable(array $data): DOMElement
    {
        $nestedRenderer = $this->createNestedRenderer();

        if ($this->isAssociativeArray($data)) {
            return $nestedRenderer->render(array_keys($data), array_values($data));
        }

        if ($this->isArrayOfObjectsForTable($data)) {
            $headers = array_keys($data[0]);
            return $nestedRenderer->render($headers, $data);
        }

        return $nestedRenderer->render([], $data);
    }

    protected function isArrayOfObjectsForTable(array $data): bool
    {
        if (empty($data)) {
            return false;
        }

        foreach ($data as $item) {
            if (!is_array($item) || $this->isAssociativeArray($item) === false) {
                return false;
            }
        }

        return true;
    }

    protected function isAssociativeArray(array $data): bool
    {
        if ($data === []) {
            return false;
        }

        return array_keys($data) !== range(0, count($data) - 1);
    }
}
