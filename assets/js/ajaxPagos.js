$(document).ready( function() {
    mostrarListado();
    listadoPagos();

    // Ir al formulario desde el listado
    $(document).on("click", "#nuevo", function (e){
        obtenerResidente(0);
        obtenerRepresentante(0);
        obtenerCargos(0);
        obtenerCargosPendientes(0);
        obtenerMetodoPago(0);
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
        const residente = $("#residente").val();
        const representante = $("#representante").val();
        const fecha = $("#fecha").val();
        const monto = $("#monto").val();
        const metodo = $("#metodo").val();
        const referencia = $("#referencia").val();
        const observaciones = $("#observaciones").val();

        const aplicaciones = [];

        $(".monto-aplicar").each(function(){

            const montoAplicado = parseFloat($(this).val()) || 0;

            if(montoAplicado > 0){

                aplicaciones.push({
                    id_cargo: $(this).data("id-cargo"),
                    monto_aplicado: montoAplicado
                });

            }

        });
        
        const info = {
            fecha: fecha,
            monto: monto,
            metodo_pago: metodo,
            id_residente: residente,
            id_representante: representante,
            referencia: referencia,
            observaciones: observaciones,
            aplicaciones: aplicaciones
        }
        if(id == "0"){
            guardarPagos(info);
        } else{
            modificarPagos(id, info);
        }
        mostrarListado();
    });

    $(document).on("change", "#residente", function(){

        const idResidente = $(this).val();

        const opcion = $(this).find(":selected");

        const idRepresentante = opcion.data("representante");

        console.log("Residente seleccionado:", idResidente);
        console.log("Representante asociado:", idRepresentante);

        if(idResidente === "Elegir Residente"){
            obtenerCargosPendientes(0);
            obtenerRepresentante(0);
            return;
        }

        obtenerCargosPendientes(idResidente);
        obtenerRepresentanteSegunResidente(idResidente);

    });

    $(document).on("input", "#monto", function(){

        const montoPago = parseFloat($(this).val()) || 0;

        distribuirPago(montoPago);

    });

    $(document).on("click", "#editar", function(e){
        const id = $(this).attr("value");
        obtenerPagos(id);
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
                eliminarPagos(id);
            }
        })
    });

});

function formatearFecha(fechaISO) {
    if (!fechaISO) return '';
    const partes = fechaISO.split('-');
    return `${partes[2]}-${partes[1]}-${partes[0]}`;
}

// Funciones de AJAX
function listadoPagos(){
    $.ajax({
        type: "GET", 
        url: "/ajaxPagos",
        dataType: "json",
        success: function(response) {
            html = '';
            response.forEach((element) => {
                html += `
                <tr class="text-center">
                    <td colspan = "2">${formatearFecha(element.fecha)}</td>
                    <td colspan = "2">${element.nombres_residente} ${element.apellidos_residente}</td>
                    <td colspan = "2">${element.nombres_representante} ${element.apellidos_representante}</td>
                    <td colspan = "2">${element.monto}</td>
                    <td colspan = "2">${element.metodo_pago}</td>
                    <td colspan = "2">${element.referencia}</td>
                    <td colspan = "3">${element.observaciones ? element.observaciones : "Sin comentarios"}</td>
                    <td colspan = "2">
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

function obtenerResidente(id){
    $.ajax({
        type: "GET",
        url: "/ajaxResidentes",
        dataType: "json",
        success: function (response){
            $("#residente").empty();
            valor='';
            html = '<option selected>Elegir Residente</option>';
            if(id==0){
                $("#residente").append(html);

                response.forEach((element) => {
                    html = '<option value="' + element.id + '" data-representante="' + element.id_representante + '">' + element.nombres + ' ' + element.apellidos + '</option>';
                    $('#residente').append(html);
                });
            } 
            
            if(id > 0){
                response.forEach((element) => {
                    if(element.id == id){
                        html = '<option selected value="' + element.id + '" data-representante="' + element.id_representante + '">' + element.nombres + ' ' + element.apellidos + '</option>';
                        $('#residente').append(html);
                        true;
                    } 
                    else{
                        html = '<option value="' + element.id + '" data-representante="' + element.id_representante + '">' + element.nombres + ' ' + element.apellidos + '</option>';
                        $('#residente').append(html);
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

function obtenerRepresentante(id){
    $.ajax({
        type: "GET",
        url: "/ajaxRepresentantes",
        dataType: "json",
        success: function (response){
            $("#representante").empty();
            valor='';
            html = '<option selected>Elegir Representante</option>';
            if(id==0){
                $("#representante").append(html);

                response.forEach((element) => {
                    html = '<option value="' + element.id + '">' + element.nombres + ' ' + element.apellidos + '</option>';
                    $('#representante').append(html);
                });
            } 
            
            if(id > 0){
                response.forEach((element) => {
                    if(element.id == id){
                        html = '<option selected value="' + element.id + '">' + element.nombres + ' ' + element.apellidos + '</option>';
                        $('#representante').append(html);
                        true;
                    } 
                    else{
                        html = '<option value="' + element.id + '">' + element.nombres + ' ' + element.apellidos + '</option>';
                        $('#representante').append(html);
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

function obtenerCargos(id){
    $.ajax({
        type: "GET",
        url: "/ajaxCargos",
        dataType: "json",
        success: function (response){
            $("#cargo").empty();
            valor='';
            html = '<option selected>Seleccionar Cargo</option>';
            if(id==0){
                $("#cargo").append(html);

                response.forEach((element) => {
                    html = '<option value="' + element.id + '">' + element.nombres + ' ' + element.apellidos + ' - ' + element.monto + '$' + '</option>';
                    $('#cargo').append(html);
                });
            } 
            
            if(id > 0){
                response.forEach((element) => {
                    if(element.id == id){
                        html = '<option selected value="' + element.id + '">' + element.nombres + ' ' + element.apellidos + ' - ' + element.monto + '$' + '</option>';
                        $('#cargo').append(html);
                        true;
                    } 
                    else{
                        html = '<option value="' + element.id + '">' + element.nombres + ' ' + element.apellidos + ' - ' + element.monto + '$' + '</option>';
                        $('#cargo').append(html);
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

function obtenerMetodoPago(id){
    $.ajax({
        type: "GET",
        url: "/ajaxMetodoPago",
        dataType: "json",
        success: function (response){
            $("#metodo").empty();
            valor='';
            html = '<option selected>Seleccionar Método de pago</option>';
            if(id==0){
                $("#metodo").append(html);

                response.forEach((element) => {
                    html = '<option value="' + element.id + '">' + element.nombre + '</option>';
                    $('#metodo').append(html);
                });
            } 
            
            if(id > 0){
                response.forEach((element) => {
                    if(element.id == id){
                        html = '<option selected value="' + element.id + '">' + element.nombre + '</option>';
                        $('#metodo').append(html);
                        true;
                    } 
                    else{
                        html = '<option value="' + element.id + '">' + element.nombre + '</option>';
                        $('#metodo').append(html);
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

function obtenerCargosPendientes(id, aplicaciones = []){

    $.ajax({
        type: "GET",
        url: "/ajaxCargosPendientes",
        dataType: "json",

        success: function(response){

            $("#tbodyCargos").empty();

            let valor = 0;
            let html = '';

            // -----------------------------------------
            // SIN RESIDENTE SELECCIONADO
            // -----------------------------------------

            if(id == 0){

                html = `
                    <tr class="text-center">
                        <td colspan="10">
                            Selecciona un residente para ver sus cargos pendientes
                        </td>
                    </tr>
                `;

                $("#tbodyCargos").html(html);

                return;
            }


            // -----------------------------------------
            // CARGOS DEL RESIDENTE
            // -----------------------------------------

            response.forEach((element) => {

                if(element.id_residente == id){

                    /*
                    Buscar si este cargo tiene una
                    aplicación perteneciente al pago
                    que estamos editando.
                    */

                    const aplicacion = aplicaciones.find(
                        app => app.id_cargo == element.id
                    );


                    /*
                    Monto que este pago tenía aplicado
                    anteriormente sobre este cargo.
                    */

                    const montoAplicadoAnteriormente = aplicacion
                        ? parseFloat(aplicacion.monto_aplicado)
                        : 0;


                    /*
                    Saldo real disponible para la edición.

                    Ejemplo:

                    saldo actual = 200
                    aplicación anterior = 100

                    saldo disponible = 300
                    */

                    const saldoEdicion =
                        parseFloat(element.saldo) +
                        montoAplicadoAnteriormente;


                    html += `
                        <tr 
                            class="text-center"
                            data-id-cargo="${element.id}"
                        >

                            <td colspan="3">
                                ${element.periodo}
                            </td>

                            <td colspan="2">
                                ${element.monto}
                            </td>

                            <td colspan="2">
                                ${element.total_pagado}
                            </td>

                            <td colspan="2">
                                ${element.saldo}
                            </td>

                            <td colspan="2">

                                <input 
                                    type="number"
                                    class="form-control monto-aplicar"

                                    data-id-cargo="${element.id}"

                                    data-saldo="${saldoEdicion}"

                                    value="${montoAplicadoAnteriormente.toFixed(2)}"

                                    min="0"

                                    max="${saldoEdicion}"

                                    step="0.01"
                                >

                            </td>

                        </tr>
                    `;

                    valor++;

                }

            });


            // -----------------------------------------
            // SIN CARGOS PENDIENTES
            // -----------------------------------------

            if(valor == 0){

                html = `
                    <tr class="text-center">
                        <td colspan="10">
                            Este residente no tiene cargos pendientes
                        </td>
                    </tr>
                `;

            }


            $("#tbodyCargos").html(html);

        },

        error: function(req, status, error){

            const err = req.responseText;

            console.log(err);

        }

    });

}

function obtenerRepresentanteSegunResidente(id){
    $.ajax({
        type: "GET",
        url: "/ajaxRepresentantes",
        dataType: "json",
        success: function (response){
            $("#representante").empty();
            valor='';
            html = '<option selected>Elegir Representante</option>';
            if(id==0){
                $("#representante").append(html);

                response.forEach((element) => {
                    html = '<option value="' + element.id + '">' + element.nombres + ' ' + element.apellidos + '</option>';
                    $('#representante').append(html);
                });
            } 
            
            if(id > 0){
                response.forEach((element) => {
                    if(element.id == id){
                        html = '<option selected value="' + element.id + '">' + element.nombres + ' ' + element.apellidos + '</option>';
                        $('#representante').append(html);
                        true;
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

function distribuirPago(montoPago){

    let montoRestante = montoPago;

    $(".monto-aplicar").each(function(){

        const saldoCargo = parseFloat($(this).data("saldo")) || 0;

        let montoAplicar = 0;

        if(montoRestante > 0){

            montoAplicar = Math.min(
                montoRestante,
                saldoCargo
            );

            montoRestante -= montoAplicar;

        }

        $(this).val(montoAplicar.toFixed(2));

    });

    console.log("Monto restante:", montoRestante);

}

function guardarPagos(params){
    $.ajax({
        type: "POST",
        url: "/ajaxPagos",
        data: params,
        dataType: "json",
        success: function (response){
            listadoPagos();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function modificarPagos(id, params){
    $.ajax({
        type: "PUT",
        url: "/ajaxPagos/" + id,
        data: params,
        dataType: "json",
        success: function (response){
            listadoPagos();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}
function obtenerPagos(id){

    $.ajax({
        type: "GET",
        url: "/ajaxPagos/" + id,
        dataType: "json",
        success: function(response){

            const pago = response.pago;
            const aplicaciones = response.aplicaciones;

            console.log("Pago:", pago);

            console.log(
                "Aplicaciones:",
                aplicaciones
            );


            // -----------------------------------------
            // DATOS DEL PAGO
            // -----------------------------------------

            $("#id").val(pago.id);
            obtenerResidente(pago.id_residente);
            obtenerRepresentante(pago.id_representante);
            $("#fecha").val(pago.fecha);
            //$("#monto").val(pago.monto);
            obtenerMetodoPago(pago.metodo_pago);
            $("#referencia").val(pago.referencia);
            $("#observaciones").val(pago.observaciones);


            // -----------------------------------------
            // CARGAR CARGOS
            // -----------------------------------------

            obtenerCargosPendientes(
                pago.id_residente,
                aplicaciones
            );

        },

        error: function(req, status, error){

            const err = req.responseText;

            console.log(err);

        }

    });

}

function eliminarPagos(id){
    $.ajax({
        type: "DELETE",
        url: "/ajaxPagos/" + id,
        dataType: "json",
        success: function (response){
            listadoPagos();
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
    $("#residente").val(obtenerResidente(0));
    $("#representante").val(obtenerRepresentante(0));
    $("#fecha").val("");
    $("#monto").val(0);
    $("#metodo").val(obtenerMetodoPago(0));
    $("#referencia").val("");
    $("#observaciones").val("");
}
