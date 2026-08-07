<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pagos</title>
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
                Historial de pagos por residente
            </h5>

            <button type="button" class="btn btn-primary btn-app shadow-sm" id="nuevo">
                <i class="fas fa-plus-circle me-2"></i>Registrar nuevo pago
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
                            <th colspan="2">Fecha</th>
                            <th colspan="2">Residente</th>  
                            <th colspan="2">Representante</th>  
                            <th colspan="2">Monto</th>
                            <th colspan="2">Método de pago</th>
                            <th colspan="2">Referencia</th>
                            <th colspan="3">Observaciones</th>
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
        <h5>Formulario para registrar un nuevo pago</h5>
        <hr />
        <form id="formulario">
            <input type="hidden" id="id" />

            <div class="mb-3">
                <label for="residente" class="form-label">Residente</label>

                <select class="form-select" id="residente">
                    <option selected>Seleccionar residente</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="representante" class="form-label">Representante</label>

                <select class="form-select" id="representante">
                    <option selected>Seleccionar representante</option>
                </select>
            </div>

            <table class="table table-bordered m-0" style="font-size: 0.88rem; width: 100%; min-width: 100%;">
                <thead>
                    <!-- Fila de encabezado principal -->
                    <tr class="text-center">
                        <th colspan="3">Periodo</th>
                        <th colspan="2">Monto</th>  
                        <th colspan="2">Pagado</th>  
                        <th colspan="2">Saldo</th>
                        <th colspan="2">Aplicar</th>
                    </tr>
                </thead>
                <tbody id="tbodyCargos" class="table-group-divider">
                    <!-- Las filas se generarán dinámicamente aquí -->
                </tbody>
            </table>

            <div class="mb-3">
                <label for="fecha" class="form-label">Fecha de pago</label>
                <input 
                    type="date" 
                    class="form-control" 
                    id="fecha" 
                    value=""
                    required
                />
            </div>

            <div class="mb-3">
                <label for="monto" class="form-label">Monto pagado (USD)</label>
                <input 
                    type="number"
                    step="0.01" 
                    class="form-control" 
                    id="monto" 
                    value=""
                    required
                />
            </div>

            <div class="mb-3">
                <label for="metodo" class="form-label">Método de pago</label>

                <select class="form-select" id="metodo">
                    <option selected>Seleccionar método de pago</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="referencia" class="form-label">Referencia</label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="referencia" 
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

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="../assets/js/ajaxPagos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.all.min.js"></script>

  </body>
</html>