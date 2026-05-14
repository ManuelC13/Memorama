<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../core/php/Session.php';

class SessionTest extends TestCase {

    private $session;

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


    // ---- PRUEBA UNITARIA POSITIVA ----  (Elimina variable existente)
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

    // ---- PRUEBA UNITARIA POSITIVA ----  (Eliminar variable inexistente)
    public function testDeleteVarNegative() {
        // Act
        $this->session->delete_var("fake");

        // Assert
        $this->assertFalse(
            $this->session->get("fake")
        );
    }



    // ---- PRUEBA UNITARIA POSITIVA ----  (limpiar sesión)
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