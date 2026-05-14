<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . "/../core/php/IDataBaseManager.php";
require_once __DIR__ . "/../core/php/ISession.php";
require_once __DIR__ . "/../core/php/AuthService.php";

class AuthServiceTest extends TestCase {

    // ---- PRUEBA UNITARIA POSITIVA ----
    public function testLoginExitoso() {
        
        $mockDb = $this->createMock(IDataBaseManager::class);
        $mockSession = $this->createMock(ISession::class);

        $dbResultadoSimulado = [
            ['id' => 1, 'tipo' => 'admin']
        ];

        $mockSession->expects($this->once())
                    ->method('set')
                    ->with('user', 1);

        $service = new AuthService($mockDb, $mockSession);

        $result = $service->verifyLogin($dbResultadoSimulado);

        $this->assertNotNull($result);
        $this->assertEquals([['type' => 'admin']], $result);
    }

    // ---- PRUEBA UNITARIA NEGATIVA ----
    public function testLoginFallido() {
        
        $mockDb = $this->createMock(IDataBaseManager::class);
        $mockSession = $this->createMock(ISession::class);

        $mockSession->expects($this->never())
                    ->method('set');

        $service = new AuthService($mockDb, $mockSession);

        $result = $service->verifyLogin([]);

        $this->assertNull($result);
    }
}