<?php

require_once __DIR__ . '/../config/ConnectionFerro.php';

class Pagos {
    static private $tableName = 'pagos';

    static public function getAll(){
        $table = self::$tableName;
        // $sql = "SELECT * FROM $table ORDER BY fecha";
        $sql = "SELECT p.*,
            r.nombres AS nombres_residente, r.apellidos AS apellidos_residente, rp.nombres AS nombres_representante, rp.apellidos AS apellidos_representante, m.nombre AS metodo_pago
            FROM pagos p
            INNER JOIN representantes rp ON p.id_representante = rp.id
            INNER JOIN residentes r ON p.id_residente = r.id
            INNER JOIN metodo_pago m ON p.metodo_pago = m.id
            ORDER BY p.fecha DESC";

        $stmt = ConnectionFerro::getConnection()->prepare($sql);

        try{
            $stmt->execute();
        } catch(PDOException $e) {
            return $e->getMessage();
        }

        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    static public function getById($id){
        $conn = ConnectionFerro::getConnection();

        try {

            // --------------------------------
            // 1. OBTENER PAGO
            // --------------------------------

            $sqlPago = "
                SELECT
                    id,
                    fecha,
                    monto,
                    metodo_pago,
                    id_residente,
                    id_representante,
                    referencia,
                    observaciones
                FROM pagos
                WHERE id = :id
            ";

            $stmtPago = $conn->prepare($sqlPago);

            $stmtPago->execute([
                ':id' => $id
            ]);

            $pago = $stmtPago->fetch(PDO::FETCH_ASSOC);


            // --------------------------------
            // 2. OBTENER APLICACIONES
            // --------------------------------

            $sqlAplicaciones = "
                SELECT
                    id_cargo,
                    monto_aplicado
                FROM pago_aplicacion
                WHERE id_pago = :id_pago
                ORDER BY id_cargo ASC
            ";

            $stmtAplicaciones = $conn->prepare($sqlAplicaciones);

            $stmtAplicaciones->execute([
                ':id_pago' => $id
            ]);

            $aplicaciones = $stmtAplicaciones->fetchAll(PDO::FETCH_ASSOC);


            // --------------------------------
            // 3. DEVOLVER INFORMACIÓN
            // --------------------------------

            return [
                'pago' => $pago,
                'aplicaciones' => $aplicaciones
            ];


        } catch (PDOException $e) {

            return [
                'resultado' => 'error',
                'mensaje' => $e->getMessage()
            ];

        }
    }

    static public function getFiltrados($filtros){
        $table = self::$tableName;
        $params = [];
        // $sql = "SELECT * FROM $table WHERE id = :id";
        $sql = "SELECT p.*,
            r.nombres AS nombres_residente, r.apellidos AS apellidos_residente, rp.nombres AS nombres_representante, rp.apellidos AS apellidos_representante, m.nombre AS metodo_pago
            FROM pagos p
            INNER JOIN representantes rp ON p.id_representante = rp.id
            INNER JOIN residentes r ON p.id_residente = r.id
            INNER JOIN metodo_pago m ON p.metodo_pago = m.id
            WHERE 1=1";

        if(!empty($filtros['fecha_inicio_filtro'])){
            $sql .= " AND fecha >= ?";
            $params[] = $filtros['fecha_inicio_filtro'];
        }
        if(!empty($filtros['fecha_fin_filtro'])){
            $sql .= " AND fecha <= ?";
            $params[] = $filtros['fecha_fin_filtro'];
        }

        $sql .= " ORDER BY p.fecha DESC";

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
            // 1. REGISTRAR PAGO
            // --------------------------------

            $sqlPago = "
                INSERT INTO pagos
                (
                    fecha,
                    monto,
                    metodo_pago,
                    id_residente,
                    id_representante,
                    referencia,
                    observaciones
                )
                VALUES
                (
                    :fecha,
                    :monto,
                    :metodo_pago,
                    :id_residente,
                    :id_representante,
                    :referencia,
                    :observaciones
                )
                RETURNING id
            ";

            $stmtPago = $conn->prepare($sqlPago);

            $stmtPago->execute([
                ':fecha' => $data['fecha'],
                ':monto' => $data['monto'],
                ':metodo_pago' => $data['metodo_pago'],
                ':id_residente' => $data['id_residente'],
                ':id_representante' => $data['id_representante'],
                ':referencia' => $data['referencia'],
                ':observaciones' => $data['observaciones']
            ]);

            // Recuperar ID generado
            $idPago = $stmtPago->fetchColumn();


            // --------------------------------
            // 2. REGISTRAR RESIDENTE
            // --------------------------------

            $sqlAplicacion = "
                INSERT INTO pago_aplicacion
                (
                    id_pago,
                    id_cargo,
                    monto_aplicado
                )
                VALUES
                (
                    :id_pago,
                    :id_cargo,
                    :monto_aplicado
                )
            ";

            $stmtAplicacion = $conn->prepare($sqlAplicacion);

            foreach ($data['aplicaciones'] as $aplicacion) {
                $stmtAplicacion->execute([
                    ':id_pago' => $idPago,
                    ':id_cargo' => $aplicacion['id_cargo'],
                    ':monto_aplicado' => $aplicacion['monto_aplicado']
                ]);
            }


            // --------------------------------
            // 3. CONFIRMAR TRANSACCIÓN
            // --------------------------------

            $conn->commit();

            return [
                'resultado' => 'Registro realizado correctamente',
                'id_pago' => $idPago
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
            // 1. ACTUALIZAR PAGO
            // --------------------------------

            $sqlPago = "
                UPDATE pagos
                SET
                    fecha = :fecha,
                    monto = :monto,
                    metodo_pago = :metodo_pago,
                    id_residente = :id_residente,
                    id_representante = :id_representante,
                    referencia = :referencia,
                    observaciones = :observaciones
                WHERE id = :id
            ";

            $stmtPago = $conn->prepare($sqlPago);

            $stmtPago->execute([
                ':fecha' => $data['fecha'],
                ':monto' => $data['monto'],
                ':metodo_pago' => $data['metodo_pago'],
                ':id_residente' => $data['id_residente'],
                ':id_representante' => $data['id_representante'],
                ':referencia' => $data['referencia'],
                ':observaciones' => $data['observaciones'],
                ':id' => $id
            ]);


            // --------------------------------
            // 2. ELIMINAR APLICACIONES ANTERIORES
            // --------------------------------

            $sqlEliminarAplicaciones = "
                DELETE FROM pago_aplicacion
                WHERE id_pago = :id_pago
            ";

            $stmtEliminarAplicaciones = $conn->prepare(
                $sqlEliminarAplicaciones
            );

            $stmtEliminarAplicaciones->execute([
                ':id_pago' => $id
            ]);


            // --------------------------------
            // 3. INSERTAR NUEVAS APLICACIONES
            // --------------------------------

            $sqlAplicacion = "
                INSERT INTO pago_aplicacion
                (
                    id_pago,
                    id_cargo,
                    monto_aplicado
                )
                VALUES
                (
                    :id_pago,
                    :id_cargo,
                    :monto_aplicado
                )
            ";

            $stmtAplicacion = $conn->prepare($sqlAplicacion);


            // Recorrer todas las aplicaciones
            foreach ($data['aplicaciones'] as $aplicacion) {

                $stmtAplicacion->execute([
                    ':id_pago' => $id,
                    ':id_cargo' => $aplicacion['id_cargo'],
                    ':monto_aplicado' => $aplicacion['monto_aplicado']
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

            $conn->beginTransaction();

            // 1. Eliminar aplicaciones
            $sqlAplicaciones = "
                DELETE FROM pago_aplicacion
                WHERE id_pago = :id_pago
            ";

            $stmtAplicaciones = $conn->prepare($sqlAplicaciones);

            $stmtAplicaciones->execute([
                ':id_pago' => $id
            ]);


            // 2. Eliminar pago
            $sqlPago = "
                DELETE FROM pagos
                WHERE id = :id
            ";

            $stmtPago = $conn->prepare($sqlPago);

            $stmtPago->execute([
                ':id' => $id
            ]);


            // 3. Confirmar
            $conn->commit();

            return [
                'resultado' => 'Pago eliminado correctamente'
            ];

        } catch (PDOException $e) {

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