$(document).ready( function() {
    let idmaterial = 0;
    let idempresa = 0;
    let idmina = 0;
    mostrarListado();
    listadoReportes();

    // Ir al formulario desde el listado
    $(document).on("click", "#nuevo", function (e){
        // obtenerExcavacion(0);
        obtenerMaquina(0);
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
        const tipo = $("#tipo").val();
        const horaI = $("#horaI").val();
        const horaF = $("#horaF").val();
        const horaT = $("#horaT").val();
        const palas = $("#palas").val();
        const cArena = $("#cArena").val();
        const actividad = $("#actividad").val();
        const grupo = $("#grupo").val();
        
        const info = {
            fecha: fecha,
            id_tipo_maquina: tipo,
            hora_inicio: horaI,
            hora_final: horaF,
            horas_trabajadas: horaT,
            palas_agregadas: palas,
            cod_arenas: cArena,
            actividades: actividad,
            grupo: grupo
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
    $("#titulo-seccion").html(`Reportes de Operatividad de Maquinaria Pesada - Turno ${turno} <br>${formatearFecha(fechaReferencia)} - ${horaActual}`);

    $.ajax({
        type: "GET", 
        url: "/ajaxOperatividadMaquinaria",
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
            let totalHorasTrabajadas = 0; 
            let totalPalas = 0;
            let count = 0;

            // Función para convertir HH:MM a minutos
            const convertirAMinutos = (hhmm) => {
                if (!hhmm || typeof hhmm !== 'string') return 0;
                const [horas, minutos] = hhmm.split(':').map(Number);
                return (horas * 60) + (isNaN(minutos) ? 0 : minutos);
            };

            // Función para convertir minutos a HH:MM
            const convertirAHHMM = (minutos) => {
                const hrs = Math.floor(minutos / 60);
                const mins = minutos % 60;
                return `${String(hrs).padStart(2, '0')}:${String(mins).padStart(2, '0')}`;
            };

            response.forEach((element) => {
                fecha = element.fecha.substr(8, 2) + "/" + element.fecha.substr(5, 2) + "/" + element.fecha.substr(0, 4);
                
                if (element.fecha && element.fecha.startsWith(fechaReferencia)){
                    count++;
                    
                    // Convertir horas a minutos y acumular
                    totalHorasTrabajadas += convertirAMinutos(element.horas_trabajadas);
                    totalPalas += element.palas_agregadas || 0;

                    html += `
                    <tr>
                        <td>${element.maquina}</td>
                        <td>${element.hora_inicio}</td>
                        <td>${element.hora_final}</td>
                        <td>${element.horas_trabajadas}</td>
                        <td>${element.palas_agregadas}</td>
                        <td>${element.cod_arenas}</td>
                        <td colspan="2">${element.actividades}</td>
                        <td>${element.grupo}</td>
                        <td>
                            <a class="btn btn-success" id="editar" value="${element.id}">Editar</a>
                            <a class="btn btn-danger" id="eliminar" value="${element.id}">Eliminar</a>
                        </td>
                    </tr>
                    `
                }
            });

            if (count === 0) {
                html = '<tr><td colspan="11" class="text-center text-muted">No hay registros para hoy</td></tr>';
            } 
            // Agregar fila de totales
            else {
                html += `
                <tr class="fw-bold">
                    <td colspan="3">Totales</td>
                    <td>${convertirAHHMM(totalHorasTrabajadas)}</td>
                    <td>${totalPalas}</td>
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
    $("#titulo-seccion").html(`Reportes de Operatividad de Maquinaria Pesada - Turno ${turno} <br>${formatearFecha(fechaReferencia)} - ${horaActual}`);

    $.ajax({
        type: "GET", 
        url: "/ajaxOperatividadMaquinaria?" + $.param(filtros),
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
            let totalHorasTrabajadas = 0; 
            let totalPalas = 0;
            let count = 0;

            // Función para convertir HH:MM a minutos
            const convertirAMinutos = (hhmm) => {
                if (!hhmm || typeof hhmm !== 'string') return 0;
                const [horas, minutos] = hhmm.split(':').map(Number);
                return (horas * 60) + (isNaN(minutos) ? 0 : minutos);
            };

            // Función para convertir minutos a HH:MM
            const convertirAHHMM = (minutos) => {
                const hrs = Math.floor(minutos / 60);
                const mins = minutos % 60;
                return `${String(hrs).padStart(2, '0')}:${String(mins).padStart(2, '0')}`;
            };

            response.forEach((element) => {
                fecha = element.fecha.substr(8, 2) + "/" + element.fecha.substr(5, 2) + "/" + element.fecha.substr(0, 4);
                
                count++;
                
                // Convertir horas a minutos y acumular
                totalHorasTrabajadas += convertirAMinutos(element.horas_trabajadas);
                totalPalas += element.palas_agregadas || 0;

                html += `
                <tr>
                    <td>${element.maquina}</td>
                    <td>${element.hora_inicio}</td>
                    <td>${element.hora_final}</td>
                    <td>${element.horas_trabajadas}</td>
                    <td>${element.palas_agregadas}</td>
                    <td>${element.cod_arenas}</td>
                    <td colspan="2">${element.actividades}</td>
                    <td>${element.grupo}</td>
                    <td>
                        <a class="btn btn-success" id="editar" value="${element.id}">Editar</a>
                        <a class="btn btn-danger" id="eliminar" value="${element.id}">Eliminar</a>
                    </td>
                </tr>
                `
            });

            if (count === 0) {
                html = '<tr><td colspan="11" class="text-center text-muted">No hay registros para hoy</td></tr>';
            } 
            // Agregar fila de totales
            else {
                html += `
                <tr class="fw-bold">
                    <td colspan="3">Totales</td>
                    <td>${convertirAHHMM(totalHorasTrabajadas)}</td>
                    <td>${totalPalas}</td>
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
        url: "/ajaxOperatividadMaquinaria",
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
        url: "/ajaxOperatividadMaquinaria/" + id,
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
        url: "/ajaxOperatividadMaquinaria/" + id,
        dataType: "json",
        success: function (response){
            response = response[0];
            $("#id").val(response.id);
            $("#fecha").val(response.fecha);
            $("#tipo").val(obtenerMaquina(response.id_tipo_maquina));
            $("#horaI").val(response.hora_inicio);
            $("#horaF").val(response.hora_final);
            $("#horaT").val(response.horas_trabajadas);
            $("#palas").val(response.palas_agregadas);
            $("#cArena").val(response.cod_arenas);
            $("#actividad").val(response.actividades);
            $("#grupo").val(response.grupo);
            //$("#sup").val(response.id_supervisor_encargado);
            // $("#material").val(response.id_material);
            
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function obtenerMaquina(id){
    $.ajax({
        type: "GET",
        url: "/ajaxMaquina",
        dataType: "json",
        success: function (response){
            $("#tipo").empty();
            valor='';
            html = '<option selected>Elegir Maquina</option>';
            if(id==0){
                $("#tipo").append(html);

                response.forEach((element) => {
                    // fecha = element.fecha.substr(8, 2) + "/" + element.fecha.substr(5, 2) + "/" + element.fecha.substr(0, 4);
                    html = '<option value="' + element.id + '">' + element.nombre + '</option>';
                    $('#tipo').append(html);
                    // if(element.id == id) valor = element.nombre;
                });
            } 
            
            // empresaName = '<option selected>' + valor + '</option>';
            if(id > 0){
                response.forEach((element) => {
                    if(element.id == id){
                        // valor = element.nombre;
                        html = '<option selected value="' + element.id + '">' + element.nombre + '</option>';
                        $('#tipo').append(html);
                        true;
                    } 
                    else{
                        html = '<option value="' + element.id + '">' + element.nombre + '</option>';
                        $('#tipo').append(html);
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
        url: "/ajaxOperatividadMaquinaria/" + id,
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
    $("#fecha").val("");
    $("#tipo").val(obtenerMaquina(0));
    $("#horaI").val("");
    $("#horaF").val("");
    $("#horaT").val("");
    $("#palas").val(0);
    $("#cArena").val("");
    $("#actividad").val("");
    $("#grupo").val("");
}
