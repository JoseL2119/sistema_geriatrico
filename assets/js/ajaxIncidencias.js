// Variables globales
let reporteActual = {
    observaciones: []
};

// Inicialización
$(document).ready(function() {
    mostrarListado();
    listadoIncidencias();
    obtenerSupervisores(0);
    obtenerOperadores(0);
    obtenerTurnos(0);
    obtenerMaquina(0);

    // Eventos
    $("#nuevo").click(function() {
        mostrarFormulario();
        limpiarFormulario();
    });

    $("#volver").click(function() {
        mostrarListado();
    });

    $(document).on("change", "#filtro_fecha, #filtro_turno", function (e){
        const fecha_filtro = $("#filtro_fecha").val();
        const turno_filtro = $("#filtro_turno").val();
        
        if(fecha_filtro && fecha_filtro !== "" && turno_filtro && turno_filtro !== ""){
            const filtros = {
                fecha: fecha_filtro,
                id_turno: turno_filtro
            }

            filtrar(filtros);
        }
        
    });

    $("#agregarObservacion").click(function() {
        $("#modalObservacion").modal('show');
    });

    $("#guardarObservacion").click(guardarObservacion);

    $("#finalizarReporte").click(function() {
        guardarReporte(true);
    });

    $(document).on("click", "#editar", function(e){
        const id = $(this).attr("value");
        obtenerIncidencia(id);
        mostrarFormulario();
    });

    $(document).on("click", "#eliminar", function(e){
        const id = $(this).attr("value");
        Swal.fire({
            title: '¿Seguro que desea eliminar?',
            text: `El reporte ${id} será eliminado`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí',
            cancelButtonText: 'No'
        }).then( (result) => {
            if(result.value){
                eliminarIncidencia(id);
            }
        })
    });
});

function obtenerSupervisores(id){
    $.ajax({
        type: "GET",
        url: "/ajaxSupervisores",
        dataType: "json",
        success: function (response){
            $("#supervisor_id").empty();
            valor='';
            html = '<option selected>Seleccionar...</option>';
            if(id==0){
                $("#supervisor_id").append(html);

                response.forEach((element) => {
                    // fecha = element.fecha.substr(8, 2) + "/" + element.fecha.substr(5, 2) + "/" + element.fecha.substr(0, 4);
                    html = '<option value="' + element.id + '">' + element.nombre + '</option>';
                    $('#supervisor_id').append(html);
                    // if(element.id == id) valor = element.nombre;
                });
            } 
            
            // empresaName = '<option selected>' + valor + '</option>';
            if(id > 0){
                response.forEach((element) => {
                    if(element.id == id){
                        // valor = element.nombre;
                        html = '<option selected value="' + element.id + '">' + element.nombre + '</option>';
                        $('#supervisor_id').append(html);
                        true;
                    } 
                    else{
                        html = '<option value="' + element.id + '">' + element.nombre + '</option>';
                        $('#supervisor_id').append(html);
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

function obtenerOperadores(id){
    $.ajax({
        type: "GET",
        url: "/ajaxOperadores",
        dataType: "json",
        success: function (response){
            $("#operador_id").empty();
            valor='';
            html = '<option selected>Seleccionar...</option>';
            if(id==0){
                $("#operador_id").append(html);

                response.forEach((element) => {
                    // fecha = element.fecha.substr(8, 2) + "/" + element.fecha.substr(5, 2) + "/" + element.fecha.substr(0, 4);
                    html = '<option value="' + element.id + '">' + element.nombre + '</option>';
                    $('#operador_id').append(html);
                    // if(element.id == id) valor = element.nombre;
                });
            } 
            
            // empresaName = '<option selected>' + valor + '</option>';
            if(id > 0){
                response.forEach((element) => {
                    if(element.id == id){
                        // valor = element.nombre;
                        html = '<option selected value="' + element.id + '">' + element.nombre + '</option>';
                        $('#operador_id').append(html);
                        true;
                    } 
                    else{
                        html = '<option value="' + element.id + '">' + element.nombre + '</option>';
                        $('#operador_id').append(html);
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

function obtenerTurnos(id){
    $.ajax({
        type: "GET",
        url: "/ajaxTurnos",
        dataType: "json",
        success: function (response){
            $("#turno").empty();
            valor='';
            html = '<option selected>Seleccionar...</option>';
            if(id==0){
                $("#turno").append(html);

                response.forEach((element) => {
                    // fecha = element.fecha.substr(8, 2) + "/" + element.fecha.substr(5, 2) + "/" + element.fecha.substr(0, 4);
                    html = '<option value="' + element.id + '">' + element.turno + '</option>';
                    $('#turno').append(html);
                    // if(element.id == id) valor = element.nombre;
                });
            } 
            
            // empresaName = '<option selected>' + valor + '</option>';
            if(id > 0){
                response.forEach((element) => {
                    if(element.id == id){
                        // valor = element.nombre;
                        html = '<option selected value="' + element.id + '">' + element.turno + '</option>';
                        $('#turno').append(html);
                        true;
                    } 
                    else{
                        html = '<option value="' + element.id + '">' + element.turno + '</option>';
                        $('#turno').append(html);
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

function obtenerMaquina(id){
    $.ajax({
        type: "GET",
        url: "/ajaxMaquina",
        dataType: "json",
        success: function (response){
            $("#maquina").empty();
            valor='';
            html = '<option selected>Seleccionar...</option>';
            if(id==0){
                $("#maquina").append(html);

                response.forEach((element) => {
                    // fecha = element.fecha.substr(8, 2) + "/" + element.fecha.substr(5, 2) + "/" + element.fecha.substr(0, 4);
                    html = '<option value="' + element.id + '">' + element.nombre + '</option>';
                    $('#maquina').append(html);
                    // if(element.id == id) valor = element.nombre;
                });
            } 
            
            // empresaName = '<option selected>' + valor + '</option>';
            if(id > 0){
                response.forEach((element) => {
                    if(element.id == id){
                        // valor = element.nombre;
                        html = '<option selected value="' + element.id + '">' + element.nombre + '</option>';
                        $('#maquina').append(html);
                        true;
                    } 
                    else{
                        html = '<option value="' + element.id + '">' + element.nombre + '</option>';
                        $('#maquina').append(html);
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

function guardarObservacion() {
    const hora = $("#horaObservacion").val();
    const texto = $("#textoObservacion").val().trim();

    if (!hora || !texto) {
        Swal.fire('Error', 'Debe completar ambos campos', 'error');
        return;
    }

    reporteActual.observaciones.push({
        hora: hora,
        texto: texto
    });

    actualizarListaObservaciones();
    $("#modalObservacion").modal('hide');
    $("#horaObservacion").val('');
    $("#textoObservacion").val('');
}

function actualizarListaObservaciones() {
    const lista = $("#listaObservaciones");
    lista.empty();

    reporteActual.observaciones.forEach((obs, index) => {
        lista.append(`
        <div class="card mb-2" id="obs-${index}">
            <div class="card-body py-2">
                <div class="d-flex justify-content-between">
                    <div>
                        <span class="badge bg-primary me-2">${obs.hora}</span>
                        <span>${obs.texto}</span>
                    </div>
                    <button class="btn btn-sm btn-outline-danger" onclick="eliminarObservacion(${index})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>`);
    });
}

function eliminarObservacion(index) {
    reporteActual.observaciones.splice(index, 1);
    actualizarListaObservaciones();
}

function guardarReporte(finalizado) {
    const id_supervisor = $("#supervisor_id").val();
    const operadorId = $("#operador_id").val();
    const grupo = $("#grupo").val();
    const fecha = $("#fecha").val();
    const turno = $("#turno").val();
    const tv1 = $("#tv1").val();
    const tv2 = $("#tv2").val();
    const pp1 = $("#pp1").val();
    const pp2 = $("#pp2").val();
    const maquina = $("#maquina").val();
    const ptp = $("#ptp").val();
    const recepcion = $("#recepcion").val();
    const solicitados = $("#solicitados").val();
    const usados = $("#usados").val();
    const disponibles = $("#disponibles").val();
    const id = $("#id").val();

    if (!id_supervisor || !grupo || !turno) {
        Swal.fire('Error', 'Debe completar los datos básicos', 'error');
        return;
    }

    const datos = {
        id_supervisor: id_supervisor,
        id_operador: operadorId,
        grupo: grupo,
        fecha: fecha,
        id_turno: turno,
        observaciones: reporteActual.observaciones,
        tolva1: tv1,
        tolva2: tv2,
        palas_procesadas_1: pp1,
        palas_procesadas_2: pp2,
        payloader: maquina,
        palas_totales_procesadas: ptp,
        recepcion: recepcion,
        solicitados_en_turno: solicitados,
        usados_en_turno: usados,
        disponibles_sig_turno: disponibles
    };

    console.log("Datos a enviar:", JSON.stringify(datos, null, 2));

    const url = id == "0" ? '/ajaxIncidencias' : `/ajaxIncidencias/${id}`;
    const method = id == "0" ? 'POST' : 'PUT';

    $.ajax({
        url: url,
        type: method,
        contentType: 'application/json',
        data: JSON.stringify(datos),
        dataType: 'json',
        success: function(response) {
            Swal.fire('Éxito', finalizado ? 'Reporte finalizado' : 'Borrador guardado', 'success');
            mostrarListado();
            listadoIncidencias();
        },
        error: function(xhr) {
            Swal.fire('Error', xhr.responseText || 'Error en el servidor', 'error');
        }
    });
}

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

function listadoIncidencias() {

    const hoy = new Date();
    const horaActual = hoy.toTimeString().substr(0, 5);
    const fechaActual = hoy.toISOString().split('T')[0]; // Formato: "2023-11-15"
    const turno = determinarTurno(horaActual);

    const fechaReferencia = obtenerFechaSegunTurno(fechaActual, horaActual);
    $("#titulo-seccion").html(`Reportes de Incidencias - Turno ${turno} <br>${formatearFecha(fechaReferencia)} - ${horaActual}`);
    
    $.ajax({
        type: "GET", 
        url: "/ajaxIncidencias",
        dataType: "json",
        success: function(response) {
            let html = '', html2 = '';
            let html3 = `
                <tr>
                    <td>Hora:</td>
                    <td colspan="7">Observaciones:</td>
                </tr>`;

            
            response.forEach(incidencia => {
                if (incidencia.fecha && incidencia.fecha.startsWith(fechaReferencia)){
                    html += `
                    <tr>
                        <td>${formatearFecha(incidencia.fecha)}</td>
                        <td>${incidencia.supervisor}</td>
                        <td>${incidencia.operador}</td>
                        <td>${incidencia.grupo}</td>
                        <td>${incidencia.turno}</td>
                        <td colspan="3">
                            <button class="btn btn-success" id="editar" value="${incidencia.id}">Editar</button>
                            <button class="btn btn-danger" id="eliminar" value="${incidencia.id}">Eliminar</button>
                        </td>
                    </tr>`;
                } 
            });
            $("#tbody").html(html);

            response.forEach(incidencia => {
                if (incidencia.fecha && incidencia.fecha.startsWith(fechaReferencia)){
                    html2 += `
                        <tr>
                            <td>Almacenamiento Tolvas</td>
                            <td>TV1: ${incidencia.tolva1} Tn</td>
                            <td>TV2: ${incidencia.tolva2} Tn</td>
                            <td>Palas Procesadas: ${incidencia.palas_procesadas_1}</td>
                            <td>Palas Procesadas: ${incidencia.palas_procesadas_2}</td>
                            <td>Payloaders: ${incidencia.maquina}</td>
                            <td>Palas totales: ${incidencia.palas_totales_procesadas}</td>
                            <td>Toneladas totales Procesadas: ${incidencia.palas_totales_procesadas*3.5} Tn</td>
                        </tr>
                        
                        <tr>
                            <td>Agregado cal</td>
                            <td>Recepción: ${incidencia.recepcion} Kg</td>
                            <td>Solicitados en turno: ${incidencia.solicitados_en_turno} Kg</td>
                            <td>Usados en turno: ${incidencia.usados_en_turno} Kg</td>
                            <td>Disponibles sig. turno: ${incidencia.disponibles_sig_turno} Kg</td>
                        </tr>`;
                }
            });
            $("#tfooter").html(html2);
            

            response.forEach(incidencia => {
                if (incidencia.fecha && incidencia.fecha.startsWith(fechaReferencia)){
                    try {
                        const observaciones = JSON.parse(incidencia.observaciones || '[]');
                        
                        observaciones.forEach(obs => {
                            html3 += `
                            <tr>
                                <td>${obs.hora}</td>
                                <td colspan="7">${obs.texto}</td>
                            </tr>`;
                        });

                        // Si no hay observaciones
                        if (observaciones.length === 0) {
                            html3 += `
                            <tr>
                                <td colspan="8">Sin observaciones</td>
                            </tr>`;
                        }
                    } catch (e) {
                        html3 += `
                        <tr>
                            <td colspan="7">Error cargando observaciones</td>
                        </tr>`;
                    }
                }
            });
            $("#tobservaciones").html(html3);
        }
    });
}

function filtrar(filtros) {

    const hoy = new Date();
    const horaActual = hoy.toTimeString().substr(0, 5);
    const fechaActual = hoy.toISOString().split('T')[0]; // Formato: "2023-11-15"
    const turno = determinarTurno(horaActual);

    const fechaReferencia = obtenerFechaSegunTurno(fechaActual, horaActual);
    $("#titulo-seccion").html(`Reportes de Incidencias - Turno ${turno} <br>${formatearFecha(fechaReferencia)} - ${horaActual}`);
    
    $.ajax({
        type: "GET", 
        url: "/ajaxIncidencias?" + $.param(filtros),
        dataType: "json",
        success: function(response) {
            let html = '', html2 = '';
            let html3 = `
                <tr>
                    <td>Hora:</td>
                    <td colspan="7">Observaciones:</td>
                </tr>`;

            
            response.forEach(incidencia => {
                html += `
                <tr>
                    <td>${formatearFecha(incidencia.fecha)}</td>
                    <td>${incidencia.supervisor}</td>
                    <td>${incidencia.operador}</td>
                    <td>${incidencia.grupo}</td>
                    <td>${incidencia.turno}</td>
                    <td colspan="3">
                        <button class="btn btn-success" id="editar" value="${incidencia.id}">Editar</button>
                        <button class="btn btn-danger" id="eliminar" value="${incidencia.id}">Eliminar</button>
                    </td>
                </tr>`;
            });
            $("#tbody").html(html);

            response.forEach(incidencia => {
                html2 += `
                    <tr>
                        <td>Almacenamiento Tolvas</td>
                        <td>TV1: ${incidencia.tolva1} Tn</td>
                        <td>TV2: ${incidencia.tolva2} Tn</td>
                        <td>Palas Procesadas: ${incidencia.palas_procesadas_1}</td>
                        <td>Palas Procesadas: ${incidencia.palas_procesadas_2}</td>
                        <td>Payloaders: ${incidencia.maquina}</td>
                        <td>Palas totales: ${incidencia.palas_totales_procesadas}</td>
                        <td>Toneladas totales Procesadas: ${incidencia.palas_totales_procesadas*3.5} Tn</td>
                    </tr>
                    
                    <tr>
                        <td>Agregado cal</td>
                        <td>Recepción: ${incidencia.recepcion} Kg</td>
                        <td>Solicitados en turno: ${incidencia.solicitados_en_turno} Kg</td>
                        <td>Usados en turno: ${incidencia.usados_en_turno} Kg</td>
                        <td>Disponibles sig. turno: ${incidencia.disponibles_sig_turno} Kg</td>
                    </tr>`;
            });
            $("#tfooter").html(html2);
            

            response.forEach(incidencia => {
                try {
                    const observaciones = JSON.parse(incidencia.observaciones || '[]');
                    
                    observaciones.forEach(obs => {
                        html3 += `
                        <tr>
                            <td>${obs.hora}</td>
                            <td colspan="7">${obs.texto}</td>
                        </tr>`;
                    });

                    // Si no hay observaciones
                    if (observaciones.length === 0) {
                        html3 += `
                        <tr>
                            <td colspan="7">Sin observaciones</td>
                        </tr>`;
                    }
                } catch (e) {
                    html3 += `
                    <tr>
                        <td colspan="7">Error cargando observaciones</td>
                    </tr>`;
                }
            });
            $("#tobservaciones").html(html3);
        }
    });
}

function obtenerIncidencia(id) {
    $.ajax({
        type: "GET",
        url: "/ajaxIncidencias/" + id,
        dataType: "json",
        success: function(response) {
            if (response && response.length > 0) {
                const incidencia = response[0];
                $("#id").val(incidencia.id);
                $("#fecha").val(incidencia.fecha);
                $("#supervisor_id").val(obtenerSupervisores(incidencia.id_supervisor));
                $("#operador_id").val(obtenerOperadores(incidencia.id_operador));
                $("#grupo").val(incidencia.grupo);
                $("#turno").val(obtenerTurnos(incidencia.id_turno));
                $("#tv1").val(incidencia.tolva1);
                $("#tv2").val(incidencia.tolva2);
                $("#pp1").val(incidencia.palas_procesadas_1);
                $("#pp2").val(incidencia.palas_procesadas_2);
                $("#maquina").val(obtenerMaquina(incidencia.payloader));
                $("#ptp").val(incidencia.palas_totales_procesadas);
                $("#recepcion").val(incidencia.recepcion);
                $("#solicitados").val(incidencia.solicitados_en_turno);
                $("#usados").val(incidencia.usados_en_turno);
                $("#disponibles").val(incidencia.disponibles_sig_turno);

                // Parsear observaciones JSON
                reporteActual.observaciones = incidencia.observaciones ? 
                    (typeof incidencia.observaciones === 'string' ? 
                     JSON.parse(incidencia.observaciones) : 
                     incidencia.observaciones) : 
                    [];
                
                actualizarListaObservaciones();
            }
        },
        error: function(xhr) {
            console.error("Error al obtener incidencia:", xhr.responseText);
        }
    });
}

function eliminarIncidencia(id) {
    $.ajax({
        type: "DELETE",
        url: "/ajaxIncidencias/" + id,
        dataType: "json",
        success: function(response) {
            listadoIncidencias();
            Swal.fire('Eliminado', 'El reporte ha sido eliminado', 'success');
        },
        error: function(xhr) {
            Swal.fire('Error', 'No se pudo eliminar el reporte', 'error');
        }
    });
}

// Funciones de utilidad
function mostrarListado() {
    $("#lista").removeClass("d-none");
    $("#form").addClass("d-none");
}

function mostrarFormulario() {
    $("#lista").addClass("d-none");
    $("#form").removeClass("d-none");
}

function limpiarFormulario() {
    $("#id").val("0");
    $("#fecha").val("");
    $("#supervisor_id").val(obtenerSupervisores(0));
    $("#operador_id").val(obtenerOperadores(0));
    $("#maquina").val(obtenerMaquina(0));
    $("#grupo").val("");
    $("#tv1").val(0);
    $("#tv2").val(0);
    $("#pp1").val(0);
    $("#pp2").val(0);
    $("#ptp").val(0);
    $("#recepcion").val(0);
    $("#solicitados").val(0);
    $("#usados").val(0);
    $("#disponibles").val(0);
    $("#turno").val(obtenerTurnos(0));
    reporteActual.observaciones = [];
    actualizarListaObservaciones();
}