<?php 
    require 'vendor/autoload.php';
?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Carga Vagones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.min.css" rel="stylesheet">
  </head>
  <body>
    <div class="container">
        
    <?php include "fragments/navbarCdPiar.php" ?>

    <section id="lista">
        <h5>Historial de Carga de Carga de Vagones</h5>
        <hr />
        <!-- <div>
            <button type="button" class="btn btn-primary" id="nuevo">Nuevo Registro</button>
        </div> -->
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Tipo de vagón</th>
                    <th scope="col">Cantidad</th>
                    <th scope="col">Planta/Mina</th>
                    <th scope="col">Empresa</th>
                    <th scope="col">Tipo de carga</th>
                    <th scope="col">Total carga (Tn)</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">Comentario</th>
                    <!-- <th scope="col">Opciones</th> -->
                </tr>
            </thead>
            <tbody id="tbody"></tbody>
        </table>
    </section>

    <section id="form" class="d-none">
        <h5>Formulario para Nuevo Registro de Carga de Vagones/h5>
        <hr />
        <form id="formulario">
            <input type="hidden" id="id" />
            <div class="mb-3">
                <label for="tipo" class="form-label">Tipo de vagón</label>
                <select class="form-select" id="tipo">
                    <option selected>Elegir Tipo</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="cantidadT" class="form-label">Cantidad de Tolvas</label>
                <input 
                    type="number" 
                    class="form-control" 
                    id="cantidadT" 
                    value=""
                    required
                />
            </div>
            <div class="mb-3">
                <label for="planta" class="form-label">Planta de procedencia</label>
                <select class="form-select" id="planta">
                    <option selected>Elegir Planta</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="empresa" class="form-label">Empresa</label>
                <select class="form-select" id="empresa">
                    <option selected>Elegir Empresa</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="tipoC" class="form-label">Tipo de Carga</label>
                <select class="form-select" id="tipoC">
                    <option selected>Elegir Empresa</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="total" class="form-label">Total Carga</label>
                <input 
                    type="number" 
                    class="form-control" 
                    id="total" 
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
    <script src="../assets/js/ajaxCargaVagones.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.all.min.js"></script>
  </body>
</html>