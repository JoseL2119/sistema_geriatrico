$(document).ready( function() {
    mostrarListado();
    listadoResidentes();

    // Ir al formulario desde el listado
    $(document).on("click", "#nuevo", function (e){
        // obtenerExcavacion(0);
        obtenerRepresentante(0);
        obtenerStatus(0);
        obtenerMovilidad(0);
        obtenerCentro(0);
        obtenerMedico(0);
        obtenerParentesco(0);
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

    $(document).on(
        "change",
        "input[name='tipo_representante']",
        function(){

            const tipo = $(this).val();

            if(tipo === "existente"){

                $("#contenedor-representante-existente")
                    .removeClass("d-none");

                $("#contenedor-representante-nuevo")
                    .addClass("d-none");

            }

            if(tipo === "nuevo"){

                $("#contenedor-representante-existente")
                    .addClass("d-none");

                $("#contenedor-representante-nuevo")
                    .removeClass("d-none");

            }

        }
    );

    // Cuando se haga submit en el formulario...
    $("#formulario").submit( function(e){
        e.preventDefault();

        const tipoRepresentante = $("input[name='tipo_representante']:checked").val();

        let tipo_registro;

        if(tipoRepresentante === "nuevo"){
            tipo_registro = "completo";
        } else {
            tipo_registro = "parcial";
        }



        const id = $("#id").val();
        const cedula = $("#cedula").val();
        const nombres = $("#nombres").val();
        const apellidos = $("#apellidos").val();
        const fecha = $("#fecha").val();
        const fecha_ingreso = $("#fecha_ingreso").val();
        const fecha_egreso = $("#fecha_egreso").val();
        const peso = $("#peso").val();
        const altura = $("#altura").val();
        const status = $("#status").val();
        const ivss = $("#ivss").val();
        const pensionado = $("#pensionado").val();
        const privado = $("#privado").val();
        const contencion_f = $("#contencion_f").val();
        const vulnerabilidad_f = $("#vulnerabilidad_f").val();
        const apadrinazgo = $("#apadrinazgo").val();
        const psiquiatrico = $("#psiquiatrico").val();
        const diagnostico = $("#diagnostico").val();
        const condicion = $("#condicion").val();
        const control_esfinteres = $("#control_esfinteres").val();
        const centro_medico = $("#centro_medico").val();
        const medico = $("#medico").val();
        const observaciones = $("#observaciones").val();
        const representante = $("#representante").val();
        const parentesco = $("#parentesco").val();
        
        const info = {
            tipo_registro: tipo_registro,
            cedula: cedula,
            nombres: nombres,
            apellidos: apellidos,
            fecha_nacimiento: fecha,
            fecha_ingreso: fecha_ingreso,
            fecha_egreso: fecha_egreso === "" ? null : fecha_egreso,
            peso: peso,
            altura: altura,
            status: status,
            convenio_ivss: ivss,
            pensionado: pensionado,
            caso_privado: privado,
            contencion_f: contencion_f,
            vulnerabilidad_f: vulnerabilidad_f,
            apadrinazgo: apadrinazgo,
            psiquiatrico: psiquiatrico,
            diagnostico: diagnostico,
            condicion_mov: condicion,
            control_esfinteres: control_esfinteres,
            centro_medico_e: centro_medico,
            medico_tratante: medico,
            observaciones: observaciones,
            //id_representante: representante,
            parentesco: parentesco
        }

        if(tipo_registro === "parcial"){
            info.id_representante = $("#representante").val();
        }

        if(tipo_registro === "completo"){
            info.cedula_representante = $("#cedula_representante").val();
            info.nombres_representante = $("#nombres_representante").val();
            info.apellidos_representante = $("#apellidos_representante").val();
            info.telefono_representante = $("#tlf_representante").val();
            info.fecha_nacimiento_representante = $("#fecha_representante").val();
            info.domicilio_representante = $("#domicilio_representante").val();
            info.fecha_pago_representante = $("#fechaPago_representante").val();
            info.familiar_alternativo = $("#familiar_alt_representante").val();
            info.telefono_familiar_alt = $("#telefono_alt_representante").val();
            info.observaciones_representante = $("#observaciones_representante").val();

        }

        console.log("Tipo de registro:", tipo_registro);
        console.log("Datos a enviar:", info);

        if(id == "0"){
            guardarResidente(info);
        } else{
            modificarResidente(id, info);
        }
        mostrarListado();
    });

    $(document).on("click", "#verFicha", function(e){
        const id = $(this).attr("value");
        obtenerFichaResidente(id);
    });

    $(document).on("click", "#editar", function(e){
        const id = $(this).attr("value");
        obtenerResidente(id);
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
                eliminarResidente(id);
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
function listadoResidentes(){
    $.ajax({
        type: "GET", 
        url: "/ajaxResidentes",
        dataType: "json",
        success: function(response) {
            html = '';
            response.forEach((element) => {
                html += `
                <tr class="text-center">
                    <td colspan = "3">${element.cedula}</td>
                    <td colspan = "3">${element.nombres} ${element.apellidos}</td>
                    <td colspan = "2">${formatearFecha(element.fecha_nacimiento)}</td>
                    <td colspan = "2">${formatearFecha(element.fecha_ingreso)}</td>
                    <td colspan = "2">${element.status_r}</td>
                    <td colspan = "2">
                        <a class="btn btn-info" id="verFicha" value="${element.id}">Ver ficha</a>
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

function obtenerFichaResidente(id){
    $.ajax({
        type: "GET",
        url: "/ajaxResidentes/" + id,
        dataType: "json",

        success: function(response){

            const residente = response[0];

            console.log("Datos de la ficha:", residente);

            // Datos personales
            $("#ficha_cedula").text(residente.cedula);
            $("#ficha_nombres").text(residente.nombres);
            $("#ficha_apellidos").text(residente.apellidos);
            $("#ficha_fecha_nacimiento").text(formatearFecha(residente.fecha_nacimiento));

            // Datos de ingreso
            $("#ficha_fecha_ingreso").text(formatearFecha(residente.fecha_ingreso));
            $("#ficha_fecha_egreso").text(
                residente.fecha_egreso 
                    ? formatearFecha(residente.fecha_egreso) 
                    : "Actualmente residente"
            );

            // Datos físicos
            $("#ficha_peso").text(residente.peso + " kg");
            $("#ficha_altura").text(residente.altura + " m");

            // Datos administrativos
            $("#ficha_status").text(residente.status_r);
            
            if(residente.convenio_ivss == 0){
                $("#ficha_ivss").text("No");
            }else{
                $("#ficha_ivss").text("Si");
            }

            if(residente.pensionado == 0){
                $("#ficha_pensionado").text("No");
            }else{
                $("#ficha_pensionado").text("Si");
            }

            if(residente.privado == 0){
                $("#ficha_privado").text("No");
            }else{
                $("#ficha_privado").text("Si");
            }

            if(residente.contencion_f == 0){
                $("#ficha_contencion").text("No");
            }else{
                $("#ficha_contencion").text("Si");
            }

            if(residente.vulnerabilidad_f == 0){
                $("#ficha_vulnerabilidad").text("No");
            }else{
                $("#ficha_vulnerabilidad").text("Si");
            }

            if(residente.apadrinazgo == 0){
                $("#ficha_apadrinazgo").text("No");
            }else{
                $("#ficha_apadrinazgo").text("Si");
            }

            // Datos médicos
            $("#ficha_condicion_mov").text(residente.c_movilidad);
            $("#ficha_centro_medico").text(residente.centro);
            $("#ficha_medico").text(residente.medico);

            if(residente.psiquiatrico == 0){
                $("#ficha_psiquiatrico").text("No");
            }else{
                $("#ficha_psiquiatrico").text("Si");
            }

            $("#ficha_diagnostico").text(residente.diagnostico);

            // Representante
            $("#ficha_representante").text(residente.representante);
            $("#ficha_parentesco").text(residente.parentesco);

            // Observaciones
            $("#ficha_observaciones").text(
                residente.observaciones || "Sin observaciones"
            );

            // Mostrar modal
            $("#modalFichaResidente").modal("show");
        },

        error: function(req, status, error){
            const err = req.responseText;
            console.log(err);

            Swal.fire({
                icon: "error",
                title: "Error",
                text: "No se pudo obtener la información del residente."
            });
        }
    });
}

/*
function filtrar(filtros){
    const hoy = new Date();
    const horaActual = hoy.toTimeString().substr(0, 5);
    const fechaActual = hoy.toISOString().split('T')[0]; // Formato: "2023-11-15"
    const turno = determinarTurno(horaActual);

    const fechaReferencia = obtenerFechaSegunTurno(fechaActual, horaActual);
    $("#titulo-seccion").html(`Reportes del Control de Alimentación - Turno ${turno} <br>${formatearFecha(fechaReferencia)} - ${horaActual}`);

    $.ajax({
        type: "GET", 
        url: "/ajaxResidentes?" + $.param(filtros),
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
*/

function guardarResidente(params){
    $.ajax({
        type: "POST",
        url: "/ajaxResidentes",
        data: params,
        dataType: "json",
        success: function (response){
            console.log("Respuesta del servidor:", response);
            listadoResidentes();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function modificarResidente(id, params){
    $.ajax({
        type: "PUT",
        url: "/ajaxResidentes/" + id,
        data: params,
        dataType: "json",
        success: function (response){
            listadoResidentes();
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
        url: "/ajaxResidentes/" + id,
        dataType: "json",
        success: function (response){
            response = response[0];
            $("#id").val(response.id);
            $("#cedula").val(response.cedula);
            $("#nombres").val(response.nombres);
            $("#apellidos").val(response.apellidos);
            $("#fecha").val(response.fecha_nacimiento);
            $("#fecha_ingreso").val(response.fecha_ingreso);
            $("#fecha_egreso").val(response.fecha_egreso);
            $("#peso").val(response.peso);
            $("#altura").val(response.altura);
            $("#status").val(obtenerStatus(response.status));
            $("#ivss").val(response.convenio_ivss);
            $("#pensionado").val(response.pensionado);
            $("#privado").val(response.caso_privado);
            $("#contencion_f").val(response.contencion_f);
            $("#vulnerabilidad_f").val(response.vulnerabilidad_f);
            $("#apadrinazgo").val(response.apadrinazgo);
            $("#psiquiatrico").val(response.psiquiatrico);
            $("#diagnostico").val(response.diagnostico);
            $("#condicion").val(obtenerMovilidad(response.condicion_mov));
            $("#control_esfinteres").val(response.control_esfinteres);
            $("#centro_medico").val(obtenerCentro(response.centro_medico_e));
            $("#medico").val(obtenerMedico(response.medico_tratante));
            $("#observaciones").val(response.observaciones);
            $("#representante").val(obtenerRepresentante(response.id_representante));
            $("#parentesco").val(obtenerParentesco(response.id_parentesco));
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function obtenerRepresentante(id){
    $.ajax({
        type: "GET",
        url: "/ajaxRepresentantes",
        dataType: "json",
        success: function (response){
            $("#representante").empty();
            valor='';
            html = '<option selected>Elegir Representante</option>';
            if(id==0){
                $("#representante").append(html);

                response.forEach((element) => {
                    html = '<option value="' + element.id + '">' + element.nombres + '</option>';
                    $('#representante').append(html);
                });
            } 
            
            if(id > 0){
                response.forEach((element) => {
                    if(element.id == id){
                        html = '<option selected value="' + element.id + '">' + element.nombres + '</option>';
                        $('#representante').append(html);
                        true;
                    } 
                    else{
                        html = '<option value="' + element.id + '">' + element.nombres + '</option>';
                        $('#representante').append(html);
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

function obtenerMovilidad(id){
    $.ajax({
        type: "GET",
        url: "/ajaxMovilidad",
        dataType: "json",
        success: function (response){
            $("#condicion").empty();
            valor='';
            html = '<option selected>Seleccionar opción</option>';
            if(id==0){
                $("#condicion").append(html);

                response.forEach((element) => {
                    html = '<option value="' + element.id + '">' + element.condicion + '</option>';
                    $('#condicion').append(html);
                });
            } 
            
            if(id > 0){
                response.forEach((element) => {
                    if(element.id == id){
                        html = '<option selected value="' + element.id + '">' + element.condicion + '</option>';
                        $('#condicion').append(html);
                        true;
                    } 
                    else{
                        html = '<option value="' + element.id + '">' + element.condicion + '</option>';
                        $('#condicion').append(html);
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

function obtenerCentro(id){
    $.ajax({
        type: "GET",
        url: "/ajaxCentrosMedicos",
        dataType: "json",
        success: function (response){
            $("#centro_medico").empty();
            valor='';
            html = '<option selected>Seleccionar Centro</option>';
            if(id==0){
                $("#centro_medico").append(html);

                response.forEach((element) => {
                    html = '<option value="' + element.id + '">' + element.nombre + '</option>';
                    $('#centro_medico').append(html);
                });
            } 
            
            if(id > 0){
                response.forEach((element) => {
                    if(element.id == id){
                        html = '<option selected value="' + element.id + '">' + element.nombre + '</option>';
                        $('#centro_medico').append(html);
                        true;
                    } 
                    else{
                        html = '<option value="' + element.id + '">' + element.nombre + '</option>';
                        $('#centro_medico').append(html);
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

function obtenerMedico(id){
    $.ajax({
        type: "GET",
        url: "/ajaxMedicos",
        dataType: "json",
        success: function (response){
            $("#medico").empty();
            valor='';
            html = '<option selected>Seleccionar Médico</option>';
            if(id==0){
                $("#medico").append(html);

                response.forEach((element) => {
                    html = '<option value="' + element.id + '">' + element.nombre + '</option>';
                    $('#medico').append(html);
                });
            } 
            
            if(id > 0){
                response.forEach((element) => {
                    if(element.id == id){
                        html = '<option selected value="' + element.id + '">' + element.nombre + '</option>';
                        $('#medico').append(html);
                        true;
                    } 
                    else{
                        html = '<option value="' + element.id + '">' + element.nombre + '</option>';
                        $('#medico').append(html);
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

function obtenerStatus(id){
    $.ajax({
        type: "GET",
        url: "/ajaxStatus",
        dataType: "json",
        success: function (response){
            $("#status").empty();
            valor='';
            html = '<option selected>Seleccionar Status</option>';
            if(id==0){
                $("#status").append(html);

                response.forEach((element) => {
                    html = '<option value="' + element.id + '">' + element.status + '</option>';
                    $('#status').append(html);
                });
            } 
            
            if(id > 0){
                response.forEach((element) => {
                    if(element.id == id){
                        html = '<option selected value="' + element.id + '">' + element.status + '</option>';
                        $('#status').append(html);
                        true;
                    } 
                    else{
                        html = '<option value="' + element.id + '">' + element.status + '</option>';
                        $('#status').append(html);
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

function obtenerParentesco(id){
    $.ajax({
        type: "GET",
        url: "/ajaxParentesco",
        dataType: "json",
        success: function (response){
            $("#parentesco").empty();
            valor='';
            html = '<option selected>Seleccionar Parentesco</option>';
            if(id==0){
                $("#parentesco").append(html);

                response.forEach((element) => {
                    html = '<option value="' + element.id + '">' + element.parentesco + '</option>';
                    $('#parentesco').append(html);
                });
            } 
            
            if(id > 0){
                response.forEach((element) => {
                    if(element.id == id){
                        html = '<option selected value="' + element.id + '">' + element.parentesco + '</option>';
                        $('#parentesco').append(html);
                        true;
                    } 
                    else{
                        html = '<option value="' + element.id + '">' + element.parentesco + '</option>';
                        $('#parentesco').append(html);
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

function eliminarResidente(id){
    $.ajax({
        type: "DELETE",
        url: "/ajaxResidentes/" + id,
        dataType: "json",
        success: function (response){
            listadoResidentes();
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
    $("#cedula").val("");
    $("#nombres").val("");
    $("#apellidos").val("");
    $("#fecha").val("");
    $("#fecha_ingreso").val("");
    $("#fecha_egreso").val("");
    $("#peso").val(0);
    $("#altura").val(0);
    $("#status").val(obtenerStatus(0));
    $("#ivss").val();
    $("#pensionado").val();
    $("#privado").val();
    $("#contencion_f").val();
    $("#vulnerabilidad_f").val();
    $("#apadrinazgo").val();
    $("#psiquiatrico").val();
    $("#diagnostico").val("");
    $("#condicion").val(obtenerMovilidad(0));
    $("#control_esfinteres").val();
    $("#centro_medico").val(obtenerCentro(0));
    $("#medico").val(obtenerMedico(0));
    $("#observaciones").val("");
    $("#representante").val(obtenerRepresentante(0));
    $("#parentesco").val(obtenerParentesco(0));
}
