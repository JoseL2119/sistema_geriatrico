$(document).ready( function() {
    mostrarListado();
    listadoDetallesConsignacionesPendientes();

    // Ir al formulario desde el listado
    $(document).on("click", "#nuevo", function (e){
        mostrarFormulario();
        limpiarFormulario();
    });

    // Ir al listado desde el formulario
    $(document).on("click", "#volver", function (e){
        mostrarListado();
    });

    $(document).on("change", "#fecha_inicio, #fecha_fin", function (e){
        const fecha_inicio = $("#fecha_inicio").val();
        const fecha_fin = $("#fecha_fin").val();
        
        if(fecha_inicio && fecha_inicio !== "" && fecha_fin && fecha_fin !== ""){
            const filtros = {
                fecha_inicio: fecha_inicio,
                fecha_fin: fecha_fin
            }

            filtrar(filtros);
        }
        
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
            guardarDetallesConsignacionesPendientes(info);
        } else{
            modificarDetallesConsignacionesPendientes(id, info);
        }
        mostrarListado();
    });

    $(document).on("click", "#editar", function(e){
        const id = $(this).attr("value");
        obtenerDetallesConsignacionesPendientes(id);
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
                eliminarDetallesConsignacionesPendientes(id);
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
function listadoDetallesConsignacionesPendientes(){
    $.ajax({
        type: "GET", 
        url: "/ajaxDetallesConsignacionesPendientes",
        dataType: "json",
        success: function(response) {
            console.log("Respuesta recibida:", response); // Para depuración
            html = '';
            let totalPendientes = 0, totalParciales = 0, totalArticulosPendientes = 0, cont = 0;
            response.forEach((element) => {
                const fecha = formatearFecha(element.fecha);
                totalPendientes += Number(element.total_requerimientos_pendientes);
                totalParciales += Number(element.total_requerimientos_parciales);
                totalArticulosPendientes += Number(element.total_articulos_pendientes);
                html += `
                <tr class="text-center">
                    <td>${element.nombres_residente} ${element.apellidos_residente}</td>
                    <td>${element.total_requerimientos_pendientes}</td>
                    <td>${element.total_requerimientos_parciales}</td>
                    <td>${element.total_articulos_pendientes}</td>
                    <td colspan = "2">
                        <a class="btn btn-info" id="verFicha" value="${element.id_residente}">Ver ficha</a>
                    </td>
                </tr>
                `;
                cont++;
            });

            if(cont === 0){
                html = '<tr><td colspan="5" class="text-center text-muted">No hay consignaciones pendientes por entregar</td></tr>';
            }

            else{
                html += `
                <tr class="text-center fw-bold">
                    <td colspan="1">Totales</td>
                    <td>${(totalPendientes)}</td>
                    <td>${(totalParciales)}</td>
                    <td>${(totalArticulosPendientes)}</td>
                </tr>`;
            }

            $("#tbody").html(html); //id del tbody de la tabla
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function filtrar(filtros){
    $.ajax({
        type: "GET", 
        url: "/ajaxDetallesConsignacionesPendientes?" + $.param(filtros),
        dataType: "json",
        success: function(response) {
            console.log("Respuesta recibida:", response); // Para depuración
            html = '';
            let totalHumedas = 0, promedioHumedad = 0, totalSecas = 0, cont = 0;
            response.forEach((element) => {
                const humedad = Number(element.humedad_promedio);
                totalHumedas += Number(element.toneladas_procesadas);
                promedioHumedad += humedad;
                const fecha = formatearFecha(element.fecha);
                totalSecas += (element.toneladas_procesadas - ((element.toneladas_procesadas)*(humedad/100)));
                html += `
                <tr class="text-center">
                    <td>${fecha}</td>
                    <td>${element.toneladas_procesadas} Tn</td>
                    <td>${(humedad).toFixed(2)}%</td>
                    <td>${(element.toneladas_procesadas - ((element.toneladas_procesadas)*(humedad/100))).toFixed(2)} Tn</td>
                </tr>
                `;
                cont++;
            });

            if(cont === 0){
                html = '<tr><td colspan="4" class="text-center text-muted">No hay registros para el día de hoy</td></tr>';
            }

            else{
                html += `
                <tr class="text-center fw-bold">
                    <td>Totales</td>
                    <td>${(totalHumedas)} Tn</td>
                    <td>${(promedioHumedad/cont).toFixed(2)}%</td>
                    <td>${(totalSecas).toFixed(2)} Tn</td>
                </tr>`;
            }

            $("#tbody").html(html); //id del tbody de la tabla
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function guardarDetallesConsignacionesPendientes(params){
    $.ajax({
        type: "POST",
        url: "/ajaxDetallesConsignacionesPendientes",
        data: params,
        dataType: "json",
        success: function (response){
            listadoDetallesConsignacionesPendientes();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function modificarDetallesConsignacionesPendientes(id, params){
    $.ajax({
        type: "PUT",
        url: "/ajaxDetallesConsignacionesPendientes/" + id,
        data: params,
        dataType: "json",
        success: function (response){
            listadoDetallesConsignacionesPendientes();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function obtenerDetallesConsignacionesPendientes(id){
    $.ajax({
        type: "GET",
        url: "/ajaxDetallesConsignacionesPendientes/" + id,
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

function eliminarDetallesConsignacionesPendientes(id){
    $.ajax({
        type: "DELETE",
        url: "/ajaxDetallesConsignacionesPendientes/" + id,
        dataType: "json",
        success: function (response){
            listadoDetallesConsignacionesPendientes();
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
