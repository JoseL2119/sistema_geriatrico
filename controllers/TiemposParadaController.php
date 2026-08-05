<?php

// require_once "./models/ExcavacionCdPiar.php";
require_once "./models/TiemposParada.php";

class TiemposParadaController{

    static public function procesar($params = []){

        // GET, POST, PUT, DELETE
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $id = $_GET['id'] ?? 0;  // Obtener ID si existe

        switch($requestMethod){
            
            case 'GET':
                if($id==0){

                    $filtros = $_GET;

                    if(empty($filtros)){
                        $response = TiemposParada::getAll();
                    }

                    else{
                        $response = TiemposParada::getFiltrados($params);
                    }
                    
                    echo json_encode($response);
                }
                else{
                    // $response = ExcavacionCdPiar::getById($id);
                    $response = TiemposParada::getById($id);
                    echo json_encode($response);
                }
                break;
            
            case 'POST':
                // $data = array(
                //     'nombre' => $_POST['tipo']
                // );
                $data = array();
                parse_str(file_get_contents('php://input'), $data);
                $response = TiemposParada::insert($data);
                echo json_encode($response);
                break;
            
            case 'PUT':
                $data = array();
                parse_str(file_get_contents('php://input'), $data);
                $response = TiemposParada::update($id, $data);
                echo json_encode($response);
                break;

            case 'DELETE':
                $response = TiemposParada::delete($id);
                echo json_encode($response);
                break;

        }


    }
}

?>