<?php

require_once __DIR__ . '/../config/ConnectionFerro.php';

class Incidencias {
    static private $tableName = 'reporte_incidencias';

    static public function getAll() {
        $table = self::$tableName;
        //$sql = "SELECT * FROM $table ORDER BY fecha DESC, id DESC";
        $sql = "SELECT e.*,
            ma.nombre AS maquina, su.nombre AS supervisor, op.nombre AS operador, tu.turno AS turno
            FROM reporte_incidencias e
            INNER JOIN maquinaria ma ON e.payloader = ma.id
            INNER JOIN supervisores su ON e.id_supervisor = su.id
            INNER JOIN operadores op ON e.id_operador = op.id
            INNER JOIN turno tu ON e.id_turno = tu.id
            ORDER BY e.fecha DESC";

        $stmt = ConnectionFerro::getConnection()->prepare($sql);

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
            ma.id AS id_maquina, ma.nombre AS maquina, 
            su.id AS id_supervisor, su.nombre AS supervisor,
            op.id AS id_operador, op.nombre AS operador,
            tu.id AS id_turno, tu.turno AS turno
            FROM reporte_incidencias e
            INNER JOIN maquinaria ma ON e.payloader = ma.id
            INNER JOIN supervisores su ON e.id_supervisor = su.id
            INNER JOIN operadores op ON e.id_operador = op.id
            INNER JOIN turno tu ON e.id_turno = tu.id
            WHERE e.id = :id";
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
            ma.nombre AS maquina, su.nombre AS supervisor, op.nombre AS operador, tu.turno AS turno
            FROM reporte_incidencias e
            INNER JOIN maquinaria ma ON e.payloader = ma.id
            INNER JOIN supervisores su ON e.id_supervisor = su.id
            INNER JOIN operadores op ON e.id_operador = op.id
            INNER JOIN turno tu ON e.id_turno = tu.id
            WHERE 1=1";

        if(!empty($filtros['fecha'])){
            $sql .= " AND fecha = ?";
            $params[] = $filtros['fecha'];
        }

        if(!empty($filtros['id_turno'])){
            $sql .= " AND id_turno = ?";
            $params[] = $filtros['id_turno'];
        }

        $stmt = ConnectionFerro::getConnection()->prepare($sql);

        try {
            $stmt->execute($params);
        } catch (PDOException $e) {
            return null;
        }

        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    static public function insert($data) {
        $table = self::$tableName;
        $columns = implode(',', array_keys($data));
        $values = ':' . implode(',:', array_keys($data));

        $sql = "INSERT INTO $table ($columns) VALUES ($values)";

        $conn = ConnectionFerro::getConnection();
        $stmt = $conn->prepare($sql);

        foreach ($data as $key => $value) {
            if ($key === 'observaciones') {
                // Convertir a JSON si es un array
                $value = json_encode($value);
            }
            $stmt->bindValue(":$key", $value);
        }

        try {
            $stmt->execute();
            return [
                "id" => $conn->lastInsertId(),
                "resultado" => "Registro grabado"
            ];
        } catch (PDOException $e) {
            return ["error" => $e->getMessage()];
        }
    }

    static public function update($id, $data) {
        $table = self::$tableName;
        $setPart = [];
        foreach ($data as $key => $value) {
            if ($key === 'observaciones') {
                $value = json_encode($value);
            }
            $setPart[] = "$key = :$key";
        }
        $setPart = implode(',', $setPart);
        $sql = "UPDATE $table SET $setPart WHERE id = :id";

        $conn = ConnectionFerro::getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $id);

        foreach ($data as $key => $value) {
            if ($key === 'observaciones') {
                $value = json_encode($value);
            }
            $stmt->bindValue(":$key", $value);
        }

        try {
            $stmt->execute();
            return ["resultado" => "Registro actualizado"];
        } catch (PDOException $e) {
            return ["error" => $e->getMessage()];
        }
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