<?php

namespace Icmbio\Json2html;

use DOMDocument;
use DOMElement;

class RenderTable implements RenderTableInterface
{
    protected DOMDocument $dom;
    protected DOMElement $table;
    protected array $datasetHeaders = [];
    protected array $datasetList = [];
    protected array $config = [];
    protected array $attributes = [
        'root' => [],
        'nested' => []
    ];
    protected AbstractRenderer $renderer;
    protected NestedArrayRenderStrategyInterface $nestedArrayStrategy;

    public function __construct(?array $dataset = null)
    {
        $this->dom = new DOMDocument('1.0', 'utf-8');

        if (!is_null($dataset)) {
            $this->datasetHeaders = array_keys($dataset);
            $this->datasetList = array_values($dataset);
        }

        $this->table = $this->dom->createElement('table');
        
        // Default to horizontal orientation for backwards compatibility
        $this->config = [
            'orientation' => TableOrientation::HORIZONTAL,
            'nested' => TableOrientation::HORIZONTAL
        ];
        $this->nestedArrayStrategy = new AggregatedNestedArrayStrategy();
        
        $this->updateRenderer();
    }

    protected function updateRenderer(): void
    {
        $orientation = $this->config['orientation'] ?? TableOrientation::HORIZONTAL;
        
        $this->renderer = match ($orientation) {
            TableOrientation::HORIZONTAL => new HorizontalRenderer($this->dom, $this->config, $this->attributes, $this->nestedArrayStrategy),
            TableOrientation::VERTICAL => new VerticalRenderer($this->dom, $this->config, $this->attributes, $this->nestedArrayStrategy),
        };
    }

    public static function make(array $dataset): static
    {
        $instance = new static($dataset);
        $instance->boot();
        return $instance;
    }

    protected function boot(): void
    {
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
        $this->config = array_merge($this->config, $set);
        $this->updateRenderer();
        return $this;
    }

    public function nestedArrayStrategy(NestedArrayRenderStrategyInterface $strategy): self
    {
        $this->nestedArrayStrategy = $strategy;
        $this->updateRenderer();
        return $this;
    }

    public function tableClass(string $class, bool $nested = true): self
    {
        $this->attributes['root']['class'][] = $class;
        if ($nested) {
            $this->attributes['nested']['class'][] = $class;
        }
        $this->updateRenderer();
        return $this;
    }

    public function tableId(string $id, bool $nested = false): self
    {
        $this->attributes['root']['id'] = $id;
        if ($nested) {
            $this->attributes['nested']['id'] = $id;
        }
        $this->updateRenderer();
        return $this;
    }

    public function tableBorder(int $border, bool $nested = true): self
    {
        $this->attributes['root']['border'] = $border;
        if ($nested) {
            $this->attributes['nested']['border'] = $border;
        }
        $this->updateRenderer();
        return $this;
    }

    public function tableAttribute(string $name, string $value, bool $nested = true): self
    {
        $this->attributes['root'][$name] = $value;
        if ($nested) {
            $this->attributes['nested'][$name] = $value;
        }
        $this->updateRenderer();
        return $this;
    }

    public function render(): string
    {
        $table = $this->renderer->render($this->datasetHeaders, $this->datasetList);
        $this->dom->appendChild($table);
        return $this->dom->saveHTML($table);
    }
}
