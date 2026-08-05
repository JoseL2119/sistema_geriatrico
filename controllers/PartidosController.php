<?php

require_once "./models/Partidos.php";

class PartidosController{

    static public function procesar(){
        // partidos
        // partidos/
        // partidos/!

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
                    $response = Partidos::getAll();
                    echo json_encode($response);
                }
                else{
                    $response = Partidos::getById($id);
                    echo json_encode($response);
                }
                break;
            
            case 'POST':
                $data = array(
                    'jornada' => intval($_POST['jornada']),
                    'fecha' => $_POST['fecha'],
                    'orden' => intval($_POST['orden']),
                    'idlocal' => intval($_POST['idlocal']),
                    'idvisitante' => intval($_POST['idvisitante']),
                    'goleslocal' => intval($_POST['goleslocal']),
                    'golesvisitante' => intval($_POST['golesvisitante'])
                );
                $response = Partidos::insert($data);
                echo json_encode($response);
                break;
            
            case 'PUT':
                $data = array();
                parse_str(file_get_contents('php://input'), $data);
                $response = Partidos::update($id, $data);
                echo json_encode($response);
                break;

            case 'DELETE':
                $response = Partidos::delete($id);
                echo json_encode($response);
                break;

        }


    }
}

?>