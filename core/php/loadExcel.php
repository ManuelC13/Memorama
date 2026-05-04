<?php
/**
 * Created by PhpStorm.
 * User: Andre
 * Date: 02/05/2016
 * Time: 03:23 AM
 */
header('Content-type: text/plain; charset=utf-8');
include "Couple.php";
include_once("ParejasManager.php");

$file = $_FILES['file'];


move_uploaded_file(
    $_FILES['file']['tmp_name'],
    './my_new_filename.whatever'
);
require_once dirname(__FILE__) . './PHPExcel/Classes/PHPExcel/IOFactory.php';
$objPHPExcel = PHPExcel_IOFactory::load('./my_new_filename.whatever');

if (!file_exists('./my_new_filename.whatever')) {
    exit("No se encontrò el archivo Excel" . EOL);
} else {

}



$sheet = $objPHPExcel->getSheet(0);

$conceptColumn = 'A';
$definitionColumn = 'B';
$idMatterColumn = 'C';


$coupleList = [];
$number = 2;
while(!is_null($sheet->getCell($conceptColumn.$number)->getValue())){

    $concept = $sheet->getCell($conceptColumn.$number)->getValue();
    $definition = $sheet->getCell($definitionColumn.$number)->getValue();
    $idMatter = $sheet->getCell($idMatterColumn.$number)->getValue();
    $couple = new Couple($concept,$definition,$idMatter);
    $coupleList[] = $couple;
    $number++;
}

try{
    $i=0;
    $parejasManager = ParejasManager::getInstance();
    foreach ($coupleList as $myCouple) {


        $id = $myCouple->getIdMatter();
        $con = $myCouple->getConcept();
        $def = $myCouple->getDefinition();

        $parejasManager->setPareja($id, $con, $def);


    }

}catch (Exception $e){
    echo 'Excepción capturada: ',  $e->getMessage(), "\n";
}

//var_dump($coupleList);














