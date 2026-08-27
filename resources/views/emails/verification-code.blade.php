<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Código de verificação</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f4f4f5; padding: 32px;">
    <div style="max-width: 420px; margin: 0 auto; background: #ffffff; border-radius: 12px; padding: 32px; text-align: center;">
        <h1 style="font-size: 18px; color: #111827; margin-bottom: 8px;">Virtual Notes</h1>
        <p style="color: #4b5563; font-size: 14px; margin-bottom: 24px;">
            Use o código abaixo para confirmar seu e-mail. Ele expira em 15 minutos.
        </p>
        <div style="font-size: 32px; font-weight: 700; letter-spacing: 8px; color: #1d4ed8; margin-bottom: 24px;">
            {{ $code }}
        </div>
        <p style="color: #9ca3af; font-size: 12px;">
            Se você não solicitou isso, pode ignorar este e-mail.
        </p>
    </div>
</body>
</html>
