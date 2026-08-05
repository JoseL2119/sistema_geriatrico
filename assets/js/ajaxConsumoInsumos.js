// Inicialización
$(document).ready(function() {
    mostrarListado();
    listadoConsumo();
    obtenerInsumos(0);

    // Eventos
    $("#nuevo").click(function() {
        mostrarFormulario();
        limpiarFormulario();

        cargarInsumosCriticos();
    });

    $("#volver").click(function() {
        mostrarListado();
    });

    $("#agregarInsumo").click(function() {
        obtenerInsumos(0);
    });

    $(document).on("change", "#filtro_fecha", function (e){
        const fecha_filtro = $("#filtro_fecha").val();
        
        if(fecha_filtro && fecha_filtro !== ""){
            const filtros = {
                fecha_filtro: fecha_filtro
            }

            filtrar(filtros);
        }
        
    });

    $(document).on("change", "#fecha", function (e){
        const fechaRegistro = $("#fecha").val();
        
        if(fechaRegistro && fechaRegistro !== ""){

            const fechaSeleccionada = new Date(fechaRegistro);
            const fechaAnterior = new Date(fechaSeleccionada);
            fechaAnterior.setDate(fechaSeleccionada.getDate() - 1);

            const fecha = {
                fecha_filtro: fechaAnterior.toISOString().split('T')[0]
            }

            cargarStockInicial(fecha);
        }
        
    });

    $(document).on('input', '.fila-insumo .stock_inicial, .fila-insumo .cantidad_recibida, .fila-insumo .cantidad_retirada', function() {
        const fila = $(this).closest('.fila-insumo');
        calcularStockFinal(fila);
    });

    $("#guardarReporte").click(function() {
        guardarReporte();
    });

    $(document).on("click", "#editar", function(e){
        const id = $(this).attr("value");
        obtenerConsumo(id);
        mostrarFormularioEditar();
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
                eliminarConsumo(id);
            }
        })
    });
});

function obtenerInsumos(id){
    $.ajax({
        type: "GET",
        url: "/ajaxInsumos",
        dataType: "json",
        success: function (response){
            $(".insumo_id").empty();
            valor='';
            html = '<option selected>Seleccionar...</option>';
            if(id==0){
                $(".insumo_id").append(html);

                response.forEach((element) => {
                    // fecha = element.fecha.substr(8, 2) + "/" + element.fecha.substr(5, 2) + "/" + element.fecha.substr(0, 4);
                    html = '<option value="' + element.id + '">' + element.nombre + '</option>';
                    $('.insumo_id').append(html);
                    // if(element.id == id) valor = element.nombre;
                });
            } 
            
            // empresaName = '<option selected>' + valor + '</option>';
            if(id > 0){
                response.forEach((element) => {
                    if(element.id == id){
                        // valor = element.nombre;
                        html = '<option selected value="' + element.id + '">' + element.nombre + '</option>';
                        $('.insumo_id').append(html);
                        true;
                    } 
                    else{
                        html = '<option value="' + element.id + '">' + element.nombre + '</option>';
                        $('.insumo_id').append(html);
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

function cargarInsumosCriticos() {
    $.ajax({
        url: '/ajaxInsumos',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if(Array.isArray(response) && response.length > 0) {
                insumosCriticos = response;
                
                $("#tablaInsumos tbody").empty();
                
                // Crear una fila por cada insumo crítico
                response.forEach(insumo => {
                    agregarFila(insumo.id, true);
                });
            } else {
                // Si no hay insumos, mantener una fila vacía
                agregarFila();
                Swal.fire('Información', 'No se encontraron insumos críticos registrados', 'info');
            }
        },
        error: function(xhr) {
            console.error("Error al cargar insumos:", xhr);
            // Mantener una fila vacía como respaldo
            agregarFila();
            Swal.fire('Error', 'No se pudieron cargar los insumos críticos', 'error');
        }
    });
}

function guardarReporte() {
    const fecha = $("#fecha").val();
    const detallesInsumos = [];
    let idGlobal = "0";

    $(".fila-insumo").each(function(index) {

        const fila = $(this);

        const insumo_id = fila.find(".insumo_id").val();
        const stock_inicial = fila.find(".stock_inicial").val();
        const cantidad_recibida = fila.find(".cantidad_recibida").val();
        const cantidad_retirada = fila.find(".cantidad_retirada").val();
        const stock_final = fila.find(".stock_final").val();
        const observacion = fila.find(".observacion").val();
        const id = fila.find(".id").val();

        if (id && id !== "0") {
            idGlobal = id;
        }

        if (!insumo_id || !stock_inicial || !cantidad_recibida || !cantidad_retirada || !stock_final) {
            Swal.fire('Error', `Fila ${index + 1}: Complete los datos básicos`, 'error');
            return false; // Detiene el each
        }

        detallesInsumos.push({
            insumo_id: insumo_id,
            stock_inicial: parseFloat(stock_inicial),
            cantidad_recibida: parseFloat(cantidad_recibida),
            cantidad_entregada: parseFloat(cantidad_retirada),
            stock_final: parseFloat(stock_final),
            observacion: observacion
        });
    });

    // Si no hay filas válidas, no enviar
    if (detallesInsumos.length === 0) return;

    const datos = {
        dataReporte: {
            fecha: fecha
        },
        detallesInsumos: detallesInsumos
    };

    const url = idGlobal === "0" ? '/ajaxConsumoInsumos' : `/ajaxConsumoInsumos/${idGlobal}`;
    const method = idGlobal === "0" ? 'POST' : 'PUT';

    if (idGlobal === "0"){
        console.log("Datos a enviar:", JSON.stringify(datos, null, 2));

        $.ajax({
            url: url,
            type: method,
            contentType: 'application/json',
            data: JSON.stringify(datos),
            dataType: 'json',
            success: function(response) {
                Swal.fire('Reporte Finalizado');
                mostrarListado();
                listadoConsumo();
            },
            error: function(xhr) {
                Swal.fire('Error', xhr.responseText || 'Error en el servidor', 'error');
            }
        });
    }
    else{
        const datosUpdate = {
            insumo_id: detallesInsumos[0].insumo_id,
            stock_inicial: detallesInsumos[0].stock_inicial,
            cantidad_recibida: detallesInsumos[0].cantidad_recibida,
            cantidad_entregada: detallesInsumos[0].cantidad_entregada,
            stock_final: detallesInsumos[0].stock_final,
            observacion: detallesInsumos[0].observacion
        }

        console.log("Datos a Actualizar:", JSON.stringify(datosUpdate, null, 2));

        $.ajax({
            url: url,
            type: method,
            contentType: 'application/json',
            data: JSON.stringify(datosUpdate),
            dataType: 'json',
            success: function(response) {
                Swal.fire('Línea Actualizada');
                mostrarListado();
                listadoConsumo();
            },
            error: function(xhr) {
                Swal.fire('Error', xhr.responseText || 'Error en el servidor', 'error');
            }
        });
    }

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

function listadoConsumo() {

    const hoy = new Date();
    const horaActual = hoy.toTimeString().substr(0, 5);
    const fechaActual = hoy.toISOString().split('T')[0]; // Formato: "2023-11-15"
    const turno = determinarTurno(horaActual);

    const fechaReferencia = obtenerFechaSegunTurno(fechaActual, horaActual);
    $("#titulo-seccion").html(`Reportes de Consumo de Insumos Críticos - Turno ${turno} <br>${formatearFecha(fechaReferencia)} - ${horaActual}`);
    
    $.ajax({
        type: "GET", 
        url: "/ajaxConsumoInsumos",
        dataType: "json",
        success: function(response) {
            let html = '';

            $("#campoFecha").html(formatearFecha(response[0].fecha));

            
            response.forEach(incidencia => {
                html += `
                <tr class="text-center">
                    <td>${incidencia.insumo}</td>
                    <td>${incidencia.unidad}</td>
                    <td>${incidencia.departamento}</td>
                    <td>${incidencia.stock_inicial}</td>
                    <td>${incidencia.cantidad_recibida}</td>
                    <td>${incidencia.cantidad_entregada}</td>
                    <td>${incidencia.stock_final}</td>
                    <td>${incidencia.observacion}</td>
                    <td colspan="2">
                        <button class="btn btn-success" id="editar" value="${incidencia.id}">Editar</button>
                        <button class="btn btn-danger" id="eliminar" value="${incidencia.id}">Eliminar</button>
                    </td>
                </tr>`;
            });
            $("#tbody").html(html);
        }
    });
}

function filtrar(filtros) {
    const hoy = new Date();
    const horaActual = hoy.toTimeString().substr(0, 5);
    const fechaActual = hoy.toISOString().split('T')[0];
    const turno = determinarTurno(horaActual);
    const fechaReferencia = obtenerFechaSegunTurno(fechaActual, horaActual);
    
    $("#titulo-seccion").html(`Reportes de Consumo de Insumos Críticos - Turno ${turno} <br>${formatearFecha(fechaReferencia)} - ${horaActual}`);
    
    $.ajax({
        type: "GET", 
        url: "/ajaxConsumoInsumos?" + $.param(filtros),
        dataType: "json",
        success: function(response) {
            // Verificación más robusta
            if(!response || !Array.isArray(response)) {
                console.error("Respuesta inválida:", response);
                $("#tbody").html('<tr><td colspan="9">No hay datos disponibles</td></tr>');
                $("#campoFecha").html(formatearFecha(fechaReferencia)); // Usar fecha actual como fallback
                return;
            }

            if(response.length === 0) {
                $("#tbody").html('<tr><td colspan="9">No se encontraron resultados para los filtros aplicados</td></tr>');
                $("#campoFecha").html(formatearFecha(fechaReferencia)); // Usar fecha actual como fallback
                return;
            }

            let html = '';
            response.forEach(incidencia => {
                if(!incidencia) return; // Saltar elementos undefined
                
                html += `
                <tr class="text-center">
                    <td>${incidencia.insumo || 'N/A'}</td>
                    <td>${incidencia.unidad || 'N/A'}</td>
                    <td>${incidencia.departamento || 'N/A'}</td>
                    <td>${incidencia.stock_inicial || '0'}</td>
                    <td>${incidencia.cantidad_recibida || '0'}</td>
                    <td>${incidencia.cantidad_entregada || '0'}</td>
                    <td>${incidencia.stock_final || '0'}</td>
                    <td>${incidencia.observacion || ''}</td>
                    <td colspan="2">
                        <button class="btn btn-success" id="editar" value="${incidencia.id || ''}">Editar</button>
                        <button class="btn btn-danger" id="eliminar" value="${incidencia.id || ''}">Eliminar</button>
                    </td>
                </tr>`;
            });
            
            $("#tbody").html(html);
            $("#campoFecha").html(formatearFecha(response[0]?.fecha || fechaReferencia));
        },
        error: function(xhr) {
            console.error("Error en la solicitud:", xhr);
            $("#tbody").html('<tr><td colspan="9">Error al cargar los datos</td></tr>');
            $("#campoFecha").html(formatearFecha(fechaReferencia));
            Swal.fire('Error', 'No se pudieron cargar los datos filtrados', 'error');
        }
    });
}

function cargarStockInicial(fecha){
    const hoy = new Date();
    const horaActual = hoy.toTimeString().substr(0, 5);
    const fechaActual = hoy.toISOString().split('T')[0];
    const turno = determinarTurno(horaActual);
    const fechaReferencia = obtenerFechaSegunTurno(fechaActual, horaActual);
    
    
    $.ajax({
        type: "GET", 
        url: "/ajaxConsumoInsumos?" + $.param(fecha),
        dataType: "json",
        success: function(response) {
            // Verificación más robusta
            if(!response || !Array.isArray(response)) {
                console.error("Respuesta inválida:", response);
                Swal.fire('Error', 'No se pudo cargar Stock Inicial.', 'error');
                return;
            }

            if(response.length === 0) {
                console.error("Respuesta inválida:", response);
                Swal.fire('Error', 'No se pudo cargar Stock Inicial.', 'error');
                return;
            }

            // Mapear stocks por insumo_id para mejor performance
            const stocks = {};
            response.forEach(item => {
                if(item && item.insumo_id) {
                    stocks[item.insumo_id] = item.stock_final;
                }
            });

            // Aplicar a cada fila
            $(".fila-insumo").each(function() {
                const fila = $(this);
                const insumoId = fila.find(".insumo_id").val();
                
                if(stocks[insumoId] !== undefined) {
                    fila.find(".stock_inicial").val(parseFloat(stocks[insumoId]).toFixed(2));
                }
            });
        },
        error: function(xhr) {
            console.error("Error en la solicitud:", xhr);
            Swal.fire('Error', 'No se pudieron cargar los datos del Stock Inicial', 'error');
        }
    });
}

function calcularStockFinal(fila) {
    try {
        const inicial = parseFloat(fila.find(".stock_inicial").val()) || 0;
        const recibido = parseFloat(fila.find(".cantidad_recibida").val()) || 0;
        const retirado = parseFloat(fila.find(".cantidad_retirada").val()) || 0;
        
        const stockFinal = inicial + recibido - retirado;
        fila.find(".stock_final").val(stockFinal.toFixed(2));
    } catch(e) {
        console.error("Error en cálculo:", e);
        fila.find(".stock_final").val("0.00");
    }
}

function obtenerConsumo(id) {
    $.ajax({
        type: "GET",
        url: "/ajaxConsumoInsumos/" + id,
        dataType: "json",
        success: function(response) {
            if (response && response.length > 0) {
                const incidencia = response[0];
                $(".id").val(incidencia.id);
                $("#fecha").val(incidencia.fecha);
                $(".insumo_id").val(obtenerInsumos(incidencia.insumo_id));
                $(".stock_inicial").val(incidencia.stock_inicial);
                $(".cantidad_recibida").val(incidencia.cantidad_recibida);
                $(".cantidad_retirada").val(incidencia.cantidad_entregada);
                $(".stock_final").val(incidencia.stock_final);
                $(".observaciones").val(incidencia.observacion);
            }
        },
        error: function(xhr) {
            console.error("Error al obtener el insumo:", xhr.responseText);
        }
    });
}

function eliminarConsumo(id) {
    $.ajax({
        type: "DELETE",
        url: "/ajaxConsumoInsumos/" + id,
        dataType: "json",
        success: function(response) {
            listadoConsumo();
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
    $("#agregarInsumo").removeClass("d-none");
}

function mostrarFormulario() {
    $("#lista").addClass("d-none");
    $("#form").removeClass("d-none");
}

function mostrarFormularioEditar() {
    $("#lista").addClass("d-none");
    $("#form").removeClass("d-none");
    $("#agregarInsumo").addClass("d-none");
}

function limpiarFormulario() {
    $("#fecha").val("");
}