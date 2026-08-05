<?php

require_once "./models/ReportesSalaControl.php";
// require_once "./models/Empresa.php";

class ReportesSalaControlController{

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
                        $response = ReportesSalaControl::getAll();
                    }
                    else{
                        $response = ReportesSalaControl::getFiltrados($params);
                    }

                    echo json_encode($response);
                }
                else{
                    $response = ReportesSalaControl::getById($id);
                    // $response = Empresa::getAll();
                    echo json_encode($response);
                }
                break;
            
            case 'POST':
                // $data = array(
                //     'id_tipo_material' => $_POST['material'],
                //     'cantidad' => $_POST['cantidad'],
                //     'id_mina' => $_POST['mina'],
                //     'id_empresa' => $_POST['empresa'],
                //     'fecha' => $_POST['fecha'],
                //     'comentario' => $_POST['comentario']
                // );
                $data = array();
                parse_str(file_get_contents('php://input'), $data);
                $response = ReportesSalaControl::insert($data);
                echo json_encode($response);
                break;
            
            case 'PUT':
                $data = array();
                parse_str(file_get_contents('php://input'), $data);
                $response = ReportesSalaControl::update($id, $data);
                echo json_encode($response);
                break;

            case 'DELETE':
                $response = ReportesSalaControl::delete($id);
                echo json_encode($response);
                break;

        }


    }
}

?>