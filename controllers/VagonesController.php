<?php

// require_once "./models/ExcavacionCdPiar.php";
require_once "./models/Vagones.php";

class VagonesController{

    static public function procesar(){
        // vagones
        // vagones/
        // vagones/!

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
                    $response = Vagones::getAll();
                    echo json_encode($response);
                }
                else{
                    // $response = ExcavacionCdPiar::getById($id);
                    $response = Vagones::getById($id);
                    echo json_encode($response);
                }
                break;
            
            case 'POST':
                $data = array(
                    'tipo' => $_POST['tipo'],
                    'capacidad' => intval($_POST['capacidad'])
                );
                $response = Vagones::insert($data);
                echo json_encode($response);
                break;
            
            case 'PUT':
                $data = array();
                parse_str(file_get_contents('php://input'), $data);
                $response = Vagones::update($id, $data);
                echo json_encode($response);
                break;

            case 'DELETE':
                $response = Vagones::delete($id);
                echo json_encode($response);
                break;

        }


    }
}

?>