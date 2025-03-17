<!-- Página registro.php: En esta página, los usuarios pueden registrarse para obtener una cuenta en el sitio. -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de usuario</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="/mascotas/public/css/styles.css">
</head>
<body>
    <form id="registro-form" action="/mascotas/src/controllers/registrar.php" method="post">
        <h2>Registro</h2>
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" placeholder="Nombre"required>
        <span id="nombre-error" class="error-message"></span><br><br>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" placeholder="Email" required>
        <span id="email-error" class="error-message"></span><br><br>

        <label for="password">Contraseña:</label>
        <div class="password-container">
            <input type="password" id="password" name="password" placeholder="Contraseña" required>
            <i class="fa-solid fa-eye toggle-password" onclick="togglePassword('password')"></i>
        </div> 
        <span id="password-error" class="error-message"></span><br><br>
        <div class="botones-container">
            <button  type="submit">Registrarse</button>
            <a href="/" id="volver-login"><i class="fas fa-arrow-left"></i> Volver Login</a>
            <!--button type="button" id="volver-login"><i class="fas fa-arrow-left"></i> Volver Login</button>-->
       </div>
    </form>
    <div id="registro-exitoso" class="hidden">
        <h3>¡Bienvenido!</h3>
        <div class="infoReporte">
            <h4>Importante sobre los reportes de mascotas</h4>
            <p>Cuando reportas una mascota perdida o encontrada, el aviso permanecerá activo en nuestra plataforma durante 60 días a partir de la fecha del reporte.</p>
            <p>Si después de ese tiempo aún necesitas que el reporte siga visible, deberás volver a publicarlo para extender su duración por 60 días más. Esto nos ayuda a mantener la información actualizada y relevante para todos los usuarios.</p>
            <p>¡Gracias por ayudar a reunir a las mascotas con sus familias!</p>
        </div>
        <div class="modal-contenido">
            <div class="seccion">
                <h4>🔍 Buscar mascota</h4>
                <img src="/mascotas/public/img/buscar.jpg" alt="Buscar Mascota">
                <p>Usa nuestra plataforma para encontrar a tu mascota perdida en tu área. Puedes filtrar por tipo de animal, raza o color.</p>
            </div>
            <div class="seccion">
                <h4>📢 Reportar mascota</h4>
                <img src="/mascotas/public/img/reportar.jpg" alt="Reportar Mascota">
                <p>Si has encontrado una mascota perdida, repórtala en nuestra plataforma para ayudar a reunirla con su dueño.</p>
            </div>
        </div>
        <button id="continuar">Todo listo</button>
    </div>    
    
    <script src="/mascotas/public/js/operativa_registro.js"></script>
    <script src="/mascotas/public/js/operativa_login.js"></script>
</body>
</html>