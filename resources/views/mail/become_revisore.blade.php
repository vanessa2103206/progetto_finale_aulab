<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Nuova Candidatura Revisore</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8f9fa; padding: 30px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 20px; border-radius: 8px; border: 1px solid #dee2e6;">
        <h2 style="color: #198754; border-bottom: 2px solid #198754; padding-bottom: 10px;">Presto.it - Richiesta Collaborazione</h2>
        <p>Un utente ha compilato il modulo per richiedere di diventare revisore del portale.</p>
        
        <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
            <tr>
                <td style="padding: 8px; font-weight: bold; border-bottom: 1px solid #dee2e6;">Nome Utente:</td>
                <td style="padding: 8px; border-bottom: 1px solid #dee2e6;">{{ $user->name }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; font-weight: bold; border-bottom: 1px solid #dee2e6;">Email Candidato:</td>
                <td style="padding: 8px; border-bottom: 1px solid #dee2e6;">{{ $user->email }}</td>
            </tr>
        </table>
        
        <p style="margin-top: 30px; text-align: center;">
            Se desideri approvare questa richiesta, puoi lanciare il comando personalizzato di automazione nel terminale del server di Presto.it usando questa email.
        </p>
    </div>
</body>
</html>
