<?php

namespace Icmbio\Json2html;

use DOMElement;

class HorizontalRenderer extends AbstractRenderer
{
    public function render(array $headers, array $data): DOMElement
    {
        $table = $this->dom->createElement('table');

        // Create headers (thead)
        if (!empty($headers)) {
            $thead = $this->dom->createElement('thead');
            $headerRow = $this->dom->createElement('tr');

            foreach ($headers as $title) {
                $th = $this->dom->createElement('th', (string)$title);
                $headerRow->appendChild($th);
            }

            $thead->appendChild($headerRow);
            $table->appendChild($thead);
        }

        // Create body (tbody)
        if (!empty($data)) {
            $tbody = $this->dom->createElement('tbody');

            if ($this->isMultidimensionalArray($data)) {
                if ($this->shouldTranspose($data)) {
                    // Original transpose logic for parallel arrays  
                    $transposedData = array_map(null, ...$data);
                    
                    foreach ($transposedData as $items) {
                        $bodyRow = $this->dom->createElement('tr');
                        foreach ($items as $item) {
                            $td = $this->dom->createElement('td', (string)$item);
                            $bodyRow->appendChild($td);
                        }
                        $tbody->appendChild($bodyRow);
                    }
                } else {
                    // Check if this is an array of objects that should be rendered as table rows
                    if ($this->isArrayOfObjectsForTable($data)) {
                        // Render as table rows, not nested tables
                        foreach ($data as $row) {
                            $bodyRow = $this->dom->createElement('tr');
                            foreach ($headers as $header) {
                                $value = $row[$header] ?? '';
                                $td = $this->dom->createElement('td', (string)$value);
                                $bodyRow->appendChild($td);
                            }
                            $tbody->appendChild($bodyRow);
                        }
                    } else {
                        // Handle mixed structure with nested tables
                        $bodyRow = $this->dom->createElement('tr');

                        foreach ($data as $item) {
                            $td = $this->dom->createElement('td');
                            
                            if (is_array($item)) {
                                // Create nested table with configured orientation
                                $nestedTable = $this->createNestedTable($item);
                                $td->appendChild($nestedTable);
                            } else {
                                // Simple text content for non-array data
                                $td->textContent = (string)$item;
                            }
                            
                            $bodyRow->appendChild($td);
                        }

                        $tbody->appendChild($bodyRow);
                    }
                }
            } else {
                // Simple one-dimensional array
                $bodyRow = $this->dom->createElement('tr');
                
                foreach ($data as $item) {
                    $td = $this->dom->createElement('td', (string)$item);
                    $bodyRow->appendChild($td);
                }
                
                $tbody->appendChild($bodyRow);
            }

            $table->appendChild($tbody);
        }

        // Apply attributes to the table
        $this->applyAttributes($table);

        return $table;
    }

    private function createNestedTable(array $data): DOMElement
    {
        // Handle array of associative arrays (like the test case)
        if ($this->isArrayOfObjects($data)) {
            $headers = array_keys($data[0]);
            $nestedRenderer = $this->createNestedRenderer();
            return $nestedRenderer->render($headers, $data);
        }
        
        // Handle other types of arrays (fallback)
        $nestedRenderer = $this->createNestedRenderer();
        return $nestedRenderer->render([], $data);
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

    private function isArrayOfObjects(array $data): bool
    {
        // Check if we have a non-empty array where the first element is an associative array
        if (empty($data) || !is_array($data[0] ?? null)) {
            return false;
        }
        
        // Check if the first element has non-numeric keys (associative array)
        $firstElement = $data[0];
        return !empty($firstElement) && !is_numeric(key($firstElement));
    }
}