<?php

namespace Icmbio\Json2html;

use DOMElement;

class VerticalRenderer extends AbstractRenderer
{
    public function render(array $headers, array $data): DOMElement
    {
        $table = $this->dom->createElement('table');

        if (!empty($data)) {
            $tbody = $this->dom->createElement('tbody');

            if ($this->isMultidimensionalArray($data)) {
                if ($this->shouldTranspose($data)) {
                    // For parallel arrays, transpose and render vertically
                    $transposedData = array_map(null, ...$data);
                    
                    // Each header becomes a row
                    foreach ($headers as $index => $header) {
                        $row = $this->dom->createElement('tr');
                        
                        // First column: header
                        $headerTd = $this->dom->createElement('td');
                        $headerTd->textContent = (string)$header;
                        $row->appendChild($headerTd);
                        
                        // Subsequent columns: data
                        foreach ($transposedData as $dataRow) {
                            $dataTd = $this->dom->createElement('td', (string)($dataRow[$index] ?? ''));
                            $row->appendChild($dataTd);
                        }
                        
                        $tbody->appendChild($row);
                    }
                } else {
                    // Check if this is an array of objects that should be rendered as separate rows
                    if ($this->isArrayOfObjectsForTable($data)) {
                        // Each object gets its own row(s) with each field as a separate row
                        foreach ($data as $objectData) {
                            foreach ($headers as $header) {
                                $row = $this->dom->createElement('tr');
                                
                                // First column: header
                                $headerTd = $this->dom->createElement('td');
                                $headerTd->textContent = (string)$header;
                                $row->appendChild($headerTd);
                                
                                // Second column: data
                                $dataTd = $this->dom->createElement('td', (string)($objectData[$header] ?? ''));
                                $row->appendChild($dataTd);
                                
                                $tbody->appendChild($row);
                            }
                        }
                    } else {
                        // Handle mixed structure with nested tables
                        foreach ($headers as $index => $header) {
                            $row = $this->dom->createElement('tr');
                            
                            // First column: header
                            $headerTd = $this->dom->createElement('td');
                            $headerTd->textContent = (string)$header;
                            $row->appendChild($headerTd);
                            
                            // Second column: data
                            $dataTd = $this->dom->createElement('td');
                            $item = $data[$index] ?? null;
                            
                            if (is_array($item)) {
                                // Create nested table with configured orientation
                                $nestedTable = $this->createNestedTable($item);
                                $dataTd->appendChild($nestedTable);
                            } else {
                                // Simple text content for non-array data
                                $dataTd->textContent = (string)$item;
                            }
                            
                            $row->appendChild($dataTd);
                            $tbody->appendChild($row);
                        }
                    }
                }
            } else {
                // Simple one-dimensional array - each header in a row
                foreach ($headers as $index => $header) {
                    $row = $this->dom->createElement('tr');
                    
                    // First column: header
                    $headerTd = $this->dom->createElement('td');
                    $headerTd->textContent = (string)$header;
                    $row->appendChild($headerTd);
                    
                    // Second column: data
                    $dataTd = $this->dom->createElement('td', (string)($data[$index] ?? ''));
                    $row->appendChild($dataTd);
                    
                    $tbody->appendChild($row);
                }
            }

            $table->appendChild($tbody);
        }

        return $table;
    }

    private function isArrayOfObjectsForTable(array $data): bool
    {
        // Check if we have an array where each element is an associative array with the same keys
        if (empty($data)) {
            return false;
        }
        
        foreach ($data as $item) {
            if (!is_array($item) || is_numeric(key($item))) {
                return false;
            }
        }
        
        return true;
    }

    private function createNestedTable(array $data): DOMElement
    {
        // Handle array of associative arrays (like the test case)
        if ($this->isArrayOfObjectsForTable($data)) {
            $headers = array_keys($data[0]);
            $nestedRenderer = $this->createNestedRenderer();
            return $nestedRenderer->render($headers, $data);
        }
        
        // Handle other types of arrays (fallback)
        $nestedRenderer = $this->createNestedRenderer();
        return $nestedRenderer->render([], $data);
    }
}