$(document).ready( function() {
    let idmaterial = 0;
    let idempresa = 0;
    let idplanta = 0;
    mostrarListado();
    listadoDespacho();

    // Ir al formulario desde el listado
    $(document).on("click", "#nuevo", function (e){
        // obtenerProcesamiento(0);
        obtenerEmpresa(0);
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
        const empresa = $("#empresa").val();
        const cantidadCamiones = $("#cantidadCamiones").val();
        const cantidadTotal = $("#cantidadTotal").val();
        const fecha = $("#fecha").val();
        const comentario = $("#comentario").val();
        const info = {
            id_tipo_mineral: mineral,
            id_empresa_d: empresa,
            cant_camiones: cantidadCamiones,
            total_toneladas: cantidadTotal,
            fecha: fecha,
            comentario: comentario
        }

        // console.log("Datos a enviar:", info);

        if(id == "0"){
            guardarDespacho(info);
        } else{
            modificarDespacho(id, info);
        }
        mostrarListado();
    });

    $(document).on("click", "#editar", function(e){
        const id = $(this).attr("value");
        obtenerDespacho(id);
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
                eliminarDespacho(id);
            }
        })
    });

});

// Funciones de AJAX
function listadoDespacho(){
    $.ajax({
        type: "GET", 
        url: "/ajaxCamionesPzo",
        dataType: "json",
        success: function(response) {
            html = '';
            response.forEach((element) => {
                fecha = element.fecha.substr(8, 2) + "/" + element.fecha.substr(5, 2) + "/" + element.fecha.substr(0, 4);
                html += `
                <tr>
                    <td>${element.id}</td>
                    <td>${element.mineral}</td>
                    <td>${element.empresa}</td>
                    <td>${element.cant_camiones}</td>
                    <td>${element.total_toneladas} Tn</td>
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

function guardarDespacho(params){
    $.ajax({
        type: "POST",
        url: "/ajaxCamionesPzo",
        data: params,
        dataType: "json",
        success: function (response){
            console.log("Respuesta del servidor:", response);
            listadoDespacho();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function modificarDespacho(id, params){
    $.ajax({
        type: "PUT",
        url: "/ajaxCamionesPzo/" + id,
        data: params,
        dataType: "json",
        success: function (response){
            listadoDespacho();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function obtenerDespacho(id){
    $.ajax({
        type: "GET",
        url: "/ajaxCamionesPzo/" + id,
        dataType: "json",
        success: function (response){
            response = response[0];
            $("#id").val(response.id);
            // $("#material").val(response.id_material);
            obtenerEmpresa(response.id_empresa_d);
            // $("#mina").val(response.id_mina);
            // obtenerPlanta(id);
            // $("#empresa").val(response.id_empresa);
            obtenerMineral(response.id_tipo_mineral);
            $("#cantidadCamiones").val(response.cant_camiones);
            $("#cantidadTotal").val(response.total_toneladas);
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
        url: "/ajaxEmpresa",
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
        url: "/ajaxMineralProcesado",
        dataType: "json",
        success: function (response){
            $("#mineral").empty();
            valor='';
            html = '<option selected>Elegir Mineral</option>';
            if(id==0){
                $("#mineral").append(html);
                response.forEach((element) => {
                    // fecha = element.fecha.substr(8, 2) + "/" + element.fecha.substr(5, 2) + "/" + element.fecha.substr(0, 4);
                    html = '<option value="' + element.id + '">' + element.tipo + '</option>';
                    $('#mineral').append(html);
                    
                    // if(element.id == id) valor = element.tipo;
                });
            } 
            // mineralName = '<option selected>' + valor + '</option>';
            if(id > 0){
                // $('#mineral').value(valor);

                response.forEach((element) => {
                    if(element.id == id){
                        // valor = element.tipo;
                        html = '<option selected value="' + element.id + '">' + element.tipo + '</option>';
                        $('#mineral').append(html);
                        true;
                    } 
                    else{
                        html = '<option value="' + element.id + '">' + element.tipo + '</option>';
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

function eliminarProcesamiento(id){
    $.ajax({
        type: "DELETE",
        url: "/ajaxCamionesPzo/" + id,
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
