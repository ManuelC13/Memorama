<?php
/**
 * Created by PhpStorm.
 * User: Andre
 * Date: 01/05/2016
 * Time: 03:33 PM
 */
include_once("MateriasManager.php");
$id = $_POST["id"];
$nombreMateria = $_POST["materia"];


$materiaManager = MateriasManager::getInstance();

$materiaManager->updateMateria($id,$nombreMateria);