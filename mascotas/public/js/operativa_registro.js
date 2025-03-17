document.addEventListener("DOMContentLoaded", function () {
    const formRegistro = document.getElementById("registro-form");
    const mensajeRegistro = document.getElementById("registro-exitoso");
    const botonContinuar = document.getElementById("continuar");
    const btnVolverLogin = document.getElementById("volver-login"); 

    mensajeRegistro.classList.add("hidden");

    if (formRegistro) {
        formRegistro.addEventListener("submit", function (event) {
            event.preventDefault();

            const nombreError = document.getElementById("nombre-error");
            const emailError = document.getElementById("email-error");
            const passwordError = document.getElementById("password-error");

            nombreError.textContent = "";
            emailError.textContent = "";
            passwordError.textContent = "";

            let formData = new FormData(formRegistro);

            fetch("/mascotas/src/controllers/registrar.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Ocultamos el formulario y mostramos el mensaje de éxito
                    formRegistro.style.display = "none";
                    mensajeRegistro.classList.remove("hidden");
                } else {
                    if (data.errores) {
                        for (const campo in data.errores) {
                            const errorSpan = document.getElementById(campo + "-error");
                            if (errorSpan) {
                                errorSpan.textContent = "❌ " + data.errores[campo];
                            }
                        }
                    } else if (data.mensaje) {
                        alert(data.mensaje);
                    }
                }
            })
            .catch(error => console.error("Error en el registro:", error));
        });
    }
     if (botonContinuar) {
        botonContinuar.addEventListener("click", function () {
            window.location.href = "/";
        });
    }

});
