$(document).ready( function() {
    mostrarListado();
    listadoProductos();

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
        const nombre = $("#nombre").val();
        const info = {
            nombre: nombre
        }
        if(id == "0"){
            guardarProducto(info);
        } else{
            modificarProducto(id, info);
        }
        mostrarListado();
    });

    $(document).on("click", "#editar", function(e){
        const id = $(this).attr("value");
        obtenerProducto(id);
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
                eliminarProducto(id);
            }
        })
    });

});

// Funciones de AJAX
function listadoProductos(){
    $.ajax({
        type: "GET", 
        url: "/ajaxSubproductos",
        dataType: "json",
        success: function(response) {
            html = '';
            response.forEach((element) => {
                html += `
                <tr>
                    <td>${element.id}</td>
                    <td>${element.nombre}</td>
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

function guardarProducto(params){
    $.ajax({
        type: "POST",
        url: "/ajaxSubproductos",
        data: params,
        dataType: "json",
        success: function (response){
            listadoProductos();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function modificarProducto(id, params){
    $.ajax({
        type: "PUT",
        url: "/ajaxSubproductos/" + id,
        data: params,
        dataType: "json",
        success: function (response){
            listadoProductos();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function obtenerProducto(id){
    $.ajax({
        type: "GET",
        url: "/ajaxSubproductos/" + id,
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

function eliminarProducto(id){
    $.ajax({
        type: "DELETE",
        url: "/ajaxSubproductos/" + id,
        dataType: "json",
        success: function (response){
            listadoProductos();
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
