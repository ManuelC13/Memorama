/**
 * Autor: Manuel Cupul
 * Tipo de mantenimiento: Preventivo (MR 2026.4)
 * Descripción: Lógica pura de validación de login e interpretación de respuestas
 * del servidor, extraída de login.js y toast.js. Permite probar
 * el manejo de errores de forma unitaria sin depender del DOM ni jQuery.
 */



/**
 * Valida los campos del formulario de login.
 * @returns {{ valido: boolean, mensaje: string }}
 */
function validarCamposLogin(username, password) {
    var u = (username || "").trim();
    var p = (password || "");

    if (u === "" && p === "") {
        return { valido: false, mensaje: "Por favor, ingresa tu usuario y contraseña." };
    }
    if (u === "") {
        return { valido: false, mensaje: "Por favor, ingresa tu nombre de usuario." };
    }
    if (p === "") {
        return { valido: false, mensaje: "Por favor, ingresa tu contraseña." };
    }
    return { valido: true, mensaje: "" };
}


// Interpretación de la respuesta del servidor
var TIPO_ALUMNO  = 0;
var TIPO_DOCENTE = 1;

/**
 * Interpreta la respuesta JSON del servidor de login.
 * @returns {{ accion: string, mensaje: string }}
 *   accion puede ser: "alumno" | "docente" | "tipo_invalido" | "credenciales_invalidas" | "error_respuesta"
 */
function interpretarRespuestaLogin(responseText) {
    try {
        var data = JSON.parse(responseText);

        if (data === null) {
            return {
                accion: "credenciales_invalidas",
                mensaje: "Usuario o contraseña incorrectos. Por favor, inténtalo de nuevo."
            };
        }

        var tipo = data[0].type;

        if (tipo == TIPO_ALUMNO) {
            return { accion: "alumno", mensaje: "" };
        } else if (tipo == TIPO_DOCENTE) {
            return { accion: "docente", mensaje: "" };
        } else {
            return {
                accion: "tipo_invalido",
                mensaje: "Tu cuenta no tiene un tipo de usuario válido. Contacta al administrador."
            };
        }

    } catch (e) {
        return {
            accion: "error_respuesta",
            mensaje: "Ocurrió un error inesperado. Por favor, intenta de nuevo."
        };
    }
}


// Lógica del módulo Toast
var DURACIONES_TOAST = {
    error:   5000,
    success: 3000,
    warning: 4000,
    info:    3000
};

var CLASES_TOAST = {
    error:   "toast-error",
    success: "toast-success",
    warning: "toast-warning",
    info:    "toast-info"
};

/**
 * Construye el objeto de configuración de un toast sin tocaer el DOM.
 * @returns {{ clase: string, duracion: number, mensaje: string }} | null si el tipo es inválido
 */
function construirConfigToast(tipo, mensaje) {
    if (!CLASES_TOAST[tipo]) return null;
    return {
        clase:    CLASES_TOAST[tipo],
        duracion: DURACIONES_TOAST[tipo],
        mensaje:  mensaje
    };
}



if (typeof module !== "undefined") {
    module.exports = {
        validarCamposLogin,
        interpretarRespuestaLogin,
        construirConfigToast,
        TIPO_ALUMNO,
        TIPO_DOCENTE,
        DURACIONES_TOAST,
        CLASES_TOAST
    };
}