<?php

require_once __DIR__ . '/../config/ConnectionFerro.php';

class Residentes {
    static private $tableName = 'residentes';

    static public function getAll(){
        $table = self::$tableName;
        // $sql = "SELECT * FROM $table ORDER BY fecha";
        $sql = "SELECT r.*,
            re.nombres AS representante, re.telefono AS telefono, mo.condicion AS c_movilidad, c.nombre AS centro, m.nombre AS medico, p.parentesco AS parentesco, s.status AS status_r
            FROM residentes r
            INNER JOIN representantes re ON r.id_representante = re.id
            INNER JOIN movilidad mo ON r.condicion_mov = mo.id
            INNER JOIN centro_medico c ON r.centro_medico_e = c.id
            INNER JOIN medico_tratante m ON r.medico_tratante = m.id
            INNER JOIN parentesco p ON r.parentesco = p.id
            INNER JOIN status_residente s ON r.status = s.id
            ORDER BY r.cedula ASC";

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
            re.id AS id_representante, re.nombres AS representante, mo.id AS id_movilidad, mo.condicion AS c_movilidad, c.id AS id_centro, c.nombre AS centro, m.id AS id_medico, m.nombre AS medico, p.id AS id_parentesco, p.parentesco AS parentesco, s.id AS id_status, s.status AS status_r
            FROM residentes r
            INNER JOIN representantes re ON r.id_representante = re.id
            INNER JOIN movilidad mo ON r.condicion_mov = mo.id
            INNER JOIN centro_medico c ON r.centro_medico_e = c.id
            INNER JOIN medico_tratante m ON r.medico_tratante = m.id
            INNER JOIN parentesco p ON r.parentesco = p.id
            INNER JOIN status_residente s ON r.status = s.id
            WHERE r.id = :id";

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
            re.nombres AS representante, mo.condicion AS c_movilidad, c.nombre AS centro, m.nombre AS medico, p.parentesco AS parentesco, s.status AS status_r
            FROM residentes r
            INNER JOIN representantes re ON r.id_representante = re.id
            INNER JOIN movilidad mo ON r.condicion_mov = mo.id
            INNER JOIN centro_medico c ON r.centro_medico_e = c.id
            INNER JOIN medico_tratante m ON r.medico_tratante = m.id
            INNER JOIN parentesco p ON r.parentesco = p.id
            INNER JOIN status_residente s ON r.status = s.id
            WHERE 1=1";

        if(!empty($filtros['fecha'])){
            $sql .= " AND fecha = ?";
            $params[] = $filtros['fecha'];
        }

        $sql .= " ORDER BY r.cedula ASC";

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

    static public function registrarCompleto($data){

        $conn = ConnectionFerro::getConnection();

        try {

            // Iniciar transacción
            $conn->beginTransaction();

            // --------------------------------
            // 1. REGISTRAR REPRESENTANTE
            // --------------------------------

            $sqlRepresentante = "
                INSERT INTO representantes
                (
                    cedula,
                    nombres,
                    apellidos,
                    telefono,
                    fecha_nacimiento,
                    domicilio,
                    fecha_pago,
                    familiar_alternativo,
                    telefono_familiar_alt,
                    observaciones
                )
                VALUES
                (
                    :cedula,
                    :nombres,
                    :apellidos,
                    :telefono,
                    :fecha_nacimiento,
                    :domicilio,
                    :fecha_pago,
                    :familiar_alternativo,
                    :telefono_familiar_alt,
                    :observaciones
                )
                RETURNING id
            ";

            $stmtRepresentante = $conn->prepare($sqlRepresentante);

            $stmtRepresentante->execute([
                ':cedula' => $data['cedula_representante'],
                ':nombres' => $data['nombres_representante'],
                ':apellidos' => $data['apellidos_representante'],
                ':telefono' => $data['telefono_representante'],
                ':fecha_nacimiento' => $data['fecha_nacimiento_representante'],
                ':domicilio' => $data['domicilio_representante'],
                ':fecha_pago' => $data['fecha_pago_representante'],
                ':familiar_alternativo' => $data['familiar_alternativo'],
                ':telefono_familiar_alt' => $data['telefono_familiar_alt'],
                ':observaciones' => $data['observaciones_representante'],
            ]);

            // Recuperar ID generado
            $idRepresentante = $stmtRepresentante->fetchColumn();


            // --------------------------------
            // 2. REGISTRAR RESIDENTE
            // --------------------------------

            $sqlResidente = "
                INSERT INTO residentes
                (
                    cedula,
                    nombres,
                    apellidos,
                    fecha_nacimiento,
                    fecha_ingreso,
                    fecha_egreso,
                    peso,
                    altura,
                    status,
                    convenio_ivss,
                    pensionado,
                    caso_privado,
                    contencion_f,
                    vulnerabilidad_f,
                    apadrinazgo,
                    psiquiatrico,
                    diagnostico,
                    condicion_mov,
                    control_esfinteres,
                    centro_medico_e,
                    medico_tratante,
                    observaciones,
                    id_representante,
                    parentesco
                )
                VALUES
                (
                    :cedula,
                    :nombres,
                    :apellidos,
                    :fecha_nacimiento,
                    :fecha_ingreso,
                    :fecha_egreso,
                    :peso,
                    :altura,
                    :status,
                    :convenio_ivss,
                    :pensionado,
                    :caso_privado,
                    :contencion_f,
                    :vulnerabilidad_f,
                    :apadrinazgo,
                    :psiquiatrico,
                    :diagnostico,
                    :condicion_mov,
                    :control_esfinteres,
                    :centro_medico_e,
                    :medico_tratante,
                    :observaciones,
                    :id_representante,
                    :parentesco
                )
            ";

            $stmtResidente = $conn->prepare($sqlResidente);

            $stmtResidente->execute([
                ':cedula' => $data['cedula'],
                ':nombres' => $data['nombres'],
                ':apellidos' => $data['apellidos'],
                ':fecha_nacimiento' => $data['fecha_nacimiento'],
                ':fecha_ingreso' => $data['fecha_ingreso'],
                ':fecha_egreso' => $data['fecha_egreso'] == '' ? null : $data['fecha_egreso'],
                ':peso' => $data['peso'],
                ':altura' => $data['altura'],
                ':status' => $data['status'],
                ':convenio_ivss' => $data['convenio_ivss'],
                ':pensionado' => $data['pensionado'],
                ':caso_privado' => $data['caso_privado'],
                ':contencion_f' => $data['contencion_f'],
                ':vulnerabilidad_f' => $data['vulnerabilidad_f'],
                ':apadrinazgo' => $data['apadrinazgo'],
                ':psiquiatrico' => $data['psiquiatrico'],
                ':diagnostico' => $data['diagnostico'],
                ':condicion_mov' => $data['condicion_mov'],
                ':control_esfinteres' => $data['control_esfinteres'],
                ':centro_medico_e' => $data['centro_medico_e'],
                ':medico_tratante' => $data['medico_tratante'],
                ':observaciones' => $data['observaciones'],
                ':id_representante' => $idRepresentante,
                ':parentesco' => $data['parentesco']
            ]);


            // --------------------------------
            // 3. CONFIRMAR TRANSACCIÓN
            // --------------------------------

            $conn->commit();

            return [
                'resultado' => 'Registro realizado correctamente',
                'id_representante' => $idRepresentante
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
        $table = self::$tableName;

        //Comprobar que el ID existe
        $response = Residentes::getById($id);
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

        $response = Residentes::getById( $id );
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