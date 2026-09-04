# Segurança — Juari Gerenciador

## O que já está implementado

- **Sem depoimentos/fotos gravados aqui**: este app não guarda dado sensível de cliente — tudo fica no `juari-eventos-02`. Se este servidor for comprometido, o pior cenário é acesso ao painel, não ao banco do site.
- **Comunicação com o site protegida por token**: toda chamada à API do `juari-eventos-02` leva um token secreto no cabeçalho. Sem o token certo, a API recusa (401).
- **Limite de tentativas de login** (5 erradas = bloqueio temporário).
- **Cabeçalhos de proteção do navegador** (clickjacking, MIME sniffing) em todas as respostas.
- **Cadastro público desligável**: `ALLOW_REGISTRATION=false` no `.env` remove a rota `/register` inteira.
- **Lista de e-mails autorizados**: `ADMIN_ALLOWED_EMAILS` no `.env` (separados por vírgula) restringe quem consegue criar conta ou continuar logado — qualquer e-mail fora da lista é bloqueado na hora, mesmo com uma sessão já aberta. Deixe em branco pra não restringir ninguém.
- **Upload de foto restrito** a jpg/png/webp, até 8MB.

## Antes de colocar no ar

- [ ] `APP_DEBUG=false` — crítico, evita vazar informação do servidor em erros.
- [ ] `APP_ENV=production`
- [ ] HTTPS ativo + `SESSION_SECURE_COOKIE=true`
- [ ] `ALLOW_REGISTRATION=false` assim que as contas da equipe estiverem criadas.
- [ ] `ADMIN_ALLOWED_EMAILS` preenchido com os e-mails de quem deve ter acesso.
- [ ] `JUARI_SITE_API_TOKEN` gerado com `Str::random(64)` (ou maior) — nunca um valor curto ou previsível.
- [ ] O token nunca deve aparecer em prints de tela, mensagens ou repositório público.

## O que fazer se algo der errado

**"Não foi possível conectar ao site" aparece no painel**
- O `juari-eventos-02` está fora do ar, ou o `JUARI_SITE_API_URL`/`JUARI_SITE_API_TOKEN` estão errados no `.env` deste projeto. Confira os dois primeiro.

**Desconfia que o token vazou**
1. Gere um novo token (`php artisan tinker --execute="echo Str::random(64);"` no juari-eventos-02).
2. Atualize `GERENCIADOR_API_TOKEN` no `.env` do juari-eventos-02 **e** `JUARI_SITE_API_TOKEN` aqui, com o mesmo valor novo.
3. Reinicie os dois serviços.

**Alguém criou uma conta sem autorização**
- Verifique se `ALLOW_REGISTRATION` ainda está `true` — se sim, desligue. Delete a conta indevida pelo `tinker`: `App\Models\User::where('email', '...')->delete();`

Ver também o `SEGURANCA.md` do [juari-eventos-02](https://github.com/Matheus-Fantin/juari-eventos-02) — a maior parte dos dados (depoimentos, fotos) mora lá, então o plano de backup e recuperação também é de lá.
