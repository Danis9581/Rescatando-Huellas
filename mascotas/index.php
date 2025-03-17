//Página inicial donde el usuario podrá acceder a la pàgina, registrarse o restablecer contraseña.
//Regular rutas.
<?php
$url = isset($_GET['url']) ? $_GET['url'] : '';

switch ($url) {
    case 'registro':
        include 'mascotas/src/views/registro.php';
        break;
    case 'recuperar-contrasena':
        include 'mascotas/src/views/recuperar_contrasena_form.php';
        break;
    case 'login':
        include 'mascotas/src/views/login.php';
        break;
    case 'mapa':
        include 'mascotas/src/views/mapa.php';
        break;
    case '':
        // Página de inicio (index)
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Rescatando Huellas - Portal de Mascotas Perdidas</title>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
            <link rel="stylesheet" href="mascotas/public/css/styles.css">
        </head>
        <body>
            <div id="bodyIndex">
                <div class="hero">
                    <div class="hero-content">
                        <h1>Rescatando Huellas</h1>
                        <p>Cada paso cuenta. Ayudemos a que regresen a casa.</p>
                    </div>
                </div>

                <div class="login-container">
                    <h2>Iniciar Sesión</h2>
                    <form method="POST" id="login-form">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" placeholder="Email" required>
                        <span id="email-error" class="error-message"></span>
                    
                        <label for="password">Contraseña:</label>
                        <div class="password-container">
                            <input type="password" id="password" name="password" placeholder="Contraseña" required>
                            <i class="fa-solid fa-eye toggle-password" onclick="togglePassword('password')"></i>
                        </div> 
                        <span id="password-error" class="error-message"></span>
                    
                        <button type="submit">Acceder</button>
                    </form>            
                    <p>¿No tienes cuenta? <a href="/registro">Registrarse</a></p>
                    <a href="/recuperar-contrasena">¿Has olvidado tu contraseña?</a>
                </div>
            </div>
            <script src="mascotas/public/js/operativa_login.js"></script>
        </body>
        </html>
        <?php
        break;
}
?>
