$(document).ready( function() {
    let idmaterial = 0;
    let idempresa = 0;
    let idplanta = 0;
    mostrarListado();
    listadoCarga();

    // Ir al formulario desde el listado
    $(document).on("click", "#nuevo", function (e){
        // obtenerProcesamiento(0);
        obtenerEmpresa(0);
        // obtenerPlanta(0);
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
        const nombre = $("#nombre").val();
        const numero = $("#numero").val();
        const destino = $("#destino").val();
        const empresa = $("#empresa").val();
        const exportacion = $("#exportacion").val();
        const fecha = $("#fecha").val();
        const comentario = $("#comentario").val();
        const info = {
            id_mineral: mineral,
            cantidad: cantidad,
            nombre_barco: nombre,
            num_embarque: numero,
            destino: destino,
            id_empresa: empresa,
            exportacion: exportacion,
            fecha: fecha,
            comentario: comentario
        }

        // console.log("Datos a enviar:", info);

        if(id == "0"){
            guardarCarga(info);
        } else{
            modificarCarga(id, info);
        }
        mostrarListado();
    });

    $(document).on("click", "#editar", function(e){
        const id = $(this).attr("value");
        obtenerCarga(id);
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
                eliminarCarga(id);
            }
        })
    });

});

// Funciones de AJAX
function listadoCarga(){
    $.ajax({
        type: "GET", 
        url: "/ajaxCargaBarco",
        dataType: "json",
        success: function(response) {
            html = '';
            response.forEach((element) => {
                respuesta = "";
                if(element.exportacion == 1 || element.exportacion == true){
                    respuesta="Si";
                } else{
                    respuesta="No";
                }

                fecha = element.fecha.substr(8, 2) + "/" + element.fecha.substr(5, 2) + "/" + element.fecha.substr(0, 4);
                html += `
                <tr>
                    <td>${element.id}</td>
                    <td>${element.mineral}</td>
                    <td>${element.cantidad}</td>
                    <td>${element.nombre_barco}</td>
                    <td>${element.num_embarque}</td>
                    <td>${element.destino}</td>
                    <td>${element.empresa}</td>
                    <td>${respuesta}</td>
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

function guardarCarga(params){
    $.ajax({
        type: "POST",
        url: "/ajaxCargaBarco",
        data: params,
        dataType: "json",
        success: function (response){
            console.log("Respuesta del servidor:", response);
            listadoCarga();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function modificarCarga(id, params){
    $.ajax({
        type: "PUT",
        url: "/ajaxCargaBarco/" + id,
        data: params,
        dataType: "json",
        success: function (response){
            listadoCarga();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function obtenerCarga(id){
    $.ajax({
        type: "GET",
        url: "/ajaxCargaBarco/" + id,
        dataType: "json",
        success: function (response){
            if(response.exportacion == 1 || response.exportacion == true){
                    respuesta="Si";
                    valor=1;
                } else{
                    respuesta="No";
                    valor=0;
                }
            response = response[0];
            $("#id").val(response.id);
            // $("#material").val(response.id_material);
            obtenerMineral(response.id_mineral);
            $("#cantidad").val(response.cantidad);
            $("#nombre").val(response.nombre_barco);
            $("#numero").val(response.num_embarque);
            $("#destino").val(response.destino);
            // obtenerPlanta(id);
            // $("#empresa").val(response.id_empresa);
            obtenerEmpresa(response.id_empresa);
            $("#exportacion").val(valor);
            $("#fecha").val(response.fecha);
            $("#comentario").val(response.comentario);
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function obtenerEmpresa(id){
    $.ajax({
        type: "GET",
        url: "/ajaxEmpresaEx",
        dataType: "json",
        success: function (response){
            $("#empresa").empty();
            valor='';
            html = '<option selected>Elegir Empresa</option>';
            if(id==0){
                $("#empresa").append(html);

                response.forEach((element) => {
                    // fecha = element.fecha.substr(8, 2) + "/" + element.fecha.substr(5, 2) + "/" + element.fecha.substr(0, 4);
                    html = '<option value="' + element.id + '">' + element.nombre + '</option>';
                    $('#empresa').append(html);
                    // if(element.id == id) valor = element.nombre;
                });
            } 
            
            // empresaName = '<option selected>' + valor + '</option>';
            if(id > 0){
                response.forEach((element) => {
                    if(element.id == id){
                        // valor = element.nombre;
                        html = '<option selected value="' + element.id + '">' + element.nombre + '</option>';
                        $('#empresa').append(html);
                        true;
                    } 
                    else{
                        html = '<option value="' + element.id + '">' + element.nombre + '</option>';
                        $('#empresa').append(html);
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

function eliminarCarga(id){
    $.ajax({
        type: "DELETE",
        url: "/ajaxCargaBarco/" + id,
        dataType: "json",
        success: function (response){
            listadoProcesamiento();
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
