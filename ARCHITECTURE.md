# json2html - Documentação da Arquitetura

## Visão Geral

O `json2html` é uma biblioteca PHP que implementa o **Strategy Pattern** para converter estruturas de dados JSON/Array em tabelas HTML flexíveis. Desenvolvida especificamente para as necessidades do ICMBio em visualização de dados ambientais e de biodiversidade.

## Diagrama da Arquitetura

```
┌─────────────────────┐
│   RenderTable       │ ← Classe principal (Facade)
│   ---------------   │
│   + render()        │
│   + config()        │
│   + tableClass()    │
│   + tableId()       │
└─────────┬───────────┘
          │
          │ uses
          ▼
┌─────────────────────┐
│ RenderTableInterface│ ← Contrato da API
└─────────┬───────────┘
          │
          │ implements
          ▼
┌─────────────────────┐    composition    ┌─────────────────────┐
│   RenderTable       │◄─────────────────►│ AbstractRenderer    │
│   ---------------   │                  │ ---------------     │
│   - renderer        │                  │ + render()          │
│   - updateRenderer()│                  │ # isMulti...Array() │
└─────────────────────┘                  └─────────┬───────────┘
                                                   │
                                                   │ extends
                                 ┌─────────────────┼─────────────────┐
                                 ▼                 ▼                 ▼
                    ┌─────────────────────┐ ┌─────────────────────┐
                    │ HorizontalRenderer  │ │  VerticalRenderer   │
                    │ ---------------     │ │ ---------------     │
                    │ + render()          │ │ + render()          │
                    │ - createHeaders()   │ │ - shouldTranspose() │
                    │ - createBody()      │ │ - renderVertical()  │
                    └─────────────────────┘ └─────────────────────┘
```

## Padrões de Design Implementados

### 1. Strategy Pattern
- **Context**: `RenderTable`
- **Strategy**: `AbstractRenderer`
- **Concrete Strategies**: `HorizontalRenderer`, `VerticalRenderer`

**Benefícios**:
- Permite trocar algoritmos de renderização em runtime
- Facilita adição de novos tipos de renderização
- Mantém o código da classe principal limpo

```php
// Seleção da estratégia baseada em configuração
$this->renderer = match ($orientation) {
    TableOrientation::HORIZONTAL => new HorizontalRenderer($this->dom, $this->config, $this->attributes),
    TableOrientation::VERTICAL => new VerticalRenderer($this->dom, $this->config, $this->attributes),
};
```

### 2. Facade Pattern
- **Facade**: `RenderTable`
- **Subsystem**: Renderizadores, DOMDocument, configurações

**Benefícios**:
- API simplificada para o cliente
- Encapsula complexidade da manipulação DOM
- Centralizaconfiguração e estado

### 3. Fluent Interface (Method Chaining)
```php
$table = (new RenderTable($data))
    ->tableClass('table table-bordered')
    ->tableId('report')
    ->tableBorder(1)
    ->render();
```

## Estrutura de Classes Detalhada

### RenderTable (Facade Principal)

**Responsabilidades**:
- Gerenciar configuração e atributos
- Coordenar renderização através das estratégias
- Implementar API fluente
- Controlar aplicação de atributos em tabelas aninhadas

**Atributos principais**:
```php
protected DOMDocument $dom;              // Manipulador HTML
protected DOMElement $table;             // Elemento raiz da tabela
protected array $datasetHeaders = [];   // Cabeçalhos extraídos
protected array $datasetList = [];      // Dados para renderização
protected array $config = [];           // Configurações (orientação etc)
protected array $attributes = [         // Atributos HTML organizados
    'root' => [],                       // Apenas tabela principal
    'nested' => []                      // Todas as tabelas
];
protected AbstractRenderer $renderer;   // Estratégia atual
```

### AbstractRenderer (Strategy Base)

**Responsabilidades**:
- Definir interface comum para renderizadores
- Fornecer métodos auxiliares compartilhados
- Abstração para manipulação DOM

**Métodos principais**:
```php
abstract public function render(array $headers, array $data): DOMElement;
protected function isMultidimensionalArray(array $array): bool;
protected function applyAttributes(DOMElement $table, string $scope): void;
```

### HorizontalRenderer (Strategy Concreta)

**Layout gerado**:
```html
<table>
    <thead><tr><th>Col1</th><th>Col2</th></tr></thead>
    <tbody>
        <tr><td>Data1</td><td>Data2</td></tr>
    </tbody>
</table>
```

**Características**:
- Headers como primeira linha (thead)
- Dados em linhas subsequentes (tbody)
- Suporte a tabelas aninhadas recursivas
- Compatibilidade com arrays paralelos

### VerticalRenderer (Strategy Concreta)

**Layout gerado**:
```html
<table>
    <tbody>
        <tr><td>Col1</td><td>Data1</td></tr>
        <tr><td>Col2</td><td>Data2</td></tr>
    </tbody>
</table>
```

**Características**:
- Headers como primeira coluna
- Dados nas colunas seguintes
- Transposição automática para arrays paralelos
- Renderização por linhas (field-by-field)

## Fluxo de Dados

### 1. Inicialização
```php
$table = new RenderTable($dataset);
```
- Extrai headers com `array_keys()`
- Extrai values com `array_values()`
- Configura orientação padrão (HORIZONTAL)
- Instancia renderer apropriado

### 2. Configuração (Opcional)
```php
$table->config(['orientation' => TableOrientation::VERTICAL])
      ->tableClass('table')
      ->tableId('report');
```
- Atualiza `$config` array
- Adiciona atributos aos arrays `root`/`nested`
- Re-instancia renderer se orientação mudou

### 3. Renderização
```php
echo $table->render();
```
- Delega para `$renderer->render($headers, $data)`
- Renderer cria estrutura DOM recursivamente
- Aplica atributos configurados
- Retorna HTML final via `$dom->saveHTML()`

### 4. Processamento de Arrays Multidimensionais
```
Input: ["Monitor" => [["Nome" => "João"], ["Nome" => "Maria"]]]

┌─► Detecta array multidimensional
│   └─► Cria nova instância RenderTable para subarray  
│       └─► Renderiza recursivamente
│           └─► Insere como conteúdo de célula <td>
└─► Resultado: <td><table>...</table></td>
```

## Sistema de Atributos HTML

### Arquitetura de Atributos
```php
$attributes = [
    'root' => [        // Aplicados apenas na tabela principal
        'id' => 'main-table',
        'data-source' => 'api'
    ],
    'nested' => [      // Aplicados em todas as tabelas (incluindo aninhadas)
        'class' => 'table table-bordered',
        'border' => '1'
    ]
];
```

### Padrões de Aplicação por Tipo
| Atributo | Scope Padrão | Razão |
|----------|--------------|-------|
| `class` | `nested: true` | Classes CSS geralmente devem ser mantidas consistentes |
| `id` | `nested: false` | IDs devem ser únicos no documento |
| `border` | `nested: true` | Borders visuais aplicam-se a estrutura completa |
| Customizados | `nested: true` | Comportamento mais útil na maioria dos casos |

### Implementação da Aplicação
```php
protected function applyAttributes(DOMElement $table, string $scope): void
{
    foreach ($this->attributes[$scope] as $name => $value) {
        if ($name === 'class') {
            // Acumular múltiplas classes
            $existing = $table->getAttribute('class');
            $table->setAttribute('class', trim($existing . ' ' . $value));
        } else {
            $table->setAttribute($name, $value);
        }
    }
}
```

## Casos de Uso e Cenários

### Cenário 1: Dados Simples (Relatório Básico)
```php
$relatorio = [
    "total_especies" => 45,
    "area_monitorada_km2" => 1250,
    "periodo" => "Jan-Mar 2026"
];
// Resultado: Tabela horizontal simples 1x3
```

### Cenário 2: Lista de Monitores (Array de Objetos)
```php
$monitores = [
    "monitores_campo" => [
        ["nome" => "João Silva", "especialidade" => "Mamíferos"],
        ["nome" => "Maria Santos", "especialidade" => "Aves"],
        ["nome" => "Pedro Costa", "especialidade" => "Repteis"]
    ]
];
// Resultado: Tabela com subtabela aninhada 3x2
```

### Cenário 3: Dados Hierárquicos Complexos (Levantamento Completo)
```php
$levantamento = [
    "unidade_conservacao" => "PARNA Serra da Bodoquena",
    "coordenador" => "Dr. Ana Paula",
    "observacoes" => [
        [
            "data" => "2026-03-15",
            "coordenadas" => "-20.1234, -56.5678", 
            "especies" => [
                ["nome_cientifico" => "Panthera onca", "individuos" => 2],
                ["nome_cientifico" => "Puma concolor", "individuos" => 1]
            ]
        ]
    ]
];
// Resultado: Múltiplos níveis de aninhamento com preservação da hierarquia
```

## Considerações de Performance

### Complexidade Algorítmica
- **Tempo**: O(n) onde n = número total de elementos (incluindo aninhados)
- **Espaço**: O(d) onde d = profundidade máxima de aninhamento
- **DOM**: Criação incremental sem reconstrução

### Otimizações Implementadas
1. **Lazy evaluation**: DOMDocument criado apenas uma vez
2. **Reuso de renderer**: Strategy instanciada apenas quando orientação muda
3. **Detecção eficiente**: `array_filter` com `is_array` callback
4. **Recursão controlada**: Sem limites artificiais, mas respeita memória do PHP

### Limitações Conhecidas
- Arrays muito profundos (>50 níveis) podem causar stack overflow
- Datasets massivos (>10MB) podem esgotar memória PHP
- Renderização síncrona não adequada para real-time streaming

## Extensibilidade

### Adicionando Novos Renderers
1. **Herdar de AbstractRenderer**:
```php
class CustomRenderer extends AbstractRenderer 
{
    public function render(array $headers, array $data): DOMElement 
    {
        // Implementação customizada
    }
}
```

2. **Atualizar TableOrientation**:
```php
enum TableOrientation: string 
{
    case HORIZONTAL = 'horizontal';
    case VERTICAL = 'vertical';
    case CUSTOM = 'custom';  // ← Novo
}
```

3. **Modificar RenderTable::updateRenderer()**:
```php
$this->renderer = match ($orientation) {
    TableOrientation::HORIZONTAL => new HorizontalRenderer(...),
    TableOrientation::VERTICAL => new VerticalRenderer(...),
    TableOrientation::CUSTOM => new CustomRenderer(...),  // ← Novo
};
```

### Adicionando Novos Atributos
Seguir padrão fluent API:
```php
public function tableCustomAttribute(string $value, bool $nested = true): self
{
    $scope = $nested ? 'nested' : 'root';
    $this->attributes[$scope]['custom-attr'] = $value;
    return $this;
}
```

## Testes e Qualidade

### Estratégia de Testes
- **Unit tests**: Cada renderer isoladamente
- **Integration tests**: Fluxo completo RenderTable
- **Edge cases**: Arrays vazios, dados malformados, aninhamento profundo
- **Regression tests**: Compatibilidade com versões anteriores

### Cobertura de Cenários
- [x] Arrays simples (key-value)
- [x] Arrays multidimensionais (2-3 níveis)  
- [x] Arrays paralelos (["col1" => [1,2,3], "col2" => [a,b,c]])
- [x] Dados ICMBio reais (monitores, espécies, coordenadas)
- [x] Orientações mistas (horizontal + vertical aninhadas)
- [x] Todos os tipos de atributos HTML
- [x] Controle de escopo (root vs nested)

### Métricas de Qualidade
- **PSR-12**: Compliance completo
- **Type safety**: Strict types em todos os arquivos
- **Interface contracts**: RenderTableInterface define API
- **SOLID principles**: Single responsibility, Open/closed, etc.

---

Esta arquitetura garante flexibilidade, manutenibilidade e extensibilidade para os casos de uso do ICMBio, mantendo simplicidade na interface pública.