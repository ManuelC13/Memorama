<?php
/**
 * Created by PhpStorm.
 * User: Andre
 * Date: 14/05/2016
 * Time: 03:28 PM
 */



function generateXLS($table) {

    header('Content-Type: application/force-download');
    header('Content-Disposition: attachment; filename="Reporte.xls"');
    header('Content-Transfer-Encoding: binary');
    print $table;

}


