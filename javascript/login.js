/**
 * Created by Andre on 12/03/2016.
 */

$(function(){
    $("#login").click(function(){

        var username = document.getElementById("username").value;
        var password = document.getElementById("password").value;

        /*
         * MR 2026.4 | Preventivo | Manuel Cupul
         * Validación de campos vacíos antes de hacer la petición al servidor.
         * Antes no había validación: el login simplemente no respondía.
         */
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

                /*
                * MR 2026.4 | Preventivo | Manuel Cupul
                * Antes el error de red producía un alert() genérico.
                * Ahora se diferencia entre timeout y error de servidor.
                */
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
                // MR 2026.4 | Preventivo | Manuel Cupul — tipo de usuario no reconocido
                Toast.error("Tu cuenta no tiene un tipo de usuario válido. Contacta al administrador.");
            }

        } else {
            // MR 2026.4 | Preventivo | Manuel Cupul — credenciales incorrectas
            Toast.error("Usuario o contraseña incorrectos. Por favor, inténtalo de nuevo.");
        }

    } catch (e) {
        // MR 2026.4 | Preventivo | Manuel Cupul — respuesta inesperada del servidor
        console.error("Error al procesar la respuesta del servidor:", e);
        Toast.error("Ocurrió un error inesperado. Por favor, intenta de nuevo.");
    }
}

