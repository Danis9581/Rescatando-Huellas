<!-- Página token.php: Muestra un mensaje de "Token expirado" y permite al usuario volver a la página de solicitud para intentarlo nuevamente. -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Token Expirado</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
</head>
<body>
    <div class="login-container">
        <!-- <div class="modalToken"> -->
            <div class="modalToken">
                <h2>¡Tiempo agotado!</h2>
                <p>Se acabó el tiempo para restablecer la contraseña. Por favor, vuelve a solicitar un enlace.</p>
                <button onclick="window.location.href='recuperar_contrasena_form.php'">Volver a solicitar enlace</button>
            </div>
        <!-- </div> -->
    </div>
</body>
</html>