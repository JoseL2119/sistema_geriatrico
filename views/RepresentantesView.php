<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Representantes</title>
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
                Listado de representantes
            </h5>

            <button type="button" class="btn btn-primary btn-app shadow-sm" id="nuevo">
                <i class="fas fa-plus-circle me-2"></i>Nuevo representante
            </button>
        </div>

        <hr class="page-divider">

        <!-- <div class="filter-bar">
            <div class="filter-field">
                <label for="filtro_fecha">Filtrar por fecha</label>
                <input type="date" class="form-control form-control-sm" name="" id="filtro_fecha">
            </div>
        </div>

        <button id="pdf-link" class="btn btn-pdf btn-app mb-3">
            <i class="fas fa-file-pdf me-2"></i>Generar PDF
        </button> -->

        <div class="container-fluid px-0">  <!-- Contenedor fluido sin padding horizontal -->
            <div class="table-responsive rounded-3 shadow-sm" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                <table class="table table-bordered m-0" style="font-size: 0.88rem; width: 100%; min-width: 100%;">
                    <thead>
                        <!-- Fila de encabezado principal -->
                        <tr class="text-center">
                            <th colspan="3">Cedula</th>
                            <th colspan="3">Nombres y apellidos</th>
                            <th colspan="2">Telefono</th>  
                            <th colspan="2">Fecha nacimiento</th>
                            <th colspan="2">Fecha de pago</th>
                            <th colspan="2">Opciones</th>
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
        <h5>Formulario para registrar a un nuevo representante</h5>
        <hr />
        <form id="formulario">
            <input type="hidden" id="id" />

            <div class="mb-3">
                <label for="cedula" class="form-label">Cédula</label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="cedula" 
                    value=""
                    required
                />
            </div>

            <div class="mb-3">
                <label for="nombres" class="form-label">Nombres</label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="nombres" 
                    value=""
                    required
                />
            </div>

            <div class="mb-3">
                <label for="apellidos" class="form-label">Apellidos</label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="apellidos" 
                    value=""
                    required
                />
            </div>

            <div class="mb-3">
                <label for="tlf" class="form-label">Teléfono</label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="tlf" 
                    value=""
                    required
                />
            </div>

            <div class="mb-3">
                <label for="fecha" class="form-label">Fecha de nacimiento</label>
                <input 
                    type="date" 
                    class="form-control" 
                    id="fecha" 
                    value=""
                    required
                />
            </div>

            <div class="mb-3">
                <label for="domicilio" class="form-label">Domicilio</label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="domicilio" 
                    value=""
                    required
                />
            </div>

            <div class="mb-3">
                <label for="fechaPago" class="form-label">Fecha de pago (día)</label>
                <input 
                    type="number" 
                    class="form-control" 
                    id="fechaPago" 
                    value=""
                    required
                />
            </div>

            <div class="mb-3">
                <label for="familiar_alt" class="form-label">Nombre de familiar alternativo (opcional)</label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="familiar_alt" 
                    value=""
                />
            </div>

            <div class="mb-3">
                <label for="telefono_alt" class="form-label">Teléfono de familiar alternativo (opcional)</label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="telefono_alt" 
                    value=""
                />
            </div>

            <div class="mb-3">
                <label for="observaciones" class="form-label">Observaciones</label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="observaciones" 
                    value=""
                />
            </div>

            <hr />


            
            <div>
                <button type="submit" class="btn btn-primary">Guardar</button>
                <button type="reset" class="btn btn-danger" id="volver">Volver</button>
            </div>
        </form>
    </section>

    <section>
        <div class="modal fade" id="modalFichaRepresentante" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">
                            Ficha del representante
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
                                <strong>Nombres:</strong>
                                <p id="ficha_nombres">-</p>
                            </div>

                            <div class="col-md-4">
                                <strong>Apellidos:</strong>
                                <p id="ficha_apellidos">-</p>
                            </div>

                            <div class="col-md-4">
                                <strong>Fecha de nacimiento:</strong>
                                <p id="ficha_fecha_nacimiento">-</p>
                            </div>

                            <div class="col-md-4">
                                <strong>Domicilio:</strong>
                                <p id="ficha_domicilio">-</p>
                            </div>

                        </div>


                        <!-- INFORMACIÓN ADMINISTRATIVA -->
                        <h5 class="section-title border-bottom pb-2">
                            Información administrativa
                        </h5>

                        <div class="row mb-3">

                            <div class="col-md-4">
                                <strong>Fecha de Pago:</strong>
                                <p id="ficha_fecha_pago">-</p>
                            </div>

                            <div class="col-md-4">
                                <strong>Familiar alternativo:</strong>
                                <p id="ficha_familiar_alt">-</p>
                            </div>

                            <div class="col-md-4">
                                <strong>Teléfono familiar alternativo:</strong>
                                <p id="ficha_telefono_alt">-</p>
                            </div>

                        </div>

                        <!-- OBSERVACIONES -->
                        <h5 class="section-title border-bottom pb-2">
                            Observaciones
                        </h5>

                        <div class="mb-3">
                            <p id="ficha_observaciones">-</p>
                        </div>

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
    <script src="../assets/js/ajaxRepresentantes.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.all.min.js"></script>

  </body>
</html>