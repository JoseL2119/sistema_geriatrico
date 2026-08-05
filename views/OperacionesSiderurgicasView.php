<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Operaciones Siderúrgicas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.min.css" rel="stylesheet">
  </head>
  <body>
    <div class="container">
        
    <?php include "fragments/navbarSiderurgicas.php" ?>

    <section id="lista">
        <h5>Historial Operaciones Siderúrgicas</h5>
        <hr />
        <div>
            <button type="button" class="btn btn-primary" id="nuevo">Nuevo Registro</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Planta</th>
                    <th scope="col">Cantidad (Tn)</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">Comentario</th>
                </tr>
            </thead>
            <tbody id="tbody"></tbody>
        </table>
    </section>

    <section id="form" class="d-none">
        <h5>Formulario para Nuevo Registro de Operaciones Siderúrgicas</h5>
        <hr />
        <form id="formulario">
            <input type="hidden" id="id" />
            <div class="mb-3">
                <label for="planta" class="form-label">Planta</label>
                <select class="form-select" id="planta">
                    <option selected>Elegir Planta</option>
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
    <script src="../assets/js/ajaxOperacionesSiderurgicas.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.all.min.js"></script>
  </body>
</html>