<?php
/**
 * Created by PhpStorm.
 * User: Andre
 * Date: 14/05/2016
 * Time: 05:07 PM
 */
require_once("PuntajesManajer.php");
require_once ("MateriasManager.php");
require_once ("userManager.php");
include ("PHPExcel/CreateExcel.php");

$idMatter = $_GET['idMatter'];

$scoresManager = PuntajesManajer::getInstance();
$scores = $scoresManager->getAllPuntajeForMateria($idMatter);

createTable($scores);



function createTable($scores){
    $scoresArray = json_decode($scores);
    $table ='<html><body>';
    $table .= '<table border="1">';
    $table .= '<tr>';
    $table .= '<th>Usuario</th>';
    $table .= '<th>Materia</th>';
    $table .= '<th>Fecha</th>';
    $table .= '<th>Dificultad</th>';
    $table .= '<th>Puntaje</th>';
    $table .= '<th>Parejas Encontradas</th>';
    $table .= '</tr>';
    foreach ($scoresArray as $item) {
        $userName = getUserName($item->id_usuario);
        $matterName = getMatterName($item->id_materia);
        $table .= '<tr>';
        $table .= '<td>'.$userName.'</td>';
        $table .= '<td>'.$matterName.'</td>';
        $table .= '<td>'.$item->fecha.'</td>';
        $table .= '<td>'.$item->dificultad.'</td>';
        $table .= '<td>'.$item->puntaje.'</td>';
        $table .= '<td>'.$item->parejas_encontradas.'</td>';
        $table .= '</tr>';
        $table .= '<tr></tr>';
    }

    $table .= '</table>';
    $table.='</body></html>';
    generateXLS($table);
}


function getUserName($idUser){
    $userManager  = userManager::getInstance();
    $userText = $userManager->getUserById($idUser);
    $userJsonObject = json_decode($userText);
    $user = $userJsonObject[0];

    return $user->{'nombre'};

}

function getMatterName($idMatter){
    $matterManager = MateriasManager::getInstance();
    $matterText = $matterManager->getMateria($idMatter);
    $matterJsonObject = json_decode($matterText);
    $matter = $matterJsonObject[0];

    return $matter->{'nombre'};
}