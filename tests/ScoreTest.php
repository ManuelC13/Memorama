<?php
/**
 * Created by Raúl Nahuat.
 * User: Raúl Nahuat
 * Date: 05/05/202026
 * 
 * Descripción: Pruebas unitaria para verificar el estado de los puntajes.
 */

use PHPUnit\Framework\TestCase;

set_include_path(__DIR__ . '/../core/php' . PATH_SEPARATOR . get_include_path());

require_once __DIR__ . '/../core/php/IDataBaseManager.php';
require_once __DIR__ . '/../core/php/PuntajesManajer.php';

class ScoreTest extends TestCase
{
	private function createManagerWithMockDb(IDataBaseManager $mockDb): PuntajesManajer
	{
		$reflectionClass = new ReflectionClass(PuntajesManajer::class);
		$manager = $reflectionClass->newInstanceWithoutConstructor();

		$dbProperty = $reflectionClass->getProperty('dbManager');
		$dbProperty->setAccessible(true);
		$dbProperty->setValue($manager, $mockDb);

		return $manager;
	}

	// ---- PRUEBA UNITARIA POSITIVA ----
	public function testSetPuntajePersisteElRegistroConLosDatosEsperados()
	{
		$mockDb = $this->createMock(IDataBaseManager::class);
		$manager = $this->createManagerWithMockDb($mockDb);

		$mockDb->expects($this->once())
			->method('insertQuery')
			->with(
				"INSERT INTO puntajes (id_usuario,id_materia,fecha,dificultad,puntaje,parejas_encontradas) VALUES('1','2','2026-05-04','facil',10,4)"
			)
			->willReturn(true);

		$resultado = $manager->setPuntaje(1, 2, '2026-05-04', 'facil', 10, 4);

		$this->assertSame('', $resultado);
	}

	// ---- PRUEBA UNITARIA NEGATIVA ----
	public function testRankingDebeIncluirNombreParaLaVista()
	{
		$mockDb = $this->createMock(IDataBaseManager::class);
		$manager = $this->createManagerWithMockDb($mockDb);

		$mockDb->expects($this->once())
			->method('realizeQuery')
			->with("SELECT puntajes.id_usuario, usuario.nombre, puntajes.id_materia, puntajes.fecha, puntajes.dificultad, puntajes.puntaje, puntajes.parejas_encontradas FROM puntajes INNER JOIN usuario ON puntajes.id_usuario = usuario.id WHERE puntajes.id_materia='2' ORDER BY puntajes.puntaje DESC, puntajes.fecha ASC")
			->willReturn([
				[
					'id_usuario' => 1,
					'nombre' => 'Raul',
					'id_materia' => 2,
					'fecha' => '2026-05-04',
					'dificultad' => 'facil',
					'puntaje' => 10,
					'parejas_encontradas' => 4,
				],
			]);

		$resultado = $manager->getAllPuntajeForMateria(2);
		$registros = json_decode($resultado, true);

		$this->assertIsArray($registros);
		$this->assertArrayHasKey('nombre', $registros[0]);
		$this->assertSame('Raul', $registros[0]['nombre']);
	}
}
