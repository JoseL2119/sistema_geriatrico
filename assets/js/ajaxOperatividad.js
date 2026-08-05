$(document).ready( function() {
    mostrarListado();
    listadoOperatividad();

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
            guardarOperatividad(info);
        } else{
            modificarOperatividad(id, info);
        }
        mostrarListado();
    });

    $(document).on("click", "#editar", function(e){
        const id = $(this).attr("value");
        obtenerOperatividad(id);
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
                eliminarOperatividad(id);
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
function listadoOperatividad(){
    $.ajax({
        type: "GET", 
        url: "/ajaxOperatividad",
        dataType: "json",
        success: function(response) {
            console.log("Respuesta recibida:", response); // Para depuración
            html = '';
            let totalHoras = 0, totalMinutos = 0, cont = 0;
            response.forEach((element) => {
                const [horas, minutos] = element.total_horas_trabajadas.split(':').map(Number);
                totalHoras += horas;
                totalMinutos += minutos;
                const fecha = formatearFecha(element.fecha);
                
                html += `
                <tr class="text-center">
                    <td>${fecha}</td>
                    <td>${element.nombre_maquina}</td>
                    <td>${element.total_horas_trabajadas}</td>
                </tr>
                `;
                cont++;
            });

            if(cont === 0){
                html = '<tr><td colspan="3" class="text-center text-muted">No hay registros para los filtros seleccionados</td></tr>';
            }

            else{

                totalHoras += Math.floor(totalMinutos / 60);
                totalMinutos = totalMinutos % 60;

                // Formatear el total como HH:MM
                const totalFormateado = `${totalHoras.toString().padStart(2, '0')}:${totalMinutos.toString().padStart(2, '0')}`;


                html += `
                <tr class="text-center fw-bold">
                    <td colspan="2">Totales</td>
                    <td>${(totalFormateado)} h</td>
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
        url: "/ajaxOperatividad?" + $.param(filtros),
        dataType: "json",
        success: function(response) {
            console.log("Respuesta recibida:", response); // Para depuración
            html = '';
            let total = 0, cont = 0;
            response.forEach((element) => {
                total += Number(element.total_horas_trabajadas);
                const fecha = formatearFecha(element.fecha);
                
                html += `
                <tr class="text-center">
                    <td>${fecha}</td>
                    <td>${element.nombre_maquina}</td>
                    <td>${element.total_horas_trabajadas}</td>
                </tr>
                `;
                cont++;
            });

            if(cont === 0){
                html = '<tr><td colspan="3" class="text-center text-muted">No hay registros para los filtros seleccionados</td></tr>';
            }

            else{
                html += `
                <tr class="text-center fw-bold">
                    <td colspan="2">Totales</td>
                    <td>${(total)} h</td>
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

function guardarOperatividad(params){
    $.ajax({
        type: "POST",
        url: "/ajaxOperatividad",
        data: params,
        dataType: "json",
        success: function (response){
            listadoOperatividad();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function modificarOperatividad(id, params){
    $.ajax({
        type: "PUT",
        url: "/ajaxOperatividad/" + id,
        data: params,
        dataType: "json",
        success: function (response){
            listadoOperatividad();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function obtenerOperatividad(id){
    $.ajax({
        type: "GET",
        url: "/ajaxOperatividad/" + id,
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

function eliminarOperatividad(id){
    $.ajax({
        type: "DELETE",
        url: "/ajaxOperatividad/" + id,
        dataType: "json",
        success: function (response){
            listadoOperatividad();
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
