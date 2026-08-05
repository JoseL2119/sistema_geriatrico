$(document).ready( function() {
    mostrarListado();
    listadoMineralesProcesados();

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
        const tipo = $("#nombre").val();
        const info = {
            tipo: tipo
        }
        if(id == "0"){
            guardarMineralProcesado(info);
        } else{
            modificarMineralProcesado(id, info);
        }
        mostrarListado();
    });

    $(document).on("click", "#editar", function(e){
        const id = $(this).attr("value");
        obtenerMineralProcesado(id);
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
                eliminarMineralProcesado(id);
            }
        })
    });

});

// Funciones de AJAX
function listadoMineralesProcesados(){
    $.ajax({
        type: "GET", 
        url: "/ajaxMineralProcesado",
        dataType: "json",
        success: function(response) {
            html = '';
            response.forEach((element) => {
                html += `
                <tr>
                    <td>${element.id}</td>
                    <td>${element.tipo}</td>
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

function guardarMineralProcesado(params){
    $.ajax({
        type: "POST",
        url: "/ajaxMineralProcesado",
        data: params,
        dataType: "json",
        success: function (response){
            listadoMineralesProcesados();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function modificarMineralProcesado(id, params){
    $.ajax({
        type: "PUT",
        url: "/ajaxMineralProcesado/" + id,
        data: params,
        dataType: "json",
        success: function (response){
            listadoMineralesProcesados();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function obtenerMineralProcesado(id){
    $.ajax({
        type: "GET",
        url: "/ajaxMineralProcesado/" + id,
        dataType: "json",
        success: function (response){
            response = response[0];
            $("#id").val(response.id);
            $("#nombre").val(response.tipo);
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function eliminarMineralProcesado(id){
    $.ajax({
        type: "DELETE",
        url: "/ajaxMineralProcesado/" + id,
        dataType: "json",
        success: function (response){
            listadoMineralesProcesados();
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
