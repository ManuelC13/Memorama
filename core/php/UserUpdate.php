<?php
/**
 * Created by PhpStorm.
 * User: Andre
 * Date: 01/05/2016
 * Time: 03:43 PM
 */

require_once("userManager.php");
$id = $_POST["id"];
$userName = $_POST["username"];
$password = $_POST["password"];
$type = $_POST["type"];
$userManager = UserManager::getInstance();

$userManager->updateUser($id,$userName,$password,$type);