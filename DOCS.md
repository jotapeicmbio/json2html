# Documentação do Projeto json2html

## 📑 Índice da Documentação

Este projeto possui documentação estruturada para diferentes audiências e necessidades:

### 🚀 Para Usuários
- **[README.md](README.md)** - Guia de uso, exemplos práticos e API completa
- Início rápido, casos de uso comuns, configuração de atributos

### 🏗️ Para Desenvolvedores
- **[ARCHITECTURE.md](ARCHITECTURE.md)** - Arquitetura técnica detalhada
- Strategy Pattern, fluxo de dados, extensibilidade, performance
- **[BUSINESS_RULES.md](BUSINESS_RULES.md)** - Regras de negocio da renderizacao
- Contrato funcional, comportamento atual e ambiguidades abertas

### 🤝 Para Contribuidores  
- **[CONTRIBUTING.md](CONTRIBUTING.md)** - Guia completo de contribuição
- Setup do ambiente, padrões de código, processo de PR, troubleshooting

### 🤖 Para Agentes de IA
- **[.copilot-instructions.md](.copilot-instructions.md)** - Instruções específicas para IA
- Regras de desenvolvimento, padrões arquiteturais, contexto do projeto
- **[BUSINESS_RULES.md](BUSINESS_RULES.md)** - Fonte de verdade para comportamento funcional
- Use este arquivo antes de propor mudanças em renderização ou testes

## 🎯 Navegação Rápida

### Preciso implementar uma nova funcionalidade
1. Leia [ARCHITECTURE.md](ARCHITECTURE.md) → Seção "Extensibilidade"
2. Leia [BUSINESS_RULES.md](BUSINESS_RULES.md) → Regras e ambiguidades do contrato atual
3. Configure ambiente com [CONTRIBUTING.md](CONTRIBUTING.md) → "Setup do Ambiente"
4. Siga padrões definidos em [.copilot-instructions.md](.copilot-instructions.md)

### Preciso usar a biblioteca
1. Comece com [README.md](README.md) → "Instalação" e "Uso Básico"
2. Veja exemplos em [README.md](README.md) → "Exemplos Avançados"  
3. Consulte API completa em [README.md](README.md) → "API Completa"

### Preciso entender como funciona internamente
1. Visão geral em [ARCHITECTURE.md](ARCHITECTURE.md) → "Visão Geral"
2. Fluxo de dados em [ARCHITECTURE.md](ARCHITECTURE.md) → "Fluxo de Dados"
3. Padrões implementados em [ARCHITECTURE.md](ARCHITECTURE.md) → "Padrões de Design"
4. Contrato funcional em [BUSINESS_RULES.md](BUSINESS_RULES.md)

### Preciso configurar um agente de IA
1. Instruções completas em [.copilot-instructions.md](.copilot-instructions.md)
2. Regras de negocio em [BUSINESS_RULES.md](BUSINESS_RULES.md)
3. Contexto arquitetural em [ARCHITECTURE.md](ARCHITECTURE.md)
4. Exemplos de desenvolvimento em [CONTRIBUTING.md](CONTRIBUTING.md)

## 📋 Checklist de Atualização da Documentação

Quando modificar o projeto, lembre-se de atualizar:

- [ ] **README.md** - Se mudou API pública, exemplos ou instalação
- [ ] **ARCHITECTURE.md** - Se mudou arquitetura, padrões ou fluxos
- [ ] **BUSINESS_RULES.md** - Se mudou contrato funcional ou interpretação dos dados
- [ ] **CONTRIBUTING.md** - Se mudou processo de desenvolvimento ou setup
- [ ] **.copilot-instructions.md** - Se mudou regras ou contexto para IA
- [ ] **DOCS.md** - Se adicionou novos arquivos de documentação

## 🔄 Versionamento da Documentação

A documentação segue a mesma versão do projeto:
- **v1.0.0** - Documentação inicial completa
- Mudanças na documentação acompanham releases do código

## 💡 Dicas de Manutenção

1. **Mantenha consistência** entre todos os arquivos de documentação
2. **Atualize exemplos** quando a API mudar
3. **Teste instruções** periodicamente para garantir que funcionam
4. **Use linguagem clara** apropriada para cada audiência
5. **Mantenha exemplos realistas** baseados em casos de uso ICMBio

---

*Documentação mantida pela equipe ICMBio - Atualizada em abril de 2026*
