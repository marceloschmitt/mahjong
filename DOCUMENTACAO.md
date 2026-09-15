# Documentação dos arquivos

Descrição rápida de cada arquivo de código do sistema do clube de mahjong.

Separação: PHP monta dados e HTML; CSS em `public/assets/css/` define a aparência; JavaScript em `public/assets/js/` cuida do comportamento. Os três não se misturam.

## Raiz

| Arquivo | Função |
| --- | --- |
| [`config.php`](config.php) | Configuração da aplicação: nome do clube e caminhos do SQLite, do schema e do seed. |
| [`README.md`](README.md) | Como subir o servidor, acesso inicial de desenvolvimento e visão geral das pastas. |

## `public/` — entrada HTTP e assets

| Arquivo | Função |
| --- | --- |
| [`public/index.php`](public/index.php) | Front controller: registra as rotas (login, cadastro, dashboard, módulos do menu) e despacha a requisição. |
| [`public/.htaccess`](public/.htaccess) | No Apache, envia para `index.php` qualquer URL que não seja arquivo estático. |
| [`public/assets/css/app.css`](public/assets/css/app.css) | Estilos globais: barra lateral admin, área de conteúdo, login, formulários e cards. |
| [`public/assets/js/app.js`](public/assets/js/app.js) | Menu mobile da sidebar, estado de envio dos formulários e desaparecimento dos avisos (flash). |

## `src/` — lógica compartilhada

| Arquivo | Função |
| --- | --- |
| [`src/bootstrap.php`](src/bootstrap.php) | Arranque: sessão, carregamento dos outros arquivos de `src` e helpers (`e()`, `view()`, CSRF, flash). |
| [`src/db.php`](src/db.php) | Abre o PDO do SQLite, aplica o schema se preciso e executa o seed quando ainda não há usuários. |
| [`src/auth.php`](src/auth.php) | Cadastro, login, logout, usuário atual e proteção de rotas (`requireAuth()`, `guestOnly()`). |
| [`src/Router.php`](src/Router.php) | Roteador simples por método + caminho; se a rota não existe, responde 404. |

## `views/` — HTML das telas

| Arquivo | Função |
| --- | --- |
| [`views/layout.php`](views/layout.php) | Casca comum: login (visitante) ou sidebar admin + área principal (usuário autenticado). |
| [`views/dashboard.php`](views/dashboard.php) | Painel inicial com totais e atalhos para eventos, partidas, participantes e rankings. |
| [`views/coming-soon.php`](views/coming-soon.php) | Página genérica “em breve” usada por torneios, notícias, usuários, configurações e área do app. |
| [`views/auth/login.php`](views/auth/login.php) | Formulário de entrada. |
| [`views/auth/register.php`](views/auth/register.php) | Formulário de cadastro de novo usuário. |
| [`views/events/index.php`](views/events/index.php) | Placeholder de gerenciamento de eventos. |
| [`views/matches/index.php`](views/matches/index.php) | Placeholder de partidas. |
| [`views/members/index.php`](views/members/index.php) | Placeholder de participantes. |
| [`views/progress/index.php`](views/progress/index.php) | Placeholder de rankings. |
| [`views/errors/404.php`](views/errors/404.php) | Tela de página não encontrada. |

## `database/` — SQLite

| Arquivo | Função |
| --- | --- |
| [`database/schema.sql`](database/schema.sql) | Cria as tabelas: usuários, membros, eventos, inscrições, partidas, jogadores e notas de progresso. |
| [`database/seed.sql`](database/seed.sql) | Insere o administrador inicial de desenvolvimento (`admin@clube.local`). |

## Fora do código (referência)

- `data/app.sqlite` — banco local gerado em tempo de execução; não entra no Git.
- `data/.gitkeep` — mantém a pasta `data/` no repositório.
- `Imagens_base/` — capturas de telas planejadas, usadas como referência visual.
