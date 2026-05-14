<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . "/../core/php/userManager.php";

class UserManagerTest extends TestCase
{

    private $userManager;

    protected function setUp(): void
    {
        $this->userManager = UserManager::getInstance();
    }

    // ---- PRUEBA UNITARIA POSITIVA ----
    public function testGetUserExistente()
    {
        // Datos del usuario de prueba
        $name = "Raul";
        $password = "1234";
        $tipo = 1;

        // Creación del usuario de prueba
        $this->userManager->setUser($name, $password, $tipo);

        // Ejecución del método a probar
        $resultado = $this->userManager->getUser($name, $password);

        // Verificar que NO esté vacío
        $this->assertNotEmpty($resultado);

        // Verificar que sea JSON válido
        $this->assertJson($resultado);

        $datos = json_decode($resultado, true);

        // Verificar que el nombre sea correcto
        $this->assertEquals($name, $datos[0]['nombre']);
    }

    // ---- PRUEBA UNITARIA NEGATIVA ----
    public function testGetUserNoExistente()
    {
        // Datos del usuario de prueba
        $name = "Manuel";
        $password = "contrasena";

        $resultado = $this->userManager->getUser($name, $password);

        // Cuando no existe
        $this->assertEquals("Tabla usuario vacia", $resultado);
    }
}

?>