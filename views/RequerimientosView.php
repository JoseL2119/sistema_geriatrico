<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Requerimientos</title>
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
            <h5 id="titulo-seccion">
                <i class="fas fa-clipboard-list page-icon"></i>
                Requerimientos de artículos por Residente
            </h5>

            <button type="button" class="btn btn-primary btn-app shadow-sm" id="nuevo">
                <i class="fas fa-plus-circle me-2"></i>Registrar Nuevo Requerimiento
            </button>
        </div>

        <hr class="page-divider">

        <div class="filter-bar">
            <div class="filter-field">
                <label for="filtro_fecha">Filtrar por fecha</label>
                <input type="date" class="form-control form-control-sm" name="" id="filtro_fecha">
            </div>
        </div>

        <button id="pdf-link" class="btn btn-pdf btn-app mb-3">
            <i class="fas fa-file-pdf me-2"></i>Generar PDF
        </button>

        <div class="container-fluid px-0">  <!-- Contenedor fluido sin padding horizontal -->
            <div class="table-responsive rounded-3 shadow-sm" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                <table class="table table-bordered m-0" style="font-size: 0.88rem; width: 100%; min-width: 100%;">
                    <thead>
                        <!-- Fila de encabezado principal -->
                        <tr class="text-center">
                            <th colspan="1">#</th>
                            <th colspan="3">Residente</th>
                            <th colspan="3">Opciones</th>
                        </tr>
                    </thead>
                    <tbody id="tbody" class="table-group-divider">
                        <!-- Las filas se generarán dinámicamente aquí -->
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <section id="form" class="d-none">
        <h5>Formulario para registrar la lista de artículos requeridos</h5>
        <hr />
        <form id="formulario">
            <input type="hidden" id="id" />

            <div class="mb-3">
                <label for="residente" class="form-label">Residente</label>

                <select class="form-select" id="residente">
                    <option selected>Seleccionar Residente</option>
                </select>
            </div>


            <table class="table table-bordered m-0" style="font-size: 0.88rem; width: 100%; min-width: 100%;">
                <thead>
                    <!-- Fila de encabezado principal -->
                    <tr class="text-center">
                        <th colspan="3">Artículo</th>
                        <th colspan="2">Cantidad</th>  
                        <th colspan="2">Frecuencia (meses)</th>  
                        <th colspan="2">Fecha Inicio</th>
                        <th colspan="2">Fecha Fin</th>
                        <th colspan="2">Observaciones</th>
                        <th colspan="2">Acción</th>
                    </tr>
                </thead>
                <tbody id="tbodyArticulos" class="table-group-divider">
                    <!-- Las filas se generarán dinámicamente aquí -->
                </tbody>
            </table>

            <hr>
  
            <div>
                <button type="submit" class="btn btn-primary">Guardar</button>
                <button type="reset" class="btn btn-danger" id="volver">Volver</button>
            </div>
        </form>
    </section>

    <section>
        <div class="modal fade" id="modalRequerimientosResidente" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">
                            Ficha de Requerimientos del residente
                        </h5>

                        <button type="button" 
                                class="btn-close" 
                                data-bs-dismiss="modal" 
                                aria-label="Cerrar">
                        </button>
                    </div>

                    <div class="modal-body">

                        <!-- DATOS PERSONALES -->
                        <h5 class="section-title border-bottom pb-2">
                            Datos personales
                        </h5>

                        <div class="row mb-3">

                            <div class="col-md-4">
                                <strong>Cédula:</strong>
                                <p id="ficha_cedula">-</p>
                            </div>

                            <div class="col-md-4">
                                <strong>Nombres y Apellidos:</strong>
                                <p id="ficha_nombres">-</p>
                            </div>

                        </div>


                        <!-- INGRESO -->
                        <h5 class="section-title border-bottom pb-2">
                            Listado de Artículos
                        </h5>

                        <table class="table table-bordered m-0" style="font-size: 0.88rem; width: 100%; min-width: 100%;">
                            <thead>
                                <!-- Fila de encabezado principal -->
                                <tr class="text-center">
                                    <th colspan="3">Artículo</th>
                                    <th colspan="2">Cantidad</th>  
                                    <th colspan="2">Frecuencia (meses)</th>  
                                    <th colspan="2">Fecha Inicio</th>
                                    <th colspan="2">Fecha Fin</th>
                                    <th colspan="2">Observaciones</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyModalArticulos" class="table-group-divider">
                                <!-- Las filas se generarán dinámicamente aquí -->
                            </tbody>
                        </table>

                    </div>

                    <div class="modal-footer">

                        <button type="button" 
                                class="btn btn-secondary" 
                                data-bs-dismiss="modal">
                            Cerrar
                        </button>

                    </div>

                </div>
            </div>
        </div>
    </section>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="../assets/js/ajaxRequerimientos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.all.min.js"></script>

  </body>
</html>