<?php
// Genera automáticamente el cargo del mes para cada residente activo,
// usando su tarifa más reciente y el día de pago de su representante.
// Pensado para ejecutarse una vez al mes (cron job en Railway).

require_once __DIR__ . '/../config/ConnectionFerro.php';

$meses = [
    1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
    5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
    9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
];

$anio = (int) date('Y');
$mes = (int) date('n');
$periodo = $meses[$mes] . ' ' . $anio;
$fechaEmision = date('Y-m-d');
$ultimoDiaMes = (int) date('t');

$conn = ConnectionFerro::getConnection();

// Para cada residente activo, la tarifa más reciente (por fecha_inicio)
$sqlTarifasActivas = "
    SELECT DISTINCT ON (t.id_residente)
        t.id AS id_tarifa,
        t.monto,
        rep.fecha_pago,
        r.nombres,
        r.apellidos
    FROM tarifas t
    INNER JOIN residentes r ON t.id_residente = r.id
    INNER JOIN status_residente s ON r.status = s.id
    INNER JOIN representantes rep ON r.id_representante = rep.id
    WHERE s.status = 'En el geriátrico'
    ORDER BY t.id_residente, t.fecha_inicio DESC, t.id DESC
";

$tarifasActivas = $conn->query($sqlTarifasActivas)->fetchAll(PDO::FETCH_ASSOC);

$stmtExiste = $conn->prepare(
    "SELECT id FROM cargos WHERE id_tarifa = :id_tarifa AND periodo = :periodo"
);

$stmtInsert = $conn->prepare(
    "INSERT INTO cargos (id_tarifa, periodo, fecha_emision, fecha_vencimiento, monto, observaciones)
     VALUES (:id_tarifa, :periodo, :fecha_emision, :fecha_vencimiento, :monto, :observaciones)"
);

$generados = 0;
$omitidos = 0;

foreach ($tarifasActivas as $tarifa) {
    $stmtExiste->execute([
        ':id_tarifa' => $tarifa['id_tarifa'],
        ':periodo' => $periodo,
    ]);

    if ($stmtExiste->fetch()) {
        echo "Omitido: {$tarifa['nombres']} {$tarifa['apellidos']} ya tiene cargo de $periodo." . PHP_EOL;
        $omitidos++;
        continue;
    }

    $diaVencimiento = min((int) $tarifa['fecha_pago'], $ultimoDiaMes);
    $fechaVencimiento = sprintf('%04d-%02d-%02d', $anio, $mes, $diaVencimiento);

    $stmtInsert->execute([
        ':id_tarifa' => $tarifa['id_tarifa'],
        ':periodo' => $periodo,
        ':fecha_emision' => $fechaEmision,
        ':fecha_vencimiento' => $fechaVencimiento,
        ':monto' => $tarifa['monto'],
        ':observaciones' => 'Cargo generado automáticamente',
    ]);

    echo "Generado: {$tarifa['nombres']} {$tarifa['apellidos']} - $periodo - vence $fechaVencimiento - {$tarifa['monto']}$." . PHP_EOL;
    $generados++;
}

echo "Total generados: $generados. Total omitidos: $omitidos." . PHP_EOL;
