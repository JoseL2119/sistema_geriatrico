$(document).ready( function() {
    let idmaterial = 0;
    let idempresa = 0;
    let idmina = 0;
    mostrarListado();
    listadoReportes();

    // Ir al formulario desde el listado
    $(document).on("click", "#nuevo", function (e){
        // obtenerExcavacion(0);
        obtenerSupervisor(0);
        mostrarFormulario();
        limpiarFormulario();
    });

    // Ir al listado desde el formulario
    $(document).on("click", "#volver", function (e){
        mostrarListado();
    });

    $(document).on("change", "#filtro_fecha", function (e){
        const fecha_filtro = $("#filtro_fecha").val();
        const filtros = {
            fecha: fecha_filtro
        }
        filtrar(filtros);
    });

    // Cuando se haga submit en el formulario...
    $("#formulario").submit( function(e){
        e.preventDefault();
        const id = $("#id").val();
        const fecha = $("#fecha").val();
        const hora = $("#hora").val();
        const padct = $("#padct").val();
        const molino1 = $("#molino1").val();
        const molino2 = $("#molino2").val();
        const molino3 = $("#molino3").val();
        const molino1P = $("#molino1P").val();
        const molino2P = $("#molino2P").val();
        const molino3P = $("#molino3P").val();
        const hum = $("#hum").val();
        const cArena = $("#cArena").val();
        const nTolva = $("#nTolva").val();
        const comentario = $("#comentario").val();
        const sup = $("#sup").val();
        const info = {
            fecha: fecha,
            hora: hora,
            pad_ct_5: padct,
            operatividad_molino_1: molino1,
            operatividad_molino_2: molino2,
            operatividad_molino_3: molino3,
            parada_molino_1: molino1P,
            parada_molino_2: molino2P,
            parada_molino_3: molino3P,
            porcentaje_humedad: hum,
            codigo_arena: cArena,
            numero_tolva: nTolva,
            observacion: comentario,
            id_supervisor_encargado: sup
        }

        // console.log("Datos a enviar:", info);

        if(id == "0"){
            guardarReporte(info);
        } else{
            modificarReporte(id, info);
        }
        mostrarListado();
    });

    $(document).on("click", "#editar", function(e){
        const id = $(this).attr("value");
        obtenerReporte(id);
        mostrarFormulario();
    });

    $(document).on("click", "#eliminar", function(e){
        const id = $(this).attr("value");
        Swal.fire({
            text: `El registro ${id} será eliminado`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí',
            cancelButtonText: 'No'
        }).then( (result) => {
            if(result.value){
                eliminarReporte(id);
            }
        })
    });

});

// Función para determinar el turno actual
function determinarTurno(horaActual) {
    const hora = parseInt(horaActual.substr(0, 2));
    return (hora >= 7 && hora < 19) ? 'Diurno' : 'Nocturno';
}

// Función para obtener la fecha correcta según el turno
function obtenerFechaSegunTurno(fechaISO, horaActual) {
    const turno = determinarTurno(horaActual);
    const fecha = new Date(fechaISO);
    
    // Si es turno nocturno y la hora es antes de las 7am, pertenece al día anterior
    if (turno === 'Nocturno' && parseInt(horaActual.substr(0, 2)) < 7) {
        fecha.setDate(fecha.getDate() - 1);
    }
    
    return fecha.toISOString().split('T')[0];
}

function formatearFecha(fechaISO) {
    if (!fechaISO) return '';
    const partes = fechaISO.split('-');
    return `${partes[2]}-${partes[1]}-${partes[0]}`;
}

// Funciones de AJAX
function listadoReportes(){

    const hoy = new Date();
    const horaActual = hoy.toTimeString().substr(0, 5);
    const fechaActual = hoy.toISOString().split('T')[0]; // Formato: "2023-11-15"
    const turno = determinarTurno(horaActual);

    const fechaReferencia = obtenerFechaSegunTurno(fechaActual, horaActual);
    $("#titulo-seccion").html(`Reportes del Control de Alimentación - Turno ${turno} <br>${formatearFecha(fechaReferencia)} - ${horaActual}`);

    $.ajax({
        type: "GET", 
        url: "/ajaxReportesSalaControl",
        dataType: "json",
        success: function(response) {
            console.log("Respuesta recibida:", response); // Inspecciona la respuesta

            if (!response) {
                console.error("La respuesta está vacía o es null");
                $("#tbody").html('<tr><td colspan="18">No hay datos disponibles</td></tr>');
                return;
            }

            if (!Array.isArray(response)) {
                console.error("La respuesta no es un array:", typeof response);
                return;
            }

            html = '';
            let totalOp1 = 0, totalOp2 = 0, totalOp3 = 0;
            let totalParada1 = 0, totalParada2 = 0, totalParada3 = 0, totalParadaCT5 = 0;
            let totalHumedad = 0, promedioHumedad = 0.00;
            let count = 0;
            response.forEach((element) => {
                if (element.fecha && element.fecha.startsWith(fechaReferencia)){
                    fecha = element.fecha.substr(8, 2) + "/" + element.fecha.substr(5, 2) + "/" + element.fecha.substr(0, 4);
                
                    totalOp1 += parseFloat(element.operatividad_molino_1) || 0;
                    totalOp2 += parseFloat(element.operatividad_molino_2) || 0;
                    totalOp3 += parseFloat(element.operatividad_molino_3) || 0;
                    totalParada1 += parseFloat(element.parada_molino_1) || 0;
                    totalParada2 += parseFloat(element.parada_molino_2) || 0;
                    totalParada3 += parseFloat(element.parada_molino_3) || 0;
                    totalHumedad += parseFloat(element.porcentaje_humedad) || 0;
                    totalParadaCT5 += parseFloat(element.pad_ct_5)
                    count ++;

                    html += `
                    <tr>
                        <td>${element.hora}</td>
                        <td>${element.pad_ct_5}</td>
                        <td>${element.operatividad_molino_1} min</td>
                        <td>${element.operatividad_molino_2} min</td>
                        <td>${element.operatividad_molino_3} min</td>
                        <td>${element.parada_molino_1} min</td>
                        <td>${element.parada_molino_2} min</td>
                        <td>${element.parada_molino_3} min</td>
                        <td>${element.porcentaje_humedad}%</td>
                        <td>${element.codigo_arena}</td>
                        <td>${element.numero_tolva}</td>
                        <td>${element.observacion}</td>
                        <td>${element.supervisor}</td>
                        <td>
                            <a class="btn btn-success" id="editar" value="${element.id}">Editar</a>
                            <a class="btn btn-danger" id="eliminar" value="${element.id}">Eliminar</a>
                        </td>
                    </tr>
                    `
                }

            }); 

            if (count === 0) {
                html = '<tr><td colspan="14" class="text-center text-muted">No hay registros para el día de hoy</td></tr>';
            } 

            else {
                html += `
                <tr class="fw-bold">
                    <td>Totales</td>
                    <td>${totalParadaCT5} min</td>
                    <td>${totalOp1} min</td>
                    <td>${totalOp2} min</td>
                    <td>${totalOp3} min</td>
                    <td>${totalParada1} min</td>
                    <td>${totalParada2} min</td>
                    <td>${totalParada3} min</td>
                    <td>${(totalHumedad/count).toFixed(2)}%</td>
                    <td colspan="5"></td>
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
    const hoy = new Date();
    const horaActual = hoy.toTimeString().substr(0, 5);
    const fechaActual = hoy.toISOString().split('T')[0]; // Formato: "2023-11-15"
    const turno = determinarTurno(horaActual);

    const fechaReferencia = obtenerFechaSegunTurno(fechaActual, horaActual);
    $("#titulo-seccion").html(`Reportes del Control de Alimentación - Turno ${turno} <br>${formatearFecha(fechaReferencia)} - ${horaActual}`);

    $.ajax({
        type: "GET", 
        url: "/ajaxReportesSalaControl?" + $.param(filtros),
        dataType: "json",
        success: function(response) {
            console.log("Respuesta recibida:", response); // Inspecciona la respuesta

            if (!response) {
                console.error("La respuesta está vacía o es null");
                $("#tbody").html('<tr><td colspan="18">No hay datos disponibles</td></tr>');
                return;
            }

            if (!Array.isArray(response)) {
                console.error("La respuesta no es un array:", typeof response);
                return;
            }

            html = '';
            let totalOp1 = 0, totalOp2 = 0, totalOp3 = 0;
            let totalParada1 = 0, totalParada2 = 0, totalParada3 = 0, totalParadaCT5 = 0;
            let totalHumedad = 0, promedioHumedad = 0.00;
            let count = 0;
            response.forEach((element) => {
                
                fecha = element.fecha.substr(8, 2) + "/" + element.fecha.substr(5, 2) + "/" + element.fecha.substr(0, 4);
            
                totalOp1 += parseFloat(element.operatividad_molino_1) || 0;
                totalOp2 += parseFloat(element.operatividad_molino_2) || 0;
                totalOp3 += parseFloat(element.operatividad_molino_3) || 0;
                totalParada1 += parseFloat(element.parada_molino_1) || 0;
                totalParada2 += parseFloat(element.parada_molino_2) || 0;
                totalParada3 += parseFloat(element.parada_molino_3) || 0;
                totalHumedad += parseFloat(element.porcentaje_humedad) || 0;
                totalParadaCT5 += parseFloat(element.pad_ct_5)
                count ++;

                html += `
                <tr>
                    <td>${element.hora}</td>
                    <td>${element.pad_ct_5}</td>
                    <td>${element.operatividad_molino_1} min</td>
                    <td>${element.operatividad_molino_2} min</td>
                    <td>${element.operatividad_molino_3} min</td>
                    <td>${element.parada_molino_1} min</td>
                    <td>${element.parada_molino_2} min</td>
                    <td>${element.parada_molino_3} min</td>
                    <td>${element.porcentaje_humedad}%</td>
                    <td>${element.codigo_arena}</td>
                    <td>${element.numero_tolva}</td>
                    <td>${element.observacion}</td>
                    <td>${element.supervisor}</td>
                    <td>
                        <a class="btn btn-success" id="editar" value="${element.id}">Editar</a>
                        <a class="btn btn-danger" id="eliminar" value="${element.id}">Eliminar</a>
                    </td>
                </tr>
                `
            

            }); 

            if (count === 0) {
                html = '<tr><td colspan="14" class="text-center text-muted">No hay registros para el día de hoy</td></tr>';
            } 

            else {
                html += `
                <tr class="fw-bold">
                    <td>Totales</td>
                    <td>${totalParadaCT5} min</td>
                    <td>${totalOp1} min</td>
                    <td>${totalOp2} min</td>
                    <td>${totalOp3} min</td>
                    <td>${totalParada1} min</td>
                    <td>${totalParada2} min</td>
                    <td>${totalParada3} min</td>
                    <td>${(totalHumedad/count).toFixed(2)}%</td>
                    <td colspan="5"></td>
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

function guardarReporte(params){
    $.ajax({
        type: "POST",
        url: "/ajaxReportesSalaControl",
        data: params,
        dataType: "json",
        success: function (response){
            console.log("Respuesta del servidor:", response);
            listadoReportes();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function modificarReporte(id, params){
    $.ajax({
        type: "PUT",
        url: "/ajaxReportesSalaControl/" + id,
        data: params,
        dataType: "json",
        success: function (response){
            listadoReportes();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function obtenerReporte(id){
    $.ajax({
        type: "GET",
        url: "/ajaxReportesSalaControl/" + id,
        dataType: "json",
        success: function (response){
            response = response[0];
            $("#id").val(response.id);
            $("#fecha").val(response.fecha);
            $("#hora").val(response.hora);
            $("#padct").val(response.pad_ct_5);
            $("#molino1").val(response.operatividad_molino_1);
            $("#molino2").val(response.operatividad_molino_2);
            $("#molino3").val(response.operatividad_molino_3);
            $("#molino1P").val(response.parada_molino_1);
            $("#molino2P").val(response.parada_molino_2);
            $("#molino3P").val(response.parada_molino_3);
            $("#hum").val(response.porcentaje_humedad);
            $("#cArena").val(response.codigo_arena);
            $("#nTolva").val(response.numero_tolva);
            $("#comentario").val(response.observacion);
            //$("#sup").val(response.id_supervisor_encargado);
            // $("#material").val(response.id_material);
            $("#sup").val(obtenerSupervisor(response.id_supervisor_encargado));
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function obtenerSupervisor(id){
    $.ajax({
        type: "GET",
        url: "/ajaxSupervisores",
        dataType: "json",
        success: function (response){
            $("#sup").empty();
            valor='';
            html = '<option selected>Elegir Supervisor</option>';
            if(id==0){
                $("#sup").append(html);

                response.forEach((element) => {
                    // fecha = element.fecha.substr(8, 2) + "/" + element.fecha.substr(5, 2) + "/" + element.fecha.substr(0, 4);
                    html = '<option value="' + element.id + '">' + element.nombre + '</option>';
                    $('#sup').append(html);
                    // if(element.id == id) valor = element.nombre;
                });
            } 
            
            // empresaName = '<option selected>' + valor + '</option>';
            if(id > 0){
                response.forEach((element) => {
                    if(element.id == id){
                        // valor = element.nombre;
                        html = '<option selected value="' + element.id + '">' + element.nombre + '</option>';
                        $('#sup').append(html);
                        true;
                    } 
                    else{
                        html = '<option value="' + element.id + '">' + element.nombre + '</option>';
                        $('#sup').append(html);
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

function eliminarReporte(id){
    $.ajax({
        type: "DELETE",
        url: "/ajaxReportesSalaControl/" + id,
        dataType: "json",
        success: function (response){
            listadoReportes();
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
