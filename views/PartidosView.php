<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quinielas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.min.css" rel="stylesheet">
  </head>
  <body>
    <div class="container">
        
    <?php include "fragments/navbar.php" ?>

    <section id="lista">
        <h5>Listado Partidos</h5>
        <hr />
        <div>
            <button type="button" class="btn btn-primary" id="nuevo">Nuevo</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">J</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">Local</th>
                    <th scope="col">Visitante</th>
                    <th scope="col">Goles</th>
                    <th scope="col">Opciones</th>
                </tr>
            </thead>
            <tbody id="tbody"></tbody>
        </table>
    </section>

    <section id="form" class="d-none">
        <h5>Formulario Partidos</h5>
        <hr />
        <form id="formulario">
            <input type="hidden" id="id" />
            <div class="mb-3">
                <label for="jornada" class="form-label">Jornada</label>
                <select class="form-select" id="jornada">
                    <option selected>Elegir Jornada</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="orden" class="form-label">Orden</label>
                <input 
                    type="number" 
                    class="form-control" 
                    id="orden" 
                    value=""
                    required
                />
            </div>

            <div class="mb-3">
            <label for="idlocal" class="form-label">Equipo Local</label>
                <select class="form-select" id="idlocal">
                    <option selected>Selecciona equipo local</option>
                </select>
            </div>

            <div class="mb-3">
            <label for="idlocal" class="form-label">Equipo Visitante</label>
                <select class="form-select" id="idvisitante">
                    <option selected>Selecciona equipo visitante</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="goleslocal" class="form-label">Goles local</label>
                <input 
                    type="number" 
                    class="form-control" 
                    id="goleslocal" 
                    value=""
                    required
                />
            </div>

            <div class="mb-3">
                <label for="golesvisitante" class="form-label">Goles Visitante</label>
                <input 
                    type="number" 
                    class="form-control" 
                    id="golesvisitante" 
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.all.min.js"></script>
    <script src="../assets/js/ajaxPartidos.js"></script>
  </body>
</html>