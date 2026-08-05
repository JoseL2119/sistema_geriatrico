<?php

session_start();

$routes = require __DIR__.'/routes/routes.php';

// Obtener la URI sin query parameters
$requestURI = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestURI = trim($requestURI, '/');

// Obtener los query parameters
$queryParams = [];
parse_str($_SERVER['QUERY_STRING'] ?? '', $queryParams);

$uriParts = explode('/', $requestURI);
$baseURI = $uriParts[0];

// Control de acceso: toda ruta que no sea login/auth requiere sesión activa
$rutasPublicas = ['login', 'auth'];
if(!in_array($baseURI, $rutasPublicas) && empty($_SESSION['usuario_id'])){
    header('Location: /login');
    exit;
}

if(empty($requestURI)){
    $uri = 'landing';
}
else{
    $uri = $requestURI;
}

if(array_key_exists($baseURI, $routes)){
    switch($baseURI){

        case 'login':
            include "./views/LoginView.php";
            break;

        case 'auth':
            require $routes[$baseURI];
            AuthController::procesar($queryParams);
            break;

        case 'landing':
            include "./views/LandingPageView.php";
            break;

        case 'ajaxLanding':
            require $routes[$baseURI];
            
            LandingController::procesar();
            break;

        case 'reportesSalaControl':
            include './views/ReportesSalaControlView.php';
            break;

        case 'ajaxReportesSalaControl':
            require $routes[$baseURI];

            if(count($uriParts) > 1 && is_numeric($uriParts[1])){
                $_GET['id'] = $uriParts[1];
            }

            ReportesSalaControlController::procesar($queryParams);
            break;

        case 'residentes':
            include './views/ResidentesView.php';
            break;

        case 'ajaxResidentes':
            require $routes[$baseURI];

            if(count($uriParts) > 1 && is_numeric($uriParts[1])){
                $_GET['id'] = $uriParts[1];
            }

            ResidentesController::procesar($queryParams);
            break;

        case 'supervisores':
            include './views/SupervisoresView.php';
            break;

        case 'ajaxSupervisores':
            require $routes[$baseURI];
            
            SupervisoresController::procesar();
            break;

        case 'operatividadMaquinaria':
            include './views/OperatividadMaquinariaView.php';
            break;

        case 'ajaxOperatividadMaquinaria':
            require $routes[$baseURI];

            if(count($uriParts) > 1 && is_numeric($uriParts[1])){
                $_GET['id'] = $uriParts[1];
            }

            OperatividadMaquinariaController::procesar($queryParams);
            break;

        case 'maquina':
            include './views/MaquinaView.php';
            break;

        case 'ajaxMaquina':
            require $routes[$baseURI];
            MaquinaController::procesar();
            break;

        case 'status':
            include './views/StatusView.php';
            break;

        case 'ajaxStatus':
            require $routes[$baseURI];
            StatusController::procesar();
            break;

        case 'movilidad':
            include './views/MovilidadView.php';
            break;

        case 'ajaxMovilidad':
            require $routes[$baseURI];
            MovilidadController::procesar();
            break;

        case 'centrosMedicos':
            include './views/CentrosMedicosView.php';
            break;

        case 'ajaxCentrosMedicos':
            require $routes[$baseURI];
            CentrosMedicosController::procesar();
            break;

        case 'medicos':
            include './views/MedicosView.php';
            break;

        case 'ajaxMedicos':
            require $routes[$baseURI];
            MedicosController::procesar();
            break;

        case 'parentesco':
            include './views/ParentescoView.php';
            break;

        case 'ajaxParentesco':
            require $routes[$baseURI];
            ParentescoController::procesar();
            break;

        case 'metodoPago':
            include './views/MetodoPagoView.php';
            break;

        case 'ajaxMetodoPago':
            require $routes[$baseURI];
            MetodoPagoController::procesar();
            break;

        case 'articulos':
            include './views/ArticulosView.php';
            break;

        case 'ajaxArticulos':
            require $routes[$baseURI];
            ArticulosController::procesar();
            break;

        case 'representantes':
            include './views/RepresentantesView.php';
            break;

        case 'ajaxRepresentantes':
            require $routes[$baseURI];
            RepresentantesController::procesar();
            break;

        case 'tarifas':
            include './views/TarifasView.php';
            break;

        case 'ajaxTarifas':
            require $routes[$baseURI];
            TarifasController::procesar();
            break;

        case 'cargos':
            include './views/CargosView.php';
            break;

        case 'ajaxCargos':
            require $routes[$baseURI];
            CargosController::procesar();
            break;

        case 'pagos':
            include './views/PagosView.php';
            break;

        case 'ajaxPagos':
            require $routes[$baseURI];
            PagosController::procesar();
            break;

        case 'cargosPendientes':
            include './views/CargosPendientesView.php';
            break;

        case 'ajaxCargosPendientes':
            require $routes[$baseURI];
            CargosPendientesController::procesar();
            break;

        case 'requerimientos':
            include './views/RequerimientosView.php';
            break;

        case 'ajaxRequerimientos':
            require $routes[$baseURI];
            RequerimientosController::procesar();
            break;

        case 'consignaciones':
            include './views/ConsignacionesView.php';
            break;

        case 'ajaxConsignaciones':
            require $routes[$baseURI];
            ConsignacionesController::procesar();
            break;   
            
        case 'consignacionesPendientes':
            include './views/ConsignacionesPendientesView.php';
            break;

        case 'ajaxConsignacionesPendientes':
            require $routes[$baseURI];
            ConsignacionesPendientesController::procesar();
            break;

        case 'DetallesConsignacionesPendientes':
            include './views/DetallesConsignacionesPendientesView.php';
            break;

        case 'ajaxDetallesConsignacionesPendientes':
            require $routes[$baseURI];
            DetallesConsignacionesPendientesController::procesar();
            break;

        case 'operadores':
            include './views/OperadoresView.php';
            break;

        case 'ajaxOperadores':
            require $routes[$baseURI];
            OperadoresController::procesar();
            break;

        case 'incidencias':
            include './views/IncidenciasView.php';
            break;

        case 'ajaxIncidencias':
            require $routes[$baseURI];

            if(count($uriParts) > 1 && is_numeric($uriParts[1])){
                $_GET['id'] = $uriParts[1];
            }

            IncidenciasController::procesar($queryParams);
            break;

        case 'ajaxTurnos':
            require $routes[$baseURI];
            TurnosController::procesar();
            break;

        case 'visualizarDatos':
            include './views/VisualizarDatosView.php';
            break;

        case 'produccion':
            include './views/ProduccionView.php';
            break;

        case 'ajaxProduccion':
            require $routes[$baseURI];

            if(count($uriParts) > 1 && is_numeric($uriParts[1])){
                $_GET['id'] = $uriParts[1];
            }

            ProduccionController::procesar($queryParams);
            break;

        case 'alimentacion':
            include './views/AlimentacionView.php';
            break;

        case 'ajaxAlimentacion':
            require $routes[$baseURI];
            AlimentacionController::procesar($queryParams);
            break;

        case 'tiemposParada':
            include './views/TiemposParadaView.php';
            break;

        case 'ajaxTiemposParada':
            require $routes[$baseURI];
            TiemposParadaController::procesar($queryParams);
            break;

        case 'operatividad':
            include './views/OperatividadView.php';
            break;

        case 'ajaxOperatividad':
            require $routes[$baseURI];
            OperatividadController::procesar($queryParams);
            break;

        case 'alimentacionTurno':
            include './views/AlimentacionView.php';
            break;

        case 'ajaxAlimentacionTurno':
            require $routes[$baseURI];
            AlimentacionTurnoController::procesar();
            break;

        case 'reporteAlimentacion':
            include './views/reportes/ReporteAlimentacion.php';
            break;
        
        case 'reporteTiemposParada':
            include './views/reportes/ReporteTiemposParada.php';
            break;

        case 'reporteOperatividad':
            include './views/reportes/ReporteOperatividad.php';
            break;

        case 'reporteOperatividadMaquinaria':
            include './views/reportes/ReporteOperatividadMaquinaria.php';
            break;
        
        case 'reporteIncidencias':
            include './views/reportes/ReporteIncidencias.php';
            break;

        case 'reporteProduccion':
            include './views/reportes/ReporteProduccion.php';
            break;

        case 'reporteControlAlimentacion':
            include './views/reportes/ReporteControlAlimentacion.php';
            break;

        case 'insumos':
            include './views/InsumosView.php';
            break;

        case 'ajaxInsumos':
            require $routes[$baseURI];
            
            InsumosController::procesar();
            break;

        case 'departamentos':
            include './views/DepartamentosView.php';
            break;

        case 'ajaxDepartamento':
            require $routes[$baseURI];
            
            DepartamentosController::procesar();
            break;

        case 'consumoInsumos':
            include './views/ConsumoInsumosView.php';
            break;

        case 'ajaxConsumoInsumos':
            require $routes[$baseURI];

            if(count($uriParts) > 1 && is_numeric($uriParts[1])){
                $_GET['id'] = $uriParts[1];
            }

            ConsumoInsumosController::procesar($queryParams);
            break;

    }
}
else{
    echo "ERROR en url \n";
}

?>