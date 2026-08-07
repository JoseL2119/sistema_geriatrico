<?php

require_once "./models/Residentes.php";
// require_once "./models/Empresa.php";

class ResidentesController{

    static public function procesar($params = []){
        // excavacion
        // excavacion/
        // excavacion/!


        // GET, POST, PUT, DELETE
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $id = $_GET['id'] ?? 0;  // Obtener ID si existe

        switch($requestMethod){
            
            case 'GET':
                if($id==0){

                    $filtros = $_GET;

                    if(empty($filtros)){
                        $response = Residentes::getAll();
                    }
                    else{
                        $response = Residentes::getFiltrados($params);
                    }

                    echo json_encode($response);
                }
                else{
                    $response = Residentes::getById($id);
                    // $response = Empresa::getAll();
                    echo json_encode($response);
                }
                break;
            
            case 'POST':
                $data = array();
                parse_str(file_get_contents('php://input'), $data);

                $tipoRegistro = $data['tipo_registro'] ?? null;
                unset($data['tipo_registro']);

                if($tipoRegistro == 'completo'){
                    $response = Residentes::registrarCompleto($data);
                }
                else{
                    $response = Residentes::insert($data);
                }


                echo json_encode($response);
                break;

            case 'PUT':
                $data = array();
                parse_str(file_get_contents('php://input'), $data);
                unset($data['tipo_registro']);
                $response = Residentes::update($id, $data);
                echo json_encode($response);
                break;

            case 'DELETE':
                $response = Residentes::delete($id);
                echo json_encode($response);
                break;

        }


    }
}

?>