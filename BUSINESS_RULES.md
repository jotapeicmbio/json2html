# json2html - Regras de Negocio de Renderizacao

## Objetivo

Este documento registra as regras de negocio da biblioteca `json2html` para que pessoas e agentes de IA consigam distinguir:

- comportamento atual implementado
- intencao funcional da biblioteca
- pontos que ainda dependem de alinhamento antes de mudar o codigo

O foco aqui nao e arquitetura interna, e sim o contrato de renderizacao esperado a partir de estruturas `array`/JSON.

## Principios da Biblioteca

- A biblioteca converte estruturas PHP `array` em tabelas HTML.
- A estrutura dos dados deve orientar a estrutura HTML resultante.
- A configuracao de orientacao define a disposicao da tabela atual.
- A configuracao `nested` define a orientacao usada nas tabelas aninhadas.
- A renderizacao e recursiva: valores que tambem sao arrays geram subtabelas.
- A biblioteca possui um motor base (`RenderTable`) e presets opinativos por classe.

## Pontos de Entrada

### 1. Motor base

`RenderTable` continua sendo a API flexivel para configuracoes manuais.

### 2. Presets opinativos

Classes como `TableHorizontal`, `TableVertical` e `TableVerticalSeparate` representam combinacoes prontas de orientacao e estrategia para arrays aninhados.

Regra pratica:

- use `RenderTable` quando precisar montar a configuracao manualmente
- use presets quando a regra de negocio ja estiver clara no nome da classe

## Conceitos Base

### 1. Tabela raiz

E a tabela criada a partir do dataset entregue ao `RenderTable`.

### 2. Tabela aninhada

E qualquer tabela criada a partir de um valor do dataset que tambem seja um `array`.

### 3. Orientacao horizontal

- cabecalhos no `thead`
- dados em linhas do `tbody`

### 4. Orientacao vertical

- cabecalho logico na primeira coluna
- valores nas colunas seguintes
- em datasets simples, cada campo vira uma linha

## Regra Geral de Interpretacao dos Dados

Ao receber um `array`, a biblioteca interpreta a estrutura em uma destas categorias:

### 1. Array associativo

Exemplo:

```php
[
    "name" => "json2html",
    "description" => "Converts JSON to HTML"
]
```

Comportamento esperado:

- horizontal: uma tabela com cabecalhos no topo e uma linha de valores
- vertical: uma tabela com pares `campo => valor`

### 2. Arrays paralelos

Exemplo:

```php
[
    "Linguagens" => ["PHP", "JS", "CSS"],
    "Banco de dados" => ["Postgres", "MySQL", "SQLite"]
]
```

Comportamento esperado:

- horizontal: uma linha por indice correspondente
- vertical: transposicao logica, mantendo cada chave principal como linha

### 3. Lista de objetos

Exemplo:

```php
[
    ["Nome" => "Luiz"],
    ["Nome" => "Michele"]
]
```

Comportamento atual implementado:

- horizontal: uma unica subtabela com uma linha por objeto
- vertical: uma unica subtabela com os campos de cada objeto renderizados em sequencia no mesmo `tbody`

Observacao importante:

- este e o ponto atualmente mais sensivel da regra de negocio
- no horizontal, esse comportamento esta aceito como correto para os cenarios atuais
- no vertical, esse comportamento ainda precisa de alinhamento melhor em casos reais mais complexos

### 4. Estruturas mistas

Exemplo:

```php
[
    "Name" => "Ana",
    "Phones" => [
        ["Type" => "Work", "Number" => "1111-1111"]
    ],
    "Dimensions" => [
        "Height" => "80cm",
        "Width" => "120cm"
    ]
]
```

Comportamento esperado:

- campos escalares permanecem como texto
- campos `array` geram subtabelas
- a renderizacao segue recursivamente para niveis mais profundos

## Regras Consolidadas por Orientacao

## Horizontal

As regras atualmente consolidadas para horizontal sao:

- orientacao padrao da biblioteca
- datasets simples viram uma tabela classica
- arrays paralelos sao transpostos em linhas
- listas de objetos viram uma unica subtabela com cabecalhos compartilhados
- arrays dentro de objetos continuam gerando subtabelas recursivas

Em resumo:

- o horizontal privilegia leitura tabular compacta
- colecoes homogeneas sao agregadas na mesma subtabela

## Vertical

As regras atualmente consolidadas para vertical sao:

- datasets simples viram pares `campo => valor`
- arrays paralelos sao transpostos para manter a chave principal como linha
- estruturas aninhadas respeitam a configuracao `nested`

Comportamento atual implementado para lista de objetos:

- a lista inteira vira uma unica subtabela
- cada objeto e percorrido campo a campo
- os campos de objetos consecutivos sao empilhados no mesmo `tbody`

Observacao:

- isso funciona para casos simples e para os testes atuais menores
- em cenarios reais com objetos ricos e arrays internos, essa saida pode ficar menos intuitiva do que o esperado pelo usuario

## Ambiguidade Atualmente Aberta

Existe uma decisao de negocio que precisa ser tratada explicitamente antes de alterar o renderer vertical:

Quando a orientacao aninhada e vertical e o valor e uma lista de objetos, o resultado correto deve ser:

- opcao A: uma unica subtabela vertical contendo todos os objetos em sequencia
- opcao B: uma subtabela separada para cada item da lista

Estado atual do alinhamento:

- para horizontal, a leitura vigente e que o comportamento atual esta correto
- para vertical, surgiu um caso real em que a expectativa do dominio puxa para uma leitura mais estrutural e menos agregada
- essa necessidade agora pode ser atendida por preset/strategy especifica sem quebrar a semantica agregada existente

## Regra Provisoria para Discussao Futura

A regra provisoria que deve orientar novas discussoes e analises e:

- nao assumir que a regra do horizontal deve ser copiada para o vertical
- em casos verticais complexos, avaliar se a melhor representacao e agregada ou estrutural
- qualquer mudanca nessa area deve ser tratada como mudanca de contrato, nao como ajuste cosmetico

## Casos de Referencia no Codigo

Casos uteis para consulta:

- `tests/Unit/RenderTableTest.php`
- `tests/Unit/VerticalOrientationTest.php`
- `src/HorizontalRenderer.php`
- `src/VerticalRenderer.php`
- `src/AbstractRenderer.php`

## Orientacao para Agentes

Antes de propor alteracoes na renderizacao:

- identificar se o caso pertence a horizontal ou vertical
- verificar se o dado e associativo, paralelo, lista de objetos ou misto
- conferir se a expectativa desejada e compativel com o comportamento hoje coberto pelos testes
- registrar explicitamente quando uma proposta mudar o contrato da biblioteca

Se houver duvida, priorizar:

- preservar o comportamento atual documentado
- separar comportamento vigente de comportamento desejado
- criar ou ajustar testes somente depois de alinhar a regra de negocio
