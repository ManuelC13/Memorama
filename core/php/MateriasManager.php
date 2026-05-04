<?php
/**
 * Created by IntelliJ IDEA.
 * User: jonathaneduardo
 * Date: 09/04/2016
 * Time: 02:37 PM
 */

require_once("DataBaseManager.php");

class MateriasManager{
    private $dbManager;
    private static $_instance;

    private function __construct(){
        $this->dbManager = DataBaseManager::getInstance();
    }

    public function __destruct(){
        /*
         * Falla cuando se llama a la funcion close();
         * */
        //$this->dbManager->close();
        self::$_instance = null;
    }

    public static function getInstance(){
        if(self::$_instance == null){
            self::$_instance = new MateriasManager();
        }
        return self::$_instance;
    }

    public function getMateria($idmateria){
        $query = "SELECT * FROM materias WHERE id = $idmateria";

        $resultado = $this->dbManager->realizeQuery($query);

        if($resultado == null){
            return "Tabla de materias esta vacia";
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

    public function setMateria($name){
        $query = "INSERT INTO materias (nombre) VALUES('$name')";

        $resultado = $this->dbManager->insertQuery($query);

        if(!is_bool($resultado)){
            return $resultado;
        }
        return "";
    }


    public function updateMateria($id,$name){
        $query = "UPDATE materias set nombre= '$name' WHERE id =".intval($id);

        $resultado = $this->dbManager->insertQuery($query);

        if(!is_bool($resultado)){
            return $resultado;
        }
        return "";
    }

    public function deleteMateria($idMateria){
        $query = "DELETE FROM materias WHERE id = '$idMateria'";

        $resultado = $this->dbManager->insertQuery($query);

        if(!is_bool($resultado)){
            return $resultado;
        }

        return "";
    }

    public function getAllMateria(){
        $query = "SELECT * FROM materias";

        $resultado = $this->dbManager->realizeQuery($query);

        if($resultado == null){
            return "tabla materia vacia";
        }
        else{
            if(is_array($resultado)){
                $matterList[] = $this->setValuesToResult($resultado);
                return json_encode($matterList);
            }
            else{
                return $resultado->num_rows;
            }
        }
    }




    private function setValuesToResult($result){
        $matter = array();
        for ($i=0;$i<count($result);$i++) {
            $matter['id'] = $result[$i]['id'];
            $matter['name'] = $result[$i]['nombre'];

            $matterList[] = $matter;

        }

        return $matterList;
    }
}