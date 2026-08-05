$(document).ready( function() {
    mostrarListado();
    listadoTarifas();

    // Ir al formulario desde el listado
    $(document).on("click", "#nuevo", function (e){
        obtenerResidente(0);
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
        const tarifa = $("#tarifa").val();
        const fecha = $("#fecha").val();
        const fecha_fin = $("#fecha_fin").val();
        const observaciones = $("#observaciones").val();
        
        const info = {
            id_residente: residente,
            monto: tarifa,
            fecha_inicio: fecha,
            fecha_fin: fecha_fin,
            observaciones: observaciones
        }
        if(id == "0"){
            guardarTarifas(info);
        } else{
            modificarTarifas(id, info);
        }
        mostrarListado();
    });

    $(document).on("click", "#editar", function(e){
        const id = $(this).attr("value");
        obtenerTarifas(id);
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
                eliminarTarifas(id);
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
function listadoTarifas(){
    $.ajax({
        type: "GET", 
        url: "/ajaxTarifas",
        dataType: "json",
        success: function(response) {
            html = '';
            response.forEach((element) => {
                html += `
                <tr class="text-center">
                    <td colspan = "1">${element.id}</td>
                    <td colspan = "3">${element.nombres} ${element.apellidos}</td>
                    <td colspan = "3">${element.monto}$</td>
                    <td colspan = "2">${formatearFecha(element.fecha_inicio)}</td>
                    <td colspan = "2">${element.fecha_fin ? formatearFecha(element.fecha_fin) : "Tarifa aún activa"}</td>
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
            html = '<option selected>Elegir Residente</option>';
            if(id==0){
                $("#residente").append(html);

                response.forEach((element) => {
                    html = '<option value="' + element.id + '">' + element.nombres + '</option>';
                    $('#residente').append(html);
                });
            } 
            
            if(id > 0){
                response.forEach((element) => {
                    if(element.id == id){
                        html = '<option selected value="' + element.id + '">' + element.nombres + '</option>';
                        $('#residente').append(html);
                        true;
                    } 
                    else{
                        html = '<option value="' + element.id + '">' + element.nombres + '</option>';
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

function guardarTarifas(params){
    $.ajax({
        type: "POST",
        url: "/ajaxTarifas",
        data: params,
        dataType: "json",
        success: function (response){
            listadoTarifas();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function modificarTarifas(id, params){
    $.ajax({
        type: "PUT",
        url: "/ajaxTarifas/" + id,
        data: params,
        dataType: "json",
        success: function (response){
            listadoTarifas();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function obtenerTarifas(id){
    $.ajax({
        type: "GET",
        url: "/ajaxTarifas/" + id,
        dataType: "json",
        success: function (response){
            response = response[0];
            $("#id").val(response.id);
            $("#residente").val(obtenerResidente(response.id_residente));
            $("#tarifa").val(response.monto);
            $("#fecha").val(response.fecha_inicio);
            $("#fecha_fin").val(response.fecha_fin);
            $("#observaciones").val(response.observaciones);
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function eliminarTarifas(id){
    $.ajax({
        type: "DELETE",
        url: "/ajaxTarifas/" + id,
        dataType: "json",
        success: function (response){
            listadoTarifas();
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
    $("#tarifa").val(0);
    $("#fecha").val("");
    $("#fecha_fin").val("");
    $("#observaciones").val("");
}
