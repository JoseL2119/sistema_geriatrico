$(document).ready( function() {
    mostrarListado();
    listadoMovilidad();

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
        const condicion = $("#condicion").val();
        const info = {
            condicion: condicion
        }
        if(id == "0"){
            guardarMovilidad(info);
        } else{
            modificarMovilidad(id, info);
        }
        mostrarListado();
    });

    $(document).on("click", "#editar", function(e){
        const id = $(this).attr("value");
        obtenerMovilidad(id);
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
                eliminarMovilidad(id);
            }
        })
    });

});

// Funciones de AJAX
function listadoMovilidad(){
    $.ajax({
        type: "GET", 
        url: "/ajaxMovilidad",
        dataType: "json",
        success: function(response) {
            html = '';
            response.forEach((element) => {
                html += `
                <tr>
                    <td>${element.id}</td>
                    <td>${element.condicion}</td>
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

function guardarMovilidad(params){
    $.ajax({
        type: "POST",
        url: "/ajaxMovilidad",
        data: params,
        dataType: "json",
        success: function (response){
            listadoMovilidad();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function modificarMovilidad(id, params){
    $.ajax({
        type: "PUT",
        url: "/ajaxMovilidad/" + id,
        data: params,
        dataType: "json",
        success: function (response){
            listadoMovilidad();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function obtenerMovilidad(id){
    $.ajax({
        type: "GET",
        url: "/ajaxMovilidad/" + id,
        dataType: "json",
        success: function (response){
            response = response[0];
            $("#id").val(response.id);
            $("#condicion").val(response.condicion);
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function eliminarMovilidad(id){
    $.ajax({
        type: "DELETE",
        url: "/ajaxMovilidad/" + id,
        dataType: "json",
        success: function (response){
            listadoMovilidad();
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
    $("#condicion").val("");
}
