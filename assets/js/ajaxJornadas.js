$(document).ready( function() {
    mostrarListado();
    listadoJornadas();

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
        const jornada = $("#jornada").val();
        const fecha = $("#fecha").val();
        const info = {
            jornada: jornada,
            fecha: fecha
        }
        if(id == "0"){
            guardarJornada(info);
        } else{
            modificarJornada(id, info);
        }
        mostrarListado();
    });

    $(document).on("click", "#editar", function(e){
        const id = $(this).attr("value");
        obtenerJornada(id);
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
                eliminarJornada(id);
            }
        })
    });

});

// Funciones de AJAX
function listadoJornadas(){
    $.ajax({
        type: "GET", 
        url: "/ajaxJornadas",
        dataType: "json",
        success: function(response) {
            html = '';
            response.forEach((element) => {
                // aaaa-mm-dd
                fecha = element.fecha.substr(8, 2) + "/" + element.fecha.substr(5, 2) + "/" + element.fecha.substr(0, 4);
                html += `
                <tr>
                    <td>${element.id}</td>
                    <td>${element.jornada}</td>
                    <td>${fecha}</td>
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

function guardarJornada(params){
    $.ajax({
        type: "POST",
        url: "/ajaxJornadas",
        data: params,
        dataType: "json",
        success: function (response){
            listadoJornadas();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function modificarJornada(id, params){
    $.ajax({
        type: "PUT",
        url: "/ajaxJornadas/" + id,
        data: params,
        dataType: "json",
        success: function (response){
            listadoJornadas();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function obtenerJornada(id){
    $.ajax({
        type: "GET",
        url: "/ajaxJornadas/" + id,
        dataType: "json",
        success: function (response){
            response = response[0];
            $("#id").val(response.id);
            $("#jornada").val(response.jornada);
            $("#fecha").val(response.fecha);
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function eliminarJornada(id){
    $.ajax({
        type: "DELETE",
        url: "/ajaxJornadas/" + id,
        dataType: "json",
        success: function (response){
            listadoJornadas();
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
    $("#jornada").val("");
    $("#fecha").val("");
}
