<?php
/**
 * MANTENIMIENTO DE SOFTWARE - 2026
 *  
 * Creado por: Raúl Nahuat and Manuel Cupul.
 * 
 * Descripción: Pruebas unitarias para las funciones:
 * -- set(),
 * -- delete_var()
 * -- session_finish()
 * de la clase Session,
*/

use PHPUnit\Framework\TestCase;

set_include_path(__DIR__ . '/../core/php' . PATH_SEPARATOR . get_include_path());

require_once __DIR__ . '/../core/php/ISession.php';
require_once __DIR__ . '/../core/php/Session.php';

class SessionTest extends TestCase
{
	private Session $session;

	protected function setUp(): void {
        // Reinicia sesión limpia
        if(session_status() === PHP_SESSION_ACTIVE){
            session_destroy();
        }

        $_SESSION = [];

        $this->session = new Session();
    }

	protected function tearDown(): void {
        $_SESSION = [];

        if(session_status() === PHP_SESSION_ACTIVE){
            session_destroy();
        }
    }

    // ---------------------------------------------
    // TEST: #6
    // TEST POSITIVO: set()
    // ---------------------------------------------
	/**
	 * Verifica que set() almacene correctamente una variable en la sesión
	 */
	public function testSetAlmacenaVariableEnSesion()
	{
		$this->session->set('usuario', 'Juan');

		$this->assertSame('Juan', $_SESSION['usuario']);
	}

    // ---------------------------------------------
    // TEST: #6
    // TEST NEGATIVO: set()
    // ---------------------------------------------
	/**
	 * Verifica que set() almacene correctamente un valor falsy (0)
	 */
	public function testSetAlmacenaCeroComoValor()
	{
		$this->session->set('intentos', 0);

		$this->assertSame(0, $_SESSION['intentos']);
		$this->assertNotNull($_SESSION['intentos']);
	}

    // ---------------------------------------------
    // TEST: #7
    // TEST POSITIVO: delete_var()
    // ---------------------------------------------
	/**
	 * Verifica que delete_var() elimine correctamente una variable de la sesión
	 */
	public function testDeleteVarPositive() {
        // Arrange
        $this->session->set("user", "Juan");

        // Verifica que exista
        $this->assertEquals(
            "Juan",
            $this->session->get("user")
        );

        // Act
        $this->session->delete_var("user");

        // Assert
        $this->assertFalse(
            $this->session->get("user")
        );
    }

    // ---------------------------------------------
    // TEST: #7
    // TEST NEGATIVO: delete_var()
    // ---------------------------------------------
    /**
     * Verifica que delete_var() no cause errores al intentar eliminar una variable que no existe
     */
    public function testDeleteVarNegative() {
        // Act
        $this->session->delete_var("fake");

        // Assert
        $this->assertFalse(
            $this->session->get("fake")
        );
    }


    // ---------------------------------------------
    // TEST: #8
    // TEST POSITIVO: session_finish()
    // ---------------------------------------------
    /**
    * Verifica que session_finish() limpie correctamente todas las variables de la sesión
    */
    public function testSessionFinishPositive() {
        // Arrange
        $this->session->set("user", "Juan");
        $this->session->set("rol", "admin");

        $this->assertEquals(
            "Juan",
            $this->session->get("user")
        );

        // Act
        $this->session->session_finish();

        // Assert
        $this->assertEmpty($_SESSION);
    }
}