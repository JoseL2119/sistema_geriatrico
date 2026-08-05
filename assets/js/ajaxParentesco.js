$(document).ready( function() {
    mostrarListado();
    listadoParentesco();

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
        const parentesco = $("#parentesco").val();
        const info = {
            parentesco: parentesco
        }
        if(id == "0"){
            guardarParentesco(info);
        } else{
            modificarParentesco(id, info);
        }
        mostrarListado();
    });

    $(document).on("click", "#editar", function(e){
        const id = $(this).attr("value");
        obtenerParentesco(id);
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
                eliminarParentesco(id);
            }
        })
    });

});

// Funciones de AJAX
function listadoParentesco(){
    $.ajax({
        type: "GET", 
        url: "/ajaxParentesco",
        dataType: "json",
        success: function(response) {
            html = '';
            response.forEach((element) => {
                html += `
                <tr>
                    <td>${element.id}</td>
                    <td>${element.parentesco}</td>
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

function guardarParentesco(params){
    $.ajax({
        type: "POST",
        url: "/ajaxParentesco",
        data: params,
        dataType: "json",
        success: function (response){
            listadoParentesco();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function modificarParentesco(id, params){
    $.ajax({
        type: "PUT",
        url: "/ajaxParentesco/" + id,
        data: params,
        dataType: "json",
        success: function (response){
            listadoParentesco();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function obtenerParentesco(id){
    $.ajax({
        type: "GET",
        url: "/ajaxParentesco/" + id,
        dataType: "json",
        success: function (response){
            response = response[0];
            $("#id").val(response.id);
            $("#parentesco").val(response.parentesco);
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function eliminarParentesco(id){
    $.ajax({
        type: "DELETE",
        url: "/ajaxParentesco/" + id,
        dataType: "json",
        success: function (response){
            listadoParentesco();
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
    $("#parentesco").val("");
}
