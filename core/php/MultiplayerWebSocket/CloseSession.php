<?php
/**
 * Created by PhpStorm.
 * User: Andre
 * Date: 16/04/2016
 * Time: 03:24 AM
 */
require_once("Session.php");


$session = new session();
try {
    $session->delete_var("user");
    $session->session_finish();
    
}catch (Exception $e){
    echo "Ocurrio un error".$e->getCode();
}