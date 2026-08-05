<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lista de Artículos</title>
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
            <h5>
                <i class="fas fa-users page-icon"></i>
                Listado de Artículos para consignaciones
            </h5>

            <button type="button" class="btn btn-primary btn-app shadow-sm" id="nuevo">
                <i class="fas fa-plus-circle me-2"></i>Nuevo Artículo
            </button>
        </div>

        <hr class="page-divider">

        <div class="container-fluid px-0">
            <div class="table-responsive rounded-3 shadow-sm">
                <table class="table table-bordered table-hover m-0" style="font-size: 0.88rem; width: 100%; min-width: 100%;">
                    <thead class="text-center">
                        <tr>
                            <th style="width: 5%; min-width: 50px;">#</th>
                            <th style="width: 70%; min-width: 200px;">Nombre</th>
                            <th style="width: 25%; min-width: 100px;">Opciones</th>
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
        <h5>Formulario de Registro de Métodos de pago</h5>
        <hr />
        <form id="formulario">
            <input type="hidden" id="id" />
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="nombre" 
                    value=""
                    required
                />
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
    <script src="../assets/js/ajaxArticulos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.all.min.js"></script>
  </body>
</html>