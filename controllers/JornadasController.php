<?php

require_once "./models/Jornadas.php";

class JornadasController{

    static public function procesar(){
        // jornadas
        // jornadas/
        // jornadas/!

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
                    $response = Jornadas::getAll();
                    echo json_encode($response);
                }
                else{
                    $response = Jornadas::getById($id);
                    echo json_encode($response);
                }
                break;
            
            case 'POST':
                $data = array(
                    'jornada' => $_POST['jornada'],
                    'fecha' => $_POST['fecha']
                );
                $response = Jornadas::insert($data);
                echo json_encode($response);
                break;
            
            case 'PUT':
                $data = array();
                parse_str(file_get_contents('php://input'), $data);
                $response = Jornadas::update($id, $data);
                echo json_encode($response);
                break;

            case 'DELETE':
                $response = Jornadas::delete($id);
                echo json_encode($response);
                break;

        }


    }
}

?>