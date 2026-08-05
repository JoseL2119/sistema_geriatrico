<?php
// Datos de ejemplo para la gráfica
$datosCompras = array(
    array('Mes', 'Compras', 'Proveedores'),
    array('Enero', 100, 10),
    array('Febrero', 125, 12),
    array('Marzo', 110, 8),
    array('Abril', 130, 14),
    array('Mayo', 150, 15)
);

$datosVentas = array(
    array('Mes', 'Ventas', 'Clientes'),
    array('Enero', 80, 15),
    array('Febrero', 95, 18),
    array('Marzo', 120, 22),
    array('Abril', 145, 25),
    array('Mayo', 160, 30)
);
?>

<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Visualización de Datos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- Google Charts -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  </head>
  <body>
    <div class="container">
        
        <?php include "fragments/navbarLanding.php" ?>

        <label for="fecha_registro" class="form-label">Seleccionar Fecha</label>
        <br>
        <input type="date" name="" id="fecha_registro">

        <div class="row">
            <div class="col-xl-6">
                <div id="chart_div" style="height: 500px;">

                </div>
            </div>

            <div class="col-xl-6">
                <div id="chart_div2" style="height: 500px;">
                    
                </div>
            </div>
        </div>
        
        <script src="../assets/js/grafico.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.all.min.js"></script>

        <script>
            $(document).ready(function () {

                $('#fecha_registro').change(function() {
                    var fecha = $(this).val();
                    cargarDatosYGraficar(fecha, 'Toneladas Procesadas', 'chart_div');
                });

                cargarDatosYGraficar('Toneladas Procesadas', 'Toneladas Procesadas Por Turno', 'chart_div', 'chart_div2'); // Muestra los de hoy por defecto
            });
        </script>

        

  </body>
</html>