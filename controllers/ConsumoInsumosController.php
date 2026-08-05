<?php

require_once "./models/ConsumoInsumos.php";

class ConsumoInsumosController {

    static public function procesar($params = []) {

        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $id = $_GET['id'] ?? 0;  // Obtener ID si existe

        switch ($requestMethod) {
            case 'GET':
                if ($id == 0) {

                    $filtros = $_GET;

                    if(empty($filtros)){
                        $response = ConsumoInsumos::getAll();
                    }

                    else{
                        $response = ConsumoInsumos::getFiltrados($params);
                    }
                    
                    echo json_encode($response);
                    
                } else {
                    $response = ConsumoInsumos::getById($id);

                    echo json_encode($response);
                }
                
                break;

            case 'POST':
                $rawData = file_get_contents('php://input');
                $decodedData = json_decode($rawData, true);

                // Validar estructura básica
                if (!isset($decodedData['dataReporte']) || !isset($decodedData['detallesInsumos'])) {
                    http_response_code(400); // Bad Request
                    echo json_encode(["error" => "Estructura de datos inválida. Se espera 'dataReporte' y 'detallesInsumos'"]);
                    break;
                }

                // Validar que haya al menos un insumo
                if (count($decodedData['detallesInsumos']) === 0) {
                    http_response_code(400);
                    echo json_encode(["error" => "El reporte debe incluir al menos un insumo"]);
                    break;
                }

                // Llamar al método con transacción
                $response = ConsumoInsumos::insert(
                    $decodedData['dataReporte'],
                    $decodedData['detallesInsumos']
                );

                // Manejar errores
                if (isset($response['error'])) {
                    http_response_code(500); // Internal Server Error
                }

                echo json_encode($response);
                break;

            case 'PUT':
                $data = json_decode(file_get_contents('php://input'), true);
                $response = ConsumoInsumos::update($id, $data);
                echo json_encode($response);
                break;

            case 'DELETE':
                $response = ConsumoInsumos::delete($id);
                echo json_encode($response);
                break;
        }
    }
}