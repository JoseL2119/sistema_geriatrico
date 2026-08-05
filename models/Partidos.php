<?php

require_once "./config/Connection.php";

class Partidos {
    static private $tableName = 'partidos';

    // Devuelve todos las partidos ordenados por jornada y orden
    static public function getAll(){
        $table = self::$tableName;
        $sql = "SELECT p.*,
            el.nombre AS local, ev.nombre AS visitante
            FROM partidos p
            INNER JOIN equipos el ON p.idlocal = el.id
            INNER JOIN equipos ev ON p.idvisitante = ev.id
            ORDER BY p.jornada DESC, p.orden ASC";

        $stmt = Connection::getConnection()->prepare($sql);

        try{
            $stmt->execute();
        } catch(PDOException $e) {
            return null;
        }

        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    // Devuelve el Partido pasado por ID

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

    // Inserta un Partido nuevo

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

    // Modifica el Partido pasado por ID

    static public function update($id, $data){
        $table = self::$tableName;

        //Comprobar que el ID existe
        $response = Partidos::getById($id);
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

    // Elimina el partido pasado por ID

    static public function delete($id){
        $table = self::$tableName;

        $response = Partidos::getById( $id );
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