<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../config/config.php';
date_default_timezone_set('Europe/Madrid');

header('Content-Type: application/json');

// Iniciar sesión para acceder al ID del usuario
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si el usuario ha iniciado sesión
    if (!isset($_SESSION["usuario"])) {
        echo json_encode(["success" => false, "mensaje" => "Debes iniciar sesión para reportar una mascota."]);
        exit;
    }

    // Obtener el ID del usuario desde la sesión
    $usuario_id = $_SESSION["id"];

    // Recuperar datos del formulario
    $nombre = $_POST["nombre"];
    $especie = $_POST["especie"];
    $raza = $_POST["raza"] ?? null;
    $color = $_POST["color"];
    $descripcion = $_POST["descripcion"];
    $contacto = $_POST["contacto"];
    $latitud = $_POST["latitud"];
    $longitud = $_POST["longitud"];
    $fecha_reporte = date('Y-m-d H:i:s');

    // Validar los datos del formulario
    $errores = [];

    if (empty($nombre) || strlen($nombre) > 255 || !preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/", $nombre)) {
        $errores['nombre'] = "Nombre inválido. Solo se permiten letras y espacios.";
    }

    if (empty($especie) || strlen($especie) > 255) {
        $errores['especie'] = "Especie inválida.";
    }

    if ($raza !== null && strlen($raza) > 255) {
        $errores['raza'] = "Raza inválida.";
    }

    if ($color !== null && strlen($color) > 255) {
        $errores['color'] = "Color inválido.";
    }

    if (empty($descripcion) || strlen($descripcion) > 500) {
        $errores['descripcion'] = "Descripción inválida.";
    }

    if (empty($contacto) || strlen($contacto) > 255 || !preg_match("/^[0-9+ ]+$/", $contacto)) {
        $errores['contacto'] = "Contacto inválido. Solo se permiten números, el signo '+' y espacios.";
    }

    if (!is_numeric($latitud) || !is_numeric($longitud)) {
        $errores['ubicacion'] = "Latitud y/o longitud inválidas.";
    }

    if (!isset($_FILES['foto']) || $_FILES['foto']['error'] === UPLOAD_ERR_NO_FILE) {
        $errores['foto'] = "Por favor, selecciona una imagen.";
    } else {
        $archivo = $_FILES['foto'];
        $nombre_original = $archivo['name'];
        $tipo_mime = mime_content_type($archivo['tmp_name']);
        $extension = strtolower(pathinfo($nombre_original, PATHINFO_EXTENSION));

        $extensiones_permitidas = ['jpg', 'jpeg', 'png', 'gif'];
        $mime_permitidos = ['image/jpeg', 'image/png', 'image/gif'];

        if (!in_array($extension, $extensiones_permitidas) || !in_array($tipo_mime, $mime_permitidos)) {
            $errores['foto'] = "El archivo debe ser una imagen válida (JPG, PNG o GIF).";
        }

        if ($archivo['size'] > 5 * 1024 * 1024) {
            $errores['foto'] = "El tamaño de la imagen no debe superar los 5MB.";
        }
    }

    // Si hay errores, devolverlos al cliente
    if (!empty($errores)) {
        echo json_encode(["success" => false, "errores" => $errores]);
        exit;
    }

    // Guardar la imagen en el servidor
    $nombre_unico = uniqid() . "." . $extension;
    $fotoRuta = "../../fotos/" . $nombre_unico;

    if (!move_uploaded_file($archivo["tmp_name"], $fotoRuta)) {
        echo json_encode(["success" => false, "mensaje" => "Error al cargar la foto."]);
        exit;
    }

    // Insertar el reporte en la base de datos
    $stmt = $conn->prepare("INSERT INTO mascotas (nombre, especie, raza, color, descripcion, foto, latitud, longitud, contacto, fecha_reporte, usuario_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        echo json_encode(["success" => false, "mensaje" => "Error al preparar la consulta: " . $conn->error]);
        exit;
    }

    $stmt->bind_param("ssssssddssi", $nombre, $especie, $raza, $color, $descripcion, $nombre_unico, $latitud, $longitud, $contacto, $fecha_reporte, $usuario_id);

    if ($stmt->execute()) {
        echo json_encode([
            "success" => true,
            "mensaje" => "Mascota reportada con éxito.",
            "fecha_reporte" => $fecha_reporte // Incluir la fecha de reporte en la respuesta
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "mensaje" => "Error al guardar.",
            "error" => $stmt->error
        ]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "mensaje" => "Método no permitido."]);
}
?>