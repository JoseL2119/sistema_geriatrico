<?php

require_once __DIR__ . '/../config/ConnectionFerro.php';

class ConsumoInsumos {
    static private $tableName = 'detalles_reportes_insumos';

    static public function getAll() {
        $table = self::$tableName;
        //$sql = "SELECT * FROM $table ORDER BY fecha DESC, id DESC";

        $fecha = date('Y-m-d');

        $sql = "SELECT e.*,
            ins.nombre AS insumo, ins.unidad AS unidad, 
            r.fecha AS fecha,
            d.nombre AS departamento
            FROM detalles_reportes_insumos e
            INNER JOIN insumos_criticos ins ON e.insumo_id = ins.id
            INNER JOIN reportes_diarios_insumos r ON e.reporte_id = r.id 
            INNER JOIN departamentos d ON ins.id_departamento = d.id
            WHERE r.fecha = :fecha
            ORDER BY ins.nombre ASC";

        $stmt = ConnectionFerro::getConnection()->prepare($sql);
        $stmt->bindParam(":fecha", $fecha, PDO::PARAM_STR);

        try {
            $stmt->execute();
        } catch (PDOException $e) {
            return null;
        }

        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    static public function getById($id) {
        $table = self::$tableName;
        //$sql = "SELECT * FROM $table WHERE id = :id";
        $sql = "SELECT e.*,
            ins.id AS id_insumo, ins.nombre AS insumo,
            r.fecha AS fecha
            FROM detalles_reportes_insumos e
            INNER JOIN insumos_criticos ins ON e.insumo_id = ins.id
            INNER JOIN reportes_diarios_insumos r ON e.reporte_id = r.id 
            WHERE e.id = :id
            ORDER BY ins.nombre ASC";

        $stmt = ConnectionFerro::getConnection()->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);

        try {
            $stmt->execute();
        } catch (PDOException $e) {
            return null;
        }

        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    static public function getFiltrados($filtros) {
        $table = self::$tableName;
        $params = [];
        //$sql = "SELECT * FROM $table ORDER BY fecha DESC, id DESC";
        $sql = "SELECT e.*,
            ins.id AS id_insumo, ins.nombre AS insumo, ins.unidad AS unidad,
            r.fecha AS fecha,
            d.nombre AS departamento
            FROM detalles_reportes_insumos e
            INNER JOIN insumos_criticos ins ON e.insumo_id = ins.id
            INNER JOIN reportes_diarios_insumos r ON e.reporte_id = r.id
            INNER JOIN departamentos d ON ins.id_departamento = d.id
            WHERE 1=1";

        if(!empty($filtros['fecha_filtro'])){
            $sql .= " AND r.fecha = :fecha";
            $params[':fecha'] = $filtros['fecha_filtro'];
        }

        $sql .= " ORDER BY ins.nombre ASC";

        $stmt = ConnectionFerro::getConnection()->prepare($sql);

        try {
            $stmt->execute($params);
        } catch (PDOException $e) {
            return null;
        }

        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    static public function insert(array $dataReporte, array $detallesInsumos) {
        $conn = ConnectionFerro::getConnection();
        $conn->beginTransaction(); 

        try {
            // 1. Insertar en 'reportes_diarios_insumos' (tabla principal)
            $sqlReporte = "INSERT INTO reportes_diarios_insumos (fecha) 
                        VALUES (:fecha)";
            $stmtReporte = $conn->prepare($sqlReporte);
            $stmtReporte->bindValue(':fecha', $dataReporte['fecha']);
            $stmtReporte->execute();
            $reporteId = $conn->lastInsertId(); // ID del reporte recién creado

            // 2. Insertar múltiples registros en 'detalles_reportes_insumos' (detalles)
            $sqlDetalle = "INSERT INTO detalles_reportes_insumos 
                            (reporte_id, insumo_id, stock_inicial, cantidad_recibida, cantidad_entregada, stock_final, observacion)
                            VALUES 
                            (:reporte_id, :insumo_id, :stock_inicial, :cantidad_recibida, :cantidad_entregada, :stock_final, :observacion)";
            $stmtDetalle = $conn->prepare($sqlDetalle);

            foreach ($detallesInsumos as $insumo) {
                $stmtDetalle->bindValue(':reporte_id', $reporteId);
                $stmtDetalle->bindValue(':insumo_id', $insumo['insumo_id']);
                $stmtDetalle->bindValue(':stock_inicial', $insumo['stock_inicial']);
                $stmtDetalle->bindValue(':cantidad_recibida', $insumo['cantidad_recibida']);
                $stmtDetalle->bindValue(':cantidad_entregada', $insumo['cantidad_entregada']);
                $stmtDetalle->bindValue(':stock_final', $insumo['stock_final']);
                $stmtDetalle->bindValue(':observacion', $insumo['observacion'] ?? null); // Campo opcional
                $stmtDetalle->execute();
            }

            $conn->commit(); // Confirmar transacción si todo va bien
            return [
                "id" => $reporteId,
                "resultado" => "Reporte diario y detalles grabados correctamente."
            ];

        } catch (PDOException $e) {
            $conn->rollBack(); // Revertir cambios en caso de error
            return ["error" => "Error al guardar: " . $e->getMessage()];
        }
    }

    static public function update($id, $data){
        $table = self::$tableName;

        //Comprobar que el ID existe
        $response = ConsumoInsumos::getById($id);
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

    static public function delete($id) {
        $table = self::$tableName;
        $sql = "DELETE FROM $table WHERE id = :id";
        $conn = ConnectionFerro::getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $id);

        try {
            $stmt->execute();
            return ["resultado" => "Registro borrado"];
        } catch (PDOException $e) {
            return ["error" => $e->getMessage()];
        }
    }
}