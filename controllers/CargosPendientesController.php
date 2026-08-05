<?php

// require_once "./models/ExcavacionCdPiar.php";
require_once "./models/CargosPendientes.php";

class CargosPendientesController{

    static public function procesar($params = []){

        // GET, POST, PUT, DELETE
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $id = $_GET['id'] ?? 0;  // Obtener ID si existe

        switch($requestMethod){
            
            case 'GET':
                if($id==0){

                    $filtros = $_GET;

                    if(empty($filtros)){
                        $response = CargosPendientes::getAll();
                    }

                    else{
                        $response = CargosPendientes::getFiltrados($params);
                    }
                    
                    echo json_encode($response);
                }
                else{
                    // $response = ExcavacionCdPiar::getById($id);
                    $response = CargosPendientes::getById($id);
                    echo json_encode($response);
                }
                break;
            
            case 'POST':
                // $data = array(
                //     'nombre' => $_POST['tipo']
                // );
                $data = array();
                parse_str(file_get_contents('php://input'), $data);
                $response = CargosPendientes::insert($data);
                echo json_encode($response);
                break;
            
            case 'PUT':
                $data = array();
                parse_str(file_get_contents('php://input'), $data);
                $response = CargosPendientes::update($id, $data);
                echo json_encode($response);
                break;

            case 'DELETE':
                $response = CargosPendientes::delete($id);
                echo json_encode($response);
                break;

        }


    }
}

?>