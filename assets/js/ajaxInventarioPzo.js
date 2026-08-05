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
        obtenerAlmacen(0);
        obtenerMineral(0);
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
        const mineral = $("#mineral").val();
        const cantidad = $("#cantidad").val();
        const almacen = $("#almacen").val();
        const fecha = $("#fecha").val();
        const comentario = $("#comentario").val();
        const info = {
            id_tipo_mineral: mineral,
            cantidad: cantidad,
            id_ubicacion: almacen,
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
        url: "/ajaxInventarioPzo",
        dataType: "json",
        success: function(response) {
            html = '';
            response.forEach((element) => {
                fecha = element.fecha.substr(8, 2) + "/" + element.fecha.substr(5, 2) + "/" + element.fecha.substr(0, 4);
                html += `
                <tr>
                    <td>${element.id}</td>
                    <td>${element.mineral}</td>
                    <td>${element.cantidad} Tn</td>
                    <td>${element.almacen}</td>
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
        url: "/ajaxInventarioPzo",
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
        url: "/ajaxInventarioPzo/" + id,
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
        url: "/ajaxInventarioPzo/" + id,
        dataType: "json",
        success: function (response){
            response = response[0];
            $("#id").val(response.id);
            // $("#material").val(response.id_material);
            obtenerMineral(response.id_tipo_mineral);
            $("#cantidad").val(response.cantidad);
            // $("#mina").val(response.id_mina);
            obtenerAlmacen(response.id_ubicacion);
            // $("#empresa").val(response.id_empresa);
            // obtenerEmpresa(id);
            $("#fecha").val(response.fecha);
            $("#comentario").val(response.comentario);
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function obtenerAlmacen(id){
    $.ajax({
        type: "GET",
        url: "/ajaxAlmacenesMineralesPzo",
        dataType: "json",
        success: function (response){
            $("#almacen").empty();
            html = '<option selected>Elegir Almacén</option>';
            if(id==0){
                $("#almacen").append(html);
                response.forEach((element) => {
                    // fecha = element.fecha.substr(8, 2) + "/" + element.fecha.substr(5, 2) + "/" + element.fecha.substr(0, 4);
                    html = '<option value="' + element.id + '">' + element.nombre + '</option>';
                    $('#almacen').append(html);
                });
            } 
            
            if(id > 0){
                response.forEach((element) => {
                    if(element.id == id){
                        html = '<option selected value="' + element.id + '">' + element.nombre + '</option>';
                        $('#almacen').append(html);
                    }

                    else{
                        // fecha = element.fecha.substr(8, 2) + "/" + element.fecha.substr(5, 2) + "/" + element.fecha.substr(0, 4);
                        html = '<option value="' + element.id + '">' + element.nombre + '</option>';
                        $('#almacen').append(html);
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

function obtenerMineral(id){
    $.ajax({
        type: "GET",
        url: "/ajaxTiposMineralesPzo",
        dataType: "json",
        success: function (response){
            $("#mineral").empty();
            valor='';
            html = '<option selected>Elegir Mineral</option>';
            if(id==0){
                $("#mineral").append(html);
                response.forEach((element) => {
                    // fecha = element.fecha.substr(8, 2) + "/" + element.fecha.substr(5, 2) + "/" + element.fecha.substr(0, 4);
                    html = '<option value="' + element.id + '">' + element.nombre + '</option>';
                    $('#mineral').append(html);
                    
                    // if(element.id == id) valor = element.nombre;
                });
            } 
            // mineralName = '<option selected>' + valor + '</option>';
            if(id > 0){
                // $('#mineral').value(valor);

                response.forEach((element) => {
                    if(element.id == id){
                        // valor = element.nombre;
                        html = '<option selected value="' + element.id + '">' + element.nombre + '</option>';
                        $('#mineral').append(html);
                        true;
                    } 
                    else{
                        html = '<option value="' + element.id + '">' + element.nombre + '</option>';
                        $('#mineral').append(html);
                    }
                    // fecha = element.fecha.substr(8, 2) + "/" + element.fecha.substr(5, 2) + "/" + element.fecha.substr(0, 4);
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
        url: "/ajaxInventarioPzo/" + id,
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
