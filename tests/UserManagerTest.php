<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . "/../core/php/UserManager.php";
require_once __DIR__ . "/../core/php/IDataBaseManager.php";

class UserManagerTest extends TestCase
{

    private $userManager;
    private $mockDbManager;

    protected function setUp(): void
    {
        // Crear un mock de DataBaseManager
        $this->mockDbManager = $this->createMock('IDataBaseManager');
        
        // Instanciar UserManager con el mock
        $this->userManager = UserManager::getInstanceWithDependencies($this->mockDbManager);
    }

    // ---- PRUEBA UNITARIA POSITIVA ----
    public function testGetUserExistente()
    {
        // Datos del usuario de prueba
        $name = "Raul";
        $password = "1234";
        $tipo = 1;

        // Configurar el mock para que retorne true en insertQuery (usuario creado)
        $this->mockDbManager->method('insertQuery')
            ->willReturn(true);

        // Configurar el mock para que retorne datos en realizeQuery
        $userData = array(
            array(
                'id' => '1',
                'nombre' => 'Raul',
                'clave' => '1234',
                'tipo' => '1'
            )
        );
        $this->mockDbManager->method('realizeQuery')
            ->willReturn($userData);

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

        // Configurar el mock para que retorne null cuando no existe
        $this->mockDbManager->method('realizeQuery')
            ->willReturn(null);

        $resultado = $this->userManager->getUser($name, $password);

        // Cuando no existe
        $this->assertEquals("Tabla usuario vacia", $resultado);
    }
}

?>