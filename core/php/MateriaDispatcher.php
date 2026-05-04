<?php
/**
 * Created by IntelliJ IDEA.
 * User: jonathaneduardo
 * Date: 09/04/2016
 * Time: 12:35 PM
 */
include_once("MateriasManager.php");

$materiaManager = MateriasManager::getInstance();

echo $materiaManager->getAllMateria();