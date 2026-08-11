<?php


// 1. Incluir tu modelo y obtener los datos directamente con PHP
require_once '../../vendor/autoload.php';
require_once '../../models/Landing.php'; // Ajusta la ruta según tu estructura

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;


$datos = Landing::getEstadisticas();


$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setTitle('Estadísticas');

$sheet->setCellValue('A1', 'Cantidad Residentes');
$sheet->setCellValue('B1', 'Residentes sin contención familiar');
$sheet->setCellValue('C1', 'Residentes con discapacidad');
$sheet->setCellValue('D1', 'Residentes que usan pañales');
$sheet->setCellValue('E1', 'Residentes con convenio IVSS');
$sheet->setCellValue('F1', 'Residentes privados');
$sheet->setCellValue('G1', 'Residentes psiquiátricos');

$sheet->setCellValue('A2', $datos['total_residentes']);
$sheet->setCellValue('B2', $datos['total_residentes_sin_contencion']);
$sheet->setCellValue('C2', $datos['total_residentes_con_discapacidad']);
$sheet->setCellValue('D2', $datos['total_residentes_pañales']);
$sheet->setCellValue('E2', $datos['total_residentes_ivss']);
$sheet->setCellValue('F2', $datos['total_residentes_privados']);
$sheet->setCellValue('G2', $datos['total_residentes_psiquiatricos']);

// Ajusta el ancho de cada columna al contenido más largo que tenga
$ultimaColumna = Coordinate::columnIndexFromString($sheet->getHighestColumn());
for ($col = 1; $col <= $ultimaColumna; $col++) {
    $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($col))->setAutoSize(true);
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="datos_geriatrico.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

exit;

?>
