/**
 * Autor: Manuel Cupul
 * Tipo de mantenimiento: Preventivo
 * Descripción: Pruebas unitarias y de integración para el manejo centralizado
 * de errores y mensajes informativos mediante toasts (MR 2026.4).
 */


// [+] --> Pruebas Unitarias Positivas
// [-] --> Pruebas Unitarias Negativas


const {
    validarCamposLogin,
    interpretarRespuestaLogin,
    construirConfigToast,
    TIPO_ALUMNO,
    TIPO_DOCENTE,
    DURACIONES_TOAST,
    CLASES_TOAST
} = require("../javascript/login.logica");


describe("validarCamposLogin()", () => {

    test("[+] Retornar valido = true cuando usuario y contraseña están presentes", () => {
        const resultado = validarCamposLogin("admin", "1234");

        expect(resultado.valido).toBe(true);
        expect(resultado.mensaje).toBe("");
    });

    test("[+] Ignorar espacios en blanco al inicio y final del usuario", () => {
        const resultado = validarCamposLogin("   admin   ", "1234");

        expect(resultado.valido).toBe(true);
    });

    test("[-] Retornar valido = false cuando ambos campos están vacíos", () => {
        const resultado = validarCamposLogin("", "");

        expect(resultado.valido).toBe(false);
        expect(resultado.mensaje).toBe("Por favor, ingresa tu usuario y contraseña.");
    });

    test("[-] Retornar valido = false cuando el usuario está vacío", () => {
        const resultado = validarCamposLogin("", "1234");

        expect(resultado.valido).toBe(false);
        expect(resultado.mensaje).toBe("Por favor, ingresa tu nombre de usuario.");
    });

    test("[-] Retornar valido = false cuando la contraseña está vacía", () => {
        const resultado = validarCamposLogin("admin", "");

        expect(resultado.valido).toBe(false);
        expect(resultado.mensaje).toBe("Por favor, ingresa tu contraseña.");
    });

    test("[-] Tratar un usuario de solo espacios como vacío", () => {
        const resultado = validarCamposLogin("     ", "1234");

        expect(resultado.valido).toBe(false);
        expect(resultado.mensaje).toBe("Por favor, ingresa tu nombre de usuario.");
    });

});



describe("interpretarRespuestaLogin()", () => {

    test("[+] Retornar accion = 'alumno' cuando el servidor responde con type = 0", () => {
        const response = JSON.stringify([{ type: TIPO_ALUMNO, nombre: "Juan" }]);

        const resultado = interpretarRespuestaLogin(response);

        expect(resultado.accion).toBe("alumno");
        expect(resultado.mensaje).toBe("");
    });

    test("[+] Retornar accion = 'docente' cuando el servidor responde con type = 1", () => {
        const response = JSON.stringify([{ type: TIPO_DOCENTE, nombre: "Prof. García" }]);

        const resultado = interpretarRespuestaLogin(response);

        expect(resultado.accion).toBe("docente");
        expect(resultado.mensaje).toBe("");
    });

    test("[-] Retornar accion = 'credenciales_invalidas' cuando el servidor responde null", () => {
        const response = JSON.stringify(null);

        const resultado = interpretarRespuestaLogin(response);

        expect(resultado.accion).toBe("credenciales_invalidas");
        expect(resultado.mensaje).toBe("Usuario o contraseña incorrectos. Por favor, inténtalo de nuevo.");
    });

    test("[-] Retornar accion = 'tipo_invalido' cuando el type no es 0 ni 1", () => {
        const response = JSON.stringify([{ type: 99, nombre: "Raro" }]);

        const resultado = interpretarRespuestaLogin(response);

        expect(resultado.accion).toBe("tipo_invalido");
        expect(resultado.mensaje).toBe("Tu cuenta no tiene un tipo de usuario válido. Contacta al administrador.");
    });

    test("[-] Retornar accion = 'error_respuesta' cuando la respuesta no es JSON válido", () => {
        const resultado = interpretarRespuestaLogin("<html>Error 500</html>");

        expect(resultado.accion).toBe("error_respuesta");
        expect(resultado.mensaje).toBe("Ocurrió un error inesperado. Por favor, intenta de nuevo.");
    });

    test("[-] Retornar accion = 'error_respuesta' cuando la respuesta está vacía", () => {
        const resultado = interpretarRespuestaLogin("");

        expect(resultado.accion).toBe("error_respuesta");
        expect(resultado.mensaje).toBe("Ocurrió un error inesperado. Por favor, intenta de nuevo.");
    });

});



describe("construirConfigToast()", () => {

    test("[+] Configurar correctamente un toast de tipo error", () => {
        const config = construirConfigToast("error", "Algo salió mal");

        expect(config).not.toBeNull();
        expect(config.clase).toBe("toast-error");
        expect(config.duracion).toBe(5000);
        expect(config.mensaje).toBe("Algo salió mal");
    });

    test("[+] Configurar correctamente un toast de tipo warning", () => {
        const config = construirConfigToast("warning", "Campo vacío");

        expect(config).not.toBeNull();
        expect(config.clase).toBe("toast-warning");
        expect(config.duracion).toBe(4000);
        expect(config.mensaje).toBe("Campo vacío");
    });

    test("[+] Configurar correctamente un toast de tipo success", () => {
        const config = construirConfigToast("success", "Operación exitosa");

        expect(config.clase).toBe("toast-success");
        expect(config.duracion).toBe(3000);
    });

    test("[+] Configurar correctamente un toast de tipo info", () => {
        const config = construirConfigToast("info", "Cargando datos...");

        expect(config.clase).toBe("toast-info");
        expect(config.duracion).toBe(3000);
    });

    test("[-] Retornar null cuando el tipo de toast no existe", () => {
        const config = construirConfigToast("critico", "Mensaje");

        expect(config).toBeNull();
    });

    test("[-] Retornar null cuando el tipo es una cadena vacía", () => {
        const config = construirConfigToast("", "Mensaje");

        expect(config).toBeNull();
    });

    test("[-] Mensaje del toast debe ser exactamente el que se le pasó, sin modificaciones", () => {
        const mensaje = "Usuario o contraseña incorrectos. Por favor, inténtalo de nuevo.";
        const config = construirConfigToast("error", mensaje);

        expect(config.mensaje).toBe(mensaje);
    });

});


// ------------------------------------------------------------------
// PRUEBAS DE INTEGRACIÓN: validación -> interpretación -> toast
// -----------------------------------------------------------------

describe("Flujo completo: validación y respuesta del servidor", () => {

    test("[+] Credenciales válidas de alumno producen redirección sin toast de error", () => {
        // Paso 1: validar campos
        const validacion = validarCamposLogin("juan123", "pass");
        expect(validacion.valido).toBe(true);

        // Paso 2: interpretar respuesta exitosa del servidor
        const response = JSON.stringify([{ type: TIPO_ALUMNO, nombre: "Juan" }]);
        const resultado = interpretarRespuestaLogin(response);
        expect(resultado.accion).toBe("alumno");

        // No debe generarse ningún toast de error en este flujo
        const config = resultado.mensaje === "" ? null : construirConfigToast("error", resultado.mensaje);
        expect(config).toBeNull();
    });

    test("[-] Campos vacíos cortan el flujo antes de llegar al servidor", () => {
        // Paso 1: validar debe fallar
        const validacion = validarCamposLogin("", "");
        expect(validacion.valido).toBe(false);

        // El toast de advertencia debe construirse correctamente
        const config = construirConfigToast("warning", validacion.mensaje);
        expect(config).not.toBeNull();
        expect(config.clase).toBe("toast-warning");
        expect(config.mensaje).toBe("Por favor, ingresa tu usuario y contraseña.");
    });

    test("[-] Credenciales incorrectas producen un toast de error", () => {
        // Paso 1: campos completados pasan la validación
        const validacion = validarCamposLogin("usuario", "wrongpass");
        expect(validacion.valido).toBe(true);

        // Paso 2: servidor responde null (credenciales incorrectas)
        const resultado = interpretarRespuestaLogin(JSON.stringify(null));
        expect(resultado.accion).toBe("credenciales_invalidas");

        // Paso 3: se debe mostrar un toast de error
        const config = construirConfigToast("error", resultado.mensaje);
        expect(config).not.toBeNull();
        expect(config.clase).toBe("toast-error");
        expect(config.mensaje).toContain("Usuario o contraseña incorrectos");
    });

});