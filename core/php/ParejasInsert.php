<?php
/**
 * Created by IntelliJ IDEA.
 * User: jonathaneduardo
 * Date: 09/04/2016
 * Time: 01:54 PM
 */

include_once("ParejasManager.php");

$idMatter = $_GET["idMatter"];
$concept = $_GET["concept"];
$definition = $_GET["definition"];


$parejasManager = ParejasManager::getInstance();

echo $parejasManager->setPareja($idMatter,$concept,$definition);