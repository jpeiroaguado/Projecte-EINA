<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Restablir contrasenya</title>
</head>
<body style="font-family: 'Roboto', sans-serif; background-color: #f4f4f4; padding: 2rem;">
    <div style="max-width: 600px; margin: auto; background: white; padding: 2rem; border-radius: 10px;">
        <div style="text-align: center;">
            <img src="{{ $logo }}" alt="Logo EINA" style="max-width: 150px; margin-bottom: 1rem;">
            <h2 style="color: #4f46e5;">¡Hola desde EINA!</h2>
        </div>

        <p>Estàs rebent aquest correu perquè s’ha sol·licitat el restabliment de la contrasenya del teu compte.</p>

        <div style="text-align: center; margin: 2rem 0;">
            <a href="{{ $url }}" style="background: #4f46e5; color: white; padding: 10px 20px; border-radius: 6px; text-decoration: none;">Restablir la contrasenya</a>
        </div>

        <p>Aquest enllaç caducarà en 60 minuts.</p>

        <p>Si no has fet aquesta sol·licitud, no cal que faces res.</p>

        <p style="margin-top: 2rem;">Salutacions,<br><strong>EINA</strong></p>
    </div>
</body>
</html>
