<?php
include '../config/config.php';

//function index() {
  //  require_once __DIR__ . '/../views/registro.php';
//}

function validarNombre($nombre) {
    return preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/", $nombre);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    $errores = []; // Array para almacenar los errores

    // Validación del nombre
    if (empty($nombre) || strlen($nombre) < 3) {
        $errores['nombre'] = "Nombre muy corto (mín. 3 caracteres).";
    } elseif (strlen($nombre) > 255 || !validarNombre($nombre)) {
        $errores['nombre'] = "Nombre inválido. Solo se permiten letras y espacios.";
    }

    // Validación del email
    if (empty($email) || !preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email)) {
        $errores['email'] = "Email inválido.";
    }

    // Verificación de email existente
    $stmt = $conn->prepare("SELECT email FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $errores['email'] = "El email ya está registrado.";
    }

    $stmt->close();

    // Validación de la contraseña
    if (empty($password) || strlen($password) < 8) {
        $errores['password'] = "Contraseña muy corta (mín. 8 caracteres).";
    } elseif (!preg_match("/[A-Z]/", $password)) {
        $errores['password'] = "La contraseña debe contener al menos una letra mayúscula.";
    } elseif (!preg_match("/[a-z]/", $password)) {
        $errores['password'] = "La contraseña debe contener al menos una letra minúscula.";
    } elseif (!preg_match("/[0-9]/", $password)) {
        $errores['password'] = "La contraseña debe contener al menos un número.";
    } elseif (!preg_match("/[^A-Za-z0-9]/", $password)) {
        $errores['password'] = "La contraseña debe contener al menos un carácter especial.";
    }

    // Si hay errores, se envían al cliente
    if (!empty($errores)) {
        echo json_encode(["success" => false, "errores" => $errores]);
        exit;
    }

    // Si no hay errores, se procede al registro del usuario
    $password_hasheado = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nombre, $email, $password_hasheado);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "mensaje" => "Registro exitoso. Ahora puedes iniciar sesión."]);
    } else {
        echo json_encode(["success" => false, "mensaje" => "Error al registrar el usuario."]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "mensaje" => "Método de solicitud no válido."]);
}
?>