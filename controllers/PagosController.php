<?php

// require_once "./models/ExcavacionCdPiar.php";
require_once "./models/Pagos.php";

class PagosController{

    static public function procesar($params = []){
        // productos
        // productos/
        // productos/!

        // Devuelve un array de elementos separándolos por "/"
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $id = $_GET['id'] ?? 0;  // Obtener ID si existe

        switch($requestMethod){
            
            case 'GET':
                if($id==0){

                    $filtros = $_GET;
                    // $response = ExcavacionCdPiar::getAll();
                    if(empty($filtros)){
                        $response = Pagos::getAll();
                    }
                    else{
                        $response = Pagos::getFiltrados($params);
                    }
                    echo json_encode($response);
                }
                else{
                    // $response = ExcavacionCdPiar::getById($id);
                    $response = Pagos::getById($id);
                    echo json_encode($response);
                }
                break;
            
            case 'POST':
                // $data = array(
                //     'nombre' => $_POST['tipo']
                // );
                $data = array();
                parse_str(file_get_contents('php://input'), $data);
                $response = Pagos::insert($data);
                echo json_encode($response);
                break;
            
            case 'PUT':
                $data = array();
                parse_str(file_get_contents('php://input'), $data);
                $response = Pagos::update($id, $data);
                echo json_encode($response);
                break;

            case 'DELETE':
                $response = Pagos::delete($id);
                echo json_encode($response);
                break;

        }


    }
}

?>