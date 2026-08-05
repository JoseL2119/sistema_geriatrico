<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tiempos de Parada</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        /* Estilos optimizados */
        .table thead th {
            white-space: nowrap;
            vertical-align: middle;
            border: 1px solid #a5d6a7;  /* Borde verde */
            font-weight: 600;
            padding: 8px 4px;  /* Padding más compacto */
        }
        
        .table tbody td {
            vertical-align: middle;
            border: 1px solid #e0e0e0;
            padding: 6px 4px;  /* Padding más compacto */
        }
        
        .table-bordered {
            border: 2px solid #4db6ac;  /* Borde turquesa más intenso */
        }
        
        /* Efecto hover para filas */
        .table-hover tbody tr:hover {
            background-color: rgba(200, 230, 201, 0.5);  /* Hover más visible */
        }
        
        /* Asegurar que la tabla ocupe todo el ancho */
        .container-fluid {
            max-width: 99vw;  /* Ocupa casi todo el ancho de la ventana */
            padding-right: 5px;
            padding-left: 5px;
        }
        
        /* Scroll muy sutil solo si es absolutamente necesario */
        .table-responsive {
            overflow-x: auto;
            padding-bottom: 2px;
        }
        
        /* Color para filas pares/impares opcional */
        .table tbody tr:nth-child(even) {
            background-color: #f5f5f5;
        }

        #nuevo {
            padding: 0.5rem 1.25rem;
            font-weight: 500;
            transition: all 0.2s;
            background-color: #2e7d32;
            border: none;
        }
        
        #nuevo:hover {
            transform: translateY(-1px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            background-color: #1b5e20;
        }

        /* Ajusta el título */
        #lista h5 {
            color: #2e7d32;
            font-size: 1.25rem;
        }
    </style>
  </head>
  <body>
    <div class="container">
        
    <?php include "fragments/navbarLanding.php" ?>

    <section id="lista">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0 fw-bold">
                <i class="fas fa-users me-2"></i>
                Registros de Tiempos de Parada
            </h5>
        </div>

        <!-- Línea divisoria -->
        <hr class="border-2 border-top border-primary opacity-25 mb-4">

        <div style="display: flex; justify-content: center; gap: 20px; align-items: center; margin: 20px 0; background: #f5f5f5; padding: 15px; border-radius: 8px;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <label for="fecha_inicio" class="form-label" style="font-weight: 600; color: #333;">Fecha Inicial:</label>
                <input type="date" id="fecha_inicio" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; font-family: inherit;">
            </div>

            <div style="display: flex; align-items: center; gap: 8px;">
                <label for="fecha_fin" class="form-label" style="font-weight: 600; color: #333;">Fecha Final:</label>
                <input type="date" id="fecha_fin" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; font-family: inherit;">
            </div>
        </div>

        <button id="pdf-link" 
                style="background-color: #4CAF50; /* Verde */
                    color: white;
                    border: none;
                    padding: 10px 20px;
                    text-align: center;
                    text-decoration: none;
                    display: inline-block;
                    font-size: 14px;
                    margin: 4px 2px;
                    cursor: pointer;
                    border-radius: 4px;
                    transition: background-color 0.3s;
                    box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <i class="fas fa-file-pdf" style="margin-right: 8px;"></i> Generar PDF
        </button>

        <div class="container-fluid px-0">
            <div class="table-responsive rounded-3 shadow-sm">
                <table class="table table-bordered table-hover m-0" style="font-size: 0.88rem; width: 100%; min-width: 100%;">
                    <thead class="text-center" style="background-color: #c8e6c9;">
                        <tr>
                            <th style="width: 20%; min-width: 100px;">Fecha</th>
                            <th style="width: 20%; min-width: 100px;">Total Parada MOBO 1</th>
                            <th style="width: 20%; min-width: 100px;">Total Parada MOBO 2</th>
                            <th style="width: 20%; min-width: 100px;">Total Parada MOBO 3</th>
                            <th style="width: 20%; min-width: 100px;">Total Parada Cinta</th>
                        </tr>
                    </thead>
                    <tbody id="tbody" class="table-group-divider" style="border-top: 2px solid #80cbc4;">
                        <!-- Las filas se generarán dinámicamente aquí -->
                    </tbody>
                </table>
            </div>
        </div>
    </section>


    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="../assets/js/ajaxTiemposParada.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <script>
    // Actualiza el enlace con los filtros actuales
    document.getElementById('pdf-link').addEventListener('click', function(e) {
        e.preventDefault();
        const fechaInicio = document.getElementById('fecha_inicio').value;
        const fechaFin = document.getElementById('fecha_fin').value;
        
        // Construye la URL con parámetros
        let url = '/views/reportes/ReporteTiemposParada.php';
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