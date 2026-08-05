<?php

// require_once "./models/ExcavacionCdPiar.php";
require_once "./models/EmpresaEx.php";

class EmpresaExController{

    static public function procesar(){
        // empresaEx
        // empresaEx/
        // empresaEx/!

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
                    $response = EmpresaEx::getAll();
                    echo json_encode($response);
                }
                else{
                    // $response = ExcavacionCdPiar::getById($id);
                    $response = EmpresaEx::getById($id);
                    echo json_encode($response);
                }
                break;
            
            case 'POST':
                $data = array(
                    'nombre' => $_POST['nombre']
                );
                $response = EmpresaEx::insert($data);
                echo json_encode($response);
                break;
            
            case 'PUT':
                $data = array();
                parse_str(file_get_contents('php://input'), $data);
                $response = EmpresaEx::update($id, $data);
                echo json_encode($response);
                break;

            case 'DELETE':
                $response = EmpresaEx::delete($id);
                echo json_encode($response);
                break;

        }


    }
}

?>