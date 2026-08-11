<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Residentes</title>
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
                Listado de residentes
            </h5>

            <div class="page-header-actions">
                <button id="pdf-link" class="btn btn-pdf btn-app">
                    <i class="fas fa-file-pdf me-2"></i>Descargar datos
                </button>

                <button type="button" class="btn btn-primary btn-app shadow-sm" id="nuevo">
                    <i class="fas fa-plus-circle me-2"></i>Nuevo residente
                </button>
            </div>
        </div>

        <hr class="page-divider">

        <div class="filter-bar">
            <div class="filter-field">
                <label for="fecha_inicio_filtro">Fecha Inicial:</label>
                <input type="date" class="form-control form-control-sm" id="fecha_inicio_filtro">
            </div>

            <div class="filter-field">
                <label for="fecha_fin_filtro">Fecha Final:</label>
                <input type="date" class="form-control form-control-sm" id="fecha_fin_filtro">
            </div>
        </div>

        <div class="container-fluid px-0">  <!-- Contenedor fluido sin padding horizontal -->
            <div class="table-responsive rounded-3 shadow-sm" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                <table class="table table-bordered m-0" style="font-size: 0.88rem; width: 100%; min-width: 100%;">
                    <thead>
                        <!-- Fila de encabezado principal -->
                        <tr class="text-center">
                            <th colspan="3">Cédula</th>
                            <th colspan="3">Nombres y apellidos</th>
                            <th colspan="2">Fecha nacimiento</th>  
                            <th colspan="2">Fecha ingreso</th>
                            <th colspan="2">Status</th>
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
        <h5>Formulario para registrar a un nuevo residente</h5>
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
                <label for="fecha_ingreso" class="form-label">Fecha de ingreso</label>
                <input 
                    type="date" 
                    class="form-control" 
                    id="fecha_ingreso" 
                    value=""
                    required
                />
            </div>

            <div class="mb-3">
                <label for="fecha_egreso" class="form-label">Fecha de egreso</label>
                <input 
                    type="date" 
                    class="form-control" 
                    id="fecha_egreso" 
                    value=""
                />
            </div>

            <div class="mb-3">
                <label for="genero" class="form-label">Género</label>
                <select class="form-select" id="genero">
                    <option selected>Seleccionar opción</option>
                    <option value="1">Masculino</option>
                    <option value="2">Femenino</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="peso" class="form-label">Peso (kg)</label>
                <input 
                    type="number" 
                    step="0.1"
                    class="form-control" 
                    id="peso" 
                    value=""
                    required
                />
            </div>

            <div class="mb-3">
                <label for="altura" class="form-label">Altura (m)</label>
                <input 
                    type="number" 
                    step="0.01"
                    class="form-control" 
                    id="altura" 
                    value=""
                    required
                />
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status del residente</label>
                <select class="form-select" id="status">
                    <option selected>Seleccionar status</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="ivss" class="form-label">¿Posee convenio con el IVSS?</label>
                <select class="form-select" id="ivss">
                    <option selected>Seleccionar opción</option>
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="pensionado" class="form-label">¿Es pensionado?</label>
                <select class="form-select" id="pensionado">
                    <option selected>Seleccionar opción</option>
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="privado" class="form-label">¿Es caso privado?</label>
                <select class="form-select" id="privado">
                    <option selected>Seleccionar opción</option>
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="contencion_f" class="form-label">¿Tiene contención familiar?</label>
                <select class="form-select" id="contencion_f">
                    <option selected>Seleccionar opción</option>
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="vulnerabilidad_f" class="form-label">¿Tiene vulnerabilidad familiar?</label>
                <select class="form-select" id="vulnerabilidad_f">
                    <option selected>Seleccionar opción</option>
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="apadrinazgo" class="form-label">¿Tiene apadrinazgo?</label>
                <select class="form-select" id="apadrinazgo">
                    <option selected>Seleccionar opción</option>
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="psiquiatrico" class="form-label">¿Es paciente psiquiátrico?</label>
                <select class="form-select" id="psiquiatrico">
                    <option selected>Seleccionar opción</option>
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="diagnostico" class="form-label">Diagnóstico</label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="diagnostico" 
                    value=""
                />
            </div>

            <div class="mb-3">
                <label for="condicion" class="form-label">Condición de movilidad</label>
                <select class="form-select" id="condicion">
                    <option selected>Seleccionar opción</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="control_esfinteres" class="form-label">¿Controla sus esfínteres?</label>
                <select class="form-select" id="control_esfinteres">
                    <option selected>Seleccionar opción</option>
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="centro_medico" class="form-label">Centro médico de evaluación</label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="centro_medico" 
                    value=""
                />
            </div>

            <div class="mb-3">
                <label for="medico" class="form-label">Médico tratante</label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="medico" 
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

            <hr id="divisor_tarifa"/>

            <h5 id="titulo_tarifa">Tarifa</h5>

            <div class="mb-3">
                <label for="tarifa" id="label_tarifa" class="form-label">Tarifa a cobrar (USD)</label>
                <input 
                    type="number" 
                    step="0.01"
                    class="form-control" 
                    id="tarifa" 
                    value=""
                />
            </div>

            <div class="mb-3">
                <label for="observaciones_tarifa" id="label_observaciones_tarifa" class="form-label">Observaciones</label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="observaciones_tarifa" 
                    value=""
                />
            </div>

            <hr />

            <h5>Representante</h5>

            <div class="mb-3">
                <label for="parentesco" class="form-label">
                    Parentesco
                </label>

                <input 
                    type="text" 
                    class="form-control" 
                    id="parentesco" 
                    value=""
                />
            </div>

            <div class="mb-3">

                <label class="form-label" id="label_selector">
                    ¿El representante ya está registrado?
                </label>

                <div class="form-check">
                    <input 
                        class="form-check-input"
                        type="radio"
                        name="tipo_representante"
                        id="representante_existente"
                        value="existente"
                        checked
                    >

                    <label 
                        class="form-check-label"
                        for="representante_existente"
                        id="label_existente"
                    >
                        Seleccionar representante existente
                    </label>
                </div>

                <!-- OPCIÓN REPRESENTANTE NUEVO -->

                <div class="form-check">

                    <input 
                        class="form-check-input"
                        type="radio"
                        name="tipo_representante"
                        id="representante_nuevo"
                        value="nuevo"
                    >

                    <label 
                        class="form-check-label"
                        for="representante_nuevo"
                        id="label_nuevo"
                    >
                        Registrar nuevo representante
                    </label>

                </div>

                <!-- REPRESENTANTE EXISTENTE -->
                <div id="contenedor-representante-existente">

                    <div class="mb-3">
                        <label for="representante" class="form-label">
                            Representante responsable
                        </label>

                        <select class="form-select" id="representante">
                            <option selected>Seleccionar representante</option>
                        </select>
                    </div>

                </div>


                <!-- FORMULARIO REPRESENTANTE NUEVO -->

                <div id="contenedor-representante-nuevo" class="d-none">

                    <div class="mb-3">
                        <label for="cedula_representante" class="form-label">
                            Cédula
                        </label>

                        <input 
                            type="text" 
                            class="form-control" 
                            id="cedula_representante"
                        />
                    </div>


                    <div class="mb-3">
                        <label for="nombres_representante" class="form-label">
                            Nombres
                        </label>

                        <input 
                            type="text" 
                            class="form-control" 
                            id="nombres_representante"
                        />
                    </div>


                    <div class="mb-3">
                        <label for="apellidos_representante" class="form-label">
                            Apellidos
                        </label>

                        <input 
                            type="text" 
                            class="form-control" 
                            id="apellidos_representante"
                        />
                    </div>


                    <div class="mb-3">
                        <label for="tlf_representante" class="form-label">
                            Teléfono
                        </label>

                        <input 
                            type="text" 
                            class="form-control" 
                            id="tlf_representante"
                        />
                    </div>


                    <div class="mb-3">
                        <label for="fecha_representante" class="form-label">
                            Fecha de nacimiento
                        </label>

                        <input 
                            type="date" 
                            class="form-control" 
                            id="fecha_representante"
                        />
                    </div>


                    <div class="mb-3">
                        <label for="domicilio_representante" class="form-label">
                            Domicilio
                        </label>

                        <input 
                            type="text" 
                            class="form-control" 
                            id="domicilio_representante"
                        />
                    </div>


                    <div class="mb-3">
                        <label for="fechaPago_representante" class="form-label">
                            Fecha de pago (día)
                        </label>

                        <input 
                            type="number" 
                            class="form-control" 
                            id="fechaPago_representante"
                        />
                    </div>


                    <div class="mb-3">
                        <label for="familiar_alt_representante" class="form-label">
                            Nombre de familiar alternativo (opcional)
                        </label>

                        <input 
                            type="text" 
                            class="form-control" 
                            id="familiar_alt_representante"
                        />
                    </div>


                    <div class="mb-3">
                        <label for="telefono_alt_representante" class="form-label">
                            Teléfono de familiar alternativo (opcional)
                        </label>

                        <input 
                            type="text" 
                            class="form-control" 
                            id="telefono_alt_representante"
                        />
                    </div>


                    <div class="mb-3">
                        <label for="observaciones_representante" class="form-label">
                            Observaciones
                        </label>

                        <input 
                            type="text" 
                            class="form-control" 
                            id="observaciones_representante"
                        />
                    </div>

                </div>

            </div>

            
            
            <div>
                <button type="submit" class="btn btn-primary">Guardar</button>
                <button type="reset" class="btn btn-danger" id="volver">Volver</button>
            </div>
        </form>
    </section>

    <section>
        <div class="modal fade" id="modalFichaResidente" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">
                            Ficha del residente
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
                                <strong>Género:</strong>
                                <p id="ficha_genero">-</p>
                            </div>

                            <div class="col-md-4">
                                <strong>Fecha de nacimiento:</strong>
                                <p id="ficha_fecha_nacimiento">-</p>
                            </div>

                        </div>


                        <!-- INGRESO -->
                        <h5 class="section-title border-bottom pb-2">
                            Información de ingreso
                        </h5>

                        <div class="row mb-3">

                            <div class="col-md-4">
                                <strong>Fecha de ingreso:</strong>
                                <p id="ficha_fecha_ingreso">-</p>
                            </div>

                            <div class="col-md-4">
                                <strong>Fecha de egreso:</strong>
                                <p id="ficha_fecha_egreso">-</p>
                            </div>

                            <div class="col-md-4">
                                <strong>Status:</strong>
                                <p id="ficha_status">-</p>
                            </div>

                        </div>


                        <!-- REPRESENTANTE -->
                        <h5 class="section-title border-bottom pb-2">
                            Representante
                        </h5>

                        <div class="row mb-3">

                            <div class="col-md-6">
                                <strong>Representante:</strong>
                                <p id="ficha_representante">-</p>
                            </div>

                            <div class="col-md-6">
                                <strong>Parentesco:</strong>
                                <p id="ficha_parentesco">-</p>
                            </div>

                        </div>


                        <!-- INFORMACIÓN FÍSICA -->
                        <h5 class="section-title border-bottom pb-2">
                            Información física
                        </h5>

                        <div class="row mb-3">

                            <div class="col-md-4">
                                <strong>Peso:</strong>
                                <p id="ficha_peso">-</p>
                            </div>

                            <div class="col-md-4">
                                <strong>Altura:</strong>
                                <p id="ficha_altura">-</p>
                            </div>

                            <div class="col-md-4">
                                <strong>Condición de movilidad:</strong>
                                <p id="ficha_condicion_mov">-</p>
                            </div>

                        </div>


                        <!-- INFORMACIÓN MÉDICA -->
                        <h5 class="section-title border-bottom pb-2">
                            Información médica
                        </h5>

                        <div class="row mb-3">

                            <div class="col-md-4">
                                <strong>Centro médico:</strong>
                                <p id="ficha_centro_medico">-</p>
                            </div>

                            <div class="col-md-4">
                                <strong>Médico tratante:</strong>
                                <p id="ficha_medico">-</p>
                            </div>

                            <div class="col-md-4">
                                <strong>Paciente psiquiátrico:</strong>
                                <p id="ficha_psiquiatrico">-</p>
                            </div>

                            <div class="col-md-4">
                                <strong>Diagnóstico:</strong>
                                <p id="ficha_diagnostico">-</p>
                            </div>

                        </div>

                        <!-- INFORMACIÓN ADMINISTRATIVA -->
                        <h5 class="section-title border-bottom pb-2">
                            Información administrativa
                        </h5>

                        <div class="row mb-3">

                            <div class="col-md-4">
                                <strong>Convenio IVSS:</strong>
                                <p id="ficha_ivss">-</p>
                            </div>

                            <div class="col-md-4">
                                <strong>Pensionado:</strong>
                                <p id="ficha_pensionado">-</p>
                            </div>

                            <div class="col-md-4">
                                <strong>Caso privado:</strong>
                                <p id="ficha_privado">-</p>
                            </div>

                        </div>

                        <div class="row mb-3">

                            <div class="col-md-4">
                                <strong>Contención familiar:</strong>
                                <p id="ficha_contencion">-</p>
                            </div>

                            <div class="col-md-4">
                                <strong>Vulnerabilidad familiar:</strong>
                                <p id="ficha_vulnerabilidad">-</p>
                            </div>

                            <div class="col-md-4">
                                <strong>Apadrinazgo:</strong>
                                <p id="ficha_apadrinazgo">-</p>
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
    <script src="../assets/js/ajaxResidentes.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.all.min.js"></script>

    <script>
    // Actualiza el enlace con los filtros actuales
    document.getElementById('pdf-link').addEventListener('click', function(e) {
        e.preventDefault();
        
        // Construye la URL con parámetros
        let url = '/views/reportes/ReporteResidentes.php';
        
        window.open(url, '_blank');
    });
    </script>

  </body>
</html>