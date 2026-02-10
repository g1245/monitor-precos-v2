<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Mensagem de Contato</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
        <h2 style="color: #2563eb; margin-top: 0;">Nova Mensagem de Contato - Central de Ajuda</h2>
        <p style="color: #6b7280; margin-bottom: 0;">Recebida em {{ $contactMessage->created_at->format('d/m/Y \à\s H:i') }}</p>
    </div>

    <div style="background-color: #ffffff; padding: 20px; border: 1px solid #e5e7eb; border-radius: 8px;">
        <h3 style="color: #1f2937; margin-top: 0;">Dados do Contato</h3>
        
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb;"><strong>Nome:</strong></td>
                <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb;">{{ $contactMessage->name }}</td>
            </tr>
            <tr>
                <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb;"><strong>E-mail:</strong></td>
                <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb;">
                    <a href="mailto:{{ $contactMessage->email }}" style="color: #2563eb; text-decoration: none;">
                        {{ $contactMessage->email }}
                    </a>
                </td>
            </tr>
            @if($contactMessage->phone)
            <tr>
                <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb;"><strong>Telefone:</strong></td>
                <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb;">{{ $contactMessage->phone }}</td>
            </tr>
            @endif
        </table>

        <h3 style="color: #1f2937; margin-top: 30px; margin-bottom: 10px;">Mensagem</h3>
        <div style="background-color: #f9fafb; padding: 15px; border-radius: 6px; white-space: pre-wrap; word-wrap: break-word;">{{ $contactMessage->message }}</div>
    </div>

    <div style="margin-top: 20px; padding: 15px; background-color: #f3f4f6; border-radius: 8px; text-align: center;">
        <p style="margin: 0; color: #6b7280; font-size: 14px;">
            Esta mensagem foi enviada através da Central de Ajuda do Monitor de Preços
        </p>
    </div>
</body>
</html>
