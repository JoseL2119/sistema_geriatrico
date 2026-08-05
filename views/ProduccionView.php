<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reporte de Producción</title>
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
                                <th>Turno</th>
                                <th>Supervisor</th>
                                <th>Grupo</th>
                                <th colspan="2">Opciones</th>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                            <!-- Las filas se generarán dinámicamente aquí -->
                        </tbody>
                        <tbody id="tencabezado">
                            <!-- Las filas se generarán dinámicamente aquí -->
                        </tbody>
                        <tbody id="thorasparada">

                        </tbody>
                        <tbody id="tnovedades">

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
                <input type="hidden" id="horas_json">
                <input type="hidden" id="observaciones_json">
                
                <div class="row g-2 mb-4 align-items-end">
                    <div class="col">
                        <label class="form-label">Supervisor</label>
                        <select class="form-select" id="supervisor_id" required>
                            <option value="">Seleccionar...</option>
                        </select>
                    </div>
                    <div class="col">
                        <label class="form-label">Grupo</label>
                        <input type="number" class="form-control" id="grupo" required>
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

                <h5>Tolvas</h5>

                <div class="row g-2 mb-4 align-items-end"> 
                    <div class="col">
                        <label class="form-label">Toneladas recibidas Tolva 1</label>
                        <input type="number" class="form-control" id="tr1" required>
                    </div>
                    <div class="col">
                        <label class="form-label">Toneladas recibidas Tolva 2</label>
                        <input type="number" class="form-control" id="tr2" required>
                    </div>
                    <div class="col">
                        <label class="form-label">Toneladas Entregadas Tolva 1</label>
                        <input type="number" class="form-control" id="te1" required>
                    </div>
                    <div class="col">
                        <label class="form-label">Toneladas Entregadas Tolva 2</label>
                        <input type="number" class="form-control" id="te2" required>
                    </div>
                </div>

                <h5>Palas Alimentadas</h5>

                <div class="row g-2 mb-4 align-items-end"> 
                    <div class="col">
                        <label class="form-label">Palas Tolva 1</label>
                        <input type="number" class="form-control" id="pt1" required>
                    </div>
                    <div class="col">
                        <label class="form-label">Palas Tolva 2</label>
                        <input type="number" class="form-control" id="pt2" required>
                    </div>
                    <div class="col">
                        <label class="form-label">Payloader Tolva 1</label>
                        <select class="form-select" id="maquina1" required>
                            <option value="">Seleccionar...</option>
                        </select>
                    </div>
                    <div class="col">
                        <label class="form-label">Payloader Tolva 2</label>
                        <select class="form-select" id="maquina2" required>
                            <option value="">Seleccionar...</option>
                        </select>
                    </div>
                    <div class="col">
                        <label class="form-label">Código Arena Tolva 1</label>
                        <input type="text" class="form-control" id="ca1" required>
                    </div>
                    <div class="col">
                        <label class="form-label">Código Arena Tolva 2</label>
                        <input type="text" class="form-control" id="ca2" required>
                    </div>
                </div>

                <!-- Sección de observaciones por hora -->
                <div class="mb-4 border p-3 rounded">
                    <h6 class="mb-3">Registro de Horas de Parada</h6>
                    <div id="listaHoras" class="mb-3">
                        <!-- Observaciones se agregarán aquí -->
                    </div>
                    <button type="button" class="btn btn-sm btn-success" id="agregarHora">
                        <i class="fas fa-plus me-1"></i> Agregar Registro
                    </button>
                </div>

                <!-- Sección de observaciones por hora -->
                <div class="mb-4 border p-3 rounded">
                    <h6 class="mb-3">Registro de Novedades</h6>
                    <div id="listaNovedades" class="mb-3">
                        <!-- Observaciones se agregarán aquí -->
                    </div>
                    <button type="button" class="btn btn-sm btn-success" id="agregarNovedad">
                        <i class="fas fa-plus me-1"></i> Agregar Novedad
                    </button>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-primary" id="guardarReporte">
                        <i class="fas fa-check-circle me-1"></i> Guardar Reporte
                    </button>
                    <button type="button" class="btn btn-danger" id="volver">
                        <i class="fas fa-times-circle me-1"></i> Volver
                    </button>
                </div>
            </form>
        </section>
    </div>

    <!-- Modal para agregar horas de parada -->
<div class="modal fade" id="modalHorasParada" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Registro de Horas de Parada</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Tabla de horarios -->
                <div class="table-responsive mb-4">
                    <table class="table table-bordered table-sm align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="20%">Hora</th>
                                <th width="26%">MOBO 1</th>
                                <th width="26%">MOBO 2</th>
                                <th width="26%">MOBO 3</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="fw-semibold">Parada</td>
                                <td><input type="time" class="form-control form-control-sm" id="horaParada1" step="60"></td>
                                <td><input type="time" class="form-control form-control-sm" id="horaParada2" step="60"></td>
                                <td><input type="time" class="form-control form-control-sm" id="horaParada3" step="60"></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Inicio</td>
                                <td><input type="time" class="form-control form-control-sm" id="horaInicio1" step="60"></td>
                                <td><input type="time" class="form-control form-control-sm" id="horaInicio2" step="60"></td>
                                <td><input type="time" class="form-control form-control-sm" id="horaInicio3" step="60"></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Total</td>
                                <td><input type="number" class="form-control form-control-sm" id="totalParado1" readonly></td>
                                <td><input type="number" class="form-control form-control-sm" id="totalParado2" readonly></td>
                                <td><input type="number" class="form-control form-control-sm" id="totalParado3" readonly></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Motivo de parada -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Motivo de Parada</label>
                    <textarea class="form-control" id="textoMotivo" rows="3" placeholder="Describa el motivo de la parada..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancelar
                </button>
                <button type="button" class="btn btn-primary" id="guardarHoraParada">
                    <i class="fas fa-save me-2"></i>Guardar Registro
                </button>
            </div>
        </div>
    </div>
</div>

    <!-- Modal para agregar novedades -->
    <div class="modal fade" id="modalNovedades" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nueva Novedad</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Novedad</label>
                        <textarea class="form-control" id="textoNovedad" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="guardarNovedad">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="../assets/js/ajaxProduccion.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <script type="text/javascript">

        function calcularTiempoParada(horaParada, horaInicio) {
            if (!horaParada || !horaInicio) return null;
            
            // Convertir ambas horas a minutos desde medianoche
            const [hP, mP] = horaParada.split(':').map(Number);
            const [hI, mI] = horaInicio.split(':').map(Number);
            
            const minutosParada = hP * 60 + mP;
            const minutosInicio = hI * 60 + mI;
            
            // Calcular diferencia (maneja el caso de parada que cruza medianoche)
            let diferencia = minutosInicio - minutosParada;
            if (diferencia < 0) {
                diferencia += 1440; // 24 horas en minutos
            }
            
            return diferencia;
        }

        function configurarCalculoAutomatico(moboNum) {
            $(`#horaParada${moboNum}, #horaInicio${moboNum}`).on('change', function() {
                const horaParada = $(`#horaParada${moboNum}`).val();
                const horaInicio = $(`#horaInicio${moboNum}`).val();
                
                const minutos = calcularTiempoParada(horaParada, horaInicio);
                $(`#totalParado${moboNum}`).val(minutos !== null ? minutos : '');
            });
        }

        // Configurar para los 3 MOBOs
        $(document).ready(function() {
            configurarCalculoAutomatico(1);
            configurarCalculoAutomatico(2);
            configurarCalculoAutomatico(3);
        });

        document.getElementById('pdf-link').addEventListener('click', function(e) {
            e.preventDefault();
            const filtro_fecha = document.getElementById('filtro_fecha').value;
            const filtro_turno = document.getElementById('filtro_turno').value;
            
            // Construye la URL con parámetros
            let url = '/views/reportes/ReporteProduccion.php';
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