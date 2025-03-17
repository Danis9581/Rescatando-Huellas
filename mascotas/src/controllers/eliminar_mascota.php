<?php
include '../config/config.php';

// Verificar si se ha proporcionado un ID en la solicitud GET
if (isset($_GET['id'])) {
    $id = $_GET['id']; 

    // Preparar la consulta SQL para eliminar la mascota con el ID proporcionado
    $stmt = $conn->prepare("DELETE FROM mascotas WHERE id = ?");
    $stmt->bind_param("i", $id); // Vincular el parámetro ID a la consulta

    // Ejecutar la consulta y verificar si fue exitosa
    if ($stmt->execute()) {
        // Enviar respuesta JSON indicando éxito
        echo json_encode(["success" => true, "mensaje" => "Mascota eliminada con éxito."]);
    } else {
        echo json_encode(["success" => false, "mensaje" => "Error al eliminar la mascota."]);
    }

    // Cerrar la declaración y la conexión a la base de datos
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "mensaje" => "ID de mascota no proporcionado."]);
}
?>