# Clube de Mahjong

Sistema para administrar um clube de mahjong: membros, amistosos, eventos e acompanhamento de progresso.

Pilha atual: PHP, JavaScript, CSS e SQLite.

## Como subir

Na raiz do projeto:

```bash
php -S localhost:8080 -t public
```

Abra [http://localhost:8080](http://localhost:8080).

O arquivo SQLite é criado automaticamente em `data/app.sqlite` na primeira execução (a pasta `data/` precisa existir e ser gravável).

## Acesso inicial (desenvolvimento)

- E-mail: `admin@clube.local`
- Senha: `admin123`

Troque essa senha antes de usar em qualquer ambiente compartilhado.

## Estrutura

- `public/` — document root (front controller e assets)
- `src/` — conexão, autenticação e rotas
- `views/` — HTML das telas
- `database/` — schema e seed
- `data/` — banco SQLite local (não versionado)
- `Imagens_base/` — capturas de telas de referência

O arquivo `data/app.sqlite` não entra no Git. Depois de clonar, suba o servidor uma vez para o banco ser criado.
