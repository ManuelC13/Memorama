/**
 * Autor: Manuel Cupul
 * Tipo de mantenimiento: Preventivo (MR 2026.4)
 * Descripción: Módulo centralizado de notificaciones. Reemplaza los fallos
 * silenciosos y alerts nativos del sistema por mensajes visuales
 * comprensibles para el usuario.
 *
 * Uso:
 *   Toast.error("Mensaje");   
 *   Toast.success("Mensaje"); 
 *   Toast.warning("Mensaje");
 *   Toast.info("Mensaje");
 */

var Toast = (function () {

    var TIPOS = {
        error:   { clase: "toast-error",   icono: "glyphicon-exclamation-sign", duracion: 5000 },
        success: { clase: "toast-success", icono: "glyphicon-ok-circle",        duracion: 3000 },
        warning: { clase: "toast-warning", icono: "glyphicon-warning-sign",     duracion: 4000 },
        info:    { clase: "toast-info",    icono: "glyphicon-info-sign",         duracion: 3000 }
    };

    // Crea el contenedor si no existe
    function obtenerContenedor() {
        if ($("#toast-contenedor").length === 0) {
            $("body").append('<div id="toast-contenedor"></div>');
        }
        return $("#toast-contenedor");
    }

    function mostrar(mensaje, tipo) {
        var config = TIPOS[tipo] || TIPOS.info;
        var contenedor = obtenerContenedor();

        var id = "toast-" + Date.now();
        var html =
            '<div id="' + id + '" class="toast-item ' + config.clase + '">' +
                '<span class="glyphicon ' + config.icono + '" aria-hidden="true"></span> ' +
                '<span class="toast-mensaje">' + mensaje + '</span>' +
                '<button class="toast-cerrar" onclick="$(\'#' + id + '\').remove()">&times;</button>' +
            '</div>';

        contenedor.append(html);

        // Auto-desaparecer
        setTimeout(function () {
            $("#" + id).fadeOut(400, function () {
                $(this).remove();
            });
        }, config.duracion);
    }

    return {
        error:   function (msg) { mostrar(msg, "error"); },
        success: function (msg) { mostrar(msg, "success"); },
        warning: function (msg) { mostrar(msg, "warning"); },
        info:    function (msg) { mostrar(msg, "info"); }
    };

})();