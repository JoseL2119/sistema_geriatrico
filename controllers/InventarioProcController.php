<?php

require_once "./models/InventarioProc.php";
// require_once "./models/Empresa.php";

class InventarioProcController{

    static public function procesar(){
        // inventarioProc
        // inventarioProc/
        // inventarioProc/!

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
                    $response = InventarioProc::getAll();
                    // $response = Empresa::getAll();
                    echo json_encode($response);
                }
                else{
                    $response = InventarioProc::getById($id);
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
                $response = InventarioProc::insert($data);
                echo json_encode($response);
                break;
            
            case 'PUT':
                $data = array();
                parse_str(file_get_contents('php://input'), $data);
                $response = InventarioProc::update($id, $data);
                echo json_encode($response);
                break;

            case 'DELETE':
                $response = InventarioProc::delete($id);
                echo json_encode($response);
                break;

        }


    }
}

?>