<?php

require_once("PuntajesManajer.php");

$puntajesManager = PuntajesManajer::getInstance();

$nombre = $_GET['nombre'];
$materia = $_GET['materia'];
$puntaje = $_GET['puntaje'];

$fecha = date("Y-m-d");

echo $puntajesManager->setPuntajePorNombre($nombre, $materia, $fecha, $puntaje);

