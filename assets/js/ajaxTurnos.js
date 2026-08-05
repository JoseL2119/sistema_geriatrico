$(document).ready( function() {
    mostrarListado();
    listadoTurnos();

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
        const turno = $("#turno").val();
        const info = {
            turno: turno
        }
        if(id == "0"){
            guardarTurno(info);
        } else{
            modificarTurno(id, info);
        }
        mostrarListado();
    });

    $(document).on("click", "#editar", function(e){
        const id = $(this).attr("value");
        obtenerTurno(id);
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
                eliminarTurno(id);
            }
        })
    });

});

// Funciones de AJAX
function listadoTurnos(){
    $.ajax({
        type: "GET", 
        url: "/ajaxTurnos",
        dataType: "json",
        success: function(response) {
            html = '';
            response.forEach((element) => {
                html += `
                <tr>
                    <td>${element.id}</td>
                    <td>${element.turno}</td>
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

function guardarTurno(params){
    $.ajax({
        type: "POST",
        url: "/ajaxTurnos",
        data: params,
        dataType: "json",
        success: function (response){
            listadoTurnos();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function modificarTurno(id, params){
    $.ajax({
        type: "PUT",
        url: "/ajaxTurnos/" + id,
        data: params,
        dataType: "json",
        success: function (response){
            listadoTurnos();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function obtenerTurno(id){
    $.ajax({
        type: "GET",
        url: "/ajaxTurnos/" + id,
        dataType: "json",
        success: function (response){
            response = response[0];
            $("#id").val(response.id);
            $("#nombre").val(response.nombre);
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function eliminarTurno(id){
    $.ajax({
        type: "DELETE",
        url: "/ajaxTurnos/" + id,
        dataType: "json",
        success: function (response){
            listadoTurnos();
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
