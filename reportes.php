<?php

ob_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <?php

    require_once "config/ConnectionFerro.php";

    $sql = "SELECT e.*,
                ma.tipo AS material, mi.nombre AS mina, em.nombre AS empresa
                FROM excavacion_cd_piar e
                INNER JOIN material_excavado ma ON e.id_tipo_material = ma.id
                INNER JOIN minas mi ON e.id_mina = mi.id
                INNER JOIN empresa em ON e.id_empresa = em.id
                ORDER BY e.fecha DESC";

    $stmt = ConnectionFerro::getConnection()->prepare($sql);

    try{
        $stmt->execute();
    } catch(PDOException $e) {
        echo $e;
    }

    $lista = $stmt->fetchAll(PDO::FETCH_ASSOC);

    ?>


    <section id="lista">
        <h3>Reporte de Historial Excavación Ciudad Piar</h3>
        <hr />
        <table class="table">
            <thead>
                <tr>
                    <!-- <th scope="col">#</th> -->
                    <th scope="col">Material</th>
                    <th scope="col">Cantidad (Tn)</th>
                    <th scope="col">Mina</th>
                    <th scope="col">Empresa</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">Comentario</th>
                </tr>
            </thead>
            <tbody id="tbody">
                <?php foreach($lista as $excavacion) {?>
                    <tr>
                        <!-- <td><?php echo $excavacion['id'] ?></td> -->
                        <td><?php echo $excavacion['material'] ?></td>
                        <td><?php echo $excavacion['cantidad'] ?></td>
                        <td><?php echo $excavacion['mina'] ?></td>
                        <td><?php echo $excavacion['empresa'] ?></td>
                        <td><?php echo $excavacion['fecha'] ?></td>
                        <td><?php echo $excavacion['comentario'] ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </section>
</body>
</html>

<?php

$html = ob_get_clean();
// echo $html;

require_once 'lib/dompdf/autoload.inc.php';
use Dompdf\Dompdf;
$dompdf = new Dompdf();

$options = $dompdf->getOptions();
$options->set(array('isRemoteEnabled' => true));
$dompdf->setOptions($options);

$dompdf->loadHtml($html);

$dompdf->setPaper('letter');

$dompdf->render();

$dompdf->stream('archivo_.pdf', array("Attachment" => false));
?>
