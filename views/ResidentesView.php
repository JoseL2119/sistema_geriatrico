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
                Listado de Residentes
            </h5>

            <button type="button" class="btn btn-primary btn-app shadow-sm" id="nuevo">
                <i class="fas fa-plus-circle me-2"></i>Nuevo Residente
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
                            <th colspan="3">Nombres y Apellidos</th>
                            <th colspan="2">Fecha Nacimiento</th>  
                            <th colspan="2">Fecha Ingreso</th>
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
        <h5>Formulario para registrar a un nuevo Residente</h5>
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
                <label for="fecha" class="form-label">Fecha de Nacimiento</label>
                <input 
                    type="date" 
                    class="form-control" 
                    id="fecha" 
                    value=""
                    required
                />
            </div>

            <div class="mb-3">
                <label for="fecha_ingreso" class="form-label">Fecha de Ingreso</label>
                <input 
                    type="date" 
                    class="form-control" 
                    id="fecha_ingreso" 
                    value=""
                    required
                />
            </div>

            <div class="mb-3">
                <label for="fecha_egreso" class="form-label">Fecha de Egreso</label>
                <input 
                    type="date" 
                    class="form-control" 
                    id="fecha_egreso" 
                    value=""
                />
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
                <label for="ivss" class="form-label">¿Posee Convenio con el IVSS?</label>
                <select class="form-select" id="ivss">
                    <option selected>Seleccionar opción</option>
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="pensionado" class="form-label">¿Es Pensionado?</label>
                <select class="form-select" id="pensionado">
                    <option selected>Seleccionar opción</option>
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="privado" class="form-label">¿Es Caso Privado?</label>
                <select class="form-select" id="privado">
                    <option selected>Seleccionar opción</option>
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="contencion_f" class="form-label">¿Tiene Contención Familiar?</label>
                <select class="form-select" id="contencion_f">
                    <option selected>Seleccionar opción</option>
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="vulnerabilidad_f" class="form-label">¿Tiene Vulnerabilidad Familiar?</label>
                <select class="form-select" id="vulnerabilidad_f">
                    <option selected>Seleccionar opción</option>
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="apadrinazgo" class="form-label">¿Tiene Apadrinazgo?</label>
                <select class="form-select" id="apadrinazgo">
                    <option selected>Seleccionar opción</option>
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="psiquiatrico" class="form-label">¿Es Paciente Psiquiátrico?</label>
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
                <label for="condicion" class="form-label">Condición de Movilidad</label>
                <select class="form-select" id="condicion">
                    <option selected>Seleccionar opción</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="control_esfinteres" class="form-label">¿Controla sus Esfínteres?</label>
                <select class="form-select" id="control_esfinteres">
                    <option selected>Seleccionar opción</option>
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="centro_medico" class="form-label">Centro Médico de Evaluación</label>
                <select class="form-select" id="centro_medico">
                    <option selected>Seleccionar Centro</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="medico" class="form-label">Médico Tratante</label>
                <select class="form-select" id="medico">
                    <option selected>Seleccionar Médico</option>
                </select>
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

            <h5>Representante</h5>

            <div class="mb-3">
                <label for="parentesco" class="form-label">
                    Parentesco
                </label>

                <select class="form-select" id="parentesco">
                    <option selected>Seleccionar Parentesco</option>
                </select>
            </div>

            <div class="mb-3">

                <label class="form-label">
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
                    >
                        Registrar nuevo representante
                    </label>

                </div>

                <!-- REPRESENTANTE EXISTENTE -->
                <div id="contenedor-representante-existente">

                    <div class="mb-3">
                        <label for="representante" class="form-label">
                            Representante Responsable
                        </label>

                        <select class="form-select" id="representante">
                            <option selected>Seleccionar Representante</option>
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
                            Fecha de Nacimiento
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
                            Fecha de Pago (día)
                        </label>

                        <input 
                            type="number" 
                            class="form-control" 
                            id="fechaPago_representante"
                        />
                    </div>


                    <div class="mb-3">
                        <label for="familiar_alt_representante" class="form-label">
                            Nombre de Familiar alternativo (opcional)
                        </label>

                        <input 
                            type="text" 
                            class="form-control" 
                            id="familiar_alt_representante"
                        />
                    </div>


                    <div class="mb-3">
                        <label for="telefono_alt_representante" class="form-label">
                            Teléfono de Familiar alternativo (opcional)
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
                                <strong>Paciente Psiquiátrico:</strong>
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
                                <strong>Caso Privado:</strong>
                                <p id="ficha_privado">-</p>
                            </div>

                        </div>

                        <div class="row mb-3">

                            <div class="col-md-4">
                                <strong>Contención Familiar:</strong>
                                <p id="ficha_contencion">-</p>
                            </div>

                            <div class="col-md-4">
                                <strong>Vulnerabilidad Familiar:</strong>
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

  </body>
</html>