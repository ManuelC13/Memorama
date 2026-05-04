<?php
/**
 * Created by PhpStorm.
 * User: Andre
 * Date: 15/04/2016
 * Time: 05:22 PM
 */
require_once("Session.php");
try{

$session = new session();
$user = $session->get("user");

if( $user == false )  {

    echo "";
}  else{

    echo "Exist";
}
    
} catch (Exception $e){
    echo   $e->getMessage(), "\n";
}