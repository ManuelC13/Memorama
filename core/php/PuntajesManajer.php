<?php

/**
 * Created by IntelliJ IDEA.
 * User: jonathaneduardo
 * Date: 05/05/2016
 * Time: 07:09 PM
 */
require_once("DataBaseManager.php");
require_once("IDataBaseManager.php");

class PuntajesManajer {

    private $dbManager;
    private static $_instance;

    /*
    private function __construct() {
        $this->dbManager = DataBaseManager::getInstance();
    }
    */

    /*
     * Mantenimiento realizado por: Raúl Nahuat y Manuel Cupul
     * Descripción: Se agregó un constructor que permite inyectar una instancia de IDataBaseManager para facilitar las pruebas unitarias.
     * Tipo de mantenimiento: Correctivo
     * línea modificada: Se agregó el método __construct(IDataBaseManager $dbManager = null)
     */
    public function __construct(IDataBaseManager $dbManager = null) {
        $this->dbManager = $dbManager ?? DataBaseManager::getInstance();
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

    /**
     * Mantenimiento realizado por: Raúl Nahuat
     * Descripción: Se agregó el nombre del usuario a la consulta para que se pueda mostrar en la vista de ranking.
     * Tipo de mantenimiento: Correctivo
     * línea modificada: $query = "SELECT puntajes.id_usuario, usuario..."
     */
    public function getAllPuntajeForMateria($idMateria) {
        $query = "SELECT puntajes.id_usuario, usuario.nombre, puntajes.id_materia, puntajes.fecha, puntajes.dificultad, puntajes.puntaje, puntajes.parejas_encontradas FROM puntajes INNER JOIN usuario ON puntajes.id_usuario = usuario.id WHERE puntajes.id_materia='$idMateria' ORDER BY puntajes.puntaje DESC, puntajes.fecha ASC";

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