<?php

require_once "./models/Incidencias.php";

class IncidenciasController {

    static public function procesar($params = []) {

        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $id = $_GET['id'] ?? 0;  // Obtener ID si existe

        switch ($requestMethod) {
            case 'GET':
                if ($id == 0) {

                    $filtros = $_GET;

                    if(empty($filtros)){
                        $response = Incidencias::getAll();
                    }
                    
                    else{
                        $response = Incidencias::getFiltrados($params);
                    }

                    echo json_encode($response);

                    
                } else {
                    $response = Incidencias::getById($id);

                    echo json_encode($response);
                }
                
                break;

            case 'POST':
                $data = json_decode(file_get_contents('php://input'), true);
                $response = Incidencias::insert($data);
                echo json_encode($response);
                break;

            case 'PUT':
                $data = json_decode(file_get_contents('php://input'), true);
                $response = Incidencias::update($id, $data);
                echo json_encode($response);
                break;

            case 'DELETE':
                $response = Incidencias::delete($id);
                echo json_encode($response);
                break;
        }
    }
}