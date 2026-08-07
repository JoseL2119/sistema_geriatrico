<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detalle de las consignaciones pendientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="../assets/css/app.css" rel="stylesheet">
    <style>
        .container-fluid {
            max-width: 99vw;
            padding-right: 5px;
            padding-left: 5px;
        }
    </style>
  </head>
  <body>
    <div class="container">
        
    <?php include "fragments/navbarLanding.php" ?>

    <section id="lista">
        <div class="page-header">
            <h5>
                <i class="fas fa-users page-icon"></i>
                Detalle de Consignaciones pendientes por entregar
            </h5>
        </div>

        <hr class="page-divider">

        <!-- <div class="filter-bar">
            <div class="filter-field">
                <label for="fecha_inicio">Fecha Inicial:</label>
                <input type="date" class="form-control form-control-sm" id="fecha_inicio">
            </div>

            <div class="filter-field">
                <label for="fecha_fin">Fecha Final:</label>
                <input type="date" class="form-control form-control-sm" id="fecha_fin">
            </div>
        </div>

        <button id="pdf-link" class="btn btn-pdf btn-app mb-3">
            <i class="fas fa-file-pdf me-2"></i>Generar PDF
        </button> -->

        <div class="container-fluid px-0">
            <div class="table-responsive rounded-3 shadow-sm">
                <table class="table table-bordered table-hover m-0" style="font-size: 0.88rem; width: 100%; min-width: 100%;">
                    <thead class="text-center">
                        <tr>
                            <th style="width: 20%; min-width: 100px;">Residente</th>
                            <th style="width: 20%; min-width: 100px;">Requerimientos Pendientes</th>
                            <th style="width: 20%; min-width: 100px;">Requerimientos Parciales</th>
                            <th style="width: 20%; min-width: 100px;">Artículos Pendientes</th>
                            <th style="width: 20%; min-width: 100px;">Opciones</th>
                        </tr>
                    </thead>
                    <tbody id="tbody" class="table-group-divider">
                        <!-- Las filas se generarán dinámicamente aquí -->
                    </tbody>
                </table>
            </div>
        </div>
    </section>


    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="../assets/js/ajaxDetallesConsignacionesPendientes.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.all.min.js"></script>

    <script>
    // Actualiza el enlace con los filtros actuales
    document.getElementById('pdf-link').addEventListener('click', function(e) {
        e.preventDefault();
        const fechaInicio = document.getElementById('fecha_inicio').value;
        const fechaFin = document.getElementById('fecha_fin').value;
        
        // Construye la URL con parámetros
        let url = '/views/reportes/ReporteAlimentacion.php';
        const params = new URLSearchParams();
        
        if(fechaInicio) params.append('fecha_inicio', fechaInicio);
        if(fechaFin) params.append('fecha_fin', fechaFin);
        
        if(params.toString()) {
            url += '?' + params.toString();
        }
        
        window.open(url, '_blank');
    });
    </script>
  </body>
</html>