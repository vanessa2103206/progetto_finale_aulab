<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Nuova Candidatura Revisore</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8f9fa; padding: 30px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 20px; border-radius: 8px; border: 1px solid #dee2e6;">
        <h2 style="color: #198754; border-bottom: 2px solid #198754; padding-bottom: 10px;">Presto.it - Richiesta Collaborazione</h2>
        <p>Un utente ha richiesto di diventare revisore del portale.</p>
        
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
            <!-- Pulsante visivo -->
            <a href="{{ route('revisore.make_with_link', ['user' => $user->id]) }}" 
               style="background-color: #198754; color: white; padding: 12px 20px; text-decoration: none; font-weight: bold; border-radius: 5px; display: inline-block;">
                Rendi Revisore questo utente
            </a>
        </p>

        <!-- Link testuale esplicito richiesto dal docente -->
        <p style="margin-top: 20px; font-size: 0.9rem; color: #6c757d; text-align: center;">
            Se il pulsante non funziona, clicca sul link o copialo nel browser:<br>
            <a href="{{ route('revisore.make_with_link', ['user' => $user->id]) }}" style="color: #198754;">
                {{ route('revisore.make_with_link', ['user' => $user->id]) }}
            </a>
        </p>
    </div>
</body>
</html>
