<?php

require_once "./models/OperatividadMaquinaria.php";
// require_once "./models/Empresa.php";

class OperatividadMaquinariaController{

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
                        $response = OperatividadMaquinaria::getAll();
                    }
                    else{
                        $response = OperatividadMaquinaria::getFiltrados($filtros);
                    }
                    
                    // $response = Empresa::getAll();
                    echo json_encode($response);
                }
                else{
                    $response = OperatividadMaquinaria::getById($id);
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
                $response = OperatividadMaquinaria::insert($data);
                echo json_encode($response);
                break;
            
            case 'PUT':
                $data = array();
                parse_str(file_get_contents('php://input'), $data);
                $response = OperatividadMaquinaria::update($id, $data);
                echo json_encode($response);
                break;

            case 'DELETE':
                $response = OperatividadMaquinaria::delete($id);
                echo json_encode($response);
                break;

        }


    }
}

?>