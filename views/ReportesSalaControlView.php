<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reportes - Control de Alimentación</title>
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
            <h5 class="mb-0  fw-bold" id="titulo-seccion">
                <i class="fas fa-clipboard-list me-2"></i>
                
            </h5>

            
            <!-- Botón con espaciado y sombra -->
            <button type="button" class="btn btn-primary shadow-sm" id="nuevo">
                <i class="fas fa-plus-circle me-2"></i>Nuevo Reporte
            </button>
        </div>
        
        <!-- Línea divisoria estilizada -->
        <hr class="border-2 border-top border-primary opacity-25 mb-4">
        
        <div style="display: flex; justify-content: center; gap: 20px; align-items: center; margin: 20px 0; background: #f5f5f5; padding: 15px; border-radius: 8px;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <label for="filtro_fecha" class="form-label">Filtrar por fecha</label>
                <input type="date" name="" id="filtro_fecha">
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
                        <th rowspan="2" class="align-middle px-2" style="background-color: #ffd54f; width: 10%; min-width: 100px;">Opciones</th>
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
                        <!-- Las filas se generarán dinámicamente aquí -->
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <section id="form" class="d-none">
        <h5>Formulario para insertar nuevo reporte</h5>
        <hr />
        <form id="formulario">
            <input type="hidden" id="id" />

            <div class="mb-3">
                <label for="fecha" class="form-label">Fecha</label>
                <input 
                    type="date" 
                    class="form-control" 
                    id="fecha" 
                    value=""
                    required
                />
            </div>

            <div class="mb-3">
                <label for="hora" class="form-label">Hora</label>
                <input 
                    type="time" 
                    class="form-control" 
                    id="hora" 
                    required
                    step="60" 
                />
            </div>
            <hr />
            <div class="mb-3">
                <label for="padct" class="form-label">PAD CT-5 (min)</label>
                <input 
                    type="number" 
                    class="form-control" 
                    id="padct" 
                    value=""
                    required
                />
            </div>

            <hr />
            <h5>OPERATIVIDAD</h5>

            <div class="mb-3">
                <label for="molino1" class="form-label">Molino 1 (min)</label>
                <input 
                    type="number" 
                    class="form-control" 
                    id="molino1" 
                    value=""
                    required
                    oninput="calcular1()"
                />
            </div>

            <div class="mb-3">
                <label for="molino2" class="form-label">Molino 2 (min)</label>
                <input 
                    type="number" 
                    class="form-control" 
                    id="molino2" 
                    value=""
                    required
                    oninput="calcular2()"
                />
            </div>

            <div class="mb-3">
                <label for="molino3" class="form-label">Molino 3 (min)</label>
                <input 
                    type="number" 
                    class="form-control" 
                    id="molino3" 
                    value=""
                    required
                    oninput="calcular3()"
                />
            </div>

            <hr />
            <h5>MINUTOS DE PARADA</h5>

            <div class="mb-3">
                <label for="molino1P" class="form-label">Molino 1</label>
                <input 
                    type="number" 
                    class="form-control" 
                    id="molino1P" 
                    value=""
                    required
                    
                />
            </div>

            <div class="mb-3">
                <label for="molino2P" class="form-label">Molino 2</label>
                <input 
                    type="number" 
                    class="form-control" 
                    id="molino2P" 
                    value=""
                    required
                    
                />
            </div>

            <div class="mb-3">
                <label for="molino3P" class="form-label">Molino 3</label>
                <input 
                    type="number" 
                    class="form-control" 
                    id="molino3P" 
                    value=""
                    required
                    
                />
            </div>
            <hr />

            <div class="mb-3">
                <label for="hum" class="form-label">% HUM</label>
                <input 
                    type="number"
                    step="0.01" 
                    class="form-control" 
                    id="hum" 
                    value=""
                    min="0"
                    max="100"
                />
            </div>


            <hr />

            <div class="mb-3">
                <label for="cArena" class="form-label">Código de Arena</label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="cArena" 
                    value=""
                />
            </div>

            <hr />

            <div class="mb-3">
                <label for="nTolva" class="form-label">N° de Tolva</label>
                <input 
                    type="number" 
                    class="form-control" 
                    id="nTolva" 
                    value=""
                />
            </div>

            <hr />

            <div class="mb-3">
                <label for="comentario" class="form-label">Observaciones</label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="comentario" 
                    value=""
                />
            </div>

            <hr />

            <div class="mb-3">
                <label for="sup" class="form-label">Supervisor de Turno</label>
                <select class="form-select" id="sup">
                    <option selected>Elegir Supervisor</option>
                </select>
            </div>
            
            
            <div>
                <button type="submit" class="btn btn-primary">Guardar</button>
                <button type="reset" class="btn btn-danger" id="volver">Volver</button>
            </div>
        </form>
    </section>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="/proyectoCVM/assets/js/ajaxReportesSalaControl.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <script type="text/javascript">
        function calcular1(){
            try{
                var a = parseInt(document.getElementById("molino1").value) || 0

                document.getElementById("molino1P").value = 60 - a;
            } catch(e){}
        }

        function calcular2(){
            try{
                var a = parseInt(document.getElementById("molino2").value) || 0

                document.getElementById("molino2P").value = 60 - a;
            } catch(e){}
        }

        function calcular3(){
            try{
                var a = parseInt(document.getElementById("molino3").value) || 0

                document.getElementById("molino3P").value = 60 - a;
            } catch(e){}
        }

        $('#fecha_registro').change(function() {
            var fecha = $(this).val();
            cargarDatosYGraficar(fecha, 'Toneladas Procesadas', 'chart_div');
        });

        document.getElementById('pdf-link').addEventListener('click', function(e) {
            e.preventDefault();
            const filtro_fecha = document.getElementById('filtro_fecha').value;
            
            // Construye la URL con parámetros
            let url = '/views/reportes/ReporteControlAlimentacion.php';
            const params = new URLSearchParams();
            
            if(filtro_fecha) params.append('filtro_fecha', filtro_fecha);
            
            if(params.toString()) {
                url += '?' + params.toString();
            }

            console.log("URL generada: ", url);
            
            window.open(url, '_blank');
        });

    </script>

  </body>
</html>