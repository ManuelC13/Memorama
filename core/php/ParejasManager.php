<?php

/**
 * Created by IntelliJ IDEA.
 * User: jonathaneduardo
 * Date: 09/04/2016
 * Time: 03:01 PM
 */
require_once("DataBaseManager.php");

class ParejasManager {

    private $dbManager;
    private static $_instance;

    private function __construct() {
        $this->dbManager = DataBaseManager::getInstance();
    }

    public function __destruct() {
        $this->dbManager->close();
        self::$_instance = null;
    }

    public static function getInstance() {
        if (self::$_instance == null) {
            self::$_instance = new ParejasManager();
        }
        return self::$_instance;
    }

    public function getPareja($idMateria, $id) {
        $query = "SELECT concepto,descripcion, FROM parejas WHERE id='$id' AND idmateria= '$idMateria'";

        $resultado = $this->dbManager->realizeQuery($query);

        if ($resultado == null) {
            return "tabla de parejas vacia";
        } else {
            if (is_array($resultado)) {
                return json_encode($resultado);
            } else {
                return $resultado->num_rows;
            }
        }
    }

    public function setPareja($idmateria, $concepto, $descripcion) {
        $query = "INSERT INTO parejas (concepto,descripcion,idmateria) VALUES('$concepto','$descripcion','$idmateria')";

        $resultado = $this->dbManager->insertQuery($query);

        if (!is_bool($resultado)) {
            return $resultado;
        }

        return "";
    }

    public function updatePareja($id, $idMatter, $concept, $definition) {
        $query = "UPDATE parejas set idmateria = '$idMatter' , concepto = 
        '$concept' , descripcion = '$definition' WHERE id=" . intval($id);

        $resultado = $this->dbManager->insertQuery($query);

        if (!is_bool($resultado)) {
            return $resultado;
        }
        return "";
    }

    public function deletePareja($id, $idMateria) {
        $query = "DELETE FROM parejas WHERE id='$id' AND idmateria='$idMateria'";

        $resultado = $this->dbManager->insertQuery($query);

        if (!is_bool($resultado)) {
            return $resultado;
        }

        return "";
    }

    public function getAllParejasTheMateria($idMateria) {
        $query = "SELECT concepto,descripcion FROM parejas WHERE idmateria = $idMateria";

        $resultado = $this->dbManager->realizeQuery($query);

        if ($resultado == null) {
            return "";
        } else {
            if (is_array($resultado)) {
                return json_encode($resultado);
            } else {
                return $resultado->num_rows;
            }
        }
    }

    public function getAllParejas() {
        $query = "SELECT * FROM parejas";

        $resultado = $this->dbManager->realizeQuery($query);

        if ($resultado == null) {
            return "tabla materia vacia";
        } else {
            if (is_array($resultado)) {
                $coupleList[] = $this->setValuesToResult($resultado);
                return json_encode($coupleList);
            } else {
                return $resultado->num_rows;
            }
        }
    }

    private function setValuesToResult($result) {
        $couple = array();
        for ($i = 0; $i < count($result); $i++) {
            $couple['id'] = $result[$i]['id'];
            $couple['idMatter'] = $result[$i]['idmateria'];
            $couple['concept'] = $result[$i]['concepto'];
            $couple['definition'] = $result[$i]['descripcion'];


            $coupleList[] = $couple;
        }

        return $coupleList;
    }

}

