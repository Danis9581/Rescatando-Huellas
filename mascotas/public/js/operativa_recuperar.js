document.addEventListener("DOMContentLoaded", function () {
    const recuperarForm = document.getElementById("recuperar-form");
    const restablecerForm = document.getElementById("restablecer-form");
    const mensajeConfirmacion = document.getElementById("mensaje-confirmacion");
    const mensajeTexto = document.getElementById("mensaje-texto");
    const cerrarMensaje = document.getElementById("cerrar-mensaje");
    const btnVolverLogin = document.getElementById("volver-login");

    // Manejar el formulario de solicitud de restablecimiento de contraseña
    if (recuperarForm) {
        recuperarForm.addEventListener("submit", function (event) {
            event.preventDefault();
            const email = document.getElementById("email").value.trim();
            const emailError = document.getElementById("email-error");
            emailError.textContent = "";
            let formData = new FormData(recuperarForm);
            fetch("/mascotas/src/controllers/recuperar_contrasena.php", { 
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mensajeTexto.textContent = data.mensaje;
                    recuperarForm.style.display = "none"; 
                    mensajeConfirmacion.style.display = "block"; 
                } else {
                    emailError.textContent = data.mensaje;
                }
            })
            .catch(error => console.error("Error:", error));
        });
    }

    // Manejar el formulario de restablecimiento de contraseña
    if (restablecerForm) {
        restablecerForm.addEventListener("submit", function (event) {
            event.preventDefault();
            const password = document.getElementById("password").value.trim();
            const confirmPassword = document.getElementById("confirm_password").value.trim();
            const token = document.querySelector("input[name='token']").value;

            if (password !== confirmPassword) {
                alert("Las contraseñas no coinciden.");
                return;
            }

            let formData = new FormData(restablecerForm);
            fetch("/mascotas/src/controllers/actualizar_contrasena.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mensajeTexto.textContent = data.mensaje;
                    restablecerForm.style.display = "none"; 
                    mensajeConfirmacion.style.display = "block"; 
                } else {
                    alert(data.mensaje);
                }
            })
            .catch(error => console.error("Error:", error));
        });
    }

    //if (btnVolverLogin) {
      //  btnVolverLogin.addEventListener("click", function () {
        //    window.location.href = "/";
        //});
    //}

    // Cerrar el mensaje de confirmación y redirigir al inicio de sesión
    if (cerrarMensaje) {
        cerrarMensaje.addEventListener("click", function () {
            window.location.href = "/"; 
        });
    }
});