<?php
/**
 * Created by IntelliJ IDEA.
 * User: jonathaneduardo
 * Date: 09/04/2016
 * Time: 02:11 PM
 */

require_once("DataBaseManager.php");

class UserManager{
    private $dbManager;
    private static $_instance;

    private function __construct(){
        $this->dbManager = DataBaseManager::getInstance();
    }

    public function __destruct(){
        $this->dbManager->close();
        self::$_instance = null;
    }

    public static function getInstance(){
        if(self::$_instance == null){
            self::$_instance = new UserManager();
        }
        return self::$_instance;
    }

    public function setUser($name, $password, $tipo){
        $query = "INSERT INTO usuario (nombre, clave, tipo) VALUES('$name','$password','$tipo')";

        $resultado = $this->dbManager->insertQuery($query);

        if(!is_bool($resultado)){
            return "$resultado";
        }
        return "";

    }

    public function updateUser($id,$name, $password, $tipo){
        $query = "UPDATE usuario set nombre = '$name' , clave = '$password' , tipo = '$tipo' WHERE id=".intval($id);

        $resultado = $this->dbManager->insertQuery($query);

        if(!is_bool($resultado)){
            return $resultado;
        }
        return "";
    }

    public function getUser($name, $password){
        $query = "SELECT * FROM usuario WHERE nombre='$name' AND clave='$password'";

        $resultado = $this->dbManager->realizeQuery($query);

        if($resultado == null){
            return "Tabla usuario vacia";
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

    function getUserById($id){
        $query = "SELECT * FROM usuario WHERE id='$id' ";

        $resultado = $this->dbManager->realizeQuery($query);

        if($resultado == null){
            return "Tabla usuario vacia";
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

    public function deleteUser($UserId){
        $query = "DELETE FROM usuario WHERE id = $UserId";

        $resultado = $this->dbManager->insertQuery($query);

        if(!is_bool($resultado)){
            return "$resultado";
        }

        return "";
    }


    public function getAllUsers(){
        $query = "SELECT * FROM usuario";

        $resultado = $this->dbManager->realizeQuery($query);

        if($resultado == null){
            return "Tabla usuario vacia";
        }
        else{
            if(is_array($resultado)){
                $usersList[] = $this->setValuesToResult($resultado);
                return json_encode($usersList);
            }
            else{
                return $resultado->num_rows;
            }
        }
    }


    private function setValuesToResult($result){
        $users = array();
        for ($i=0;$i<count($result);$i++) {
            $users['id'] = $result[$i]['id'];
            $users['name'] = $result[$i]['nombre'];
            $users['type'] = $result[$i]['tipo'];
            $users['password'] = $result[$i]['clave'];

            $usersList[] = $users;

        }

        return $usersList;
    }
}