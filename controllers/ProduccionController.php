<?php

require_once "./models/Produccion.php";

class ProduccionController {

    static public function procesar($params = []) {

        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $id = $_GET['id'] ?? 0;  // Obtener ID si existe

        switch ($requestMethod) {
            case 'GET':
                if ($id == 0) {

                    $filtros = $_GET;

                    if(empty($filtros)){
                        $response = Produccion::getAll();
                    }

                    else{
                        $response = Produccion::getFiltrados($params);
                    }
                    
                    echo json_encode($response);
                    
                } else {
                    $response = Produccion::getById($id);

                    echo json_encode($response);
                }
                
                break;

            case 'POST':
                $data = json_decode(file_get_contents('php://input'), true);
                $response = Produccion::insert($data);
                echo json_encode($response);
                break;

            case 'PUT':
                $data = json_decode(file_get_contents('php://input'), true);
                $response = Produccion::update($id, $data);
                echo json_encode($response);
                break;

            case 'DELETE':
                $response = Produccion::delete($id);
                echo json_encode($response);
                break;
        }
    }
}