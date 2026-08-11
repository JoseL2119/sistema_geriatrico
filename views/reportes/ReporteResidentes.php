<?php


// 1. Incluir tu modelo y obtener los datos directamente con PHP
require_once '../../vendor/autoload.php';
require_once '../../models/Residentes.php'; // Ajusta la ruta según tu estructura

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;


$datos = Residentes::getAll();


$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setTitle('Residentes');

$sheet->setCellValue('A1', 'Nombre y apellido');
$sheet->setCellValue('B1', 'Sexo');
$sheet->setCellValue('C1', 'Cédula');
$sheet->setCellValue('D1', 'Fecha de nacimiento');
$sheet->setCellValue('E1', 'Edad'); // Determinada a partir de la fecha de nacimiento
$sheet->setCellValue('F1', 'Fecha de ingreso');
$sheet->setCellValue('G1', 'Fecha de egreso');
$sheet->setCellValue('H1', 'Paciente psiquiátrico'); // Si o no 
$sheet->setCellValue('I1', 'Diagnóstico');
$sheet->setCellValue('J1', 'Condición de movilidad'); 
$sheet->setCellValue('K1', 'Control de esfínteres'); // Si o no
$sheet->setCellValue('L1', 'Peso');
$sheet->setCellValue('M1', 'Altura');
$sheet->setCellValue('N1', 'Centro médico de evaluación');
$sheet->setCellValue('O1', 'Médico tratante');
$sheet->setCellValue('P1', 'Status');
$sheet->setCellValue('Q1', 'Contención familiar'); // Si o no
$sheet->setCellValue('R1', 'Nombre representante');
$sheet->setCellValue('S1', 'Cédula representante');
$sheet->setCellValue('T1', 'Parentesco del representante');
$sheet->setCellValue('U1', 'Teléfono representante');
$sheet->setCellValue('V1', 'Ubicación del representante');
$sheet->setCellValue('W1', 'Convenio con IVSS'); // Si o no
$sheet->setCellValue('X1', 'Caso Privado'); // Si o no
$sheet->setCellValue('Y1', 'Vulnerabilidad familiar'); // Si o no
$sheet->setCellValue('Z1', 'Apadrinazgo'); // Si o no
$sheet->setCellValue('AA1', 'Monto aporte');
$sheet->setCellValue('AB1', 'Fecha de pago');
$sheet->setCellValue('AC1', 'Observaciones');

// Monto de la tarifa más reciente por residente (por fecha_inicio)
$sqlTarifas = "
    SELECT DISTINCT ON (id_residente) id_residente, monto
    FROM tarifas
    ORDER BY id_residente, fecha_inicio DESC, id DESC
";
$montosPorResidente = [];
foreach (ConnectionFerro::getConnection()->query($sqlTarifas)->fetchAll(PDO::FETCH_ASSOC) as $tarifa) {
    $montosPorResidente[$tarifa['id_residente']] = $tarifa['monto'];
}

$siNo = function ($valor) {
    return $valor == 1 ? 'Sí' : 'No';
};

$fila = 2;
foreach ($datos as $residente) {

    $edad = '';
    if (!empty($residente->fecha_nacimiento)) {
        $edad = (new DateTime($residente->fecha_nacimiento))->diff(new DateTime())->y;
    }

    $sexo = $residente->genero == 1 ? 'Masculino' : ($residente->genero == 2 ? 'Femenino' : '');

    $sheet->setCellValue("A{$fila}", $residente->nombres . ' ' . $residente->apellidos);
    $sheet->setCellValue("B{$fila}", $sexo);
    $sheet->setCellValue("C{$fila}", $residente->cedula);
    $sheet->setCellValue("D{$fila}", $residente->fecha_nacimiento);
    $sheet->setCellValue("E{$fila}", $edad);
    $sheet->setCellValue("F{$fila}", $residente->fecha_ingreso);
    $sheet->setCellValue("G{$fila}", $residente->fecha_egreso);
    $sheet->setCellValue("H{$fila}", $siNo($residente->psiquiatrico));
    $sheet->setCellValue("I{$fila}", $residente->diagnostico);
    $sheet->setCellValue("J{$fila}", $residente->c_movilidad);
    $sheet->setCellValue("K{$fila}", $siNo($residente->control_esfinteres));
    $sheet->setCellValue("L{$fila}", $residente->peso);
    $sheet->setCellValue("M{$fila}", $residente->altura);
    $sheet->setCellValue("N{$fila}", $residente->centro_medico_e);
    $sheet->setCellValue("O{$fila}", $residente->medico_tratante);
    $sheet->setCellValue("P{$fila}", $residente->status_r);
    $sheet->setCellValue("Q{$fila}", $siNo($residente->contencion_f));
    $sheet->setCellValue("R{$fila}", $residente->representante . ' ' . $residente->apellidos_representante);
    $sheet->setCellValue("S{$fila}", $residente->cedula_representante);
    $sheet->setCellValue("T{$fila}", $residente->parentesco);
    $sheet->setCellValue("U{$fila}", $residente->telefono);
    $sheet->setCellValue("V{$fila}", $residente->domicilio);
    $sheet->setCellValue("W{$fila}", $siNo($residente->convenio_ivss));
    $sheet->setCellValue("X{$fila}", $siNo($residente->caso_privado));
    $sheet->setCellValue("Y{$fila}", $siNo($residente->vulnerabilidad_f));
    $sheet->setCellValue("Z{$fila}", $siNo($residente->apadrinazgo));
    $sheet->setCellValue("AA{$fila}", $montosPorResidente[$residente->id] ?? '');
    $sheet->setCellValue("AB{$fila}", $residente->fecha_pago);
    $sheet->setCellValue("AC{$fila}", $residente->observaciones);

    $fila++;
}

// Ajusta el ancho de cada columna al contenido más largo que tenga
$ultimaColumna = Coordinate::columnIndexFromString($sheet->getHighestColumn());
for ($col = 1; $col <= $ultimaColumna; $col++) {
    $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($col))->setAutoSize(true);
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="datos_residentes_geriatrico.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

exit;

?>
