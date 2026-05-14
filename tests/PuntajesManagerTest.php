<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../core/php/IDataBaseManager.php';
require_once __DIR__ . '/../core/php/PuntajesManajer.php';

class PuntajesManagerTest extends TestCase {

    // ---------------------------------------------
    // TESTS: getAllPuntajeForMateriaAndDificultad
    // ---------------------------------------------

    /**
     * Verifica que el método retorne un JSON
     * con puntajes filtrados por materia y dificultad.
     */
    public function testGetAllPuntajeForMateriaAndDificultadRetornaJsonConDatos() {

        $fakeData = [
            [
                "id_materia" => 1,
                "dificultad" => "Facil",
                "puntaje" => 100
            ]
        ];

        $dbMock = $this->createMock(IDataBaseManager::class);

        $dbMock->expects($this->once())
            ->method('realizeQuery')
            ->willReturn($fakeData);

        $manager = new PuntajesManajer($dbMock);

        $resultado = $manager
            ->getAllPuntajeForMateriaAndDificultad(
                1,
                "Facil"
            );

        $this->assertJson($resultado);

        $data = json_decode($resultado, true);

        $this->assertCount(1, $data);

        $this->assertEquals(
            1,
            $data[0]["id_materia"]
        );

        $this->assertEquals(
            "Facil",
            $data[0]["dificultad"]
        );
    }

    /**
     * Verifica que el método retorne el mensaje
     * de error cuando no existen resultados.
     */
    public function testGetAllPuntajeForMateriaAndDificultadRetornaMensajeCuandoNoHayDatos() {

        $dbMock = $this->createMock(IDataBaseManager::class);

        $dbMock->expects($this->once())
            ->method('realizeQuery')
            ->willReturn(null);

        $manager = new PuntajesManajer($dbMock);

        $resultado = $manager
            ->getAllPuntajeForMateriaAndDificultad(
                "FAKE",
                "FAKE"
            );

        $this->assertEquals(
            "tabla materia varia",
            $resultado
        );
    }

    // ------------------------------------------------------
    // TESTS: getAllPuntajeForUsuarioAndMateriaAndDificultad
    // ------------------------------------------------------

    /**
     * Verifica que el método retorne un JSON
     * con puntajes filtrados por usuario, materia y dificultad.
     */
    public function testGetAllPuntajeForUsuarioAndMateriaAndDificultadRetornaJsonConDatos() {

        $fakeData = [
            [
                "id_usuario" => 1,
                "id_materia" => 1,
                "dificultad" => "Facil",
                "puntaje" => 100
            ]
        ];

        $dbMock = $this->createMock(IDataBaseManager::class);

        $dbMock->expects($this->once())
            ->method('realizeQuery')
            ->willReturn($fakeData);

        $manager = new PuntajesManajer($dbMock);

        $resultado = $manager
            ->getAllPuntajeForUsuarioAndMateriaAndDificultad(
                1,
                1,
                "Facil"
            );

        $this->assertJson($resultado);

        $data = json_decode($resultado, true);

        $this->assertCount(1, $data);

        $this->assertEquals(
            1,
            $data[0]["id_usuario"]
        );

        $this->assertEquals(
            1,
            $data[0]["id_materia"]
        );

        $this->assertEquals(
            "Facil",
            $data[0]["dificultad"]
        );
    }

    /**
     * Verifica que el método retorne un mensaje
     * cuando no existen puntajes para el usuario indicado.
     */
    public function testGetAllPuntajeForUsuarioAndMateriaAndDificultadRetornaMensajeSinDatos() {

        $dbMock = $this->createMock(IDataBaseManager::class);

        $dbMock->expects($this->once())
            ->method('realizeQuery')
            ->willReturn(null);

        $manager = new PuntajesManajer($dbMock);

        $resultado = $manager
            ->getAllPuntajeForUsuarioAndMateriaAndDificultad(
                "FAKE_USER",
                "FAKE_MATERIA",
                "FAKE_DIFICULTAD"
            );

        $this->assertEquals(
            "tabla materia varia",
            $resultado
        );
    }
}