<?php

/**
 * Created by IntelliJ IDEA.
 * User: jonathaneduardo
 * Date: 05/05/2016
 * Time: 07:09 PM
 */
require_once("DataBaseManager.php");

class PuntajesManajer {

    private $dbManager;
    private static $_instance;

    private function __construct() {
        $this->dbManager = DataBaseManager::getInstance();
    }

    public function __destruct() {
        /*
         * Falla cuando se llama a la funcion close();
         * */
        //$this->dbManager->close();
        self::$_instance = null;
    }

    public static function getInstance() {
        if (self::$_instance == null) {
            self::$_instance = new PuntajesManajer();
        }
        return self::$_instance;
    }

    public function setPuntaje($idUsuario,$idMateria,$fecha,$dificultad,$puntaje,$foundPeers){
        $query = "INSERT INTO puntajes (id_usuario,id_materia,fecha,dificultad,puntaje,parejas_encontradas) VALUES('$idUsuario','$idMateria','$fecha','$dificultad',$puntaje,$foundPeers)";

        $resultado = $this->dbManager->insertQuery($query);

        if (!is_bool($resultado)) {
            return $resultado;
        }
        return "";
    }

    public function deletePuntaje($idUsuario,$idMateria,$fecha,$dificultad){
        $query = "DELETE FROM puntajes WHERE id_usuario = '$idUsuario' AND id_materia = '$idMateria' AND fecha='$fecha' AND '$dificultad'";

        $resultado = $this->dbManager->insertQuery($query);

        if (!is_bool($resultado)) {
            return $resultado;
        }

        return "";
    }

    public function getAllPuntajeForUsuario($idUsuario) {
        $query = "SELECT * FROM puntajes WHERE id_usuario='$idUsuario'";

        $resultado = $this->dbManager->realizeQuery($query);

        if ($resultado == null) {
            return "tabla materia vacia";
        } else {
            if (is_array($resultado)) {
                return json_encode($resultado);
            } else {
                return $resultado->num_rows;
            }
        }
    }

    public function getAllPuntajeForMateria($idMateria) {
        $query = "SELECT * FROM puntajes WHERE id_materia='$idMateria'";

        $resultado = $this->dbManager->realizeQuery($query);

        if ($resultado == null) {
            return "tabla materia varia";
        } else {
            if (is_array($resultado)) {
                return json_encode($resultado);
            } else {
                return $resultado->num_rows;
            }
        }
    }

    public function getAllPuntajeForUsuarioAndMateria($idUsuario, $idMateria) {
        $query = "SELECT * FROM puntajes WHERE id_usuario='$idUsuario' AND id_materia='$idMateria'";

        $resultado = $this->dbManager->realizeQuery($query);

        if ($resultado == null) {
            return "tabla materia varia";
        } else {
            if (is_array($resultado)) {
                return json_encode($resultado);
            } else {
                return $resultado->num_rows;
            }
        }
    }

    public function getAllPuntajeForMateriaAndDificultad($idMateria,$dificultad){
        $query = "SELECT * FROM puntajes WHERE id_materia='$idMateria' AND dificultad='$dificultad'";

        $resultado = $this->dbManager->realizeQuery($query);

        if($resultado == null){
            return "tabla materia varia";
        }
        else{
            if(is_array($resultado)){
                return json_encode($resultado);
            }
            else{
                return $resultado->num_rows;
            }
        }
    }

    public function getAllPuntajeForUsuarioAndMateriaAndDificultad($idUsuario,$idMateria,$dificultad){
        $query = "SELECT * FROM puntajes WHERE id_usuario='$idUsuario' AND id_materia='$idMateria' AND dificultad='$dificultad'";

        $resultado = $this->dbManager->realizeQuery($query);

        if($resultado == null){
            return "tabla materia varia";
        }
        else{
            if(is_array($resultado)){
                return json_encode($resultado);
            }
            else{
                return $resultado->num_rows;
            }
        }
    }
}