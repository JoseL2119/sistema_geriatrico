<?php

require_once "./models/Usuario.php";

class AuthController {

    static public function procesar($params = []) {
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        if ($requestMethod === 'GET' && ($params['action'] ?? '') === 'logout') {
            self::logout();
            return;
        }

        if ($requestMethod === 'POST') {
            self::login();
            return;
        }

        header('Location: /login');
        exit;
    }

    static private function login() {
        $data = [];
        parse_str(file_get_contents('php://input'), $data);
        if (empty($data)) {
            $data = $_POST;
        }

        $nombreUsuario = trim($data['usuario'] ?? '');
        $password = $data['password'] ?? '';

        $usuario = $nombreUsuario !== '' ? Usuario::getByNombreUsuario($nombreUsuario) : null;

        if (!$usuario || !password_verify($password, $usuario['password_hash'])) {
            header('Location: /login?error=1');
            exit;
        }

        session_regenerate_id(true);
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nombre'] = $usuario['nombre_completo'];

        header('Location: /landing');
        exit;
    }

    static private function logout() {
        $_SESSION = [];
        session_destroy();
        header('Location: /login');
        exit;
    }
}
