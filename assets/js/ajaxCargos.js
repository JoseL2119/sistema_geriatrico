$(document).ready( function() {
    mostrarListado();
    listadoCargos();

    // Ir al formulario desde el listado
    $(document).on("click", "#nuevo", function (e){
        obtenerResidente(0);
        obtenerTarifas(0);
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
        const tarifa = $("#tarifa").val();
        const monto = $("#monto").val();
        const periodo = $("#periodo").val();
        const fecha = $("#fecha").val();
        const fecha_fin = $("#fecha_fin").val();
        const observaciones = $("#observaciones").val();
        
        const info = {
            id_tarifa: tarifa,
            monto: monto,
            periodo: periodo,
            fecha_emision: fecha,
            fecha_vencimiento: fecha_fin,
            observaciones: observaciones
        }
        if(id == "0"){
            guardarCargos(info);
        } else{
            modificarCargos(id, info);
        }
        mostrarListado();
    });

    $(document).on("change", "#residente", function(){

        const idResidente = $(this).val();

        console.log("Residente seleccionado:", idResidente);

        if(idResidente === "Elegir Residente"){
            obtenerTarifasSegunResidente(0);
            return;
        }

        obtenerTarifasSegunResidente(idResidente);

    });

    $(document).on("change", "#tarifa", function(){

        const idTarifa = $(this).val();

        console.log("Tarifa Seleccionada:", idTarifa);

        if(idTarifa === "Seleccionar Tarifa"){
            obtenerMontoSegunTarifa(0);
            return;
        }

        obtenerMontoSegunTarifa(idTarifa);

    });

    $(document).on("click", "#editar", function(e){
        const id = $(this).attr("value");
        obtenerCargos(id);
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
                eliminarCargos(id);
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
function listadoCargos(){
    $.ajax({
        type: "GET", 
        url: "/ajaxCargos",
        dataType: "json",
        success: function(response) {
            html = '';
            response.forEach((element) => {
                html += `
                <tr class="text-center">
                    <td colspan = "1">${element.id}</td>
                    <td colspan = "2">${element.nombres} ${element.apellidos}</td>
                    <td colspan = "2">${element.monto}</td>
                    <td colspan = "2">${element.periodo}</td>
                    <td colspan = "2">${formatearFecha(element.fecha_emision)}</td>
                    <td colspan = "2">${formatearFecha(element.fecha_vencimiento)}</td>
                    <td colspan = "2">${element.observaciones ? element.observaciones : "Sin comentarios"}</td>
                    <td colspan = "2">
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

function obtenerResidente(id){
    $.ajax({
        type: "GET",
        url: "/ajaxResidentes",
        dataType: "json",
        success: function (response){
            $("#residente").empty();
            valor='';
            html = '<option selected>Elegir residente</option>';
            if(id==0){
                $("#residente").append(html);

                response.forEach((element) => {
                    html = '<option value="' + element.id + '">' + element.nombres + ' ' + element.apellidos + '</option>';
                    $('#residente').append(html);
                });
            } 
            
            if(id > 0){
                response.forEach((element) => {
                    if(element.id == id){
                        html = '<option selected value="' + element.id + '">' + element.nombres + ' ' + element.apellidos + '</option>';
                        $('#residente').append(html);
                        true;
                    } 
                    else{
                        html = '<option value="' + element.id + '">' + element.nombres + ' ' + element.apellidos + '</option>';
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

function obtenerTarifas(id){
    $.ajax({
        type: "GET",
        url: "/ajaxTarifas",
        dataType: "json",
        success: function (response){
            $("#tarifa").empty();
            valor='';
            html = '<option selected>Seleccionar tarifa</option>';
            if(id==0){
                $("#tarifa").append(html);

                response.forEach((element) => {
                    html = '<option value="' + element.id + '">' + element.nombres + ' ' + element.apellidos + ' - ' + element.monto + '$' + '</option>';
                    $('#tarifa').append(html);
                });
            } 
            
            if(id > 0){
                response.forEach((element) => {
                    if(element.id == id){
                        html = '<option selected value="' + element.id + '">' + element.nombres + ' ' + element.apellidos + ' - ' + element.monto + '$' + '</option>';
                        $('#tarifa').append(html);
                        true;
                    } 
                    else{
                        html = '<option value="' + element.id + '">' + element.nombres + ' ' + element.apellidos + ' - ' + element.monto + '$' + '</option>';
                        $('#tarifa').append(html);
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

function obtenerTarifasSegunResidente(id){
    $.ajax({
        type: "GET",
        url: "/ajaxTarifas",
        dataType: "json",
        success: function (response){
            $("#tarifa").empty();
            valor='';
            html = '<option selected>Seleccionar tarifa</option>';
            if(id==0){
                $("#tarifa").append(html);

                response.forEach((element) => {
                    html = '<option value="' + element.id + '">' + element.nombres + ' ' + element.apellidos + ' - ' + element.monto + '$' + '</option>';
                    $('#tarifa').append(html).trigger("change");
                });
            } 
            
            if(id > 0){
                response.forEach((element) => {
                    if(element.id_residente == id){
                        html = '<option value="' + element.id + '">' + element.nombres + ' ' + element.apellidos + ' - ' + element.monto + '$' + '</option>';
                        $('#tarifa').append(html).trigger("change");
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

function obtenerMontoSegunTarifa(id){
    $.ajax({
        type: "GET",
        url: "/ajaxTarifas",
        dataType: "json",
        success: function (response){
            if(id == 0){
                $("#monto").val(0);
                return;
            }

            response.forEach((element) => {

                if(element.id == id){
                    $("#monto").val(element.monto);
                }

            });
        },
        error: function (req, status, error){
            var err = req.responseText;
            console.log(err);
            alert(err.Message);
        }
    });
}

function guardarCargos(params){
    $.ajax({
        type: "POST",
        url: "/ajaxCargos",
        data: params,
        dataType: "json",
        success: function (response){
            listadoCargos();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function modificarCargos(id, params){
    $.ajax({
        type: "PUT",
        url: "/ajaxCargos/" + id,
        data: params,
        dataType: "json",
        success: function (response){
            listadoCargos();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function obtenerCargos(id){
    $.ajax({
        type: "GET",
        url: "/ajaxCargos/" + id,
        dataType: "json",
        success: function (response){
            response = response[0];
            $("#id").val(response.id);
            $("#residente").val(obtenerResidente(response.id_residente));
            $("#tarifa").val(obtenerTarifas(response.id_tarifa));
            $("#monto").val(response.monto);
            $("#periodo").val(response.periodo);
            $("#fecha").val(response.fecha_emision);
            $("#fecha_fin").val(response.fecha_vencimiento);
            $("#observaciones").val(response.observaciones);
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function eliminarCargos(id){
    $.ajax({
        type: "DELETE",
        url: "/ajaxCargos/" + id,
        dataType: "json",
        success: function (response){
            listadoCargos();
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
    $("#tarifa").val(obtenerTarifas(0));
    $("#monto").val(0);
    $("#periodo").val("");
    $("#fecha").val("");
    $("#fecha_fin").val("");
    $("#observaciones").val("");
}
