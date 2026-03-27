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

        // var_dump($this->isMultidimensionalArray($this->datasetList));
        // die();

        if ($this->isMultidimensionalArray($this->datasetList)) {
            $this->datasetList = array_map(null, ...$this->datasetList);

            if ($this->datasetList != []) {
                $tbody = $this->dom->createElement('tbody');
                $bodyRow = $this->dom->createElement('tr');

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

