<?php

require_once "./config/ConnectionFerro.php";

class CargaVagones {
    static private $tableName = 'carga_de_vagones_cd_piar';

    static public function getAll(){
        $table = self::$tableName;
        // $sql = "SELECT * FROM $table ORDER BY fecha";
        $sql = "SELECT 
                    c.id AS id_carga,
                    'Góndola TEU' AS tipo_vagon,
                    c.cantidad_gon AS cantidad_vagones,
                    mi.nombre AS origen,
                    em.nombre AS empresa,
                    ma.tipo AS tipo_carga,
                    c.total_carga,
                    c.fecha,
                    c.comentario,
                    'Góndola' AS tipo_vagon_real
                FROM cantidad_gondolas_teu c
                INNER JOIN vagones va ON c.id_gondola = va.id
                INNER JOIN minas mi ON c.id_mina = mi.id
                INNER JOIN empresa em ON c.id_empresa = em.id
                INNER JOIN material_excavado ma ON c.id_tipo_carga = ma.id
                
                UNION ALL

                SELECT 
                    c.id AS id_carga,
                    'Góndola FG' AS tipo_vagon,
                    c.cantidad_gon AS cantidad_vagones,
                    pl.nombre AS origen,
                    em.nombre AS empresa,
                    ma.tipo AS tipo_carga,
                    c.total_carga,
                    c.fecha,
                    c.comentario,
                    'Góndola' AS tipo_vagon_real
                FROM cantidad_gondolas_fg c
                INNER JOIN vagones va ON c.id_gondola = va.id
                INNER JOIN planta pl ON c.id_planta = pl.id
                INNER JOIN empresa em ON c.id_empresa = em.id
                INNER JOIN mineral_procesado ma ON c.id_tipo_carga = ma.id
                
                UNION ALL

                SELECT 
                    c.id AS id_carga,
                    'Tolva' AS tipo_vagon,
                    c.cantidad AS cantidad_vagones,
                    pl.nombre AS origen,
                    em.nombre AS empresa,
                    ma.tipo AS tipo_carga,
                    c.total_carga,
                    c.fecha,
                    c.comentario,
                    'Tolva' AS tipo_vagon_real
                FROM cantidad_tolvas c
                INNER JOIN planta pl ON c.id_planta = pl.id
                INNER JOIN empresa em ON c.id_empresa = em.id
                INNER JOIN mineral_procesado ma ON c.id_tipo_carga = ma.id
                
                ORDER BY fecha DESC;";

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
        $sql = "SELECT c.*, 
            va.id AS id_tolva, va.tipo AS tolva, 
            pt.id AS id_planta, pt.nombre AS planta, 
            em.id AS id_empresa, em.nombre AS empresa,
            mi.id AS id_tipo_carga, mi.tipo AS mineral
            FROM cantidad_tolvas c
            INNER JOIN vagones va ON c.id_tolva = va.id
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
        $response = CargaVagones::getById($id);
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

        $response = CargaVagones::getById( $id );
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