<?php

// require_once "./models/ExcavacionCdPiar.php";
require_once "./models/PlantasSiderurgicas.php";

class PlantasSiderurgicasController{

    static public function procesar(){
        // plantaSiderurgicas
        // plantaSiderurgicas/
        // plantaSiderurgicas/!

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
                    $response = PlantasSiderurgicas::getAll();
                    echo json_encode($response);
                }
                else{
                    // $response = ExcavacionCdPiar::getById($id);
                    $response = PlantasSiderurgicas::getById($id);
                    echo json_encode($response);
                }
                break;
            
            case 'POST':
                // $data = array(
                //     'nombre' => $_POST['nombre']
                // );
                $data = array();
                parse_str(file_get_contents('php://input'), $data);
                $response = PlantasSiderurgicas::insert($data);
                echo json_encode($response);
                break;
            
            case 'PUT':
                $data = array();
                parse_str(file_get_contents('php://input'), $data);
                $response = PlantasSiderurgicas::update($id, $data);
                echo json_encode($response);
                break;

            case 'DELETE':
                $response = PlantasSiderurgicas::delete($id);
                echo json_encode($response);
                break;

        }


    }
}

?>