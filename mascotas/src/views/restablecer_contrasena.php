<!-- Página restablecer_contrasena.php: En esta página, el usuario puede acceder a un formulario sencillo para establecer una nueva contraseña. -->
<?php
include '../config/config.php';
session_start();

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Verificar si el token existe en la base de datos
    $stmt = $conn->prepare("SELECT email, expiration_date FROM usuarios WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($email, $expiration_date);
        $stmt->fetch();

        // Verificar si el token ha expirado
        if (strtotime($expiration_date) <= time()) {
            // Token expirado: eliminarlo de la base de datos
            $stmt_clean = $conn->prepare("UPDATE usuarios SET token = NULL, expiration_date = NULL WHERE token = ?");
            $stmt_clean->bind_param("s", $token);
            $stmt_clean->execute();
            $stmt_clean->close();

            // Redirigir a la página de token expirado
            header("Location: ../../public/token_expirado.php");
            exit();
        } else {
            // Token válido: mostrar el formulario de restablecimiento
            ?>
            <!DOCTYPE html>
            <html lang="es">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Restablecer Contraseña</title>
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
                <link rel="stylesheet" href="../../public/css/styles.css">
            </head>
            <body>
                <div class="login-container">
                    <form id="restablecer-form" method="post">
                        <h2>Restablecer Contraseña</h2>
                        <label for="password">Nueva Contraseña:</label>
                        <div class="password-container">
                            <input type="password" name="password" id="password" placeholder="Coontraseña" required>
                            <i class="fa-solid fa-eye toggle-password" onclick="togglePassword('password')"></i>
                        </div>
                        <label for="confirm_password">Confirmar Contraseña:</label>
                        <div class="password-container">
                            <input type="password" name="confirm_password" id="confirm_password" placeholder="Coontraseña" required>
                            <i class="fa-solid fa-eye toggle-password" onclick="togglePassword('confirm_password')"></i>
                        </div>
                        <input type="hidden" name="token" value="<?php echo $token; ?>">
                        <button type="submit">Restablecer Contraseña</button>
                    </form>
                    <div id="mensaje-confirmacion" style="display: none;">
                        <p id="mensaje-texto"></p>
                        <button id="cerrar-mensaje">Cerrar</button>
                    </div>
                </div>
                <script src="/mascotas/public/js/operativa_recuperar.js"></script>
                <script src="/mascotas/public/js/operativa_login.js"></script>
            </body>
            </html>
            <?php
        }
    } else {
        // Token inválido (no existe en la base de datos)
        header("Location: token_expirado.php");
        exit();
    }
    $stmt->close();
} else {
    // Token no proporcionado en la URL
    header("Location: token_expirado.php");
    exit();
}

$conn->close();
?>