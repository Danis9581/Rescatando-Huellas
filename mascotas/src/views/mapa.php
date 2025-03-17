<!-- Página mapa.php: En esta página se encuentra la funcionalidad principal del sitio, que incluye un mapa interactivo para visualizar mascotas reportadas. Los usuarios pueden filtrar las mascotas por especie, raza y color. Además, hay un botón para reportar una mascota, el cual abre un formulario intuitivo para realizar el reporte de manera sencilla. -->
<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: /"); // Usa la URL amigable
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa de Mascotas Perdidas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="/mascotas/public/css/styles.css">

</head>
<body>
    <div class="container">
        <h2 id="h2Mapa">Mapa de Mascotas Perdidas</h2>
        <p id="pMapa">Bienvenido, <?php echo $_SESSION["usuario"]; ?> | <a href="/mascotas/src/controllers/logout.php">Cerrar sesión</a></p>
        <input type="hidden" id="usuarioId" value="<?php echo $_SESSION["id"]; ?>">
        <!-- AQUI QUEREMOS PONER LA CUENTA ATRAS DE DIAS -->
        <div id="cuentaRegresiva"></div>
        <div id="filtros">
            <div class="filtro-fila">
                <label>Animal:</label>
                <select id="especie" onchange="buscarMascotas()">
                    <option value="">Todas</option>
                    <option value="perro">Perro</option>
                    <option value="gato">Gato</option>
                    <option value="otro">Otro</option>
                </select>
            </div>
            <div class="filtro-fila">
                <label>Raza:</label>
                <select id="raza" onchange="buscarMascotas()">
                    <option value="">Todas</option>
                </select>
            </div>
            <div class="filtro-fila">
                <label>Color:</label>
                <select id="color" onchange="buscarMascotas()">
                    <option value="">Todos</option>
                    <option value="blanco">Blanco</option>
                    <option value="negro">Negro</option>
                    <option value="marron">Marrón</option>
                    <option value="gris">Gris</option>
                </select>
            </div>
        </div>

        <div id="map"></div>
        <div id="contButton">
            <p>Para hacer un reporte de una mascota haz click aquí:</p>
            <button id="btnReportar" onclick="mostrarFormulario()">Reportar Perdida</button>
        </div>
        <div id="formReporte" style="display:none;">
            <h3>Reportar Mascota Perdida</h3>
            <form id="formReporteMascota" enctype="multipart/form-data" action="/mascotas/src/controllers/add_mascota.php" method="POST">
                <label>Nombre:</label>
                <input type="text" name="nombre" id="nombre">
                <span id="nombre-error" class="error-message"></span><br><br>

                <label>Animal:</label>
                <select name="especie" id="especieFormulario" onchange="actualizarRazasFormulario()">
                    <option value="" selected disabled>Seleccionar Animal</option>
                    <option value="perro">Perro</option>
                    <option value="gato">Gato</option>
                    <option value="otro">Otro</option>
                </select>
                <span id="especie-error" class="error-message"></span><br><br>

                <label>Raza:</label>
                <select name="raza" id="razaFormulario">
                    <option value="">Seleccionar raza</option>
                </select>
                <span id="raza-error" class="error-message"></span><br><br>

                <label>Color:</label>
                <select name="color" id="colorFormulario">
                    <option value="">Seleccionar color</option>
                    <option value="blanco">Blanco</option>
                    <option value="negro">Negro</option>
                    <option value="marron">Marrón</option>
                    <option value="gris">Gris</option>
                </select>
                <span id="color-error" class="error-message"></span><br><br>

                <label>Descripción:</label>
                <textarea name="descripcion" id="descripcion"></textarea>
                <span id="descripcion-error" class="error-message"></span><br><br>

                <label>Foto:</label>
                <input type="file" name="foto" accept="image/*" id="foto">
                <span id="foto-error" class="error-message"></span><br><br>

                <label>Contacto:</label>
                <input type="text" name="contacto" id="contacto">
                <span id="contacto-error" class="error-message"></span><br><br>

                <label>Ubicación:</label>
                <input type="text" id="latitud" name="latitud">
                <input type="text" id="longitud" name="longitud">
                <button class="buttonVerde" type="button" onclick="obtenerUbicacion()">Usar mi ubicación</button>
                <span id="ubicacion-error" class="error-message"></span><br><br>
                <div id="butonsflex">
                    <button class="buttonVerde" id="buttonGrande" type="submit">Reportar</button>
                    <button class="buttonVerde" onclick="volverAlMapa()"><i class="fas fa-arrow-left"></i>Mapa</button>
                </div>
            </form>
        </div>
        <div id="modalExito" class="modal">
            <div class="modal-content">
                <p>Mascota reportada con éxito.</p>
                <button onclick="cerrarModal()">Aceptar</button>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/leaflet/dist/leaflet.js"></script>
    <script src="/mascotas/public/js/operativa_mascotas.js"></script>

</body>
</html>


