# RenderTable

Biblioteca PHP para conversão de dados JSON/Array para tabelas HTML com orientação configurável e sistema completo de atributos.

## Recursos

- ✅ Conversão de arrays associativos e multidimensionais para HTML
- ✅ **Orientação configurável**: Horizontal (tradicional) e Vertical  
- ✅ **Sistema de atributos completo**: Classes, IDs, bordas e atributos customizados
- ✅ **Controle de tabelas aninhadas**: Aplicação seletiva de atributos
- ✅ **API Fluente**: Configuração através de method chaining
- ✅ **Arquitetura Strategy Pattern**: Classes especializadas para cada tipo de renderização

## Instalação

```bash
composer require icmbio/json2html
```

## Uso Básico

### Dados Simples

```php
<?php

use Icmbio\Json2html\RenderTable;

$data = [
    "name" => "json2html", 
    "description" => "Converts JSON to HTML"
];

$table = new RenderTable($data);
echo $table->render();
```

**Saída HTML:**
```html
<table>
    <thead>
        <tr><th>name</th><th>description</th></tr>
    </thead>
    <tbody>
        <tr><td>json2html</td><td>Converts JSON to HTML</td></tr>
    </tbody>
</table>
```

### Arrays Multidimensionais 

```php
$data = [
    "Monitor" => [
        ["Nome" => "Luiz Loureiro"],
        ["Nome" => "Michele Rocha Silva"]  
    ]
];

$table = new RenderTable($data);
echo $table->render();
```

**Saída HTML:**
```html
<table>
    <thead><tr><th>Monitor</th></tr></thead>
    <tbody>
        <tr><td>
            <table>
                <thead><tr><th>Nome</th></tr></thead>
                <tbody>
                    <tr><td>Luiz Loureiro</td></tr>
                    <tr><td>Michele Rocha Silva</td></tr>
                </tbody>
            </table>
        </td></tr>
    </tbody>
</table>
```

## Orientação da Tabela

Além da configuração manual com `RenderTable`, a biblioteca também oferece classes prontas para presets de renderização, como `TableHorizontal`, `TableVertical` e `TableVerticalSeparate`.

### Orientação Horizontal (Padrão)

Headers ficam no topo, dados nas linhas:

```php
use Icmbio\Json2html\{RenderTable, TableOrientation};

$data = [
    "name" => "json2html",
    "description" => "Converts JSON to HTML"  
];

$table = (new RenderTable($data))
    ->config(['orientation' => TableOrientation::HORIZONTAL])
    ->render();
```

**Saída:**
```html
<table>
    <thead><tr><th>name</th><th>description</th></tr></thead>
    <tbody><tr><td>json2html</td><td>Converts JSON to HTML</td></tr></tbody>
</table>
```

### Orientação Vertical

Headers ficam na primeira coluna, dados nas colunas seguintes:

```php
$data = [
    "name" => "json2html",
    "description" => "Converts JSON to HTML"
];

$table = (new RenderTable($data))
    ->config(['orientation' => TableOrientation::VERTICAL])
    ->render();
```

**Saída:**
```html
<table>
    <tbody>
        <tr><td>name</td><td>json2html</td></tr>
        <tr><td>description</td><td>Converts JSON to HTML</td></tr>
    </tbody>
</table>
```

### Presets Prontos

Quando a regra de renderização já é conhecida, você pode usar classes opinativas:

```php
use Icmbio\Json2html\TableVerticalSeparate;

$table = TableVerticalSeparate::make($data)->render();
```

Esse preset gera tabelas verticais e interpreta arrays aninhados estruturais sem achatar listas de arrays em uma única subtabela.

## Sistema de Atributos

### Classes CSS

```php
// Adicionar classe única
$table = (new RenderTable($data))
    ->tableClass('table table-bordered')
    ->render();

// Adicionar múltiplas classes via chaining
$table = (new RenderTable($data))
    ->tableClass('table')
    ->tableClass('table-bordered') 
    ->tableClass('table-hover')
    ->render();
```

**Saída:**
```html
<table class="table table-bordered table-hover">...</table>
```

### ID da Tabela

```php
$table = (new RenderTable($data))
    ->tableId('info-table')
    ->render();
```

**Saída:**
```html
<table id="info-table">...</table>
```

### Atributo Border

```php
$table = (new RenderTable($data))
    ->tableBorder(1)
    ->render();
```

**Saída:**
```html
<table border="1">...</table>
```

### Atributos Customizados

```php
$table = (new RenderTable($data))
    ->tableAttribute('data-test', 'custom')
    ->render();
```

**Saída:**
```html
<table data-test="custom">...</table>
```

### Combinação de Múltiplos Atributos

```php
$table = (new RenderTable($data))
    ->tableId('info-table')
    ->tableClass('table table-bordered')
    ->tableBorder(1)
    ->tableAttribute('data-test', 'combined')
    ->render();
```

**Saída:**
```html
<table id="info-table" class="table table-bordered" border="1" data-test="combined">...</table>
```

## Controle de Tabelas Aninhadas

Por padrão, as classes CSS são aplicadas em tabelas aninhadas, mas IDs não. Você pode controlar isso explicitamente:

### Aplicar apenas na tabela raiz

```php
$data = [
    "Monitor" => [
        ["Nome" => "Luiz Loureiro"]
    ]
];

$table = (new RenderTable($data))
    ->tableClass('root-only', nested: false)  // Boolean explícito
    ->render();
```

**Saída:**
```html
<table class="root-only">
    <thead><tr><th>Monitor</th></tr></thead>
    <tbody>
        <tr><td>
            <table>  <!-- Sem classe aplicada -->
                <thead><tr><th>Nome</th></tr></thead>
                <tbody><tr><td>Luiz Loureiro</td></tr></tbody>
            </table>
        </td></tr>
    </tbody>
</table>
```

### Aplicar em todas as tabelas (padrão para classes)

```php
$table = (new RenderTable($data))
    ->tableClass('table', nested: true)  // Explícito (padrão)
    ->render();
```

**Saída:**
```html
<table class="table">
    <thead><tr><th>Monitor</th></tr></thead>
    <tbody>
        <tr><td>
            <table class="table">  <!-- Classe aplicada também -->
                <thead><tr><th>Nome</th></tr></thead>
                <tbody><tr><td>Luiz Loureiro</td></tr></tbody>
            </table>
        </td></tr>
    </tbody>
</table>
```

### Comportamento Padrão por Tipo de Atributo

- **Classes CSS**: `nested: true` (aplicam em tabelas aninhadas)
- **IDs**: `nested: false` (aplicam apenas na tabela raiz)  
- **Border**: `nested: true` (aplicam em tabelas aninhadas)
- **Atributos customizados**: `nested: true` (aplicam em tabelas aninhadas)

```php
$table = (new RenderTable($data))
    ->tableId('main-table')     // nested: false por padrão
    ->tableClass('table')       // nested: true por padrão  
    ->render();
```

## Orientação com Atributos

A orientação vertical funciona normalmente com todos os atributos:

```php
$data = [
    "name" => "json2html",
    "description" => "Converts JSON to HTML"  
];

$table = (new RenderTable($data))
    ->config(['orientation' => TableOrientation::VERTICAL])
    ->tableClass('vertical-table')
    ->render();
```

**Saída:**
```html
<table class="vertical-table">
    <tbody>
        <tr><td>name</td><td>json2html</td></tr>
        <tr><td>description</td><td>Converts JSON to HTML</td></tr>
    </tbody>
</table>
```

## API Completa

### RenderTable

```php
// Construtor
new RenderTable(array $data)

// Configuração
->config(array $options): self

// Atributos da tabela
->tableClass(string $class, bool $nested = true): self
->tableId(string $id, bool $nested = false): self  
->tableBorder(int $border, bool $nested = true): self
->tableAttribute(string $name, string $value, bool $nested = true): self

// Renderização
->render(): string
```

### TableOrientation

```php
// Enum com casos disponíveis  
TableOrientation::HORIZONTAL  // Padrão: headers no topo
TableOrientation::VERTICAL    // Headers na primeira coluna
```

## Exemplos Avançados

### Array de Objetos com Orientação Mista

```php
$data = [
    "departamentos" => [
        ["nome" => "TI", "funcionarios" => 15],
        ["nome" => "RH", "funcionarios" => 8]  
    ]
];

$table = (new RenderTable($data))
    ->config(['orientation' => TableOrientation::HORIZONTAL])
    ->tableClass('table table-striped')
    ->tableId('departamentos-table') 
    ->tableBorder(1)
    ->render();
```

### Controle Granular de Atributos Aninhados

```php
$data = [
    "usuarios" => [
        ["nome" => "João", "email" => "joao@teste.com"],
        ["nome" => "Maria", "email" => "maria@teste.com"]
    ]
];

$table = (new RenderTable($data))
    ->tableId('usuarios-table', nested: false)          // ID só na raiz
    ->tableClass('table', nested: true)                 // Classe em todas 
    ->tableClass('table-bordered', nested: false)       // Classe só na raiz
    ->tableBorder(1, nested: true)                      // Border em todas
    ->tableAttribute('data-module', 'users', nested: false)  // Atributo só na raiz
    ->render();
```

## Arquitetura

O RenderTable usa o **Strategy Pattern** com classes especializadas:

- `AbstractRenderer`: Lógica base e sistema de atributos
- `HorizontalRenderer`: Layout tradicional (headers no topo)  
- `VerticalRenderer`: Layout vertical (headers na primeira coluna)
- `TableOrientation`: Enum para seleção type-safe da orientação

Isso garante separação de responsabilidades e facilita extensibilidade para futuras orientações de tabela.

## 📚 Documentação Adicional

- **[ARCHITECTURE.md](ARCHITECTURE.md)** - Documentação técnica detalhada da arquitetura
- **[CONTRIBUTING.md](CONTRIBUTING.md)** - Guia completo para contribuidores
- **[.copilot-instructions.md](.copilot-instructions.md)** - Instruções específicas para agentes de IA

## 🤝 Contribuindo

Contribuições são bem-vindas! Por favor, leia o [Guia de Contribuição](CONTRIBUTING.md) para:

- Configuração do ambiente de desenvolvimento
- Padrões de código e testes
- Processo de Pull Request
- Resolução de problemas comuns

### Desenvolvimento Rápido
```bash
# Setup inicial
docker-compose up -d
./composer install

# Executar testes
./test

# Desenvolvimento com Docker
./php         # Executar comandos PHP
./composer    # Executar comandos Composer
```

## 📋 Roadmap

- [ ] Suporte a templates customizáveis
- [ ] Exportação para diferentes formatos (CSV, PDF)
- [ ] Otimizações de performance para datasets grandes
- [ ] Integração com frameworks populares (Laravel, Symfony)
- [ ] Suporte a elementos HTML5 semânticos

---

**Desenvolvido pelo ICMBio** 🌱 | **Licença MIT** | **Contribuições bem-vindas!**
