<?php

require_once __DIR__ . '/../config/ConnectionFerro.php';

class Requerimientos {
    static private $tableName = 'requerimientos_consignacion';

    static public function getAll(){
        $table = self::$tableName;
        // $sql = "SELECT * FROM $table ORDER BY fecha";
        $sql = "SELECT
            r.id_residente,
            re.nombres AS nombres_residente, re.apellidos AS apellidos_residente
            FROM requerimientos_consignacion r
            INNER JOIN residentes re ON r.id_residente = re.id
            GROUP BY
                r.id_residente,
                re.nombres,
                re.apellidos
            ORDER BY r.id_residente DESC";

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
        $sql = "SELECT r.*,
            re.nombres AS nombres_residente, re.apellidos AS apellidos_residente, re.cedula AS cedula_residente, a.nombre AS nombre_articulo
            FROM requerimientos_consignacion r
            INNER JOIN articulos a ON r.id_articulo = a.id
            INNER JOIN residentes re ON r.id_residente = re.id
            WHERE r.id_residente = :id";

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
        $sql = "SELECT r.*,
            re.nombres AS nombres_residente, re.apellidos AS apellidos_residente, a.nombre AS nombre_articulo
            FROM requerimientos_consignacion r
            INNER JOIN articulos a ON r.id_articulo = a.id
            INNER JOIN residentes re ON r.id_residente = re.id
            WHERE 1=1";

        if(!empty($filtros['fecha'])){
            $sql .= " AND fecha = ?";
            $params[] = $filtros['fecha'];
        }

        $sql .= " ORDER BY r.id ASC";

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
            // 1. REGISTRAR REQUISITOS
            // --------------------------------

            $sqlRequerimiento = "
                INSERT INTO requerimientos_consignacion
                (
                    id_residente,
                    id_articulo,
                    cantidad,
                    frecuencia_meses,
                    fecha_inicio,
                    fecha_fin,
                    observaciones
                )
                VALUES
                (
                    :id_residente,
                    :id_articulo,
                    :cantidad,
                    :frecuencia_meses,
                    :fecha_inicio,
                    :fecha_fin,
                    :observaciones
                )
            ";

            $stmtRequerimiento = $conn->prepare($sqlRequerimiento);

            foreach ($data['requerimientos'] as $requerimiento) {
                $stmtRequerimiento->execute([
                    ':id_residente' => $data['id_residente'],
                    ':id_articulo' => $requerimiento['id_articulo'],
                    ':cantidad' => $requerimiento['cantidad'],
                    ':frecuencia_meses' => $requerimiento['frecuencia_meses'],
                    ':fecha_inicio' => $requerimiento['fecha_inicio'],
                    ':fecha_fin' => $requerimiento['fecha_fin'] ? $requerimiento['fecha_fin'] : null,
                    ':observaciones' => $requerimiento['observaciones']
                ]);
            }


            // --------------------------------
            // 2. CONFIRMAR TRANSACCIÓN
            // --------------------------------

            $conn->commit();

            return [
                'resultado' => 'Registro realizado correctamente',
                'id_residente' => $data['id_residente']
            ];


        } catch (PDOException $e) {

            // --------------------------------
            // 3. DESHACER TODO SI HAY ERROR
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
            // 2. ELIMINAR REQUERIMIENTOS ANTERIORES
            // --------------------------------

            $sqlEliminarRequerimientos = "
                DELETE FROM requerimientos_consignacion
                WHERE id_residente = :id_residente
            ";

            $stmtEliminarRequerimientos = $conn->prepare(
                $sqlEliminarRequerimientos
            );

            $stmtEliminarRequerimientos->execute([
                ':id_residente' => $id
            ]);


            // --------------------------------
            // 3. INSERTAR NUEVOS REQUERIMIENTOS
            // --------------------------------

            $sqlRequerimiento = "
                INSERT INTO requerimientos_consignacion
                (
                    id_residente,
                    id_articulo,
                    cantidad,
                    frecuencia_meses,
                    fecha_inicio,
                    fecha_fin,
                    observaciones
                )
                VALUES
                (
                    :id_residente,
                    :id_articulo,
                    :cantidad,
                    :frecuencia_meses,
                    :fecha_inicio,
                    :fecha_fin,
                    :observaciones
                )
            ";

            $stmtRequerimiento = $conn->prepare($sqlRequerimiento);

            foreach ($data['requerimientos'] as $requerimiento) {
                $stmtRequerimiento->execute([
                    ':id_residente' => $data['id_residente'],
                    ':id_articulo' => $requerimiento['id_articulo'],
                    ':cantidad' => $requerimiento['cantidad'],
                    ':frecuencia_meses' => $requerimiento['frecuencia_meses'],
                    ':fecha_inicio' => $requerimiento['fecha_inicio'],
                    ':fecha_fin' => $requerimiento['fecha_fin'] ? $requerimiento['fecha_fin'] : null,
                    ':observaciones' => $requerimiento['observaciones']
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
        $table = self::$tableName;

        $response = Requerimientos::getById( $id );
        if(empty($response)){
            return null;
        }

        $sql = "DELETE FROM $table WHERE id_residente = :id";

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