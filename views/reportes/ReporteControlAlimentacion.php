<?php
set_time_limit(120); // Aumenta el límite a 120 segundos (2 minutos)

date_default_timezone_set('America/Caracas');

// 1. Incluir tu modelo y obtener los datos directamente con PHP
require_once '../../models/ReportesSalaControl.php'; // Ajusta la ruta según tu estructura

$filtros = [
    'fecha' => $_GET['filtro_fecha'] ?? null
];

if (!empty($filtros['fecha'])) {
    $datos = ReportesSalaControl::getFiltrados($filtros);
} else {
    $datos = ReportesSalaControl::getAll();
}

if (empty($datos)) {
    die("<h2>No hay datos para los filtros seleccionados</h2>");
}

$totalParadaCt5 = 0;
$totalOp1 = 0;
$totalOp2 = 0;
$totalOp3 = 0;
$totalParada1 = 0;
$totalParada2 = 0;
$totalParada3 = 0;
$totalHumedad = 0;
$cont = 0;

function imagenABase64($ruta) {
    // Verificar si el archivo existe
    if (!file_exists($ruta)) {
        return false;
    }
    
    // Obtener el tipo de imagen
    $tipo = pathinfo($ruta, PATHINFO_EXTENSION);
    
    // Leer el contenido de la imagen
    $contenido = file_get_contents($ruta);
    
    // Codificar a Base64
    return 'data:image/' . $tipo . ';base64,' . base64_encode($contenido);
}

// Convertir las imágenes
$base64LogoLeft = imagenABase64(realpath('../../src/logo cvm.jpg'));
$base64LogoRight = imagenABase64(realpath('../../src/batallaAyacucho.jpg'));
$base64LogoCenter = imagenABase64(realpath('../../src/motorMinero.jpg'));
$base64LogoFinal = imagenABase64(realpath('../../src/banner.jpg'));

// Configuración del banner
$bannerHeight = 80; // Altura en píxeles
$margin = 20; // Margen en píxeles

ob_start();
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Alimentación - Reporte PDF</title>
    <style>
        /* Estilos optimizados para PDF */
        body {
            margin: 0;
            padding: 5mm; /* Margen mínimo */
            font-family: Arial, sans-serif;
            font-size: 9pt; /* Reducir tamaño de fuente */
            line-height: 1.2;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8pt; /* Tamaño más pequeño para tabla */
            page-break-inside: avoid;
        }
        
        th, td {
            padding: 2px 3px; /* Reducir padding */
            border: 0.5px solid #000;
            text-align: center;
        }
        
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        
        h2, h3 {
            margin: 2mm 0; /* Reducir margen de títulos */
            page-break-after: avoid;
        }
        
        /* Pie de página */
        .footer {
            margin-top: 5px;
            text-align: right;
            font-size: 8px;
            color: #666;
        }

        /* Banner simple */
        .banner {
            width: 100%;
            display: flex;
            justify-content: space-between; /* Distribución uniforme */
            align-items: center; /* Centrado vertical */
            margin-bottom: 20px;
        }
        
        .banner img {
            height: <?= $bannerHeight ?>px;
            max-width: 200px;
            object-fit: contain; /* Mantiene proporciones */
            flex-grow: 1; /* Permite crecimiento proporcional */
            margin: 0 5px; /* Espaciado entre imágenes */
        }

        .imgcentral {
            height: <?= $bannerHeight ?>px;
            max-width: 550px !important;
            object-fit: contain; /* Mantiene proporciones */
            margin-left: auto;
        }
    </style>
</head>
<body>

    <div class="banner">
        <?php if ($base64LogoFinal): ?>
            <img src="<?= $base64LogoFinal ?>" alt="Logo" class="imgcentral">
        <?php endif; ?>

        <?php if ($base64LogoLeft): ?>
            <img src="<?= $base64LogoLeft ?>" alt="Logo">
        <?php endif; ?>
        
        <?php if ($base64LogoRight): ?>
            <img src="<?= $base64LogoRight ?>" alt="Logo" style="margin-left: auto;">
        <?php endif; ?>
    </div>

    <h3 style="text-align: center;">Registros de Control de Alimentación - <?= !empty($filtros['fecha']) ? date_format(date_create($filtros['fecha']), 'd-m-Y') : '' ?></h2>
    
    <!-- Resto de tu código de la tabla... -->
    <table class="table">
        <div class="container-fluid px-0">  <!-- Contenedor fluido sin padding horizontal -->
            <div class="table-responsive rounded-3 shadow-sm" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                <table class="table table-bordered m-0" style="font-size: 0.88rem; width: 100%; min-width: 100%;">
                    <thead>
                        <!-- Fila de encabezado principal -->
                        <tr class="text-center" style="background-color: #c8e6c9;">  <!-- Verde más intenso -->
                            <th rowspan="2" class="align-middle px-2" style="background-color: #ffd54f; width: 5%; min-width: 60px;">Hora</th>
                            <th rowspan="2" class="align-middle px-2" style="background-color: #ffd54f; width: 6%; min-width: 70px;">PAD CT-5</th>
                            <th colspan="3" style="background-color:rgba(100, 165, 94, 0.85); color:rgb(0, 0, 0); width: 18%;">Operatividad</th>  <!-- Amarillo más intenso -->
                            <th colspan="3" style="background-color:rgba(100, 165, 94, 0.85); color:rgb(0, 0, 0); width: 18%;">Minutos de Parada</th>  <!-- Amarillo anaranjado -->
                            <th rowspan="2" class="align-middle px-2" style="background-color: #ffd54f;width: 5%; min-width: 60px;">% HUM</th>
                            <th rowspan="2" class="align-middle px-2" style="background-color: #ffd54f; width: 8%; min-width: 80px;">Cod. Arena</th>
                            <th rowspan="2" class="align-middle px-2" style="background-color: #ffd54f; width: 6%; min-width: 70px;">N° Tolva</th>
                            <th rowspan="2" class="align-middle px-2" style="background-color: #ffd54f; width: 12%; min-width: 120px;">Observaciones</th>
                            <th rowspan="2" class="align-middle px-2" style="background-color: #ffd54f; width: 12%; min-width: 120px;">Sup. de Turno</th>
                        </tr>

                        <!-- Fila de subencabezados -->
                        <tr class="text-center" style="background-color: #dcedc8;">  <!-- Verde claro más intenso -->
                            <th style="background-color:rgba(100, 165, 94, 0.85); width: 6%;">Molino 1</th>
                            <th style="background-color:rgba(100, 165, 94, 0.85); width: 6%;">Molino 2</th>
                            <th style="background-color:rgba(100, 165, 94, 0.85); width: 6%;">Molino 3</th>
                            <th style="background-color:rgba(100, 165, 94, 0.85); width: 6%;">Molino 1</th>
                            <th style="background-color:rgba(100, 165, 94, 0.85); width: 6%;">Molino 2</th>
                            <th style="background-color:rgba(100, 165, 94, 0.85); width: 6%;">Molino 3</th>
                        </tr>
                    </thead>
                    <tbody id="tbody" class="table-group-divider" style="border-top: 2px solid #80cbc4;">
                        <?php foreach ($datos as $item): 

                            $totalParadaCt5 += $item->pad_ct_5;
                            $totalOp1 += $item->operatividad_molino_1;
                            $totalOp2 += $item->operatividad_molino_2;
                            $totalOp3 += $item->operatividad_molino_3;
                            $totalParada1 += $item->parada_molino_1;
                            $totalParada2 += $item->parada_molino_2;
                            $totalParada3 += $item->parada_molino_3;
                            $totalHumedad += $item->porcentaje_humedad;
                            $cont ++;

                            ?>
                            <tr>
                                <td><?= $item->hora ?></td>
                                <td><?= $item->pad_ct_5 ?></td>
                                <td><?= $item->operatividad_molino_1 ?> min</td>
                                <td><?= $item->operatividad_molino_2 ?> min</td>
                                <td><?= $item->operatividad_molino_3 ?> min</td>
                                <td><?= $item->parada_molino_1 ?> min</td>
                                <td><?= $item->parada_molino_2 ?> min</td>
                                <td><?= $item->parada_molino_3 ?> min</td>
                                <td><?= $item->porcentaje_humedad ?> %</td>
                                <td><?= $item->codigo_arena ?></td>
                                <td><?= $item->numero_tolva ?></td>
                                <td><?= $item->observacion ?></td>
                                <td><?= $item->supervisor ?></td>
                            </tr>
                        <?php endforeach; ?>

                        <?php if($cont == 0){?>
                            <tr><td colspan="13" class="text-center text-muted">No hay registros para el día de hoy</td></tr>
                        <?php }
                        else{ ?>
                            <tr>
                                <td>Totales</td>
                                <td><?= $totalParadaCt5 ?></td>
                                <td><?= $totalOp1 ?></td>
                                <td><?= $totalOp2 ?></td>
                                <td><?= $totalOp3 ?></td>
                                <td><?= $totalParada1 ?></td>
                                <td><?= $totalParada2 ?></td>
                                <td><?= $totalParada3 ?></td>
                                <td><?= $totalHumedad/$cont ?>%</td>
                                <td colspan="4"></td>
                            </tr>
                        <?php }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        
    </table>
    
    <!-- Pie de página opcional -->
    <div class="footer">
        Generado el <?= date('d/m/Y H:i:s') ?>
    </div>
</body>
</html>

<?php
$html = ob_get_clean();



require_once '../../lib/dompdf/autoload.inc.php';
use Dompdf\Dompdf;

$dompdf = new Dompdf();

// Configurar para que funcione con CSS y recursos externos
$options = $dompdf->getOptions();
$options->set('isRemoteEnabled', true);
$options->set('isHtml5ParserEnabled', true);
$dompdf->setOptions($options);


$dompdf->setPaper('letter', 'landscape'); // Opcional: 'portrait' o 'landscape'

$dompdf->loadHtml($html);

$dompdf->render();


// Salida del PDF
$dompdf->stream("reporte_control_alimentacion.pdf", ["Attachment" => false]);
?>