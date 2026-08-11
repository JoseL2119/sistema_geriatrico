<?php

require_once __DIR__ . '/../config/ConnectionFerro.php';

class Landing {
    static private $tableName = 'tarifas';

    static public function getEstadisticas(){

        $sql = "
            SELECT

                -- RESIDENTES
                (
                    SELECT COUNT(*)
                    FROM residentes
                ) AS total_residentes,

                -- RESIDENTES SIN CONTENCIÓN FAMILIAR
                (
                    SELECT COUNT(*)
                    FROM residentes
                    WHERE contencion_f = 0
                ) AS total_residentes_sin_contencion,

                -- RESIDENTES CON DISCAPACIDAD
                (
                    SELECT COUNT(*)
                    FROM residentes
                    WHERE condicion_mov != 2
                ) AS total_residentes_con_discapacidad,

                -- RESIDENTES QUE USAN PAÑAL
                (
                    SELECT COUNT(*)
                    FROM residentes
                    WHERE control_esfinteres = 0
                ) AS total_residentes_pañales,

                -- RESIDENTES CON CONVENIO IVSS
                (
                    SELECT COUNT(*)
                    FROM residentes
                    WHERE convenio_ivss = 1
                ) AS total_residentes_ivss,

                -- RESIDENTES QUE SON CASO PRIVADO
                (
                    SELECT COUNT(*)
                    FROM residentes
                    WHERE caso_privado = 1
                ) AS total_residentes_privados,

                -- RESIDENTES QUE SON PACIENTES PSIQUIÁTRICOS
                (
                    SELECT COUNT(*)
                    FROM residentes
                    WHERE psiquiatrico = 1
                ) AS total_residentes_psiquiatricos,


                -- REPRESENTANTES
                (
                    SELECT COUNT(*)
                    FROM representantes
                ) AS total_representantes,

                -- RESIDENTES CON REQUERIMIENTOS
                (
                    SELECT COUNT(DISTINCT id_residente)
                    FROM requerimientos_consignacion
                ) AS residentes_con_requerimientos,

                -- REQUERIMIENTOS PENDIENTES
                (
                    SELECT COUNT(*)
                    FROM vista_consignaciones_pendientes
                    WHERE estado = 'PENDIENTE'
                ) AS total_requerimientos_pendientes,

                -- REQUERIMIENTOS PARCIALES
                (
                    SELECT COUNT(*)
                    FROM vista_consignaciones_pendientes
                    WHERE estado = 'PARCIAL'
                ) AS total_requerimientos_parciales,

                -- ARTÍCULOS PENDIENTES
                (
                    SELECT COALESCE(
                        SUM(cantidad_pendiente), 
                        0
                    )
                    FROM vista_consignaciones_pendientes
                    WHERE estado IN ('PENDIENTE', 'PARCIAL')
                ) AS total_articulos_pendientes,

                -- CARGOS PENDIENTES
                (
                    SELECT COUNT(*)
                    FROM cargos_pendientes_residente
                ) AS total_cargos_pendientes,

                -- RESIDENTES CON DEUDA
                (
                    SELECT COUNT(DISTINCT id_residente)
                    FROM cargos_pendientes_residente
                ) AS residentes_con_deuda

        ";

        $stmt = ConnectionFerro::getConnection()->prepare($sql);

        try {

            $stmt->execute();

        } catch(PDOException $e) {

            return null;

        }

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    static public function getAll(){
        $table = self::$tableName;
        // $sql = "SELECT * FROM $table ORDER BY fecha";
        $sql = "SELECT t.*,
            re.nombres AS nombres, re.apellidos AS apellidos
            FROM tarifas t
            INNER JOIN residentes re ON t.id_residente = re.id
            ORDER BY t.id ASC";

        $stmt = ConnectionFerro::getConnection()->prepare($sql);

        try{
            $stmt->execute();
        } catch(PDOException $e) {
            return $e->getMessage();
        }

        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    static public function getById($id){
        $table = self::$tableName;
        // $sql = "SELECT * FROM $table WHERE id = :id";
        $sql = "SELECT t.*,
            re.id AS id_residente, re.nombres AS nombres, re.apellidos AS apellidos
            FROM tarifas t
            INNER JOIN residentes re ON t.id_residente = re.id
            WHERE t.id = :id";

        $stmt = ConnectionFerro::getConnection()->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_STR);

        try{
            $stmt->execute();
        } catch(PDOException $e) {
            return null;
        }

        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    static public function getFiltrados($filtros){
        $table = self::$tableName;
        $params = [];
        // $sql = "SELECT * FROM $table WHERE id = :id";
        $sql = "SELECT t.*,
            re.nombres AS nombres, re.apellidos AS apellidos
            FROM tarifas t
            INNER JOIN residentes re ON t.id_residente = re.id
            WHERE 1=1";

        if(!empty($filtros['fecha'])){
            $sql .= " AND fecha = ?";
            $params[] = $filtros['fecha'];
        }

        $sql .= " ORDER BY t.id ASC";

        $stmt = ConnectionFerro::getConnection()->prepare($sql);

        try{
            $stmt->execute($params);
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
            if ($value == '') {
                $stmt->bindValue(":" . $key, null, PDO::PARAM_NULL);

            } elseif (is_numeric($value)) {
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
        $response = Landing::getById($id);
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

        $response = Landing::getById( $id );
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