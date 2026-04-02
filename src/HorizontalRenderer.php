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
                                $td = $this->dom->createElement('td');
                                $this->appendValueToCell($td, $value);
                                $bodyRow->appendChild($td);
                            }
                            $tbody->appendChild($bodyRow);
                        }
                    } else {
                        // Handle mixed structure with nested tables
                        $bodyRow = $this->dom->createElement('tr');

                        foreach ($data as $item) {
                            $td = $this->dom->createElement('td');
                            $this->appendValueToCell($td, $item);
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
}
