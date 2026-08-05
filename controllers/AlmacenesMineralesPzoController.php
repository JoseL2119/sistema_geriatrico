<?php

// require_once "./models/ExcavacionCdPiar.php";
require_once "./models/AlmacenesMineralesPzo.php";

class AlmacenesMineralesPzoController{

    static public function procesar(){
        // almacenesMineralesPzo
        // almacenesMineralesPzo/
        // almacenesMineralesPzo/!

        // Devuelve un array de elementos separándolos por "/"
        $requestURI = explode("/", $_SERVER['REQUEST_URI']);
        // Elimina los valores vacios
        $requestURI = array_filter($requestURI);

        if(count($requestURI) == 1){
            $id = 0;
        }
        else{
            $id = $requestURI[2] ?? 0;
        }

        // GET, POST, PUT, DELETE
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        switch($requestMethod){
            
            case 'GET':
                if($id==0){
                    // $response = ExcavacionCdPiar::getAll();
                    $response = AlmacenesMineralesPzo::getAll();
                    echo json_encode($response);
                }
                else{
                    // $response = ExcavacionCdPiar::getById($id);
                    $response = AlmacenesMineralesPzo::getById($id);
                    echo json_encode($response);
                }
                break;
            
            case 'POST':
                // $data = array(
                //     'nombre' => $_POST['tipo']
                // );
                $data = array();
                parse_str(file_get_contents('php://input'), $data);
                $response = AlmacenesMineralesPzo::insert($data);
                echo json_encode($response);
                break;
            
            case 'PUT':
                $data = array();
                parse_str(file_get_contents('php://input'), $data);
                $response = AlmacenesMineralesPzo::update($id, $data);
                echo json_encode($response);
                break;

            case 'DELETE':
                $response = AlmacenesMineralesPzo::delete($id);
                echo json_encode($response);
                break;

        }


    }
}

?>