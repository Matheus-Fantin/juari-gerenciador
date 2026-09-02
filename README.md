# Juari Gerenciador

Painel interno para gerenciar o site [juari-eventos-02](https://github.com/Matheus-Fantin/juari-eventos-02): aprovar/excluir depoimentos e adicionar/excluir fotos da galeria. Não tem banco de dados próprio de conteúdo — tudo é lido e gravado no site público através de uma API protegida por token.

## Como funciona

```
juari-gerenciador (este app)  --->  API do juari-eventos-02  --->  banco de dados do site
    (login, telas, uploads)          (/api/..., com token)
```

Este app só guarda as contas de quem tem acesso ao painel (`users`). Depoimentos e fotos continuam morando no `juari-eventos-02`.

## Configuração local

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm run build
php artisan serve
```

Depois, configure no `.env` deste projeto:

```
JUARI_SITE_API_URL=http://127.0.0.1:8000/api   # onde o juari-eventos-02 está rodando + /api
JUARI_SITE_API_TOKEN=                          # mesmo valor do GERENCIADOR_API_TOKEN no .env do site
JUARI_SITE_URL=http://127.0.0.1:8000           # endereço público do site (só para o link "Ver site")
```

O token é gerado uma vez e usado nos dois lados:

```bash
# rodar dentro do projeto juari-eventos-02
php artisan tinker --execute="echo Str::random(64);"
```

Copie o valor gerado para `GERENCIADOR_API_TOKEN` no `.env` do **juari-eventos-02** e para `JUARI_SITE_API_TOKEN` no `.env` **deste** projeto — precisa ser o mesmo valor nos dois.

## Cadastro de contas

Por padrão o `/register` fica aberto (`ALLOW_REGISTRATION=true` no `.env`) para facilitar a criação da primeira conta. Depois que as contas da equipe estiverem criadas, mude para `ALLOW_REGISTRATION=false` — a rota some sem precisar mexer em código.

## Segurança

Ver [`SEGURANCA.md`](./SEGURANCA.md).
