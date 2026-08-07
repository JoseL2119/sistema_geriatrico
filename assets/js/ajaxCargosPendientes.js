let residentesInfo = {};

$(document).ready( function() {
    mostrarListado();
    cargarResidentesYListado();

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
            guardarCargosPendientes(info);
        } else{
            modificarCargosPendientes(id, info);
        }
        mostrarListado();
    });

    $(document).on("click", ".contactar-whatsapp", function(e){
        const telefono = $(this).attr("data-tlf");
        const monto = $(this).attr("data-deuda");
        const cedula = $(this).attr("data-cedula");
        const nombres = $(this).attr("data-nombres");
        const apellidos = $(this).attr("data-apellidos");

        if(!telefono){
            Swal.fire({
                icon: "warning",
                title: "Teléfono no disponible",
                text: "Este representante no tiene un número registrado."
            });
            return;
        }

        const mensaje = encodeURIComponent(
            "Hola, le escribimos del Hogar Clínica Madre Santa Teresa. " +
            "Nos ponemos en contacto con usted para informarle que tiene una deuda pendiente por pagar de " + monto + "$ asociada al residente " +
            nombres + " " + apellidos + ", de cédula " + cedula + ". Por favor, cancelarla lo antes posible. Gracias!"
        );

        window.open(
            "https://wa.me/" + telefono + "?text=" + mensaje,
            "_blank"
        );
    });

    $(document).on("click", "#editar", function(e){
        const id = $(this).attr("value");
        obtenerCargosPendientes(id);
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
                eliminarCargosPendientes(id);
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
function cargarResidentesYListado(){
    $.ajax({
        type: "GET",
        url: "/ajaxResidentes",
        dataType: "json",
        success: function(response){
            residentesInfo = {};
            response.forEach((r) => {
                residentesInfo[r.id] = {
                    telefono: r.telefono ? normalizarTelefono(r.telefono) : "",
                    cedula: r.cedula,
                    nombres: r.nombres,
                    apellidos: r.apellidos
                };
            });
            listadoCargosPendientes();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
            listadoCargosPendientes();
        }
    });
}

function listadoCargosPendientes(){
    $.ajax({
        type: "GET", 
        url: "/ajaxCargosPendientes",
        dataType: "json",
        success: function(response) {
            console.log("Respuesta recibida:", response); // Para depuración
            html = '';
            let totalMontos = 0, totalPagados = 0, totalSaldo = 0, cont = 0;
            response.forEach((element) => {
                const fecha = formatearFecha(element.fecha);
                totalMontos += Number(element.monto);
                totalPagados += Number(element.total_pagado);
                totalSaldo += Number(element.saldo);
                const info = residentesInfo[element.id_residente] || {};
                html += `
                <tr class="text-center">
                    <td>${element.nombres} ${element.apellidos}</td>
                    <td>${element.periodo}</td>
                    <td>${element.monto}</td>
                    <td>${element.total_pagado}</td>
                    <td>${element.saldo}</td>
                    <td colspan = "2">
                        <a class="btn btn-success contactar-whatsapp" data-deuda="${element.saldo}" data-tlf="${info.telefono || ''}" data-cedula="${info.cedula || ''}" data-nombres="${info.nombres || ''}" data-apellidos="${info.apellidos || ''}" value="${element.id_residente}">Contactar</a>
                    </td>
                </tr>
                `;
                cont++;
            });

            if(cont === 0){
                html = '<tr><td colspan="6" class="text-center text-muted">No hay cargos pendientes por pagar</td></tr>';
            }

            else{
                html += `
                <tr class="text-center fw-bold">
                    <td colspan="2">Totales</td>
                    <td>${(totalMontos)}</td>
                    <td>${(totalPagados)}</td>
                    <td>${(totalSaldo)}</td>
                    <td></td>
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
        url: "/ajaxCargosPendientes?" + $.param(filtros),
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

function normalizarTelefono(telefono){

    telefono = telefono.replace(/\D/g, "");

    if(telefono.startsWith("0")){
        telefono = "58" + telefono.substring(1);
    }

    return telefono;
}

function guardarCargosPendientes(params){
    $.ajax({
        type: "POST",
        url: "/ajaxCargosPendientes",
        data: params,
        dataType: "json",
        success: function (response){
            listadoCargosPendientes();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function modificarCargosPendientes(id, params){
    $.ajax({
        type: "PUT",
        url: "/ajaxCargosPendientes/" + id,
        data: params,
        dataType: "json",
        success: function (response){
            listadoCargosPendientes();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function obtenerCargosPendientes(id){
    $.ajax({
        type: "GET",
        url: "/ajaxCargosPendientes/" + id,
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

function eliminarCargosPendientes(id){
    $.ajax({
        type: "DELETE",
        url: "/ajaxCargosPendientes/" + id,
        dataType: "json",
        success: function (response){
            listadoCargosPendientes();
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
