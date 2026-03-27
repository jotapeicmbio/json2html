<?php

namespace Icmbio\Json2html;

use DOMDocument;
use DOMElement;

class RenderTable
{
    protected DOMDocument $dom;
    protected DOMElement $table;
    protected array $datasetHeaders = [];
    protected array $datasetList = [];
    protected string $position = 'vertical';

    public function __construct(?array $dataset = null)
    {
        $this->dom = new DOMDocument('1.0', 'utf-8');

        if (!is_null($dataset)) {
            $this->datasetHeaders = array_keys($dataset);
            $this->datasetList = array_values($dataset);
        }

        $this->table = $this->dom->createElement('table');
    }

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
        return array_keys($firstArray) === range(0, count($firstArray) - 1);
    }

    protected function createNestedTable(array $data): \DOMElement
    {
        $nestedTable = $this->dom->createElement('table');
        
        // Extract headers from the first row if data is not empty
        if (!empty($data) && is_array($data[0])) {
            $headers = array_keys($data[0]);
            $thead = $this->dom->createElement('thead');
            $headerRow = $this->dom->createElement('tr');

            foreach ($headers as $header) {
                $th = $this->dom->createElement('th', $header);
                $headerRow->appendChild($th);
            }

            $thead->appendChild($headerRow);
            $nestedTable->appendChild($thead);
        }

        // Create tbody
        $tbody = $this->dom->createElement('tbody');

        foreach ($data as $row) {
            $bodyRow = $this->dom->createElement('tr');
            
            if (is_array($row)) {
                foreach ($row as $value) {
                    $td = $this->dom->createElement('td', (string)$value);
                    $bodyRow->appendChild($td);
                }
            }
            
            $tbody->appendChild($bodyRow);
        }

        $nestedTable->appendChild($tbody);
        return $nestedTable;
    }

    public function make(array $dataset): self
    {
        return $this;
    }

    public function titles(array $headers): self
    {
        $this->datasetHeaders = $headers;
        return $this;
    }

    public function body(array $dataset): self
    {
        $this->datasetList = $dataset;
        return $this;
    }

    public function config(array $set): self
    {

    }

    public function render(): string
    {
        if ($this->datasetHeaders != []) {
            $thead = $this->dom->createElement('thead');
            $headerRow = $this->dom->createElement('tr');

            foreach ($this->datasetHeaders as $title) {
                $th = $this->dom->createElement('th', $title);
                $headerRow->appendChild($th);
            }

            $thead->appendChild($headerRow);
            $this->table->appendChild($thead);
        }

        if ($this->isMultidimensionalArray($this->datasetList)) {
            if ($this->shouldTranspose($this->datasetList)) {
                // Original transpose logic for parallel arrays  
                $this->datasetList = array_map(null, ...$this->datasetList);
                
                if ($this->datasetList != []) {
                    $tbody = $this->dom->createElement('tbody');

                    foreach ($this->datasetList as $items) {
                        $bodyRow = $this->dom->createElement('tr');
                        foreach ($items as $item) {
                            $td = $this->dom->createElement('td', $item);
                            $bodyRow->appendChild($td);
                        }
                        $tbody->appendChild($bodyRow);
                    }

                    $this->table->appendChild($tbody);
                }
            } else {
                // Handle mixed structure with nested tables
                if ($this->datasetList != []) {
                    $tbody = $this->dom->createElement('tbody');
                    $bodyRow = $this->dom->createElement('tr');

                    foreach ($this->datasetList as $item) {
                        $td = $this->dom->createElement('td');
                        
                        if (is_array($item)) {
                            // Create nested table for array data
                            $nestedTable = $this->createNestedTable($item);
                            $td->appendChild($nestedTable);
                        } else {
                            // Simple text content for non-array data
                            $td->textContent = $item;
                        }
                        
                        $bodyRow->appendChild($td);
                    }

                    $tbody->appendChild($bodyRow);
                    $this->table->appendChild($tbody);
                }
            }
        } else {
            if ($this->datasetList != []) {
                $tbody = $this->dom->createElement('tbody');
                $bodyRow = $this->dom->createElement('tr');
    
                foreach ($this->datasetList as $item) {
                    $td = $this->dom->createElement('td', $item);
                    $bodyRow->appendChild($td);
                }
    
                $tbody->appendChild($bodyRow);
                $this->table->appendChild($tbody);
            }
        }


        $this->dom->appendChild($this->table);
        return $this->dom->saveHTML($this->table);
    }
}

