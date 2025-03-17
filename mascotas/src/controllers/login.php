<?php
session_start();
include '../config/config.php';

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Modifica la consulta para seleccionar también el nombre del usuario
    $stmt = $conn->prepare("SELECT id, email, password, nombre FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $email_db, $password_db, $nombre_db);
        $stmt->fetch();

        if (password_verify($password, $password_db)) {
            // Almacena el nombre del usuario en la sesión
            $_SESSION["usuario"] = $nombre_db;
            $_SESSION["id"] = $id; // Almacenar el ID del usuario en la sesión
            echo json_encode(["success" => true]);
            exit();
        } else {
            echo json_encode(["success" => false, "error" => "Contraseña incorrecta"]);
            exit();
        }
    } else {
        echo json_encode(["success" => false, "error" => "El usuario no existe"]);
        exit();
    }

    $stmt->close();
}
$conn->close();
?>