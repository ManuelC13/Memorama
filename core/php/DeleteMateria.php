<?php
/**
 * Created by PhpStorm.
 * User: Andre
 * Date: 25/04/2016
 * Time: 08:42 PM
 */
include_once("MateriasManager.php");

$idMateria = $_POST["id"];


$materiaManager = MateriasManager::getInstance();

echo $materiaManager->deleteMateria($idMateria);