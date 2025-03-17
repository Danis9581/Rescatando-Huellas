<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluye el archivo de configuración
include __DIR__ . '/../config/config.php';

// Registra el inicio del script
error_log("Inicio de actualizar_contrasena.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si el campo "token" está presente en $_POST
    if (!isset($_POST["token"])) {
        echo json_encode(["success" => false, "mensaje" => "Token no proporcionado."]);
        exit;
    }

    $token = $_POST["token"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Verificar que las contraseñas coincidan
    if ($password !== $confirm_password) {
        echo json_encode(["success" => false, "mensaje" => "Las contraseñas no coinciden."]);
        exit;
    }

    // Verificar si el token existe en la base de datos
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE token = ?");
    if (!$stmt) {
        error_log("Error en la preparación de la consulta: " . $conn->error);
        echo json_encode(["success" => false, "mensaje" => "Error en la base de datos."]);
        exit;
    }
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Token válido: actualizar la contraseña
        $password_hasheado = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE usuarios SET password = ?, token = NULL, expiration_date = NULL WHERE token = ?");
        $stmt->bind_param("ss", $password_hasheado, $token);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "mensaje" => "Contraseña actualizada correctamente."]);
        } else {
            echo json_encode(["success" => false, "mensaje" => "Error al actualizar la contraseña."]);
        }
    } else {
        echo json_encode(["success" => false, "mensaje" => "Token inválido o expirado."]);
    }
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "mensaje" => "Método de solicitud no válido."]);
}

// Registra el final del script
error_log("Fin de actualizar_contrasena.php");
?>