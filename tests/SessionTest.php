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

	protected function setUp(): void
	{
		$this->session = new Session();
	}

	protected function tearDown(): void
	{
		$this->session->session_finish();
		parent::tearDown();
	}

	// ========== PRUEBAS PARA set() ==========

	/**
	 * PRUEBA UNITARIA POSITIVA - set()
	 * Verifica que set() almacene correctamente una variable en la sesión
	 */
	public function testSetAlmacenaVariableEnSesion()
	{
		$this->session->set('usuario', 'Juan');

		$this->assertSame('Juan', $_SESSION['usuario']);
	}

	/**
	 * PRUEBA UNITARIA NEGATIVA - set()
	 * Verifica que set() almacene correctamente un valor falsy (0)
	 */
	public function testSetAlmacenaCeroComoValor()
	{
		$this->session->set('intentos', 0);

		$this->assertSame(0, $_SESSION['intentos']);
		$this->assertNotNull($_SESSION['intentos']);
	}
}