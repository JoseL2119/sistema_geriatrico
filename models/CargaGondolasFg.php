<?php

require_once "./config/ConnectionFerro.php";
require_once "./models/CargaVagones.php";

class CargaGondolasFg {
    static private $tableName = 'cantidad_gondolas_fg';
    static private $cargaVagonesTableName = 'carga_de_vagones_cd_piar';

    static public function getAll(){
        $table = self::$tableName;
        // $sql = "SELECT * FROM $table ORDER BY fecha";
        $sql = "SELECT c.*,
            va.tipo AS gondola, pt.nombre AS planta, em.nombre AS empresa, mi.tipo AS mineral
            FROM cantidad_gondolas_fg c
            INNER JOIN vagones va ON c.id_gondola = va.id
            INNER JOIN planta pt ON c.id_planta = pt.id
            INNER JOIN empresa em ON c.id_empresa = em.id
            INNER JOIN mineral_procesado mi ON c.id_tipo_carga = mi.id         
            ORDER BY c.fecha DESC";

        $stmt = ConnectionFerro::getConnection()->prepare($sql);

        try{
            $stmt->execute();
        } catch(PDOException $e) {
            return null;
        }

        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    static public function insertaEnCargaVagones($id){

    }

    static public function getById($id){
        $table = self::$tableName;
        // $sql = "SELECT * FROM $table WHERE id = :id";
        $sql = "SELECT c.*, 
            va.id AS id_gondola, va.tipo AS gondola, 
            pt.id AS id_planta, pt.nombre AS planta, 
            em.id AS id_empresa, em.nombre AS empresa,
            mi.id AS id_tipo_carga, mi.tipo AS mineral
            FROM cantidad_gondolas_fg c
            INNER JOIN vagones va ON c.id_gondola = va.id
            INNER JOIN planta pt ON c.id_planta = pt.id
            INNER JOIN empresa em ON c.id_empresa = em.id
            INNER JOIN mineral_procesado mi ON c.id_tipo_carga = mi.id    
            WHERE c.id = :id";

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
        $vagonTable = self::$cargaVagonesTableName;
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

            $idenficador = $conn->lastInsertId();

            $sqlVagon = "INSERT INTO $vagonTable (id_cantidad_gon_fg) VALUES ($idenficador)";
            $stmtVagon = $conn->prepare($sqlVagon);
            // $stmtVagon->bindParam(":" . $key, $data[$key], PDO::PARAM_INT);

            if($stmtVagon->execute()){
                $responseVagon = array(
                    "id" => $conn->lastInsertId(),
                    "resultado" => "Registro grabado"
                );
                
            }

            return $response + $responseVagon;
        }

        return $conn->errorInfo();
    }

    static public function update($id, $data){
        $table = self::$tableName;

        //Comprobar que el ID existe
        $response = CargaGondolasFg::getById($id);
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

        $response = CargaGondolasFg::getById( $id );
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