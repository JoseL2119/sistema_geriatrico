$(document).ready( function() {
    mostrarListado();
    listadoProduccion();

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
        const cantidadVolteo = $("#cantidadVolteo").val();
        const cantidadVaciado = $("#cantidadVaciado").val();
        const cantidadFino = $("#cantidadFino").val();
        const cantidadGrueso = $("#cantidadGrueso").val();
        const cantidadTotal = $("#cantidadTotal").val();
        const fecha = $("#fecha").val();
        const comentario = $("#comentario").val();
        const info = {
            cant_volteo: cantidadVolteo,
            cant_vaciado: cantidadVaciado,
            cant_fino: cantidadFino,
            cant_grueso: cantidadGrueso,
            total_producido: cantidadTotal,
            fecha: fecha,
            comentario: comentario
        }
        if(id == "0"){
            guardarProduccion(info);
        } else{
            modificarProduccion(id, info);
        }
        mostrarListado();
    });

    $(document).on("click", "#editar", function(e){
        const id = $(this).attr("value");
        obtenerProduccion(id);
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
                eliminarProduccion(id);
            }
        })
    });

});

// Funciones de AJAX
function listadoProduccion(){
    $.ajax({
        type: "GET", 
        url: "/ajaxProduccionPzo",
        dataType: "json",
        success: function(response) {
            html = '';
            response.forEach((element) => {
                fecha = element.fecha.substr(8, 2) + "/" + element.fecha.substr(5, 2) + "/" + element.fecha.substr(0, 4);
                html += `
                <tr>
                    <td>${element.id}</td>
                    <td>${element.cant_volteo}</td>
                    <td>${element.cant_vaciado}</td>
                    <td>${element.cant_fino}</td>
                    <td>${element.cant_grueso}</td>
                    <td>${element.total_producido}</td>
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

function guardarProduccion(params){
    $.ajax({
        type: "POST",
        url: "/ajaxProduccionPzo",
        data: params,
        dataType: "json",
        success: function (response){
            listadoProduccion();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function modificarProduccion(id, params){
    $.ajax({
        type: "PUT",
        url: "/ajaxProduccionPzo/" + id,
        data: params,
        dataType: "json",
        success: function (response){
            listadoProduccion();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function obtenerProduccion(id){
    $.ajax({
        type: "GET",
        url: "/ajaxProduccionPzo/" + id,
        dataType: "json",
        success: function (response){
            response = response[0];
            $("#id").val(response.id);
            $("#cantidadVolteo").val(response.cant_volteo);
            $("#cantidadVaciado").val(response.cant_vaciado);
            $("#cantidadFino").val(response.cant_fino);
            $("#cantidadGrueso").val(response.cant_grueso);
            $("#cantidadTotal").val(response.total_producido);
            $("#fecha").val(response.fecha);
            $("#comentario").val(response.comentario);
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function eliminarProduccion(id){
    $.ajax({
        type: "DELETE",
        url: "/ajaxProduccionPzo/" + id,
        dataType: "json",
        success: function (response){
            listadoProduccion();
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
