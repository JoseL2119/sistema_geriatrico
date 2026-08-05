<?php
set_time_limit(120); // Aumenta el límite a 120 segundos (2 minutos)

date_default_timezone_set('America/Caracas');

// 1. Incluir tu modelo y obtener los datos directamente con PHP
require_once '../../models/Incidencias.php'; // Ajusta la ruta según tu estructura

$filtros = [
    'fecha' => $_GET['filtro_fecha'] ?? null,
    'id_turno' => $_GET['filtro_turno'] ?? null
];

if (!empty($filtros['fecha']) && !empty($filtros['id_turno'])) {
    $datos = Incidencias::getFiltrados($filtros);
} else {
    $datos = Incidencias::getAll();
}

if (empty($datos)) {
    die("<h2>No hay datos para los filtros seleccionados</h2>");
}


$html1 = '';
$html2 = '';
$html3 = `
        <tr>
            <td>Hora:</td>
            <td colspan="7">Observaciones:</td>
        </tr>`;

$totalHumedas = 0;
$humedadPromedio = 0;
$totalSecas = 0;
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
    <title>Incidencias - Reporte PDF</title>
    <style>
        /* Estilos para tablas en PDF */
        table {
            width: 100%;
            border-collapse: collapse !important; /* Fuerza el colapso de bordes */
            border-spacing: 0;
            page-break-inside: auto; /* Evita que la tabla se divida entre páginas */
        }
        
        table, th, td {
            border: 1px solid black !important; /* Fuerza bordes visibles */
        }
        
        th, td {
            padding: 4px 6px;
            text-align: center;
            vertical-align: middle;
            border-style: solid !important; /* Asegura que los bordes sean sólidos */
            border-width: 1px !important;
        }
        
        /* Estilo para celdas con rowspan/colspan */
        [colspan], [rowspan] {
            border-style: solid !important;
        }
        
        /* Evita saltos de página dentro de filas */
        tr {
            page-break-inside: avoid;
            page-break-after: auto;
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
    
    <h2 style="text-align: center; margin-bottom: 20px;">Registros de Incidencias</h2>
    <h3 style="text-align: center; margin-bottom: 20px;"><?= !empty($filtros['fecha']) ? date_format(date_create($filtros['fecha']), 'd-m-Y') : '' ?></h3>
    <!-- Resto de tu código de la tabla... -->
    <div class="container-fluid px-0">
        <div class="table-responsive rounded-3 shadow-sm">           
            <table class="table table-bordered table-hover m-0" style="font-size: 0.88rem; width: 100%; min-width: 100%;">
                <thead class="text-center" style="background-color: #c8e6c9;">
                    <tr>
                        <th style="border: 1px solid #000;">Fecha</th>
                        <th colspan="2" style="border: 1px solid #000;">Supervisor</th>
                        <th colspan="2" style="border: 1px solid #000;">Operador</th>
                        <th style="border: 1px solid #000;">Grupo</th>
                        <th colspan="2" style="border: 1px solid #000;">Turno</th>
                    </tr>
                </thead>
                <tbody id="tbody" class="text-center">
                    
                    <?php foreach ($datos as $item): 

                        ?>
                        <tr>
                            <td style="border: 1px solid #000;"><?= date_format(date_create($item->fecha), 'd-m-Y') ?></td>
                            <td colspan="2" style="border: 1px solid #000;"><?= $item->supervisor ?></td>
                            <td colspan="2" style="border: 1px solid #000;"><?= $item->operador ?></td>
                            <td style="border: 1px solid #000;"><?= $item->grupo ?></td>
                            <td colspan="2" style="border: 1px solid #000;"><?= $item->turno ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>

                <tbody id="tobservaciones">
                    <tr>
                        <td style="border: 1px solid #000; background-color: #c8e6c9;">Hora</td>
                        <td colspan="7" style="border: 1px solid #000; background-color: #c8e6c9;">Observaciones</td>
                    </tr>
                    <?php foreach ($datos as $item): 
                        $observaciones = json_decode($item->observaciones, true);
                        if(!empty($observaciones)){
                            foreach($observaciones as $key => $value):
                            ?>
                            <tr>
                                <td style="border: 1px solid #000;"><?= $value['hora'] ?></td>
                                <td colspan="7" style="border: 1px solid #000;"><?= $value['texto'] ?></td>
                            </tr>
                            <?php endforeach;
                        } else{
                            ?>
                            <tr>
                                <td colspan="8" style="border: 1px solid #000;">Sin observaciones</td>
                            </tr>
                        <?php }
                        
                    endforeach; ?>
                </tbody>

                <tbody id="tcontent">
                    <?php foreach ($datos as $item): 

                        ?>
                        <tr>
                            <td style="border: 1px solid #000;">Almacenamiento Tolvas</td>
                            <td style="border: 1px solid #000;">TV1: <?= $item->tolva1 ?></td>
                            <td style="border: 1px solid #000;">TV2: <?= $item->tolva2 ?></td>
                            <td style="border: 1px solid #000;">Palas Procesadas: <?= $item->palas_procesadas_1 ?></td>
                            <td style="border: 1px solid #000;">Palas Procesadas: <?= $item->palas_procesadas_2 ?></td>
                            <td style="border: 1px solid #000;">Payloaders: <?= $item->maquina ?></td>
                            <td style="border: 1px solid #000;">Palas totales: <?= $item->palas_totales_procesadas ?></td>
                            <td style="border: 1px solid #000;">Toneladas totales: <?= $item->palas_totales_procesadas*3.5 ?></td>
                        </tr>

                        <tr>
                            <td style="border: 1px solid #000;">Agregado cal</td>
                            <td style="border: 1px solid #000;">Recepción: <?= $item->recepcion ?></td>
                            <td colspan="2" style="border: 1px solid #000;">Solicitados en turno: <?= $item->solicitados_en_turno ?></td>
                            <td colspan="2" style="border: 1px solid #000;">Usados en turno: <?= $item->usados_en_turno ?></td>
                            <td colspan="2" style="border: 1px solid #000;">Disponibles sig. turno: <?= $item->disponibles_sig_turno ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                
            </table>
        </div>
    </div>

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
$options->set('dpi', 120);
$dompdf->setOptions($options);


$dompdf->setPaper('letter'); // Opcional: 'portrait' o 'landscape'

$dompdf->loadHtml($html);

$dompdf->render();


// Salida del PDF
$dompdf->stream("reporte_incidencias.pdf", ["Attachment" => false]);
?>