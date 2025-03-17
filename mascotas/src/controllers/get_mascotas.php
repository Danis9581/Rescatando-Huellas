<?php
include '../config/config.php';
session_start();

if (!isset($_SESSION["id"])) {
    echo json_encode([]); // Si no hay usuario, devolver un array vacío
    exit;
}

// Obtener el ID del usuario desde la sesión (aunque no lo usaremos para filtrar)
$usuario_id = $_SESSION["id"];

$especie = isset($_GET['especie']) ? $_GET['especie'] : '';
$raza = isset($_GET['raza']) ? $_GET['raza'] : '';
$color = isset($_GET['color']) ? $_GET['color'] : '';

// Construir la consulta SQL base (sin filtrar por usuario)
$sql = "SELECT *, fecha_reporte FROM mascotas WHERE status = 'perdido'"; // No filtrar por usuario
$parametros = [];
$tipos = "";

// Agregar filtros adicionales si se proporcionan
if ($especie) {
    $sql .= " AND especie = ?";
    $parametros[] = $especie;
    $tipos .= "s";
}
if ($raza) {
    $sql .= " AND raza = ?";
    $parametros[] = $raza;
    $tipos .= "s";
}
if ($color) {
    $sql .= " AND color = ?";
    $parametros[] = $color;
    $tipos .= "s";
}

// Ordenar los resultados por fecha de reporte (más recientes primero)
$sql .= " ORDER BY fecha_reporte DESC";

// Preparar y ejecutar la consulta
$stmt = $conn->prepare($sql);
if ($stmt) {
    if (!empty($parametros)) {
        $stmt->bind_param($tipos, ...$parametros);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $mascotas = [];

    // Recuperar los resultados
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $mascotas[] = $row;
        }
    }

    // Devolver los resultados en formato JSON
    header('Content-Type: application/json');
    echo json_encode($mascotas);

    // Cerrar la consulta
    $stmt->close();
} else {
    // Manejar errores en la preparación de la consulta
    header('Content-Type: application/json');
    echo json_encode(["error" => "Error en la consulta"]);
}

// Cerrar la conexión a la base de datos
$conn->close();
?>