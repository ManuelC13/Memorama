<?php
/**
 * Created by PhpStorm.
 * User: Andre
 * Date: 25/04/2016
 * Time: 11:40 PM
 */
include_once("UserManager.php");


$userId = $_POST["id"];
$userManager = UserManager::getInstance();

echo $userManager->deleteUser($userId);
