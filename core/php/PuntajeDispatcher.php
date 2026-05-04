<?php
/**
 * Created by IntelliJ IDEA.
 * User: jonathaneduardo
 * Date: 05/05/2016
 * Time: 07:29 PM
 */

require_once("PuntajesManajer.php");

if(array_key_exists('idUsuario',$_GET)){
    $idUsuario = $_GET["idUsuario"];
}
if(array_key_exists('idMateria',$_GET)){
    $idMateria = $_GET["idMateria"];
}
if(array_key_exists('dificultad',$_GET)){
    $dificultad = $_GET["dificultad"];
}
if(array_key_exists('tipo',$_GET)){
    $tipo = $_GET["tipo"];
}
else{
    echo "campo tipo requerido";
    return;
}
$puntajesManager = PuntajesManajer::getInstance();

switch($tipo){
    case 1:
        echo $puntajesManager->getAllPuntajeForUsuario($idUsuario);
        break;
    case 2:
        echo $puntajesManager->getAllPuntajeForMateria($idMateria);
        break;
    case 3:
        echo $puntajesManager->getAllPuntajeForUsuarioAndMateria($idUsuario,$idMateria);
        break;
    case 4:
        echo $puntajesManager->getAllPuntajeForMateriaAndDificultad($idMateria,$dificultad);
        break;
    case 5:
        echo $puntajesManager->getAllPuntajeForUsuarioAndMateriaAndDificultad($idUsuario,$idMateria,$dificultad);
        break;
}
