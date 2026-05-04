<?php
/**
 * Created by PhpStorm.
 * User: Andre
 * Date: 01/05/2016
 * Time: 10:25 PM
 */

include_once("ParejasManager.php");

$id = $_POST["id"];
$idMatter = $_POST["idMatter"];
$concept =$_POST["concept"];
$definition = $_POST["definition"];


$parejasManager = ParejasManager::getInstance();

echo $parejasManager->updatePareja($id,$idMatter,$concept,$definition);