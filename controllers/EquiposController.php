<?php

require_once "./models/Equipos.php";

class EquiposController{

    static public function procesar(){
        // equipos
        // equipos/
        // equipos/!

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
                    $response = Equipos::getAll();
                    echo json_encode($response);
                }
                else{
                    $response = Equipos::getById($id);
                    echo json_encode($response);
                }
                break;
            
            case 'POST':
                $data = array(
                    'nombre' => $_POST['nombre']
                );
                $response = Equipos::insert($data);
                echo json_encode($response);
                break;
            
            case 'PUT':
                $data = array();
                parse_str(file_get_contents('php://input'), $data);
                $response = Equipos::update($id, $data);
                echo json_encode($response);
                break;

            case 'DELETE':
                $response = Equipos::delete($id);
                echo json_encode($response);
                break;

        }


    }
}

?>