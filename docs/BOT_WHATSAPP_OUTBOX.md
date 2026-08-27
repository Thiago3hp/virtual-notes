# Envio dos códigos de verificação — integração com o bot (T.I-Sesa-bot)

## Status: implementado

O Laravel grava mensagens pendentes na tabela `whatsapp_outbox` (banco
compartilhado). O bot lê essa tabela a cada 10 segundos e envia de fato
pelo WhatsApp, usando a mesma conexão (Baileys) que ele já mantém aberta.

```
Laravel                          Bot (T.I-Sesa-bot)
--------                         -------------------
grava linha em                   a cada 10s: le linhas
whatsapp_outbox                  'pendente' em whatsapp_outbox
(status='pendente')       -->    envia via sock.sendMessage()
                                  marca 'enviado' ou 'falhou'
```

## Arquivos envolvidos (no repositório do bot)

- `src/config/db.js` — `ensureSchema()` cria `whatsapp_outbox` se não existir (mesmo padrão idempotente já usado pra `chamados`)
- `src/services/outboxService.js` — lê os pendentes e envia
- `src/index.js` — agenda `processarOutbox()` a cada 10s via `setInterval`
- `src/utils/owner.js` — `buildJidFromNumber()` (já existia, reaproveitado)

## `TECNICO_NUMBERS` é a mesma variável nos dois serviços

O bot já usa `TECNICO_NUMBERS` (`src/config/env.js`) pra liberar o comando
`tecnico` (encerrar chamados) no WhatsApp. O Laravel usa a **mesma
variável, com o mesmo formato** (números separados por vírgula) pra
autenticação de dois fatores no painel.

**Importante:** são duas variáveis de ambiente configuradas
separadamente (uma no serviço do bot no Railway, outra no serviço do
Laravel) — não é compartilhada automaticamente. Sempre que adicionar ou
remover um técnico, atualize **os dois** serviços com o mesmo valor.

## Testando manualmente

Insira uma linha direto no banco (via tinker do Laravel, por exemplo):

```php
\App\Models\WhatsappOutboxMessage::create([
    'numero' => '5586999999999',
    'mensagem' => 'Teste de envio',
    'status' => 'pendente',
]);
```

Em até 10 segundos, a mensagem deve chegar no WhatsApp e a linha virar
`status = 'enviado'` (ou `'falhou'`, com o erro no log do bot, se algo
der errado — ex: número não tem WhatsApp).
