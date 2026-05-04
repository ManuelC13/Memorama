<?php

/**
 * Created by IntelliJ IDEA.
 * User: jonathaneduardo
 * Date: 05/05/2016
 * Time: 07:19 PM
 */
require_once("PuntajesManajer.php");

$idUsuario = $_GET["idUsuario"];
$idMateria = $_GET["idMateria"];
//$fecha = $_GET["fecha"];
$dificultad = $_GET["dificultad"];
$puntaje = $_GET["puntaje"];
$foundPeers = $_GET["parejasEncontradas"];

$fecha = date("Y-m-d h:m:s");
$puntajesManager = PuntajesManajer::getInstance();

echo $puntajesManager->setPuntaje($idUsuario, $idMateria, $fecha, $dificultad, $puntaje, $foundPeers);