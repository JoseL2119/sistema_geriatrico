<?php

require_once __DIR__ . '/../config/ConnectionFerro.php';

class Usuario {
    static private $tableName = 'usuarios';

    static public function getByNombreUsuario($nombreUsuario) {
        $table = self::$tableName;
        $sql = "SELECT * FROM $table WHERE nombre_usuario = :nombre_usuario";

        $stmt = ConnectionFerro::getConnection()->prepare($sql);
        $stmt->bindParam(":nombre_usuario", $nombreUsuario, PDO::PARAM_STR);

        try {
            $stmt->execute();
        } catch (PDOException $e) {
            return null;
        }

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        return $usuario ?: null;
    }
}
