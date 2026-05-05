/**
 * Created by Andre on 12/03/2016.
 */

/**
 * Cambios respecto a la versión original:
 *  - Campos vacíos: toast de advertencia en lugar de fallo silencioso
 *  - Credenciales incorrectas: toast de error en lugar de cargar responseLogin.html
 *  - Error de servidor/red: toast de error en lugar de alert()
 *  - Error inesperado al parsear respuesta: toast de error en lugar de console.log silencioso
 */

$(function(){
    $("#login").click(function(){

        var username = document.getElementById("username").value;
        var password = document.getElementById("password").value;

        // ── Validación de campos vacíos ────────────────────────────────
        if (username === "" && password === "") {
            Toast.warning("Por favor, ingresa tu usuario y contraseña.");
            return;
        }
        if (username === "") {
            Toast.warning("Por favor, ingresa tu nombre de usuario.");
            return;
        }
        if (password === "") {
            Toast.warning("Por favor, ingresa tu contraseña.");
            return;
        }

        var data = {
            username: username,
            password: password
        };
        $.ajax({
            url: 'core/php/Login.php',
            data:data,
            type: 'post',
            beforeSend: function () {
                $("#loginResponse").html(
                    "<p class='text-muted'>Verificando credenciales, espere...</p>"
                );
                $("#login").prop("disabled", true);
            },
            success: function (response) {
                $("#loginResponse").html("");
                $("#login").prop("disabled", false);
                verifyUser(response);
            },
            error:function(){
                $("#loginResponse").html("");
                $("#login").prop("disabled", false);

                // ── Error de red o servidor ────────────────────────────
                if (status === "timeout") {
                    Toast.error("La solicitud tardó demasiado. Verifica tu conexión e intenta de nuevo.");
                } else {
                    Toast.error("No se pudo conectar con el servidor. Verifica tu conexión.");
                }
            },
            timeout: 10000
        });
    })
});

function verifyUser(response){
    try {
        var userResponse = JSON.parse(response);

        if (userResponse !== null) {
            var student = 0;
            var teacher = 1;

            if (userResponse[0].type == student) {
                location.href = "sections/MenuStudent.html";
            } else if (userResponse[0].type == teacher) {
                location.href = "sections/MenuTeacher.html";
            } else {
                // ── Tipo de usuario desconocido ────────────────────────
                Toast.error("Tu cuenta no tiene un tipo de usuario válido. Contacta al administrador.");
            }

        } else {
            // ── Credenciales incorrectas ───────────────────────────────
            Toast.error("Usuario o contraseña incorrectos. Por favor, inténtalo de nuevo.");
        }

    } catch (e) {
        // ── Respuesta inesperada del servidor ──────────────────────────
        console.error("Error al procesar la respuesta del servidor:", e);
        Toast.error("Ocurrió un error inesperado. Por favor, intenta de nuevo.");
    }
}

