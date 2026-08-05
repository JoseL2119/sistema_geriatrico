$(document).ready( function() {
    mostrarListado();
    listadoTiemposParada();

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
            guardarTiemposParada(info);
        } else{
            modificarTiemposParada(id, info);
        }
        mostrarListado();
    });

    $(document).on("click", "#editar", function(e){
        const id = $(this).attr("value");
        obtenerTiemposParada(id);
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
                eliminarTiemposParada(id);
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
function listadoTiemposParada(){
    $.ajax({
        type: "GET", 
        url: "/ajaxTiemposParada",
        dataType: "json",
        success: function(response) {
            console.log("Respuesta recibida:", response); // Para depuración
            html = '';
            let total1 = 0, total2 = 0, total3 = 0, totalC = 0, cont = 0;
            response.forEach((element) => {
                total1 += Number(element.total_tiempo_parada_1);
                total2 += Number(element.total_tiempo_parada_2);
                total3 += Number(element.total_tiempo_parada_3);
                totalC += Number(element.total_minutos_parada_cinta);
                const fecha = formatearFecha(element.fecha);
                
                html += `
                <tr class="text-center">
                    <td>${fecha}</td>
                    <td>${element.total_tiempo_parada_1} min</td>
                    <td>${element.total_tiempo_parada_2} min</td>
                    <td>${element.total_tiempo_parada_3} min</td>
                    <td>${element.total_minutos_parada_cinta} min</td>
                </tr>
                `;
                cont++;
            });

            if(cont === 0){
                html = '<tr><td colspan="4" class="text-center text-muted">No hay registros para los filtros seleccionados</td></tr>';
            }

            else{
                html += `
                <tr class="text-center fw-bold">
                    <td>Totales</td>
                    <td>${(total1/60).toFixed(2)} h</td>
                    <td>${(total2/60).toFixed(2)} h</td>
                    <td>${(total3/60).toFixed(2)} h</td>
                    <td>${(totalC/60).toFixed(2)} h</td>
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
        url: "/ajaxTiemposParada?" + $.param(filtros),
        dataType: "json",
        success: function(response) {
            console.log("Respuesta recibida:", response); // Para depuración
            html = '';
            let total1 = 0, total2 = 0, total3 = 0, totalC = 0, cont = 0;
            response.forEach((element) => {
                total1 += Number(element.total_tiempo_parada_1);
                total2 += Number(element.total_tiempo_parada_2);
                total3 += Number(element.total_tiempo_parada_3);
                totalC += Number(element.total_minutos_parada_cinta);
                const fecha = formatearFecha(element.fecha);
                
                html += `
                <tr class="text-center">
                    <td>${fecha}</td>
                    <td>${element.total_tiempo_parada_1} min</td>
                    <td>${element.total_tiempo_parada_2} min</td>
                    <td>${element.total_tiempo_parada_3} min</td>
                    <td>${element.total_minutos_parada_cinta} min</td>
                </tr>
                `;
                cont++;
            });

            if(cont === 0){
                html = '<tr><td colspan="4" class="text-center text-muted">No hay registros para los filtros seleccionados</td></tr>';
            }

            else{
                html += `
                <tr class="text-center fw-bold">
                    <td>Totales</td>
                    <td>${(total1/60).toFixed(2)} h</td>
                    <td>${(total2/60).toFixed(2)} h</td>
                    <td>${(total3/60).toFixed(2)} h</td>
                    <td>${(totalC/60).toFixed(2)} h</td>
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

function guardarTiemposParada(params){
    $.ajax({
        type: "POST",
        url: "/ajaxTiemposParada",
        data: params,
        dataType: "json",
        success: function (response){
            listadoTiemposParada();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function modificarTiemposParada(id, params){
    $.ajax({
        type: "PUT",
        url: "/ajaxTiemposParada/" + id,
        data: params,
        dataType: "json",
        success: function (response){
            listadoTiemposParada();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function obtenerTiemposParada(id){
    $.ajax({
        type: "GET",
        url: "/ajaxTiemposParada/" + id,
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

function eliminarTiemposParada(id){
    $.ajax({
        type: "DELETE",
        url: "/ajaxTiemposParada/" + id,
        dataType: "json",
        success: function (response){
            listadoTiemposParada();
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
