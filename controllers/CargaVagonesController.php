<?php

require_once "./models/CargaVagones.php";
// require_once "./models/Empresa.php";

class CargaVagonesController{

    static public function procesar(){
        // cargaVagones
        // cargaVagones/
        // cargaVagones/!

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
                    $response = CargaVagones::getAll();
                    // $response = Empresa::getAll();
                    echo json_encode($response);
                }
                else{
                    $response = CargaVagones::getById($id);
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
                $response = CargaVagones::insert($data);
                echo json_encode($response);
                break;
            
            case 'PUT':
                $data = array();
                parse_str(file_get_contents('php://input'), $data);
                $response = CargaVagones::update($id, $data);
                echo json_encode($response);
                break;

            case 'DELETE':
                $response = CargaVagones::delete($id);
                echo json_encode($response);
                break;

        }


    }
}

?>