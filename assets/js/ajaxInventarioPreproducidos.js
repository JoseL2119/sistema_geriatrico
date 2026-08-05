$(document).ready( function() {
    let idmaterial = 0;
    let idempresa = 0;
    let idplanta = 0;
    mostrarListado();
    listadoInventario();

    // Ir al formulario desde el listado
    $(document).on("click", "#nuevo", function (e){
        // obtenerProcesamiento(0);
        // obtenerEmpresa(0);
        obtenerProducto(0);
        obtenerSubproducto(0);
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
        const producto = $("#producto").val();
        const cantidadP = $("#cantidadP").val();
        const subproducto = $("#subproducto").val();
        const cantidadS = $("#cantidadS").val();
        const fecha = $("#fecha").val();
        const comentario = $("#comentario").val();
        const info = {
            id_producto: producto,
            cantidad_prod: cantidadP,
            id_subproducto: subproducto,
            cantidad_sub: cantidadS,
            fecha: fecha,
            comentario: comentario
        }

        // console.log("Datos a enviar:", info);

        if(id == "0"){
            guardarInventario(info);
        } else{
            modificarInventario(id, info);
        }
        mostrarListado();
    });

    $(document).on("click", "#editar", function(e){
        const id = $(this).attr("value");
        obtenerInventario(id);
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
                eliminarInventario(id);
            }
        })
    });

});

// Funciones de AJAX
function listadoInventario(){
    $.ajax({
        type: "GET", 
        url: "/ajaxInventarioPreproducidos",
        dataType: "json",
        success: function(response) {
            html = '';
            response.forEach((element) => {
                fecha = element.fecha.substr(8, 2) + "/" + element.fecha.substr(5, 2) + "/" + element.fecha.substr(0, 4);
                html += `
                <tr>
                    <td>${element.id}</td>
                    <td>${element.producto}</td>
                    <td>${element.cantidad_prod} Tn</td>
                    <td>${element.subproducto}</td>
                    <td>${element.cantidad_sub} Tn</td>
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

function guardarInventario(params){
    $.ajax({
        type: "POST",
        url: "/ajaxInventarioPreproducidos",
        data: params,
        dataType: "json",
        success: function (response){
            console.log("Respuesta del servidor:", response);
            listadoInventario();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function modificarInventario(id, params){
    $.ajax({
        type: "PUT",
        url: "/ajaxInventarioPreproducidos/" + id,
        data: params,
        dataType: "json",
        success: function (response){
            listadoInventario();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function obtenerInventario(id){
    $.ajax({
        type: "GET",
        url: "/ajaxInventarioPreproducidos/" + id,
        dataType: "json",
        success: function (response){
            response = response[0];
            $("#id").val(response.id);
            obtenerProducto(response.id_producto);
            $("#cantidadP").val(response.cantidad_prod);
            obtenerSubproducto(response.id_subproducto);
            $("#cantidadS").val(response.cantidad_sub);
            $("#fecha").val(response.fecha);
            $("#comentario").val(response.comentario);
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
        url: "/ajaxProductos",
        dataType: "json",
        success: function (response){
            $("#producto").empty();
            html = '<option selected>Elegir Producto</option>';
            if(id==0){
                $("#producto").append(html);
                response.forEach((element) => {
                    // fecha = element.fecha.substr(8, 2) + "/" + element.fecha.substr(5, 2) + "/" + element.fecha.substr(0, 4);
                    html = '<option value="' + element.id + '">' + element.nombre + '</option>';
                    $('#producto').append(html);
                });
            } 

            if(id > 0){
                response.forEach((element) => {
                    if(element.id == id){
                        html = '<option selected value="' + element.id + '">' + element.nombre + '</option>';
                        $('#producto').append(html);    
                    }

                    else{
                        // fecha = element.fecha.substr(8, 2) + "/" + element.fecha.substr(5, 2) + "/" + element.fecha.substr(0, 4);
                        html = '<option value="' + element.id + '">' + element.nombre + '</option>';
                        $('#producto').append(html);
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

function obtenerSubproducto(id){
    $.ajax({
        type: "GET",
        url: "/ajaxSubproductos",
        dataType: "json",
        success: function (response){
            $("#subproducto").empty();
            html = '<option selected>Elegir Subproducto</option>';
            if(id==0){
                $("#subproducto").append(html);
                response.forEach((element) => {
                    // fecha = element.fecha.substr(8, 2) + "/" + element.fecha.substr(5, 2) + "/" + element.fecha.substr(0, 4);
                    html = '<option value="' + element.id + '">' + element.nombre + '</option>';
                    $('#subproducto').append(html);
                });
            }

            if(id > 0){
                response.forEach((element) => {
                    if(element.id == id){
                        html = '<option selected value="' + element.id + '">' + element.nombre + '</option>';
                        $('#subproducto').append(html);    
                    }

                    else{
                        // fecha = element.fecha.substr(8, 2) + "/" + element.fecha.substr(5, 2) + "/" + element.fecha.substr(0, 4);
                        html = '<option value="' + element.id + '">' + element.nombre + '</option>';
                        $('#subproducto').append(html);
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

function eliminarInventario(id){
    $.ajax({
        type: "DELETE",
        url: "/ajaxInventarioPreproducidos/" + id,
        dataType: "json",
        success: function (response){
            listadoInventario();
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
