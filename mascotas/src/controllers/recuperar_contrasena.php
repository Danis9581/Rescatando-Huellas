<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluye el archivo de configuración
include __DIR__ . '/../config/config.php';

//function index() {
  //  require_once __DIR__ . '/../views/recuperar_contrasena_form.php';
//}

// Registra el inicio del script
error_log("Inicio de recuperar_contrasena.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    // Registra el email recibido
    error_log("Email recibido: " . $email);

    // Verificar si el email existe en la base de datos
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    if (!$stmt) {
        error_log("Error en la preparación de la consulta: " . $conn->error);
        echo json_encode(["success" => false, "mensaje" => "Error en la base de datos."]);
        exit;
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Registra que el email existe
        error_log("Email encontrado en la base de datos");

        // Generar token único
        $token = bin2hex(random_bytes(50));

        // Establecer la fecha de expiración (5 minutos)
        $expiration_date = date('Y-m-d H:i:s', strtotime('+5 minutes'));

        // Almacenar token y fecha de expiración en la base de datos
        $stmt = $conn->prepare("UPDATE usuarios SET token = ?, expiration_date = ? WHERE email = ?");
        $stmt->bind_param("sss", $token, $expiration_date, $email);
        $stmt->execute();

        // Enviar email con enlace de restablecimiento
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'danis9581@gmail.com';
            $mail->Password = 'qjbh iuqn gghs iwqy';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('danis9581@gmail.com', 'Rescatando Huellas');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Restablecer Contraseña';
            $mail->Body = 'Haz clic en el siguiente enlace para restablecer tu contraseña: <a href="http://localhost/mascotas/src/views/restablecer_contrasena.php?token=' . $token . '">Restablecer Contraseña</a>';

            $mail->send();
            error_log("Correo enviado con éxito");
            echo json_encode(["success" => true, "mensaje" => "Se ha enviado un enlace a tu email."]);
        } catch (Exception $e) {
            error_log("Error al enviar el email: " . $mail->ErrorInfo);
            echo json_encode(["success" => false, "mensaje" => "Error al enviar el email: {$mail->ErrorInfo}"]);
        }
    } else {
        error_log("Email no encontrado en la base de datos");
        echo json_encode(["success" => false, "mensaje" => "El email no existe."]);
    }
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "mensaje" => "Método de solicitud no válido."]);
}

// Registra el final del script
error_log("Fin de recuperar_contrasena.php");
?>