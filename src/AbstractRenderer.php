<?php

namespace Icmbio\Json2html;

use DOMDocument;

abstract class AbstractRenderer
{
    protected DOMDocument $dom;
    protected array $config;

    public function __construct(DOMDocument $dom, array $config = [])
    {
        $this->dom = $dom;
        $this->config = $config;
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
        
        return match ($nestedOrientation) {
            TableOrientation::HORIZONTAL => new HorizontalRenderer($this->dom, $this->config),
            TableOrientation::VERTICAL => new VerticalRenderer($this->dom, $this->config),
        };
    }
}