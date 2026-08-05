<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Carga Barco</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.min.css" rel="stylesheet">
  </head>
  <body>
    <div class="container">
        
    <?php include "fragments/navbarPzo.php" ?>

    <section id="lista">
        <h5>Historial de Carga a Barco</h5>
        <hr />
        <div>
            <button type="button" class="btn btn-primary" id="nuevo">Nuevo Registro</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Mineral</th>
                    <th scope="col">Cantidad (Tn)</th>
                    <th scope="col">Nombre Barco</th>
                    <th scope="col">Número de embarque</th>
                    <th scope="col">Destino</th>
                    <th scope="col">Empresa</th>
                    <th scope="col">¿Exportación?</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">Comentario</th>
                    <th scope="col">Opciones</th>
                </tr>
            </thead>
            <tbody id="tbody"></tbody>
        </table>
    </section>

    <section id="form" class="d-none">
        <h5>Formulario para Nuevo Registro de Carga a Barco</h5>
        <hr />
        <form id="formulario">
            <input type="hidden" id="id" />
            <div class="mb-3">
                <label for="mineral" class="form-label">Mineral</label>
                <select class="form-select" id="mineral">
                    <option selected>Elegir Mineral</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="cantidad" class="form-label">Cantidad (Tn)</label>
                <input 
                    type="number" 
                    class="form-control" 
                    id="cantidad" 
                    value=""
                    required
                />
            </div>
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del Barco</label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="nombre" 
                    value=""
                    required
                />
            </div>
            <div class="mb-3">
                <label for="numero" class="form-label">Número de embarque</label>
                <input 
                    type="number" 
                    class="form-control" 
                    id="numero" 
                    value=""
                    required
                />
            </div>
            <div class="mb-3">
                <label for="destino" class="form-label">Destino</label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="destino" 
                    value=""
                    required
                />
            </div>
            <div class="mb-3">
                <label for="empresa" class="form-label">Empresa</label>
                <select class="form-select" id="empresa">
                    <option selected>Elegir Empresa</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="exportacion" class="form-label">¿Es Exportación?</label>
                <select class="form-select" id="exportacion">
                    <option selected>Elegir respuesta</option>
                    <option value=1>Si</option>
                    <option value=0>No</option>
                </select>
            </div>
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
                <label for="comentario" class="form-label">Comentarios</label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="comentario" 
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
    <script src="../assets/js/ajaxCargaBarco.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.all.min.js"></script>
  </body>
</html>