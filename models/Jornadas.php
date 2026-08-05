<?php

require_once "./config/Connection.php";

class Jornadas {
    static private $tableName = 'jornadas';

    // Devuelve todas las jornadas ordenadas por número
    static public function getAll(){
        $table = self::$tableName;
        $sql = "SELECT * FROM $table ORDER BY jornada";
        $stmt = Connection::getConnection()->prepare($sql);

        try{
            $stmt->execute();
        } catch(PDOException $e) {
            return null;
        }

        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    // Devuelve la Jornada pasada por ID

    static public function getById($id){
        $table = self::$tableName;
        $sql = "SELECT * FROM $table WHERE id = :id";
        $stmt = Connection::getConnection()->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_STR);

        try{
            $stmt->execute();
        } catch(PDOException $e) {
            return null;
        }

        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    // Inserta una jonada nueva

    static public function insert($data){
        $table = self::$tableName;
        $columnas = "";
        $valores = "";

        foreach($data as $key => $value){
            $columnas .= $key . ",";
            $valores .= ":". $key .",";
        }
        
        // PARA QUITAR LAS COMAS
        $columnas = substr($columnas,0,-1);
        $valores = substr($valores,0,-1);

        $sql = "INSERT INTO $table ($columnas) VALUES ($valores)";

        $conn = Connection::getConnection();
        $stmt = $conn->prepare($sql);  

        foreach($data as $key => $value){
            $stmt->bindParam(":" . $key, $data[$key], PDO::PARAM_STR);
        }
        if($stmt->execute()){
            $response = array(
                "id" => $conn->lastInsertId(),
                "resultado" => "Registro grabado"
            );

            return $response;
        }

        return $conn->errorInfo();
    }

    // Modifica la jornada pasada por ID

    static public function update($id, $data){
        $table = self::$tableName;

        //Comprobar que el ID existe
        $response = Jornadas::getById($id);
        if(empty($response)){
            return null;
        }

        $columnas = "";
        $valores = "";

        foreach($data as $key => $value){
            $columnas .= $key . "= :" . $key . ",";
        }

        $columnas = substr( $columnas,0,-1);

        $sql = "UPDATE $table SET $columnas WHERE id = :id";

        $conn = Connection::getConnection();
        $stmt = $conn->prepare($sql);
        
        foreach($data as $key => $value){
            $stmt->bindParam(":". $key, $data[$key], PDO::PARAM_STR);
        }

        $stmt->bindParam(":id", $id, PDO::PARAM_STR);
        if($stmt->execute()){
            $response = array(
                "resultado" => "Registro actualizado"
            );
            return $response;
        }
        return $conn->errorInfo();
    }

    // Elimina la jornada pasada por ID

    static public function delete($id){
        $table = self::$tableName;

        $response = Jornadas::getById( $id );
        if(empty($response)){
            return null;
        }

        $sql = "DELETE FROM $table WHERE id = :id";

        $conn = Connection::getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_STR);
        if($stmt->execute()){
            $responde = array(
                "resultado" => "Registro borrado"
            );
            return $response;
        }
        return $conn->errorInfo();
    }

}


?>