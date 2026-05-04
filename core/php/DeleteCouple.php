<?php
/**
 * Created by PhpStorm.
 * User: Andre
 * Date: 01/05/2016
 * Time: 07:00 PM
 */


include_once("ParejasManager.php");

$id = $_POST["id"];
$idMatter = $_POST["idMatter"];


$parejasManager = ParejasManager::getInstance();

echo $parejasManager->deletePareja($id,$idMatter);