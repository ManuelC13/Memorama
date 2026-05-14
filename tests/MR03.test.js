/**
 * Autor: Manuel Cupul
 * Tipo de mantenimiento: Perfectivo
 * Descripción: Pruebas unitarias y de integración para la funcionalidad
 * de pausa, reanudación y reinicio de partida (MR 2026.3)
 */


// [+] --> Pruebas Unitarias Positivas
// [-] --> Pruebas Unitarias Negativas


const {
    pausarEstado,
    reanudarEstado,
    iniciarContadorEstado,
    calcularPuntaje,
    sonPareja
} = require("../javascript/juego.logica");

 
function nuevoEstado() {
    return { enPausa: false, tiempoActual: 0, intervalo: null };
}


describe("pausarEstado()", () => {

    test("[+] Marcar enPausa como true al pausar un juego activo", () => {
        const estado = nuevoEstado();
        estado.enPausa = false;

        const resultado = pausarEstado(estado);

        expect(resultado).toBe(true);
        expect(estado.enPausa).toBe(true);
    });

    test("[+] Detener el intervalo al pausar (intervalo queda en null)", () => {
        const estado = nuevoEstado();
        // Simulamos un intervalo activo
        estado.intervalo = setInterval(() => {}, 1000);
        expect(estado.intervalo).not.toBeNull();

        pausarEstado(estado);

        expect(estado.intervalo).toBeNull();
    });

    test("[-] No pausar si el juego ya está en pausa", () => {
        const estado = nuevoEstado();
        estado.enPausa = true;

        const resultado = pausarEstado(estado);

        // Retornar false y no cambiar el estado
        expect(resultado).toBe(false);
        expect(estado.enPausa).toBe(true);
    });

});



describe("reanudarEstado()", () => {

    test("[+] Marcar enPausa como false al reanudar un juego pausado", () => {
        const estado = nuevoEstado();
        estado.enPausa = true;

        const resultado = reanudarEstado(estado);

        expect(resultado).toBe(true);
        expect(estado.enPausa).toBe(false);
    });

    test("[-] No reanudar si el juego no está en pausa", () => {
        const estado = nuevoEstado();
        estado.enPausa = false;

        const resultado = reanudarEstado(estado);

        expect(resultado).toBe(false);
        expect(estado.enPausa).toBe(false);
    });

});



describe("iniciarContadorEstado()", () => {

    // Reemplazar setInterval/clearInterval con versiones controlables para Jest
    beforeEach(() => { jest.useFakeTimers(); });
    afterEach(() => { jest.useRealTimers(); });

    test("[+] Iniciar el contador con el tiempo correcto y decrementar cada segundo", () => {
        const estado = nuevoEstado();
        const ticks = [];

        iniciarContadorEstado(estado, 5, (t) => ticks.push(t), () => {});

        // Tiempo inicial
        expect(ticks[0]).toBe(5);

        // Avanzar 3 segundos
        jest.advanceTimersByTime(3000);

        expect(ticks).toEqual([5, 4, 3, 2]);
        expect(estado.tiempoActual).toBe(2);
    });

    test("[+] Al reanudar, el contador continúa desde el tiempo pausado", () => {
        const estado = nuevoEstado();
        const ticks = [];

        // Iniciar con 10 segundos
        iniciarContadorEstado(estado, 10, (t) => ticks.push(t), () => {});
        jest.advanceTimersByTime(4000); // avanza 4 seg -> tiempoActual = 6

        pausarEstado(estado);
        const tiempoAlPausar = estado.tiempoActual;
        expect(tiempoAlPausar).toBe(6);

        reanudarEstado(estado);
        iniciarContadorEstado(estado, estado.tiempoActual, (t) => ticks.push(t), () => {});
        jest.advanceTimersByTime(2000); // avanza 2 seg más

        // Continuar desde 6
        expect(estado.tiempoActual).toBe(4);
    });

    test("[-] Llamar a onFin() solo cuando el tiempo llega a 0", () => {
        const estado = nuevoEstado();
        const finJuego = jest.fn();

        iniciarContadorEstado(estado, 3, () => {}, finJuego);

        // Con 2 segundos transcurridos aún no debe haber terminado
        jest.advanceTimersByTime(2000);
        expect(finJuego).not.toHaveBeenCalled();

        // Al llegar al segundo 3 sí debe llamar a onFin
        jest.advanceTimersByTime(1000);
        expect(finJuego).toHaveBeenCalledTimes(1);
    });

    test("[-] El contador se detiene al llegar a 0", () => {
        const estado = nuevoEstado();
        const ticks = [];

        iniciarContadorEstado(estado, 2, (t) => ticks.push(t), () => {});
        jest.advanceTimersByTime(5000); // avanzar mucho más allá del límite

        // Solo debe haber 3 ticks: t=2, t=1, t=0
        expect(ticks).toEqual([2, 1, 0]);
        expect(estado.intervalo).toBeNull();
    });

});



describe("calcularPuntaje()", () => {

    test("[+] Sumar puntos correctamente al acertar una pareja", () => {
        expect(calcularPuntaje(0, 3)).toBe(3);
        expect(calcularPuntaje(9, 3)).toBe(12);
    });

    test("[-] Restar puntos correctamente al fallar", () => {
        expect(calcularPuntaje(9, -3)).toBe(6);
        expect(calcularPuntaje(3, -3)).toBe(0);
    });

    test("[-] El puntaje se puede volver negativo si el jugador falla mucho", () => {
        expect(calcularPuntaje(0, -3)).toBe(-3);
    });

});



describe("sonPareja()", () => {

    test("[+] Retornar true cuando dos cartas tienen el mismo ID", () => {
        expect(sonPareja("3fotosíntesis", "3fotosíntesis")).toBe(true);
    });

    test("[-] Retornar false cuando dos cartas tienen IDs distintos", () => {
        expect(sonPareja("3fotosíntesis", "5mitocondria")).toBe(false);
    });

    test("[-] Retornar false cuando se compara con un ID vacío", () => {
        expect(sonPareja("3fotosíntesis", "")).toBe(false);
    });

    test("[+] prueba de humo del pipeline", () => {
        expect(true).toBe(true);
    });
});