/**
 * Autor: Manuel Cupul
 * Tipo de mantenimiento: Perfectivo (MR 2026.3)
 * Descripción: Lógica pura de estado del juego extraída de juego.js.
 * Permite probar de forma unitaria las funciones de pausa,
 * reanudación, contador y puntaje sin depender del DOM ni jQuery.
 */

var estadoJuego = {
    enPausa: false,
    tiempoActual: 0,
    intervalo: null
};


/**
 * Pausa el juego: detiene el intervalo y marca enPausa = true.
 * No hace nada si ya está en pausa.
 * @returns {boolean} true si se pausó, false si ya estaba en pausa
 */
function pausarEstado(estado) {
    if (estado.enPausa) return false;
    clearInterval(estado.intervalo);
    estado.intervalo = null;
    estado.enPausa = true;
    return true;
}

/**
 * Reanuda el juego: marca enPausa = false.
 * El intervalo lo maneja iniciarContadorEstado por separado.
 * @returns {boolean} true si se reanudó, false si no estaba en pausa
 */
function reanudarEstado(estado) {
    if (!estado.enPausa) return false;
    estado.enPausa = false;
    return true;
}

/**
 * Inicia (o reanuda) el contador con el tiempo dado.
 * Llama a onTick(tiempoActual) cada segundo y a onFin() cuando llega a 0
 */
function iniciarContadorEstado(estado, tiempo, onTick, onFin) {
    estado.tiempoActual = tiempo;
    if (onTick) onTick(estado.tiempoActual);

    estado.intervalo = setInterval(function () {
        estado.tiempoActual -= 1;
        if (onTick) onTick(estado.tiempoActual);
        if (estado.tiempoActual === 0) {
            clearInterval(estado.intervalo);
            estado.intervalo = null;
            if (onFin) onFin();
        }
    }, 1000);
}

/**
 * Calcula el nuevo puntaje sumando los puntos dados.
 * @returns {number} nuevo puntaje
 */
function calcularPuntaje(puntajeActual, puntos) {
    return puntajeActual + puntos;
}

/**
 * Indica si dos cartas son pareja válida comparando sus IDs.
 * @returns {boolean}
 */
function sonPareja(idCarta1, idCarta2) {
    return idCarta1 === idCarta2;
}



if (typeof module !== "undefined") {
    module.exports = {
        estadoJuego,
        pausarEstado,
        reanudarEstado,
        iniciarContadorEstado,
        calcularPuntaje,
        sonPareja
    };
}