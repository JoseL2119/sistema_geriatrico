$(document).ready( function() {
    let idmaterial = 0;
    let idempresa = 0;
    let idplanta = 0;
    mostrarListado();
    listadoOperaciones();

    // Ir al formulario desde el listado
    $(document).on("click", "#nuevo", function (e){
        // obtenerProcesamiento(0);
        obtenerPlanta(0);
        mostrarFormulario();
        limpiarFormulario();
    });

    // Ir al listado desde el formulario
    $(document).on("click", "#volver", function (e){
        mostrarListado();
    });

    // Cuando se haga submit en el formulario...
    $("#formulario").submit( function(e){
        e.preventDefault();
        const id = $("#id").val();
        const planta = $("#planta").val();
        const cantidad = $("#cantidad").val();
        const fecha = $("#fecha").val();
        const comentario = $("#comentario").val();
        const info = {
            cantidad_prod: cantidad,
            fecha: fecha,
            comentario: comentario,
            id_planta_s: planta
        }

        // console.log("Datos a enviar:", info);

        if(id == "0"){
            guardarOperacion(info);
        } else{
            modificarOperacion(id, info);
        }
        mostrarListado();
    });

    $(document).on("click", "#editar", function(e){
        const id = $(this).attr("value");
        obtenerOperacion(id);
        mostrarFormulario();
    });

    $(document).on("click", "#eliminar", function(e){
        const id = $(this).attr("value");
        Swal.fire({
            tittle: '¿Seguro que desea eliminar?',
            text: `El registro ${id} será eliminado`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí',
            cancelButtonText: 'No'
        }).then( (result) => {
            if(result.value){
                eliminarOperacion(id);
            }
        })
    });

});

// Funciones de AJAX
function listadoOperaciones(){
    $.ajax({
        type: "GET", 
        url: "/ajaxOperacionesSiderurgicas",
        dataType: "json",
        success: function(response) {
            html = '';
            response.forEach((element) => {
                fecha = element.fecha.substr(8, 2) + "/" + element.fecha.substr(5, 2) + "/" + element.fecha.substr(0, 4);
                html += `
                <tr>
                    <td>${element.id}</td>
                    <td>${element.planta}</td>
                    <td>${element.cantidad_prod}</td>
                    <td>${fecha}</td>
                    <td>${element.comentario}</td>
                    <td>
                        <a class="btn btn-success" id="editar" value="${element.id}">Editar</a>
                        <a class="btn btn-danger" id="eliminar" value="${element.id}">Eliminar</a>
                    </td>
                </tr>
                `
            });
            $("#tbody").html(html); //id del tbody de la tabla
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function guardarOperacion(params){
    $.ajax({
        type: "POST",
        url: "/ajaxOperacionesSiderurgicas",
        data: params,
        dataType: "json",
        success: function (response){
            console.log("Respuesta del servidor:", response);
            listadoOperaciones();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function modificarOperacion(id, params){
    $.ajax({
        type: "PUT",
        url: "/ajaxOperacionesSiderurgicas/" + id,
        data: params,
        dataType: "json",
        success: function (response){
            listadoOperaciones();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function obtenerOperacion(id){
    $.ajax({
        type: "GET",
        url: "/ajaxOperacionesSiderurgicas/" + id,
        dataType: "json",
        success: function (response){
            response = response[0];
            $("#id").val(response.id);
            obtenerPlanta(response.id_planta_s);
            $("#cantidad").val(response.cantidad_prod);
            $("#fecha").val(response.fecha);
            $("#comentario").val(response.comentario);
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function obtenerPlanta(id){
    $.ajax({
        type: "GET",
        url: "/ajaxPlantasSiderurgicas",
        dataType: "json",
        success: function (response){
            $("#planta").empty();
            html = '<option selected>Elegir Planta</option>';
            if(id==0){
                $("#planta").append(html);
                response.forEach((element) => {
                    // fecha = element.fecha.substr(8, 2) + "/" + element.fecha.substr(5, 2) + "/" + element.fecha.substr(0, 4);
                    html = '<option value="' + element.id + '">' + element.nombre + '</option>';
                    $('#planta').append(html);
                });
            }

            if(id > 0){
                response.forEach((element) => {
                    if(element.id == id){
                        html = '<option selected value="' + element.id + '">' + element.nombre + '</option>';
                        $('#planta').append(html);
                    }

                    else{
                        // fecha = element.fecha.substr(8, 2) + "/" + element.fecha.substr(5, 2) + "/" + element.fecha.substr(0, 4);
                        html = '<option value="' + element.id + '">' + element.nombre + '</option>';
                        $('#planta').append(html);
                    }
                });
            }
        },
        error: function (req, status, error){
            var err = req.responseText;
            console.log(err);
            alert(err.Message);
        }
    });
}

function eliminarOperacion(id){
    $.ajax({
        type: "DELETE",
        url: "/ajaxOperacionesSiderurgicas/" + id,
        dataType: "json",
        success: function (response){
            listadoOperaciones();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

// Funciones de utilidad
function mostrarListado(){
    $("#lista").removeClass("d-none");
    $("#form").addClass("d-none");
}

function mostrarFormulario(){
    $("#lista").addClass("d-none");
    $("#form").removeClass("d-none");
}

function limpiarFormulario(){
    $("#id").val("0");
    $("#nombre").val("");
}
