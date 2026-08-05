// Variables globales
let reporteActual = {
    horas_parada: [],
    novedades: []
};

// Inicialización
$(document).ready(function() {
    mostrarListado();
    listadoProducciones();
    obtenerSupervisores(0);
    obtenerTurnos(0);
    obtenerMaquina1(0);
    obtenerMaquina2(0);

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

    $("#agregarHora").click(function() {
        $("#modalHorasParada").modal('show');
    });

    $("#guardarHoraParada").click(guardarHorasParada);

    $("#agregarNovedad").click(function() {
        $("#modalNovedades").modal('show');
    });

    $("#guardarNovedad").click(guardarNovedades);

    $("#guardarBorrador").click(function() {
        guardarReporte(false);
    });

    $("#guardarReporte").click(function() {
        guardarReporte(true);
    });

    $(document).on("click", "#editar", function(e){
        const id = $(this).attr("value");
        obtenerProduccion(id);
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
                eliminarProduccion(id);
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

function obtenerMaquina1(id){
    $.ajax({
        type: "GET",
        url: "/ajaxMaquina",
        dataType: "json",
        success: function (response){
            $("#maquina1").empty();
            valor='';
            html = '<option selected>Seleccionar...</option>';
            if(id==0){
                $("#maquina1").append(html);

                response.forEach((element) => {
                    // fecha = element.fecha.substr(8, 2) + "/" + element.fecha.substr(5, 2) + "/" + element.fecha.substr(0, 4);
                    html = '<option value="' + element.id + '">' + element.nombre + '</option>';
                    $('#maquina1').append(html);
                    // if(element.id == id) valor = element.nombre;
                });
            } 
            
            // empresaName = '<option selected>' + valor + '</option>';
            if(id > 0){
                response.forEach((element) => {
                    if(element.id == id){
                        // valor = element.nombre;
                        html = '<option selected value="' + element.id + '">' + element.nombre + '</option>';
                        $('#maquina1').append(html);
                        true;
                    } 
                    else{
                        html = '<option value="' + element.id + '">' + element.nombre + '</option>';
                        $('#maquina1').append(html);
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

function obtenerMaquina2(id){
    $.ajax({
        type: "GET",
        url: "/ajaxMaquina",
        dataType: "json",
        success: function (response){
            $("#maquina2").empty();
            valor='';
            html = '<option selected>Seleccionar...</option>';
            if(id==0){
                $("#maquina2").append(html);

                response.forEach((element) => {
                    // fecha = element.fecha.substr(8, 2) + "/" + element.fecha.substr(5, 2) + "/" + element.fecha.substr(0, 4);
                    html = '<option value="' + element.id + '">' + element.nombre + '</option>';
                    $('#maquina2').append(html);
                    // if(element.id == id) valor = element.nombre;
                });
            } 
            
            // empresaName = '<option selected>' + valor + '</option>';
            if(id > 0){
                response.forEach((element) => {
                    if(element.id == id){
                        // valor = element.nombre;
                        html = '<option selected value="' + element.id + '">' + element.nombre + '</option>';
                        $('#maquina2').append(html);
                        true;
                    } 
                    else{
                        html = '<option value="' + element.id + '">' + element.nombre + '</option>';
                        $('#maquina2').append(html);
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

function guardarHorasParada() {
    const horaP1 = $("#horaParada1").val();
    const horaP2 = $("#horaParada2").val();
    const horaP3 = $("#horaParada3").val();
    const horaI1 = $("#horaInicio1").val();
    const horaI2 = $("#horaInicio2").val();
    const horaI3 = $("#horaInicio3").val();
    const horaT1 = $("#totalParado1").val();
    const horaT2 = $("#totalParado2").val();
    const horaT3 = $("#totalParado3").val();
    const texto = $("#textoMotivo").val().trim();

    if (!horaP1 && !horaP2 && !horaP3 && !horaI1 && !horaI2 && !horaI3 && !horaT1 && !horaT2 && !horaT3) {
        Swal.fire('Error', 'Debe completar, al menos, un campo', 'error');
        return;
    }

    reporteActual.horas_parada.push({
        horaP1: horaP1,
        horaP2: horaP2,
        horaP3: horaP3,
        horaI1: horaI1,
        horaI2: horaI2,
        horaI3: horaI3,
        horaT1: horaT1,
        horaT2: horaT2,
        horaT3: horaT3,
        texto: texto
    });

    actualizarListaHorasParada();
    $("#modalHorasParada").modal('hide');
    $("#horaParada1").val('');
    $("#horaParada2").val('');
    $("#horaParada3").val('');
    $("#horaInicio1").val('');
    $("#horaInicio2").val('');
    $("#horaInicio3").val('');
    $("#totalParado1").val('');
    $("#totalParado2").val('');
    $("#totalParado3").val('');
    $("#textoMotivo").val('');
}

function actualizarListaHorasParada() {
    const lista = $("#listaHoras");
    lista.empty();

    reporteActual.horas_parada.forEach((obs, index) => {
        lista.append(`
        <div class="card mb-2" id="obs-${index}">
            <div class="card-body py-2">
                <div class="d-flex justify-content-between">
                    <div>
                        <span class="badge bg-primary me-2">${obs.horaP1}</span>
                        <span class="badge bg-primary me-2">${obs.horaP2}</span>
                        <span class="badge bg-primary me-2">${obs.horaP3}</span>
                    </div>
                    <div>
                        <span class="badge bg-primary me-2">${obs.horaI1}</span>
                        <span class="badge bg-primary me-2">${obs.horaI2}</span>
                        <span class="badge bg-primary me-2">${obs.horaI3}</span>
                    </div>
                    <div>
                        <span class="badge bg-primary me-2">${obs.horaT1}</span>
                        <span class="badge bg-primary me-2">${obs.horaT2}</span>
                        <span class="badge bg-primary me-2">${obs.horaT3}</span>
                        <span>${obs.texto}</span>
                    </div>
                    <button class="btn btn-sm btn-outline-danger" onclick="eliminarHorasParada(${index})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>`);
    });
}

function eliminarHorasParada(index) {
    reporteActual.horas_parada.splice(index, 1);
    actualizarListaHorasParada();
}

function guardarNovedades() {
    const texto = $("#textoNovedad").val().trim();

    if (!texto) {
        Swal.fire('Error', 'Debe completar todos los campos', 'error');
        return;
    }

    reporteActual.novedades.push({
        texto: texto
    });

    actualizarListaNovedades();
    $("#modalNovedades").modal('hide');
    $("#textoNovedad").val('');
}

function actualizarListaNovedades() {
    const lista = $("#listaNovedades");
    lista.empty();

    reporteActual.novedades.forEach((obs, index) => {
        lista.append(`
        <div class="card mb-2" id="obs-${index}">
            <div class="card-body py-2">
                <div class="d-flex justify-content-between">
                    <div>
                        <span>${obs.texto}</span>
                    </div>
                    <button class="btn btn-sm btn-outline-danger" onclick="eliminarNovedades(${index})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>`);
    });
}

function eliminarNovedades(index) {
    reporteActual.novedades.splice(index, 1);
    actualizarListaNovedades();
}

function guardarReporte(finalizado) {
    const id_supervisor = $("#supervisor_id").val();
    const grupo = $("#grupo").val();
    const fecha = $("#fecha").val();
    const id_turno = $("#turno").val();
    const recibe_ton_1 = $("#tr1").val();
    const recibe_ton_2 = $("#tr2").val();
    const entrega_ton_1 = $("#te1").val();
    const entrega_ton_2 = $("#te2").val();
    const palas_alimentadas_1 = $("#pt1").val();
    const palas_alimentadas_2 = $("#pt2").val();
    const id_payloader_1 = $("#maquina1").val();
    const id_payloader_2 = $("#maquina2").val();
    const codigo_1 = $("#ca1").val();
    const codigo_2 = $("#ca2").val();
    const id = $("#id").val();

    if (!id_supervisor || !grupo || !id_turno || !fecha) {
        Swal.fire('Error', 'Debe completar los datos básicos', 'error');
        return;
    }

    const datos = {
        id_supervisor: id_supervisor,
        grupo: grupo,
        fecha: fecha,
        id_turno: id_turno,
        recibe_ton_1: recibe_ton_1,
        recibe_ton_2: recibe_ton_2,
        entrega_ton_1: entrega_ton_1,
        entrega_ton_2: entrega_ton_2,
        palas_alimentadas_1: palas_alimentadas_1,
        palas_alimentadas_2: palas_alimentadas_2,
        id_payloader_1: id_payloader_1,
        id_payloader_2: id_payloader_2,
        codigo_1: codigo_1,
        codigo_2: codigo_2,
        horas_parada: reporteActual.horas_parada,
        novedades: reporteActual.novedades,
    };

    console.log("Datos a enviar:", JSON.stringify(datos, null, 2));

    const url = id === "0" ? '/ajaxProduccion' : `/ajaxProduccion/${id}`;
    const method = id === "0" ? 'POST' : 'PUT';

    $.ajax({
        url: url,
        type: method,
        contentType: 'application/json',
        data: JSON.stringify(datos),
        dataType: 'json',
        success: function(response) {
            Swal.fire('Éxito', finalizado ? 'Reporte finalizado' : 'Borrador guardado', 'success');
            mostrarListado();
            listadoProducciones();
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

function listadoProducciones() {

    const hoy = new Date();
    const horaActual = hoy.toTimeString().substr(0, 5);
    const fechaActual = hoy.toISOString().split('T')[0]; // Formato: "2023-11-15"
    const turno = determinarTurno(horaActual);

    const fechaReferencia = obtenerFechaSegunTurno(fechaActual, horaActual);
    $("#titulo-seccion").html(`Reportes de Produccion - Turno ${turno} <br>${formatearFecha(fechaReferencia)} - ${horaActual}`);
    
    $.ajax({
        type: "GET", 
        url: "/ajaxProduccion",
        dataType: "json",
        success: function(response) {
            let html = '';
            
            let html2 = `
                <tr class="text-center">
                    <td colspan="3">TOLVAS</td>
                    <td colspan="3">PALAS ALIMENTADAS</td>
                </tr>
                
                <tr class="text-center">
                    <td colspan="1"></td>
                    <td>RECIBE TON</td>
                    <td>ENTREGA TON</td>
                    <td>PALAS</td>
                    <td>PAYLOADER</td>
                    <td>CÓDIGO</td>
                </tr>
                
                `;
            
            let html3 = `
                <tr class="text-center">
                    <td colspan="6">HORAS DE PARADA</td>
                </tr>

                <tr class="text-center">
                    <td>HORA</td>
                    <td>MOBO 1</td>
                    <td>MOBO 2</td>
                    <td>MOBO 3</td>
                    <td colspan="2">Motivo de parada</td>
                </tr>`;

            html4 = `
                <tr class="text-center">
                    <td colspan="6">NOVEDADES</td>
                </tr>`;

            
            response.forEach(incidencia => {
                if (incidencia.fecha && incidencia.fecha.startsWith(fechaReferencia)){
                    html += `
                    <tr class="text-center">
                        <td>${formatearFecha(incidencia.fecha)}</td>
                        <td>${incidencia.turno}</td>
                        <td>${incidencia.supervisor}</td>
                        <td>${incidencia.grupo}</td>
                        <td colspan="2">
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
                        <tr class="text-center">
                            <td>Tolva 1</td>
                            <td>${incidencia.recibe_ton_1}</td>
                            <td>${incidencia.entrega_ton_1}</td>
                            <td>${incidencia.palas_alimentadas_1}</td>
                            <td>${incidencia.maquina1}</td>
                            <td>${incidencia.codigo_1}</td>
                        </tr>

                        <tr class="text-center">
                            <td>Tolva 2</td>
                            <td>${incidencia.recibe_ton_2}</td>
                            <td>${incidencia.entrega_ton_2}</td>
                            <td>${incidencia.palas_alimentadas_2}</td>
                            <td>${incidencia.maquina2}</td>
                            <td>${incidencia.codigo_2}</td>
                        </tr>`;
                }
            });
            $("#tencabezado").html(html2);
            

            response.forEach(incidencia => {
                if (incidencia.fecha && incidencia.fecha.startsWith(fechaReferencia)){
                    try {
                        const horasParada = JSON.parse(incidencia.horas_parada || '[]');
                        let cont = 0;
                        
                        horasParada.forEach(obs => {
                            if (cont == 0){
                                html3 += `
                                <tr class="text-center">
                                    <td>PARADA</td>
                                    <td>${obs.horaP1}</td>
                                    <td>${obs.horaP2}</td>
                                    <td>${obs.horaP3}</td>
                                    <td rowspan="3" colspan="3">${obs.texto}</td>
                                </tr>`;
                                cont ++;
                            }

                            if (cont == 1){
                                html3 += `
                                <tr class="text-center">
                                    <td>INICIO</td>
                                    <td>${obs.horaI1}</td>
                                    <td>${obs.horaI2}</td>
                                    <td>${obs.horaI3}</td>
                                </tr>`;
                                cont ++;
                            }

                            if (cont == 2){
                                html3 += `
                                <tr class="text-center">
                                    <td>TOTAL</td>
                                    <td>${obs.horaT1} minutos</td>
                                    <td>${obs.horaT2} minutos</td>
                                    <td>${obs.horaT3} minutos</td>
                                </tr>`;
                                cont = 0;
                            }
                        });

                        // Si no hay horasParada
                        if (horasParada.length === 0) {
                            html3 += `
                            <tr class="text-center">
                                <td colspan="6">Sin Horas de parada</td>
                            </tr>`;
                        }
                    } catch (e) {
                        html3 += `
                        <tr class="text-center">
                            <td colspan="6">Error cargando Horas de parada</td>
                        </tr>`;
                    }
                }
            });
            $("#thorasparada").html(html3);

            response.forEach(incidencia => {
                if (incidencia.fecha && incidencia.fecha.startsWith(fechaReferencia)){
                    try {
                        const novedades = JSON.parse(incidencia.novedades || '[]');
                        
                        novedades.forEach(obs => {
                            html4 += `
                            <tr class="text-center">
                                <td colspan="6">${obs.texto}</td>
                            </tr>`;
                        });

                        // Si no hay novedades
                        if (novedades.length === 0) {
                            html4 += `
                            <tr class="text-center">
                                <td colspan="6">Sin novedades</td>
                            </tr>`;
                        }
                    } catch (e) {
                        html4 += `
                        <tr class="text-center">
                            <td colspan="6">Error cargando novedades</td>
                        </tr>`;
                    }
                }
            });
            $("#tnovedades").html(html4);
        }
    });
}

function filtrar(filtros) {

    const hoy = new Date();
    const horaActual = hoy.toTimeString().substr(0, 5);
    const fechaActual = hoy.toISOString().split('T')[0]; // Formato: "2023-11-15"
    const turno = determinarTurno(horaActual);

    const fechaReferencia = obtenerFechaSegunTurno(fechaActual, horaActual);
    $("#titulo-seccion").html(`Reportes de Produccion - Turno ${turno} <br>${formatearFecha(fechaReferencia)} - ${horaActual}`);
    
    $.ajax({
        type: "GET", 
        url: "/ajaxProduccion?" + $.param(filtros),
        dataType: "json",
        success: function(response) {
            let html = '';
            
            let html2 = `
                <tr class="text-center">
                    <td colspan="3">TOLVAS</td>
                    <td colspan="3">PALAS ALIMENTADAS</td>
                </tr>
                
                <tr class="text-center">
                    <td colspan="1"></td>
                    <td>RECIBE TON</td>
                    <td>ENTREGA TON</td>
                    <td>PALAS</td>
                    <td>PAYLOADER</td>
                    <td>CÓDIGO</td>
                </tr>
                
                `;
            
            let html3 = `
                <tr class="text-center">
                    <td colspan="6">HORAS DE PARADA</td>
                </tr>

                <tr class="text-center">
                    <td>HORA</td>
                    <td>MOBO 1</td>
                    <td>MOBO 2</td>
                    <td>MOBO 3</td>
                    <td colspan="2">Motivo de parada</td>
                </tr>`;

            html4 = `
                <tr class="text-center">
                    <td colspan="6">NOVEDADES</td>
                </tr>`;

            
            response.forEach(incidencia => {
                html += `
                <tr class="text-center">
                    <td>${formatearFecha(incidencia.fecha)}</td>
                    <td>${incidencia.turno}</td>
                    <td>${incidencia.supervisor}</td>
                    <td>${incidencia.grupo}</td>
                    <td colspan="2">
                        <button class="btn btn-success" id="editar" value="${incidencia.id}">Editar</button>
                        <button class="btn btn-danger" id="eliminar" value="${incidencia.id}">Eliminar</button>
                    </td>
                </tr>`;
            });
            $("#tbody").html(html);

            response.forEach(incidencia => {
                html2 += `
                    <tr class="text-center">
                        <td>Tolva 1</td>
                        <td>${incidencia.recibe_ton_1}</td>
                        <td>${incidencia.entrega_ton_1}</td>
                        <td>${incidencia.palas_alimentadas_1}</td>
                        <td>${incidencia.maquina1}</td>
                        <td>${incidencia.codigo_1}</td>
                    </tr>

                    <tr class="text-center">
                        <td>Tolva 2</td>
                        <td>${incidencia.recibe_ton_2}</td>
                        <td>${incidencia.entrega_ton_2}</td>
                        <td>${incidencia.palas_alimentadas_2}</td>
                        <td>${incidencia.maquina2}</td>
                        <td>${incidencia.codigo_2}</td>
                    </tr>`;
            });
            $("#tencabezado").html(html2);
            

            response.forEach(incidencia => {
                try {
                    const horasParada = JSON.parse(incidencia.horas_parada || '[]');
                    let cont = 0;
                    
                    horasParada.forEach(obs => {
                        if (cont == 0){
                            html3 += `
                            <tr class="text-center">
                                <td>PARADA</td>
                                <td>${obs.horaP1}</td>
                                <td>${obs.horaP2}</td>
                                <td>${obs.horaP3}</td>
                                <td rowspan="3" colspan="3">${obs.texto}</td>
                            </tr>`;
                            cont ++;
                        }

                        if (cont == 1){
                            html3 += `
                            <tr class="text-center">
                                <td>INICIO</td>
                                <td>${obs.horaI1}</td>
                                <td>${obs.horaI2}</td>
                                <td>${obs.horaI3}</td>
                            </tr>`;
                            cont ++;
                        }

                        if (cont == 2){
                            html3 += `
                            <tr class="text-center">
                                <td>TOTAL</td>
                                <td>${obs.horaT1} minutos</td>
                                <td>${obs.horaT2} minutos</td>
                                <td>${obs.horaT3} minutos</td>
                            </tr>`;
                            cont = 0;
                        }
                    });

                    // Si no hay horasParada
                    if (horasParada.length === 0) {
                        html3 += `
                        <tr class="text-center">
                            <td colspan="6">Sin Horas de parada</td>
                        </tr>`;
                    }
                } catch (e) {
                    html3 += `
                    <tr class="text-center">
                        <td colspan="6">Error cargando Horas de parada</td>
                    </tr>`;
                }
            });
            $("#thorasparada").html(html3);

            response.forEach(incidencia => {
                try {
                    const novedades = JSON.parse(incidencia.novedades || '[]');
                    
                    novedades.forEach(obs => {
                        html4 += `
                        <tr class="text-center">
                            <td colspan="6">${obs.texto}</td>
                        </tr>`;
                    });

                    // Si no hay novedades
                    if (novedades.length === 0) {
                        html4 += `
                        <tr class="text-center">
                            <td colspan="6">Sin novedades</td>
                        </tr>`;
                    }
                } catch (e) {
                    html4 += `
                    <tr class="text-center">
                        <td colspan="6">Error cargando novedades</td>
                    </tr>`;
                }
            });
            $("#tnovedades").html(html4);
        }
    });
}

function obtenerProduccion(id) {
    $.ajax({
        type: "GET",
        url: "/ajaxProduccion/" + id,
        dataType: "json",
        success: function(response) {
            if (response && response.length > 0) {
                const incidencia = response[0];
                $("#id").val(incidencia.id);
                $("#fecha").val(incidencia.fecha);
                $("#supervisor_id").val(obtenerSupervisores(incidencia.id_supervisor));
                $("#grupo").val(incidencia.grupo);
                $("#turno").val(obtenerTurnos(incidencia.id_turno));
                $("#tr1").val(incidencia.recibe_ton_1);
                $("#tr2").val(incidencia.recibe_ton_1);
                $("#te1").val(incidencia.entrega_ton_1);
                $("#te2").val(incidencia.entrega_ton_2);
                $("#pt1").val(incidencia.palas_alimentadas_1);
                $("#pt2").val(incidencia.palas_alimentadas_2);
                $("#maquina1").val(obtenerMaquina1(incidencia.id_payloader_1));
                $("#maquina2").val(obtenerMaquina2(incidencia.id_payloader_2));
                $("#ca1").val(incidencia.codigo_1);
                $("#ca2").val(incidencia.codigo_2);

                // Parsear observaciones JSON
                reporteActual.horas_parada = incidencia.horas_parada ? 
                    (typeof incidencia.horas_parada === 'string' ? 
                     JSON.parse(incidencia.horas_parada) : 
                     incidencia.horas_parada) : 
                    [];
                
                actualizarListaHorasParada();

                // Parsear observaciones JSON
                reporteActual.novedades = incidencia.novedades ? 
                    (typeof incidencia.novedades === 'string' ? 
                     JSON.parse(incidencia.novedades) : 
                     incidencia.novedades) : 
                    [];
                
                actualizarListaNovedades();
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
        url: "/ajaxProduccion/" + id,
        dataType: "json",
        success: function(response) {
            listadoProducciones();
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
    $("#maquina1").val(obtenerMaquina1(0));
    $("#maquina2").val(obtenerMaquina2(0));
    $("#grupo").val("");
    $("#tr1").val(0);
    $("#tr2").val(0);
    $("#te1").val(0);
    $("#te2").val(0);
    $("#pt1").val(0);
    $("#pt2").val(0);
    $("#ca1").val(0);
    $("#ca2").val(0);
    $("#turno").val(obtenerTurnos(0));
    reporteActual.horas_parada = [];
    actualizarListaHorasParada();

    reporteActual.novedades = [];
    actualizarListaNovedades();
}