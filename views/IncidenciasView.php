<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reporte de Incidencias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .table thead th {
            white-space: nowrap;
            vertical-align: middle;
            border: 1px solid #a5d6a7;
            font-weight: 600;
            padding: 8px 4px;
        }
        
        .table tbody td {
            vertical-align: middle;
            border: 1px solid #e0e0e0;
            padding: 6px 4px;
        }
        
        .table-bordered {
            border: 2px solid #4db6ac;
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(200, 230, 201, 0.5);
        }
        
        .container-fluid {
            max-width: 99vw;
            padding-right: 5px;
            padding-left: 5px;
        }
        
        .table-responsive {
            overflow-x: auto;
            padding-bottom: 2px;
        }
        
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

        #lista h5 {
            color: #2e7d32;
            font-size: 1.25rem;
        }

        .observacion-item {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php include "fragments/navbarLanding.php" ?>

        <section id="lista">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0 fw-bold" id="titulo-seccion">
                    <i class="fas fa-clipboard-list me-2"></i>
                </h5>
                <button type="button" class="btn btn-primary shadow-sm" id="nuevo">
                    <i class="fas fa-plus-circle me-2"></i>Nuevo Reporte
                </button>
            </div>
            <hr class="border-2 border-top border-primary opacity-25 mb-4">
            
            <div style="display: flex; justify-content: center; gap: 20px; align-items: center; margin: 20px 0; background: #f5f5f5; padding: 15px; border-radius: 8px;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <label for="filtro_fecha" class="form-label" style="font-weight: 600; color: #333;">Fecha:</label>
                    <input type="date" id="filtro_fecha" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px; font-family: inherit;">
                </div>

                <div style="display: flex; align-items: center; gap: 8px;">
                    <label for="filtro_turno" class="form-label" style="font-weight: 600; color: #333;">Turno:</label>
                    <select id="filtro_turno" style="padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px; font-family: inherit; min-width: 120px;">
                        <option value="">Seleccionar</option>
                        <option value="1">Diurno</option>
                        <option value="2">Nocturno</option>
                    </select>
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
                                <th>Fecha</th>
                                <th>Supervisor</th>
                                <th>Operador</th>
                                <th>Grupo</th>
                                <th>Turno</th>
                                <th colspan="3">Opciones</th>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                            <!-- Las filas se generarán dinámicamente aquí -->
                        </tbody>
                        <tbody id="tobservaciones">

                        </tbody>
                        <tbody id="tfooter">

                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section id="form" class="d-none">
            <h5>Formulario de Reporte de Incidencias</h5>
            <hr />
            <form id="formulario">
                <input type="hidden" id="id" value="0">
                <input type="hidden" id="observaciones_json">
                
                <div class="row g-2 mb-4 align-items-end">
                    <div class="col">
                        <label class="form-label">Supervisor</label>
                        <select class="form-select" id="supervisor_id" required>
                            <option value="">Seleccionar...</option>
                        </select>
                    </div>
                    <div class="col">
                        <label class="form-label">Operador</label>
                        <select class="form-select" id="operador_id" required>
                            <option value="">Seleccionar...</option>
                        </select>
                    </div>
                    <div class="col">
                        <label class="form-label">Grupo</label>
                        <input type="text" class="form-control" id="grupo" required>
                    </div>
                    <div class="col">
                        <label class="form-label">Fecha</label>
                        <input type="date" class="form-control" id="fecha" required>
                    </div>
                    <div class="col">
                        <label class="form-label">Turno</label>
                        <select class="form-select" id="turno" required>
                            <option value="">Seleccionar...</option>
                        </select>
                    </div>
                </div>

                <!-- Sección de observaciones por hora -->
                <div class="mb-4 border p-3 rounded">
                    <h6 class="mb-3">Registro de Observaciones por Hora</h6>
                    <div id="listaObservaciones" class="mb-3">
                        <!-- Observaciones se agregarán aquí -->
                    </div>
                    <button type="button" class="btn btn-sm btn-success" id="agregarObservacion">
                        <i class="fas fa-plus me-1"></i> Agregar Observación
                    </button>
                </div>

                <h5>Almacenamiento Tolvas</h5> 
                <div class="row g-2 mb-4 align-items-end"> 
                    <div class="col">
                        <label class="form-label">TV1</label>
                        <input type="number" class="form-control" id="tv1" required>
                    </div>
                    <div class="col">
                        <label class="form-label">TV2</label>
                        <input type="number" class="form-control" id="tv2" required>
                    </div>
                    <div class="col">
                        <label class="form-label">Palas Procesadas 1</label>
                        <input type="number" class="form-control" id="pp1" required oninput="calcularTotales()">
                    </div>
                    <div class="col">
                        <label class="form-label">Palas Procesadas 2</label>
                        <input type="number" class="form-control" id="pp2" required oninput="calcularTotales()">
                    </div>
                    <div class="col">
                        <label class="form-label">Payloaders</label>
                        <select class="form-select" id="maquina" required>
                            <option value="">Seleccionar...</option>
                        </select>
                    </div>
                    <div class="col">
                        <label class="form-label">Palas Totales</label>
                        <input type="number" class="form-control" id="ptp" required>
                    </div>
                </div>

                <h5>Cantidad de Cal Agregada</h5> 
                <div class="row g-2 mb-4 align-items-end"> 
                    <div class="col">
                        <label class="form-label">Recepción</label>
                        <input type="number" class="form-control" id="recepcion" required oninput="calcularSigTurno()">
                    </div>
                    <div class="col">
                        <label class="form-label">Solicitada en turno</label>
                        <input type="number" class="form-control" id="solicitados" step="0.01" required oninput="calcularSigTurno()">
                    </div>
                    <div class="col">
                        <label class="form-label">Usada en Turno</label>
                        <input type="number" class="form-control" id="usados" required oninput="calcularSigTurno()">
                    </div>
                    <div class="col">
                        <label class="form-label">Disponible siguiente turno</label>
                        <input type="number" class="form-control" id="disponibles" step="0.01" required>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-primary" id="finalizarReporte">
                        <i class="fas fa-check-circle me-1"></i> Guardar Reporte
                    </button>
                    <button type="button" class="btn btn-danger" id="volver">
                        <i class="fas fa-times-circle me-1"></i> Volver
                    </button>
                </div>
            </form>
        </section>
    </div>

    <!-- Modal para agregar observación -->
    <div class="modal fade" id="modalObservacion" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nueva Observación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Hora</label>
                        <input type="time" class="form-control" id="horaObservacion" step="60">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Observación</label>
                        <textarea class="form-control" id="textoObservacion" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="guardarObservacion">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="../assets/js/ajaxIncidencias.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <script type="text/javascript">
        function calcularTotales(){
            var palasProcesadas1 = parseInt(document.getElementById("pp1").value) || 0;
            var palasProcesadas2 = parseInt(document.getElementById("pp2").value) || 0;
            
            document.getElementById("ptp").value = palasProcesadas1 + palasProcesadas2;
        }
        
        function calcularSigTurno(){
            var recibidos = parseFloat(document.getElementById("recepcion").value) || 0;
            var usados = parseFloat(document.getElementById("usados").value) || 0;
            var solicitados = parseFloat(document.getElementById("solicitados").value) || 0;
            
            document.getElementById("disponibles").value = ((recibidos + solicitados) - usados);


        }

        document.getElementById('pdf-link').addEventListener('click', function(e) {
            e.preventDefault();
            const filtro_fecha = document.getElementById('filtro_fecha').value;
            const filtro_turno = document.getElementById('filtro_turno').value;
            
            // Construye la URL con parámetros
            let url = '/views/reportes/ReporteIncidencias.php';
            const params = new URLSearchParams();
            
            if(filtro_fecha) params.append('filtro_fecha', filtro_fecha);
            if(filtro_turno) params.append('filtro_turno', filtro_turno);
            
            if(params.toString()) {
                url += '?' + params.toString();
            }

            console.log("URL generada: ", url);
            
            window.open(url, '_blank');
        });

    </script>

</body>
</html>