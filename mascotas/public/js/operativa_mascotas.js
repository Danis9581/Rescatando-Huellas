// Lista de razas disponibles por especie
var razasDisponibles = {
    "perro": ["Labrador", "Beagle", "Bulldog", "Pastor Alemán", "Poodle", "Golden Retriever", "Dachshund", "Chihuahua", "Boxer", "Rottweiler", "Cocker Spaniel", "Shih Tzu", "Doberman", "Schnauzer", "Basset Hound"],
    "gato": ["Siames", "Persa", "Bengala", "Siberiano", "Maine Coon", "Burmés", "Abisinio", "Ragdoll", "Exótico de pelo corto", "Sphynx", "American Shorthair", "Birmano", "Oriental", "Himalayo", "Scottish Fold"],
    "otro": ["No especificado"]
};

// Inicialización del mapa con Leaflet
var map = L.map('map', {
    center: [40.4637, -3.7492], 
    zoom: 5, 
    minZoom: 2, 
    maxBounds: [
        [-90, -180], 
        [90, 180]    
    ],
    maxBoundsViscosity: 1.0, // Mantiene al usuario dentro de los límites
    worldCopyJump: false // Evita duplicación del mapa al moverse
});

var markers = []; // Almacena los marcadores del mapa

// Función para inicializar el mapa
function initMap() {
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);
}

// Función para buscar mascotas según los filtros
function buscarMascotas() {
    var especie = document.getElementById("especie").value;
    var raza = document.getElementById("raza").value;
    var color = document.getElementById("color").value;

    // Limpiar marcadores anteriores
    markers.forEach(marker => map.removeLayer(marker));
    markers = [];

    // Obtener el ID del usuario desde el elemento oculto
    const usuarioId = document.getElementById("usuarioId").value;

    // Realizar la solicitud al servidor para obtener mascotas
    fetch(`/mascotas/src/controllers/get_mascotas.php?especie=${especie}&raza=${raza}&color=${color}`)
        .then(response => response.json())
        .then(mascotas => {
            // Agregar marcadores al mapa
            mascotas.forEach(mascota => {
                var radio = Math.floor(Math.random() * 401) + 100; 
                var circle = L.circle([mascota.latitud, mascota.longitud], {
                    color: 'blue',
                    fillColor: 'blue',
                    fillOpacity: 0.3,
                    radius: radio
                }).addTo(map).bindPopup(`
                    <b>${mascota.nombre}</b><br>
                    <img src="/mascotas/fotos/${mascota.foto}" width="100"><br>  
                    ${mascota.descripcion}<br>
                    <a href="tel:${mascota.contacto}">Llamar al reportador de la mascota</a>
                `);
                markers.push(circle);

                // Iniciar la cuenta regresiva solo para los reportes del usuario actual
                if (mascota.usuario_id === parseInt(usuarioId)) {
                    console.log("Fecha de reporte recibida:", mascota.fecha_reporte); // Verifica la fecha recibida
                    iniciarCuentaRegresiva(mascota.fecha_reporte, mascota.id, circle);
                }
            });
        })
        .catch(error => console.error('Error cargando los datos:', error));
}

let intervaloCuentaRegresiva = null; // Variable para almacenar el intervalo de la cuenta regresiva

// Función para iniciar la cuenta regresiva de un reporte
function iniciarCuentaRegresiva(fechaReporte, mascotaId, marker) {
    // Limpiar el intervalo anterior si existe
    if (intervaloCuentaRegresiva !== null) {
        clearInterval(intervaloCuentaRegresiva);
    }

    // Convertir la fecha de reporte a un objeto Date
    const fechaReporteObj = new Date(fechaReporte + "Z"); // Agrega "Z" para indicar que es UTC
    console.log("Fecha de reporte convertida:", fechaReporteObj); // Verifica la conversión

    if (isNaN(fechaReporteObj.getTime())) {
        console.error("Fecha de reporte inválida:", fechaReporte);
        return;
    }

    // Calcular la fecha de caducidad (5 minutos después)
    const fechaCaducidad = new Date(fechaReporteObj.getTime() + 5 * 60 * 1000); // 5 minutos en milisegundos
    console.log("Fecha de caducidad:", fechaCaducidad); // Verifica la fecha de caducidad

    function actualizarCuentaRegresiva() {
        const ahora = new Date();
        const diferencia = fechaCaducidad - ahora;

        console.log("Diferencia de tiempo:", diferencia); // Verifica la diferencia de tiempo

        if (diferencia > 0) {
            const minutos = Math.floor((diferencia / (1000 * 60)) % 60);
            const segundos = Math.floor((diferencia / 1000) % 60);
            // Mostrar el contador en el lugar original
            document.getElementById("cuentaRegresiva").innerHTML = `Su reporte caducará en: ${minutos}m ${segundos}s.`;
        } else {
            // Limpiar el mensaje de cuenta regresiva
            document.getElementById("cuentaRegresiva").innerHTML = "Su reporte anterior ha caducado.";
            // Llamada AJAX para eliminar el reporte
            fetch(`/mascotas/src/controllers/eliminar_mascota.php?id=${mascotaId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Eliminar el marcador del mapa y de la lista de marcadores
                        map.removeLayer(marker);
                        markers = markers.filter(m => m !== marker);

                        buscarMascotas(); // Recargar los datos del mapa
                    } else {
                        alert("Error al eliminar el reporte: " + data.mensaje);
                    }
                })
                .catch(error => console.error("Error:", error));

            // Detener el intervalo cuando el reporte caduca
            clearInterval(intervaloCuentaRegresiva);
        }
    }

    // Limpiar el mensaje de caducidad si está visible
    document.getElementById("cuentaRegresiva").innerHTML = "";

    // Iniciar la cuenta regresiva
    actualizarCuentaRegresiva();
    intervaloCuentaRegresiva = setInterval(actualizarCuentaRegresiva, 1000); // Actualizar cada segundo
}
// Función para mostrar el formulario de reporte
function mostrarFormulario() {
    document.getElementById("map").style.display = "none";
    document.getElementById("filtros").style.display = "none";
    document.getElementById("contButton").style.display = "none";
    document.getElementById("h2Mapa").style.display = "none";
    document.getElementById("formReporte").style.display = "block";
}

// Función para obtener la ubicación del usuario
function obtenerUbicacion() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                var latitud = position.coords.latitude;
                var longitud = position.coords.longitude;

                document.getElementById("latitud").value = latitud;
                document.getElementById("longitud").value = longitud;

                map.setView([latitud, longitud], 13);
                L.marker([latitud, longitud]).addTo(map).bindPopup("Tu ubicación");
            },
            function(error) {
                alert("Error obteniendo la ubicación: " + error.message);
            }
        );
    } else {
        alert("Tu navegador no soporta la geolocalización.");
    }
}

// Evento para manejar el envío del formulario de reporte
document.getElementById("formReporteMascota").addEventListener("submit", function(event) {
    event.preventDefault();

    // Limpiar mensajes de error
    document.querySelectorAll('.error-message').forEach(el => el.textContent = '');

    var formData = new FormData(this);

    fetch("/mascotas/src/controllers/add_mascota.php", {
        method: "POST",
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById("modalExito").style.display = "flex";
                buscarMascotas();
                document.getElementById("formReporte").style.display = "none";
                document.getElementById("map").style.display = "block";
                document.getElementById("filtros").style.display = "block";
                document.getElementById("contButton").style.display = "block";
                document.getElementById("h2Mapa").style.display = "block";
            } else if (data.errores) {
                let firstErrorField = null; // Variable para almacenar el primer campo con error

                for (const campo in data.errores) {
                    const errorSpan = document.getElementById(campo + "-error");
                    if (errorSpan) {
                        errorSpan.textContent = "❌ " + data.errores[campo];
                        const inputField = document.getElementById(campo);
                        if (inputField && !firstErrorField) {
                            firstErrorField = inputField; // Almacena el primer campo con error encontrado
                        }
                    }
                }

                if (firstErrorField) {
                    firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' }); // Lleva el foco al primer campo con error
                    firstErrorField.focus(); // Opcional: enfoca el campo
                }
            } else if (data.mensaje) {
                alert(data.mensaje);
            }
        })
        .catch(error => console.error("Error:", error));
});

// Función para actualizar las razas disponibles según la especie seleccionada
function actualizarRazas(selectRaza) {
    var especie = document.getElementById("especie").value;
    selectRaza.innerHTML = '<option value="">Todas</option>';
    if (selectRaza.id === "razaFormulario") {
        selectRaza.innerHTML = '<option value="">Seleccionar raza</option>';
    }

    if (razasDisponibles[especie]) {
        razasDisponibles[especie].forEach(raza => {
            var option = document.createElement("option");
            option.value = raza.toLowerCase();
            option.textContent = raza;
            selectRaza.appendChild(option);
        });
    }
}

// Evento para actualizar las razas cuando cambia la especie
document.getElementById("especie").addEventListener("change", function() {
    actualizarRazas(document.getElementById("raza"));
    actualizarRazas(document.getElementById("razaFormulario"));
    buscarMascotas();
});

// Función para actualizar las razas en el formulario de reporte
function actualizarRazasFormulario() {
    var especie = document.getElementById("especieFormulario").value;
    var selectRaza = document.getElementById("razaFormulario");
    selectRaza.innerHTML = '<option value="">Seleccionar raza</option>';

    if (razasDisponibles[especie]) {
        razasDisponibles[especie].forEach(raza => {
            var option = document.createElement("option");
            option.value = raza.toLowerCase();
            option.textContent = raza;
            selectRaza.appendChild(option);
        });
    }
}

// Función para cerrar el modal de éxito
function cerrarModal() {
    document.getElementById("modalExito").style.display = "none";
    volverAlMapa(); 
}

// Función para regresar al mapa
function volverAlMapa() {
    document.getElementById("formReporte").style.display = "none";
    document.getElementById("map").style.display = "block";
    document.getElementById("filtros").style.display = "block";
    document.getElementById("contButton").style.display = "flex";
    document.getElementById("h2Mapa").style.display = "block";
}

// Función para iniciar la aplicación
function iniciar() {
    if (document.getElementById("map")) {
        initMap();
        actualizarRazas(document.getElementById("raza"));
        buscarMascotas();
    }
}

// Iniciar la aplicación cuando la página se cargue
window.onload = iniciar;