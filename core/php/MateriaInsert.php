<?php
/**
 * Created by IntelliJ IDEA.
 * User: jonathaneduardo
 * Date: 09/04/2016
 * Time: 01:42 PM
 */
include_once("MateriasManager.php");

$nombreMateria = $_POST["materia"];


$materiaManager = MateriasManager::getInstance();

$materiaManager->setMateria($nombreMateria);

