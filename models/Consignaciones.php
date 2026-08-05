<?php

require_once __DIR__ . '/../config/ConnectionFerro.php';

class Consignaciones {
    static private $tableName = 'consignaciones';

    static public function getAll(){
        $table = self::$tableName;
        // $sql = "SELECT * FROM $table ORDER BY fecha";
        $sql = "SELECT c.*,
            re.nombres AS nombres_residente, re.apellidos AS apellidos_residente, r.nombres AS nombres_representante, r.apellidos AS apellidos_representante
            FROM consignaciones c
            INNER JOIN residentes re ON c.id_residente = re.id
            INNER JOIN representantes r ON c.id_representante = r.id
            ORDER BY c.fecha DESC";

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
        $sql = "SELECT c.*,
            re.cedula AS cedula_residente, re.nombres AS nombres_residente, re.apellidos AS apellidos_residente, 
            r.nombres AS nombres_representante, r.apellidos AS apellidos_representante, 
            a.nombre AS nombre_articulo, a.id AS id_articulo, d.cantidad AS cantidad, d.observaciones AS observaciones_detalle
            FROM consignaciones c
            INNER JOIN residentes re ON c.id_residente = re.id
            INNER JOIN representantes r ON c.id_representante = r.id
            INNER JOIN detalles_consignaciones d ON d.id_consignacion = c.id
            INNER JOIN articulos a ON d.id_articulo = a.id
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

    static public function getFiltrados($filtros){
        $table = self::$tableName;
        $params = [];
        // $sql = "SELECT * FROM $table WHERE id = :id";
        $sql = "SELECT c.*,
            re.nombres AS nombres_residente, re.apellidos AS apellidos_residente, r.nombres AS nombres_representante, r.apellidos AS apellidos_representante
            FROM consignaciones c
            INNER JOIN residentes re ON c.id_residente = re.id
            INNER JOIN representantes r ON c.id_representante = r.id
            WHERE 1=1";

        if(!empty($filtros['fecha'])){
            $sql .= " AND fecha = ?";
            $params[] = $filtros['fecha'];
        }

        $sql .= " ORDER BY c.id ASC";

        $stmt = ConnectionFerro::getConnection()->prepare($sql);

        try{
            $stmt->execute($params);
        } catch(PDOException $e) {
            return null;
        }

        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    static public function insert($data){
        $conn = ConnectionFerro::getConnection();

        try {

            // Iniciar transacción
            $conn->beginTransaction();

            // --------------------------------
            // 1. REGISTRAR CONSIGNACIÓN
            // --------------------------------

            $sqlConsignacion = "
                INSERT INTO consignaciones
                (
                    fecha,
                    id_residente,
                    id_representante,
                    observaciones
                )
                VALUES
                (
                    :fecha,
                    :id_residente,
                    :id_representante,
                    :observaciones
                )
                RETURNING id
            ";

            $stmtConsignacion = $conn->prepare($sqlConsignacion);

            $stmtConsignacion->execute([
                ':fecha' => $data['fecha'],
                ':id_residente' => $data['id_residente'],
                ':id_representante' => $data['id_representante'],
                ':observaciones' => $data['observaciones']
            ]);

            // Recuperar ID generado
            $idConsignacion = $stmtConsignacion->fetchColumn();


            // --------------------------------
            // 2. REGISTRAR DETALLES
            // --------------------------------

            $sqlDetalles = "
                INSERT INTO detalles_consignaciones
                (
                    id_consignacion,
                    id_articulo,
                    cantidad,
                    observaciones
                )
                VALUES
                (
                    :id_consignacion,
                    :id_articulo,
                    :cantidad,
                    :observaciones
                )
            ";

            $stmtDetalles = $conn->prepare($sqlDetalles);

            foreach ($data['detalles'] as $detalles) {
                $stmtDetalles->execute([
                    ':id_consignacion' => $idConsignacion,
                    ':id_articulo' => $detalles['id_articulo'],
                    ':cantidad' => $detalles['cantidad'],
                    ':observaciones' => $detalles['observaciones']
                ]);
            }


            // --------------------------------
            // 3. CONFIRMAR TRANSACCIÓN
            // --------------------------------

            $conn->commit();

            return [
                'resultado' => 'Registro realizado correctamente',
                'id_consignacion' => $idConsignacion
            ];


        } catch (PDOException $e) {

            // --------------------------------
            // 4. DESHACER TODO SI HAY ERROR
            // --------------------------------

            if ($conn->inTransaction()) {
                $conn->rollBack();
            }

            return [
                'resultado' => 'error',
                'mensaje' => $e->getMessage()
            ];
        }
    }

    static public function update($id, $data){

        $conn = ConnectionFerro::getConnection();

        try {

            // Iniciar transacción
            $conn->beginTransaction();

            // --------------------------------
            // 1. ACTUALIZAR CONSIGNACIONES ANTERIORES
            // --------------------------------

            $sqlActualizar = "
                UPDATE consignaciones
                SET
                    fecha = :fecha,
                    id_residente = :id_residente,
                    id_representante = :id_representante,
                    observaciones = :observaciones
                WHERE id = :id_consignacion
            ";

            $stmtActualizar = $conn->prepare($sqlActualizar);

            $stmtActualizar->execute([
                ':fecha' => $data['fecha'],
                ':id_residente' => $data['id_residente'],
                ':id_representante' => $data['id_representante'],
                ':observaciones' => $data['observaciones'],
                ':id_consignacion' => $id
            ]);


            // --------------------------------
            // 2. ELIMINAR DETALLES ANTERIORES
            // --------------------------------

            $sqlEliminarDetalles = "
                DELETE FROM detalles_consignaciones
                WHERE id_consignacion = :id_consignacion
            ";

            $stmtEliminarDetalles = $conn->prepare(
                $sqlEliminarDetalles
            );

            $stmtEliminarDetalles->execute([
                ':id_consignacion' => $id
            ]);


            // --------------------------------
            // 3. INSERTAR NUEVOS DETALLES
            // --------------------------------

            $sqlDetalles = "
                INSERT INTO detalles_consignaciones
                (
                    id_consignacion,
                    id_articulo,
                    cantidad,
                    observaciones
                )
                VALUES
                (
                    :id_consignacion,
                    :id_articulo,
                    :cantidad,
                    :observaciones
                )
            ";

            $stmtDetalles = $conn->prepare($sqlDetalles);

            foreach ($data['detalles'] as $detalles) {
                $stmtDetalles->execute([
                    ':id_consignacion' => $id,
                    ':id_articulo' => $detalles['id_articulo'],
                    ':cantidad' => $detalles['cantidad'],
                    ':observaciones' => $detalles['observaciones']
                ]);
            }


            // --------------------------------
            // 4. CONFIRMAR TRANSACCIÓN
            // --------------------------------

            $conn->commit();

            return [
                'resultado' => 'Pago actualizado correctamente',
                'id_pago' => $id
            ];


        } catch (PDOException $e) {

            // --------------------------------
            // 5. DESHACER TODO SI HAY ERROR
            // --------------------------------

            if ($conn->inTransaction()) {
                $conn->rollBack();
            }

            return [
                'resultado' => 'error',
                'mensaje' => $e->getMessage()
            ];
        }
    }

    static public function delete($id){
        $conn = ConnectionFerro::getConnection();

        try {

            // Iniciar transacción
            $conn->beginTransaction();

            // --------------------------------
            // 1. ELIMINAR DETALLES DE CONSIGNACIONES
            // --------------------------------

            $sqlEliminarDetalles = "
                DELETE FROM detalles_consignaciones
                WHERE id_consignacion = :id_consignacion
            ";

            $stmtEliminarDetalles = $conn->prepare(
                $sqlEliminarDetalles
            );

            $stmtEliminarDetalles->execute([
                ':id_consignacion' => $id
            ]);

            // --------------------------------
            // 2. ELIMINAR CONSIGNACIONES ANTERIORES
            // --------------------------------
            
            $sqlEliminarConsignacion = "
                DELETE FROM consignaciones
                WHERE id = :id_consignacion
            ";

            $stmtEliminarConsignacion = $conn->prepare(
                $sqlEliminarConsignacion
            );

            $stmtEliminarConsignacion->execute([
                ':id_consignacion' => $id
            ]);


            // --------------------------------
            // 4. CONFIRMAR TRANSACCIÓN
            // --------------------------------

            $conn->commit();

            return [
                'resultado' => 'Consignaciones eliminadas correctamente',
                'id_consignacion' => $id
            ];


        } catch (PDOException $e) {

            // --------------------------------
            // 5. DESHACER TODO SI HAY ERROR
            // --------------------------------

            if ($conn->inTransaction()) {
                $conn->rollBack();
            }

            return [
                'resultado' => 'error',
                'mensaje' => $e->getMessage()
            ];
        }
    }

}


?>