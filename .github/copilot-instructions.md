# Instruções para GitHub Copilot

## Objetivo
Sistema web com catálogo de produtos de lojas virtuais de todo o Brasil. Usuários podem salvar produtos favoritos na página "Minha conta". O fluxo principal inclui página inicial, páginas de departamentos e páginas de detalhes do produto, onde cada produto pode linkar a várias lojas.

## Estilo de Código
- Usar PSR-12 para PHP.
- Variáveis descritivas e comentários claros.
- Código simples e legível, evitando complexidade desnecessária.
- Padrões de nomenclatura:
  - camelCase para variáveis e métodos.
  - PascalCase para nomes de classes.
  - snake_case para campos do banco de dados.
  - Todos nomes (classes, objetos, variáveis) devem ser em inglês.
- Priorizar padrão RESTful para rotas e controllers.

## Segurança
- Validar e sanitizar todas as entradas do usuário rigorosamente.
- Evitar hardcoding de credenciais e informações sensíveis.
- Usar prepared statements para todas consultas SQL via Eloquent ou Query Builder.
- Não expor dados sensíveis em logs, mensagens de erro ou respostas de API.
- Implementar proteção contra ataques comuns (CSRF, XSS, SQL Injection).
- Gerenciar autenticação e autorização usando políticas e gates do Laravel.

## Práticas de Projeto
- Utilizar design modular e arquitetura limpa (separação clara entre camadas).
- Usar injeção de dependências para facilitar manutenção.
- Documentar funções públicas com PHPDoc detalhado.
- Seguir e aplicar princípios SOLID rigorosamente.
- **Não criar, executar ou sugerir testes automatizados.** Focar apenas na implementação do código funcional.
- **Não criar arquivos de documentação** (como README.md, CHANGELOG.md ou documentos explicativos). Apenas implementar o código solicitado e versionar.

## Princípios de Desenvolvimento
- Seguir os conceitos do The Twelve-Factor App para desenvolvimento de aplicações modernas e escaláveis, garantindo:
  - Código base único e versionado.
  - Configurações separadas da base do código, utilizando variáveis de ambiente.
  - Dependências explícitas e declaradas.
  - Processos estateless e portáteis.
  - Gestão adequada de logs como streams.
  - Execução da aplicação como um ou mais processos dispostos.

## Contexto do Projeto
- Ambiente em nuvem para garantir escalabilidade e alta disponibilidade.
- Utilizar PHP 8.3+ com Laravel framework.
- Painel administrativo com Filament 4.
- APIs RESTful para integrações com bancos e provedores de pagamento.
- Priorizar performance, escalabilidade e segurança em todas as soluções.

## Exemplos e Restrições
- Evitar bibliotecas externas não auditadas ou não autorizadas pela equipe.
- Preferir soluções nativas do Laravel e PHP para manipulação de dados, principalmente financeiros.
- Evitar código duplicado ou lógica repetitiva.

## Frontend
- Usar Tailwind CSS seguindo padrões e componentes do HyperUI para a interface visual.
- Priorizar javascript puro para interatividade simples e leve.
- Para interações mais complexas ou reativas, usar Livewire e Alpine.js.
- Garantir acessibilidade (a11y) e responsividade em todos componentes.

## Comentários e Interação
- Sempre sugerir melhorias que aumentem segurança e performance.
- Evitar sugestões que possam dificultar manutenção, escalabilidade ou gerar débito técnico.
- Preferir abordagens claras e bem documentadas para facilitar colaboração futura.

## Considerações Adicionais
- Manter consistência no uso de namespaces e diretórios conforme padrões Laravel.
- Usar migrations e seeders para gestão do banco de dados.
- Configurar caching e otimizações do Laravel para performance.
- Preservar logs essenciais para auditoria sem expor dados sensíveis.

## Padrões de Commits
- Utilizar o padrão Conventional Commits para mensagens de commit:
  - `feat:` para novas funcionalidades
  - `fix:` para correção de bugs
  - `docs:` para mudanças em documentação
  - `style:` para mudanças que não afetam o código (formatação, etc)
  - `refactor:` para refatoração de código que não corrige bug nem adiciona feature
  - `perf:` para melhorias de performance
  - `test:` para adicionar ou corrigir testes
  - `build:` para mudanças no sistema de build ou dependências
  - `ci:` para mudanças nos arquivos de configuração de CI
  - `chore:` para outras mudanças que não modificam código de produção
- Para commits com breaking changes, adicionar `!` após o tipo ou incluir `BREAKING CHANGE:` no corpo da mensagem
- Exemplo: `feat(products): add favorite button to product card`