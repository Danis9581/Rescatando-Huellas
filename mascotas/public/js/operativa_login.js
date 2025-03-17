// Espera a que el DOM esté completamente cargado antes de ejecutar el script
document.addEventListener("DOMContentLoaded", function () {
    const loginForm = document.getElementById("login-form");

    // Verifica si el formulario de login existe en la página
    if (loginForm) {
        // Escucha el evento 'submit' del formulario
        loginForm.addEventListener("submit", function (event) {
            event.preventDefault(); // Evita el envío tradicional del formulario

            // Obtiene los valores de los campos de email y contraseña
            const email = document.getElementById("email").value.trim();
            const password = document.getElementById("password").value.trim();
            const emailError = document.getElementById("email-error");
            const passwordError = document.getElementById("password-error");

            // Limpia los mensajes de error previos
            emailError.textContent = "";
            passwordError.textContent = "";

            // Prepara los datos del formulario para enviar
            let formData = new FormData(loginForm);

            // Envía los datos del formulario al servidor usando Fetch API
            fetch("/mascotas/src/controllers/login.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json()) // Convierte la respuesta a JSON
            .then(data => {
                console.log(data); // Log de la respuesta para depuración
                if (data.success) {
                    // Redirige al usuario si el login es exitoso
                    window.location.href = "/mapa";
                } else {
                    // Muestra mensajes de error específicos
                    if (data.error === "El usuario no existe") {
                        emailError.textContent = "❌ " + data.error;
                    }
                    if (data.error === "Contraseña incorrecta") {
                        passwordError.textContent = "❌ " + data.error;
                    }
                }
            })
            .catch(error => console.error("Error en el login:", error)); // Manejo de errores
        });
    }
});

// Función para alternar la visibilidad de la contraseña
function togglePassword(fieldId) {
    let passwordField = document.getElementById(fieldId);
    let icon = passwordField.nextElementSibling;

    // Cambia el tipo de campo de texto a password y viceversa
    if (passwordField.type === "password") {
        passwordField.type = "text"; // Muestra la contraseña
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        passwordField.type = "password"; // Oculta la contraseña
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}