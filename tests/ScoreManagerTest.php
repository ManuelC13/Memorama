<?php
/**
 * MANTENIMIENTO DE SOFTWARE - 2026
 *  
 * Creado por: Raúl Nahuat and Manuel Cupul.
 * 
 * Descripción: Pruebas unitarias para las funciones:
 * -- deletePuntaje, 
 * -- getAllPuntajeForUsuario
 * -- getAllPuntajeForUsuarioAndMateria
 * -- getAllPuntajeForUsuarioAndDificultad
 * -- getAllPuntajeForUsuarioAndMateriaAndDificultad
 * de la clase PuntajesManajer,
*/

use PHPUnit\Framework\TestCase;

set_include_path(__DIR__ . '/../core/php' . PATH_SEPARATOR . get_include_path());

require_once __DIR__ . '/../core/php/IDataBaseManager.php';
require_once __DIR__ . '/../core/php/PuntajesManajer.php';

class ScoreManagerTest extends TestCase
{
	// ========== PRUEBAS PARA deletePuntaje ==========

	/**
	 * PRUEBA UNITARIA POSITIVA - deletePuntaje
	 * Verifica que deletePuntaje ejecute correctamente la consulta DELETE
	 * cuando se proporcionan parámetros válidos
	 */
	public function testDeletePuntajeEliminaRegistroConParametrosValidos()
	{
		$dbMock = $this->createMock(IDataBaseManager::class);

		$fakeData = [
			[
				'id_usuario' => 1,
				'id_materia' => 1,
				'fecha' => '2026-05-05 02:05:16',
				'dificultad' => 'facil'
			],
		];

		$dbMock->expects($this->once())
			->method('insertQuery')
			->willReturn(true);

		$manager = new PuntajesManajer($dbMock);
		$resultado = $manager->deletePuntaje(
			$fakeData['id_Usuario'],
			$fakeData['id_Juego'],
			$fakeData['fecha'],
			$fakeData['dificultad']
    	);

		$this->assertSame('', $resultado);
	}

	/**
	 * PRUEBA UNITARIA NEGATIVA - deletePuntaje
	 * Verifica que deletePuntaje retorne string vacío cuando la base de datos retorna error
	 */
	public function testDeletePuntajeRetornaStringVacioEnCasoDeError()
	{
		$dbMock = $this->createMock(IDataBaseManager::class);

		$fakeData = [
			[
				'id_usuario' => 999,
				'id_materia' => 999,
				'fecha' => '2099-05-05 02:05:16',
				'dificultad' => 'facil'
			],
		];

		$dbMock->expects($this->once())
			->method('insertQuery')
			->willReturn(false);

		$manager = new PuntajesManajer($dbMock);
		$resultado = $manager->deletePuntaje(
			$fakeData['id_Usuario'],
			$fakeData['id_Juego'],
			$fakeData['fecha'],
			$fakeData['dificultad']
    	);

		$this->assertSame('', $resultado);
	}

	// ========== PRUEBAS PARA getAllPuntajeForUsuario ==========

	/**
	 * PRUEBA UNITARIA POSITIVA - getAllPuntajeForUsuario
	 * Verifica que la función retorne un JSON codificado cuando la consulta retorna resultados
	 */
	public function testGetAllPuntajeForUsuarioRetornaJsonConDatos()
	{
		$fakeData = [
			[
				'id_usuario' => 1,
				'id_materia' => 2,
				'fecha' => '2026-05-04',
				'dificultad' => 'facil',
				'puntaje' => 100,
				'parejas_encontradas' => 8,
			],
			[
				'id_usuario' => 1,
				'id_materia' => 3,
				'fecha' => '2026-05-05',
				'dificultad' => 'dificil',
				'puntaje' => 85,
				'parejas_encontradas' => 7,
			],
		];

		$dbMock = $this->createMock(IDataBaseManager::class);

		$dbMock->expects($this->once())
			->method('realizeQuery')
			->willReturn($fakeData);

		$manager = new PuntajesManajer($dbMock);
		$resultado = $manager->getAllPuntajeForUsuario(1);
		$registros = json_decode($resultado, true);

		$this->assertIsArray($registros);
		$this->assertCount(2, $registros);
		$this->assertSame(1, $registros[0]['id_usuario']);
		$this->assertSame(100, $registros[0]['puntaje']);
	}

	/**
	 * PRUEBA UNITARIA NEGATIVA - getAllPuntajeForUsuario
	 * Verifica que la función retorne mensaje de tabla vacía cuando no hay resultados
	 */
	public function testGetAllPuntajeForUsuarioRetornaMensajeTablaVaciaParaUsuarioSinPuntajes()
	{
		$dbMock = $this->createMock(IDataBaseManager::class);

		$dbMock->expects($this->once())
			->method('realizeQuery')
			->willReturn(null);

		$manager = new PuntajesManajer($dbMock);
		$resultado = $manager->getAllPuntajeForUsuario(999);

		$this->assertSame('tabla materia vacia', $resultado);
	}

	// ========== PRUEBAS PARA getAllPuntajeForUsuarioAndMateria ==========

	/**
	 * PRUEBA UNITARIA POSITIVA - getAllPuntajeForUsuarioAndMateria
	 * Verifica que la función retorne JSON codificado cuando existen puntajes para usuario y materia
	 */
	public function testGetAllPuntajeForUsuarioAndMateriaRetornaJsonConDatos()
	{
		$fakeData = [
			[
				'id_usuario' => 1,
				'id_materia' => 2,
				'fecha' => '2026-05-04',
				'dificultad' => 'facil',
				'puntaje' => 100,
				'parejas_encontradas' => 8,
			],
			[
				'id_usuario' => 1,
				'id_materia' => 2,
				'fecha' => '2026-05-05',
				'dificultad' => 'dificil',
				'puntaje' => 95,
				'parejas_encontradas' => 7,
			],
		];

		$dbMock = $this->createMock(IDataBaseManager::class);

		$dbMock->expects($this->once())
			->method('realizeQuery')
			->willReturn($fakeData);

		$manager = new PuntajesManajer($dbMock);
		$resultado = $manager->getAllPuntajeForUsuarioAndMateria(1, 2);
		$registros = json_decode($resultado, true);

		$this->assertIsArray($registros);
		$this->assertCount(2, $registros);
		$this->assertSame(1, $registros[0]['id_usuario']);
		$this->assertSame(2, $registros[0]['id_materia']);
	}

	/**
	 * PRUEBA UNITARIA NEGATIVA - getAllPuntajeForUsuarioAndMateria
	 * Verifica que la función retorne mensaje de tabla vacía cuando no existen puntajes
	 */
	public function testGetAllPuntajeForUsuarioAndMateriaRetornaMensajeTablaVaciaParaCombinacionInexistente()
	{
		$dbMock = $this->createMock(IDataBaseManager::class);

		$dbMock->expects($this->once())
			->method('realizeQuery')
			->willReturn(null);

		$manager = new PuntajesManajer($dbMock);
		$resultado = $manager->getAllPuntajeForUsuarioAndMateria(999, 999);

		$this->assertSame('tabla materia varia', $resultado);
	}
}
