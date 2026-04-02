# Guia de Contribuição - json2html

## Como Contribuir

Obrigado pelo interesse em contribuir com o projeto `json2html`! Este guia irá ajudá-lo a configurar o ambiente e seguir as melhores práticas.

## 🚀 Configuração Rápida

### Pré-requisitos
- Docker & Docker Compose
- Git
- Editor com suporte a PHP (VS Code recomendado)

### Setup do Ambiente
```bash
# 1. Clone o repositório
git clone <repository-url>
cd json2html

# 2. Inicie o ambiente Docker  
docker-compose up -d

# 3. Instale dependências
./composer install

# 4. Execute os testes
./test

# 5. Verifique se tudo está funcionando
./test --filter="RenderTableTest"
```

## 📋 Padrões de Desenvolvimento

### Padrões de Código
- **PHP 8.1+** obrigatório
- **PSR-4** para autoloading
- **PSR-12** para style guide
- **Strict types**: Todos os arquivos PHP devem usar `declare(strict_types=1);`
- **Type hints**: Sempre declarar tipos de parâmetros e retorno
- **Method chaining**: Manter API fluente (retornar `self`)

### Exemplo de Classe Bem Formada
```php
<?php

declare(strict_types=1);

namespace Icmbio\\Json2html;

/**
 * Classe example seguindo padrões do projeto
 */
class ExampleClass implements ExampleInterface
{
    private array $config = [];
    
    public function __construct(array $initialConfig = [])
    {
        $this->config = $initialConfig;
    }
    
    public function setOption(string $key, mixed $value): self
    {
        $this->config[$key] = $value;
        return $this;
    }
    
    public function render(): string
    {
        // Implementation
        return '';
    }
}
```

## 🧪 Testes

### Executando Testes
```bash
# Todos os testes
./test

# Testes específicos
./test --filter="RenderTableTest"
./test --filter="VerticalOrientationTest"  
./test --filter="TableAttributesTest"

# Testes com coverage (se disponível)
./test --coverage-text
```

### Padrões de Teste
- Use **PHPUnit 10.5+** com atributos PHP 8+ (`#[Test]`)
- **Nomes descritivos**: `shouldRenderVerticalTableWhenConfigured()`
- **Dados realistas**: Use cenários ICMBio (biodiversidade, monitoramento)
- **Casos extremos**: Arrays vazios, dados malformados, aninhamento profundo
- **Assertions específicas**: Verifique HTML exato, não apenas presença de elementos

### Exemplo de Teste
```php
<?php

namespace Test\\Unit;

use Icmbio\\Json2html\\{RenderTable, TableOrientation};
use PHPUnit\\Framework\\Attributes\\Test;
use PHPUnit\\Framework\\TestCase;

class NewFeatureTest extends TestCase
{
    #[Test]
    final public function shouldHandleNewFeatureCorrectly(): void
    {
        $biodiversityData = [
            "especies_observadas" => [
                ["nome_cientifico" => "Panthera onca", "individuos" => 2],
                ["nome_cientifico" => "Puma concolor", "individuos" => 1]
            ]
        ];

        $expectedHtml = '<table><thead><tr><th>especies_observadas</th></tr></thead><tbody><tr><td><table><thead><tr><th>nome_cientifico</th><th>individuos</th></tr></thead><tbody><tr><td>Panthera onca</td><td>2</td></tr><tr><td>Puma concolor</td><td>1</td></tr></tbody></table></td></tr></tbody></table>';

        $result = (new RenderTable($biodiversityData))->render();
        
        $this->assertEquals($expectedHtml, $result);
    }
}
```

## 🏗️ Adicionando Novas Funcionalidades

### 1. Adicionando Novos Renderers

**Passo 1: Criar a classe renderer**
```php
<?php

declare(strict_types=1);

namespace Icmbio\\Json2html;

use DOMElement;

class NewRenderer extends AbstractRenderer
{
    public function render(array $headers, array $data): DOMElement
    {
        $table = $this->dom->createElement('table');
        
        // Sua implementação aqui
        
        $this->applyAttributes($table, 'root');
        return $table;
    }
}
```

**Passo 2: Atualizar TableOrientation enum**
```php
enum TableOrientation: string
{
    case HORIZONTAL = 'horizontal';
    case VERTICAL = 'vertical';
    case NEW_TYPE = 'new_type';  // ← Adicionar aqui
}
```

**Passo 3: Atualizar RenderTable**
```php
protected function updateRenderer(): void
{
    $orientation = $this->config['orientation'] ?? TableOrientation::HORIZONTAL;
    
    $this->renderer = match ($orientation) {
        TableOrientation::HORIZONTAL => new HorizontalRenderer($this->dom, $this->config, $this->attributes),
        TableOrientation::VERTICAL => new VerticalRenderer($this->dom, $this->config, $this->attributes),
        TableOrientation::NEW_TYPE => new NewRenderer($this->dom, $this->config, $this->attributes),
    };
}
```

**Passo 4: Escrever testes**
```php
#[Test]
final public function shouldRenderWithNewOrientation(): void
{
    // Implementar testes abrangentes
}
```

### 2. Adicionando Novos Atributos HTML

**Passo 1: Atualizar interface**
```php
interface RenderTableInterface
{
    // Métodos existentes...
    
    public function tableNewAttribute(string $value, bool $nested = true): self;
}
```

**Passo 2: Implementar em RenderTable**
```php
public function tableNewAttribute(string $value, bool $nested = true): self
{
    $scope = $nested ? 'nested' : 'root';
    $this->attributes[$scope]['new-attribute'] = $value;
    return $this;
}
```

**Passo 3: Testar em ambos os renderers**
```php
#[Test]
final public function shouldApplyNewAttributeCorrectly(): void
{
    $expected = '<table new-attribute="test-value">...</table>';
    // Implementar teste
}
```

## 🔄 Processo de Pull Request

### Antes de Criar o PR
1. **Execute todos os testes**: `./test`
2. **Verifique PSR-12**: Use ferramentas de linting
3. **Teste com dados reais**: Use cenários ICMBio
4. **Documente mudanças**: Atualize README se necessário

### Template de PR
```markdown
## Descrição
Breve descrição da mudança implementada.

## Tipo de Mudança
- [ ] Bug fix (mudança que corrige um problema)
- [ ] Nova funcionalidade (mudança que adiciona uma funcionalidade)
- [ ] Breaking change (mudança que quebra compatibilidade)
- [ ] Documentação

## Testes
- [ ] Testes passando localmente
- [ ] Novos testes adicionados para a funcionalidade
- [ ] Testado com dados ICMBio realistas

## Checklist
- [ ] Código segue PSR-12
- [ ] Inclui `declare(strict_types=1);`
- [ ] Mantém API fluente
- [ ] Backward compatibility preservada
- [ ] Documentação atualizada
```

## 📝 Convenções de Commit

Use [Conventional Commits](https://www.conventionalcommits.org/):

```bash
# Funcionalidades
feat: add support for custom table attributes
feat(renderer): implement grid layout renderer

# Correções
fix: resolve nested table attribute inheritance
fix(vertical): fix header alignment in vertical mode

# Documentação  
docs: update architecture documentation
docs(readme): add new usage examples

# Testes
test: add edge cases for deeply nested arrays
test(attributes): improve attribute application tests

# Refactor
refactor: optimize renderer selection logic
refactor(dom): simplify DOM manipulation methods
```

## 🐳 Comandos Docker Úteis

```bash
# Desenvolvimento
./php -v                    # Verificar versão PHP
./composer --version        # Verificar versão Composer
./test --version           # Verificar versão PHPUnit

# Debugging
docker-compose logs php     # Ver logs do container
docker-compose exec php bash  # Acessar container
docker-compose restart     # Reiniciar serviços

# Limpeza
docker-compose down         # Parar containers
docker-compose down -v      # Parar e remover volumes
```

## 🚨 Resolução de Problemas

### Problemas Comuns

**1. Testes falhando após mudanças**
```bash
# Limpar cache do composer
./composer dump-autoload

# Verificar sintaxe
./php -l src/SuaClasse.php

# Executar teste isolado
./test --filter="SeuTeste"
```

**2. Docker não funcionando**
```bash
# Verificar containers
docker ps -a

# Rebuild completo
docker-compose down
docker-compose build --no-cache
docker-compose up -d
```

**3. Problemas de namespace/autoload**
```bash
# Regenerar autoload
./composer dump-autoload -o

# Verificar PSR-4 no composer.json
./composer validate
```

## 📚 Recursos Úteis

### Documentação
- [ARCHITECTURE.md](ARCHITECTURE.md) - Arquitetura técnica detalhada
- [.copilot-instructions.md](.copilot-instructions.md) - Instruções para IA
- [PHPUnit Documentation](https://phpunit.de/documentation.html)
- [PSR-12 Style Guide](https://www.php-fig.org/psr/psr-12/)

### Ferramentas Recomendadas
- **VS Code** com extensões PHP
- **Xdebug** para debugging (configurável)
- **PHP CS Fixer** para formatação automática
- **PHPStan** para análise estática (futuro)

## 💡 Dicas de Desenvolvimento

1. **Use dados realistas**: Sempre teste com dados que representam casos de uso ICMBio
2. **Mantenha simplicidade**: A API deve ser intuitiva para usuários não-técnicos
3. **Performance second**: Priorize flexibilidade e correção sobre otimização prematura
4. **Documente decisões**: Explique o "porquê" em comentários e commits
5. **Teste edge cases**: Arrays vazios, dados null, strings muito longas

## 🤝 Comunidade

- **Issues**: Relate bugs ou sugira melhorias
- **Discussions**: Para dúvidas gerais sobre uso
- **Code Review**: Todas as mudanças passam por revisão
- **Mentoria**: Desenvolvedores experientes ajudam novos contribuidores

---

**Obrigado por contribuir para o ecossistema ICMBio! 🌱**