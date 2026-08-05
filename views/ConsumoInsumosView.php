<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Consumo Diario de Insumos Críticos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                <h5 id="titulo-seccion">
                    <i class="fas fa-clipboard-list page-icon"></i>
                </h5>
                <button type="button" class="btn btn-primary btn-app shadow-sm" id="nuevo">
                    <i class="fas fa-plus-circle me-2"></i>Nuevo Reporte
                </button>
            </div>
            <hr class="page-divider">

            <div class="filter-bar">
                <div class="filter-field">
                    <label for="filtro_fecha">Fecha:</label>
                    <input type="date" class="form-control form-control-sm" id="filtro_fecha">
                </div>
            </div>

            <button id="pdf-link" class="btn btn-pdf btn-app mb-3">
                <i class="fas fa-file-pdf me-2"></i>Generar PDF
            </button>

            <div class="container-fluid px-0">
                <div class="table-responsive rounded-3 shadow-sm">
                    <table class="table table-bordered table-hover m-0" style="font-size: 0.88rem; width: 100%; min-width: 100%;">
                        <thead class="text-center">
                            <tr>
                                <th colspan="5">Fecha</th>
                                <th colspan="5" id="campoFecha"> </th>
                            </tr>
                            <tr>
                                <th>Insumo</th>
                                <th>Unidad</th>
                                <th>Departamento</th>
                                <th>Stock Inicial</th>
                                <th>Recepción</th>
                                <th>Retiro</th>
                                <th>Stock Final</th>
                                <th>Observaciones</th>
                                <th colspan="2">Opciones</th>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                            <!-- Las filas se generarán dinámicamente aquí -->
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section id="form" class="d-none">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-clipboard me-2"></i>Formulario de Reporte de Incidencias</h5>
                </div>
                <div class="card-body">
                    <form id="formReporte">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="fecha" class="form-label">Fecha</label>
                                <input type="date" id="fecha" name="fecha" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="form-section">
                            <h6 class="mb-3">Detalle de Insumos</h6>
                            <div class="table-responsive">
                                <table id="tablaInsumos" class="table table-bordered table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Insumo</th>
                                            <th>Stock Inicial</th>
                                            <th>Cantidad Recibida</th>
                                            <th>Cantidad Retirada</th>
                                            <th>Stock Final</th>
                                            <th>Observación</th>
                                            <th>Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Fila inicial -->
                                        <tr class="fila-insumo">
                                            <td>
                                                <input type="hidden" class="id" value="0">
                                                <select class="form-control form-control-sm insumo_id" name="insumos[0][insumo_id]" required>
                                                    <option value="">Seleccionar...</option>
                                                    <!-- Opciones de insumos se llenarán dinámicamente -->
                                                </select>
                                            </td>
                                            <td><input class="form-control form-control-sm stock_inicial" type="number" name="insumos[0][stock_inicial]" required></td>
                                            <td><input class="form-control form-control-sm cantidad_recibida" type="number" name="insumos[0][cantidad_recibida]" required></td>
                                            <td><input class="form-control form-control-sm cantidad_retirada" type="number" name="insumos[0][cantidad_retirada]" required></td>
                                            <td><input class="form-control form-control-sm stock_final" type="number" name="insumos[0][stock_final]" required></td>
                                            <td><input class="form-control form-control-sm observacion" type="text" name="insumos[0][observacion]"></td>
                                            <td class="text-center"><button type="button" class="btn btn-sm btn-danger eliminarFila"><i class="fas fa-times"></i></button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="mt-2">
                                <button type="button" class="btn btn-sm btn-primary" id="agregarInsumo" onclick="agregarFila()">
                                    <i class="fas fa-plus me-1"></i> Agregar Insumo
                                </button>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-primary" id="guardarReporte">
                                <i class="fas fa-check-circle me-1"></i> Guardar Reporte
                            </button>
                            <button type="button" class="btn btn-danger" id="volver">
                                <i class="fas fa-times-circle me-1"></i> Volver
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="../assets/js/ajaxConsumoInsumos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.all.min.js"></script>

    <script type="text/javascript">

        document.getElementById('pdf-link').addEventListener('click', function(e) {
            e.preventDefault();
            const filtro_fecha = document.getElementById('filtro_fecha').value;
            
            // Construye la URL con parámetros
            let url = '/views/reportes/ReporteProduccion.php';
            const params = new URLSearchParams();
            
            if(filtro_fecha) params.append('filtro_fecha', filtro_fecha);
            
            if(params.toString()) {
                url += '?' + params.toString();
            }

            console.log("URL generada: ", url);
            
            window.open(url, '_blank');
        });

        // Añadir filas dinámicamente
        let contadorFilas = 1; // Inicia en 1 porque ya hay una fila

        let insumosCriticos = []; // Variable global para almacenar los insumos
        

        function agregarFila(insumoId = null, esAutomatico = false) {
            const tbody = $("#tablaInsumos tbody");
            const contador = tbody.find("tr").length;
            
            const nuevaFila = `
            <tr class="fila-insumo">
                <td>
                    <input type="hidden" class="id" value="0">
                    <select class="form-control form-control-sm insumo_id" name="insumos[${contador}][insumo_id]" required>
                        <option value="">Seleccionar...</option>
                        ${insumosCriticos.map(insumo => 
                            `<option value="${insumo.id}" ${insumoId === insumo.id ? 'selected' : ''}>
                                ${insumo.nombre} (${insumo.unidad})
                            </option>`
                        ).join('')}
                    </select>
                </td>
                <td><input class="form-control form-control-sm stock_inicial" type="number" name="insumos[${contador}][stock_inicial]" required></td>
                <td><input class="form-control form-control-sm cantidad_recibida" type="number" name="insumos[${contador}][cantidad_recibida]" required></td>
                <td><input class="form-control form-control-sm cantidad_retirada" type="number" name="insumos[${contador}][cantidad_retirada]" required></td>
                <td><input class="form-control form-control-sm stock_final" type="number" name="insumos[${contador}][stock_final]" required></td>
                <td><input class="form-control form-control-sm observacion" type="text" name="insumos[${contador}][observacion]"></td>
                <td class="text-center">${esAutomatico ? '' : '<button type="button" class="btn btn-sm btn-danger eliminarFila"><i class="fas fa-times"></i></button>'}</td>
            </tr>`;
            
            tbody.append(nuevaFila);
        }

        function eliminarFila(boton) {
            const fila = boton.closest("tr");
            fila.remove();
            // Opcional: Reindexar los nombres de los campos si es necesario
        }

        // Delegación de eventos para los botones eliminar
        $(document).on('click', '.eliminarFila', function() {
            eliminarFila(this);
        });


    </script>

</body>
</html>