<!-- Página recuperar_contrasena_form.php: Permite al usuario restablecer su contraseña mediante la verificación de su correo electrónico.-->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="/mascotas/public/css/styles.css">
</head>
<body>
    <div class="login-container">
        <form id="recuperar-form" action="/mascotas/src/controllers/recuperar_contrasena.php" method="post">
            <h2>Recuperar Contraseña</h2>
            <p>Ingresa tu email para recibir un enlace de restablecimiento:</p>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" placeholder="Email" required>
            <span id="email-error" class="error-message"></span>
            <button type="submit">Enviar enlace</button>
            <a href="/" id="volver-login"><i class="fas fa-arrow-left"></i> Volver Login</a>
            <!--<button type="button" id="volver-login"><i class="fas fa-arrow-left"></i> Volver Login</button>-->
        </form>
        <div id="mensaje-confirmacion" style="display: none;">
            <p id="mensaje-texto"></p>
            <button id="cerrar-mensaje">Cerrar</button>
        </div>
    </div>
    <script src="/mascotas/public/js/operativa_recuperar.js"></script>
</body>
</html>