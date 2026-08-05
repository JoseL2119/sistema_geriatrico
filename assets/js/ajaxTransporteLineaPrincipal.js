$(document).ready( function() {
    mostrarListado();
    listadoTransporte();

    // Ir al formulario desde el listado
    $(document).on("click", "#nuevo", function (e){
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
        const cantidadVagones = $("#cantidadVagones").val();
        const cantidadGondolas = $("#cantidadGondolas").val();
        const cantidadTolvas = $("#cantidadTolvas").val();
        const cantidadTotal = $("#cantidadTotal").val();
        const cantidadPreparados = $("#cantidadPreparados").val();
        const cantidadExtra = $("#cantidadExtra").val();
        const cantidadAnulado = $("#cantidadAnulado").val();
        const fecha = $("#fecha").val();
        const comentario = $("#comentario").val();
        const info = {
            cant_vagones: cantidadVagones,
            cant_gondolas: cantidadGondolas,
            cant_tolvas: cantidadTolvas,
            cant_trenes_p: cantidadPreparados,
            cant_trenes_e: cantidadExtra,
            cant_trenes_a: cantidadAnulado,
            fecha: fecha,
            comentario: comentario,
            total: cantidadTotal
        }
        if(id == "0"){
            guardarTransporte(info);
        } else{
            modificarTransporte(id, info);
        }
        mostrarListado();
    });

    $(document).on("click", "#editar", function(e){
        const id = $(this).attr("value");
        obtenerTransporte(id);
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
                eliminarTransporte(id);
            }
        })
    });

});

// Funciones de AJAX
function listadoTransporte(){
    $.ajax({
        type: "GET", 
        url: "/ajaxTransporteLineaPrincipal",
        dataType: "json",
        success: function(response) {
            html = '';
            response.forEach((element) => {
                fecha = element.fecha.substr(8, 2) + "/" + element.fecha.substr(5, 2) + "/" + element.fecha.substr(0, 4);
                html += `
                <tr>
                    <td>${element.id}</td>
                    <td>${element.cant_vagones}</td>
                    <td>${element.cant_gondolas}</td>
                    <td>${element.cant_tolvas}</td>
                    <td>${element.total}</td>
                    <td>${element.cant_trenes_p}</td>
                    <td>${element.cant_trenes_e}</td>
                    <td>${element.cant_trenes_a}</td>
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

function guardarTransporte(params){
    $.ajax({
        type: "POST",
        url: "/ajaxTransporteLineaPrincipal",
        data: params,
        dataType: "json",
        success: function (response){
            listadoTransporte();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function modificarTransporte(id, params){
    $.ajax({
        type: "PUT",
        url: "/ajaxTransporteLineaPrincipal/" + id,
        data: params,
        dataType: "json",
        success: function (response){
            listadoTransporte();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function obtenerTransporte(id){
    $.ajax({
        type: "GET",
        url: "/ajaxTransporteLineaPrincipal/" + id,
        dataType: "json",
        success: function (response){
            response = response[0];
            $("#id").val(response.id);
            $("#cantidadVagones").val(response.cant_vagones);
            $("#cantidadGondolas").val(response.cant_gondolas);
            $("#cantidadTolvas").val(response.cant_tolvas);
            $("#cantidadTotal").val(response.total);
            $("#cantidadPreparados").val(response.cant_trenes_p);
            $("#cantidadExtra").val(response.cant_trenes_e);
            $("#cantidadAnulado").val(response.cant_trenes_a);
            $("#fecha").val(response.fecha);
            $("#comentario").val(response.comentario);
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function eliminarTransporte(id){
    $.ajax({
        type: "DELETE",
        url: "/ajaxTransporteLineaPrincipal/" + id,
        dataType: "json",
        success: function (response){
            listadoTransporte();
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
