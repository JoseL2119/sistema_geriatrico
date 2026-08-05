<?php

require_once "./config/ConnectionFerro.php";

class CamionesPzo {
    static private $tableName = 'camiones_pzo';

    static public function getAll(){
        $table = self::$tableName;
        // $sql = "SELECT * FROM $table ORDER BY fecha";
        $sql = "SELECT p.*,
            mi.tipo AS mineral, em.nombre AS empresa 
            FROM camiones_pzo p
            INNER JOIN mineral_procesado mi ON p.id_tipo_mineral = mi.id
            INNER JOIN empresa em ON p.id_empresa_d = em.id
            ORDER BY p.fecha DESC";

        $stmt = ConnectionFerro::getConnection()->prepare($sql);

        try{
            $stmt->execute();
        } catch(PDOException $e) {
            return null;
        }

        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    static public function getById($id){
        $table = self::$tableName;
        // $sql = "SELECT * FROM $table WHERE id = :id";
        $sql = "SELECT p.*, 
            mi.id AS id_tipo_mineral, mi.tipo AS mineral, 
            em.id AS id_empresa_d, em.nombre AS empresa
            FROM camiones_pzo p
            INNER JOIN mineral_procesado mi ON p.id_tipo_mineral = mi.id
            INNER JOIN empresa em ON p.id_empresa_d = em.id
            WHERE p.id = :id";

        $stmt = ConnectionFerro::getConnection()->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_STR);

        try{
            $stmt->execute();
        } catch(PDOException $e) {
            return null;
        }

        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

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

        $conn = ConnectionFerro::getConnection();
        $stmt = $conn->prepare($sql);  

        foreach($data as $key => $value){
            // $stmt->bindParam(":" . $key, $data[$key], PDO::PARAM_STR);
            if (is_numeric($value)) {
                $stmt->bindParam(":" . $key, $data[$key], PDO::PARAM_INT);
            } else {
                $stmt->bindParam(":" . $key, $data[$key], PDO::PARAM_STR);
            }
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

    static public function update($id, $data){
        $table = self::$tableName;

        //Comprobar que el ID existe
        $response = CamionesPzo::getById($id);
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

        $conn = ConnectionFerro::getConnection();
        $stmt = $conn->prepare($sql);
        
        foreach($data as $key => $value){
            // $stmt->bindParam(":". $key, $data[$key], PDO::PARAM_STR);
            if (is_numeric($value)) {
                $stmt->bindParam(":" . $key, $data[$key], PDO::PARAM_INT);
            } else {
                $stmt->bindParam(":" . $key, $data[$key], PDO::PARAM_STR);
            }
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

    static public function delete($id){
        $table = self::$tableName;

        $response = CamionesPzo::getById( $id );
        if(empty($response)){
            return null;
        }

        $sql = "DELETE FROM $table WHERE id = :id";

        $conn = ConnectionFerro::getConnection();
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