<?php

use BcMath\Number;

set_time_limit(120); // Aumenta el límite a 120 segundos (2 minutos)

date_default_timezone_set('America/Caracas');

// 1. Incluir tu modelo y obtener los datos directamente con PHP
require_once '../../models/Operatividad.php'; // Ajusta la ruta según tu estructura

$filtros = [
    'fecha_inicio' => $_GET['fecha_inicio'] ?? null,
    'fecha_fin' => $_GET['fecha_fin'] ?? null
];

if (!empty($filtros['fecha_inicio']) && !empty($filtros['fecha_fin'])) {
    $datos = Operatividad::getFiltrados($filtros);
} else {
    $datos = Operatividad::getAll();
}

if (empty($datos)) {
    die("<h2>No hay datos para los filtros seleccionados</h2>");
}

$total = [];
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
    <title>Operatividad - Reporte PDF</title>
    <style>
        /* Estilos generales */
        body { 
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        
        /* Contenedor del banner */
        .banner-container {
            width: 100%;
            height: 150px; /* Ajusta según el tamaño de tu banner */
            margin-bottom: 20px;
        }
        
        /* Imagen del banner */
        .banner-img {
            width: 100%;
            height: 100%;
            object-fit: cover; /* Para que la imagen cubra todo el espacio */
        }
        
        /* Estilos de la tabla */
        .table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px;
        }
        .table th, .table td { 
            border: 1px solid #ddd; 
            padding: 8px; 
        }
        .table th { 
            background-color: #c8e6c9; 
            font-weight: bold;
        }
        .text-center { 
            text-align: center; 
        }
        
        /* Pie de página */
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 12px;
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
    
    <h2 style="text-align: center; margin-bottom: 20px;">Registros de Operatividad</h2>
    
    <!-- Resto de tu código de la tabla... -->
    <table class="table">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Máquina</th>
                <th>Total de horas trabajadas</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($datos as $item): 
                $total[$cont] = $item->total_horas_trabajadas;
                $cont ++;

                ?>
                <tr>
                    <td class="text-center"><?= date_format(date_create($item->fecha), 'd-m-Y') ?></td>
                    <td class="text-center"><?= $item->nombre_maquina ?></td>
                    <td class="text-center"><?= $item->total_horas_trabajadas ?> h</td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <?php 
                $totalMinutos = 0;
                foreach($total as $hora){
                    list($h, $m) = explode(':', $hora);
                    $totalMinutos += (($h * 60) + $m);
                }

                $totalHoras = floor($totalMinutos / 60);
                $totalMinutos = $totalMinutos % 60;
                $totalFormateado = sprintf("%02d:%02d", $totalHoras, $totalMinutos);


                ?>
                <td class="text-center" colspan="2">Horas Totales</td>
                <td class="text-center"><?php echo $totalFormateado ?> h</td>
            </tr>
        </tbody>
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


$dompdf->setPaper('letter'); // Opcional: 'portrait' o 'landscape'

$dompdf->loadHtml($html);

$dompdf->render();


// Salida del PDF
$dompdf->stream("reporte_tiempos_parada.pdf", ["Attachment" => false]);
?>