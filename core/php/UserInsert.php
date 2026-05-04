<?php
/**
 * Created by PhpStorm.
 * User: Andre
 * Date: 25/04/2016
 * Time: 11:56 PM
 */
require_once("userManager.php");
$userName = $_POST["username"];
$password = $_POST["password"];
$type = $_POST["type"];
$userManager = UserManager::getInstance();

$userManager->setUser($userName,$password,$type);
