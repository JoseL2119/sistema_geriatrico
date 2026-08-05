<?php

// require_once "./models/ExcavacionCdPiar.php";
require_once "./models/Material.php";

class MaterialController{

    static public function procesar(){
        // material
        // material/
        // material/!

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
                    $response = Material::getAll();
                    echo json_encode($response);
                }
                else{
                    // $response = ExcavacionCdPiar::getById($id);
                    $response = Material::getById($id);
                    echo json_encode($response);
                }
                break;
            
            case 'POST':
                $data = array(
                    'tipo' => $_POST['nombre']
                );
                $response = Material::insert($data);
                echo json_encode($response);
                break;
            
            case 'PUT':
                $data = array();
                parse_str(file_get_contents('php://input'), $data);
                $response = Material::update($id, $data);
                echo json_encode($response);
                break;

            case 'DELETE':
                $response = Material::delete($id);
                echo json_encode($response);
                break;

        }


    }
}

?>