<?php

/**
 * Created by PhpStorm.
 * User: Andre
 * Date: 15/04/2016
 * Time: 05:22 PM
 */
require_once("Session.php");
require_once("DataBaseManager.php");

try {

    $session = new session();
    $user = $session->get("user");

    if ($user == false) {

        echo "";
    } else {

        $query = "SELECT id, nombre FROM usuario WHERE id = " . $user;

        $resultado = DataBaseManager::getInstance()->realizeQuery($query);

        if ($resultado != null) {
            //echo $user;
            echo json_encode($resultado);
        } else {
            echo "Error!";
        }
    }
} catch (Exception $e) {
    echo $e->getMessage(), "\n";
}

