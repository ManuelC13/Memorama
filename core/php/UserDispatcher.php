<?php
/**
 * Created by IntelliJ IDEA.
 * User: jonathaneduardo
 * Date: 09/04/2016
 * Time: 11:59 AM
 */
include_once("UserManager.php");

$userManager = UserManager::getInstance();

echo $userManager->getAllUsers();


