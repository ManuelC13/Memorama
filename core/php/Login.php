<?php

/**
 * Created by PhpStorm.
 * User: Andre
 * Date: 07/02/2016
 * Time: 06:29 PM
 */

include "DataBaseManager.php";
require_once "Session.php";
require_once "AuthService.php";

$username = $_POST["username"] ?? '';
$password = $_POST["password"] ?? '';

$authService = new AuthService(
    DataBaseManager::getInstance(),
    new session()
);

$result = $authService->login($username, $password);
echo json_encode($result);
