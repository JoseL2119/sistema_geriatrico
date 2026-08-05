$(document).ready( function() {
    mostrarListado();
    listadoConsignacionesPendientes();

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
            guardarConsignacionesPendientes(info);
        } else{
            modificarConsignacionesPendientes(id, info);
        }
        mostrarListado();
    });

    $(document).on("click", "#verFicha", function(e){
        const id = $(this).attr("value");
        obtenerFichaConsignaciones(id);
    });

    $(document).on("click", ".contactar-whatsapp", function(e){
        let telefono = $(this).attr("data-tlf");
        let cedula = $(this).attr("data-cedula");
        let nombres = $(this).attr("data-nombres");
        let apellidos = $(this).attr("data-apellidos");

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
            "Nos ponemos en contacto con usted para informarle que tiene consignaciones pendientes para el residente " +
            nombres + " " + apellidos + ", de cédula " + cedula + ". Por favor, entregar lo faltante lo antes posible. Gracias!"
        );

        window.open(
            "https://wa.me/" + telefono + "?text=" + mensaje,
            "_blank"
        );
    });

    $(document).on("click", "#editar", function(e){
        const id = $(this).attr("value");
        obtenerConsignacionesPendientes(id);
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
                eliminarConsignacionesPendientes(id);
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
function listadoConsignacionesPendientes(){
    $.ajax({
        type: "GET", 
        url: "/ajaxConsignacionesPendientes",
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
        url: "/ajaxConsignacionesPendientes?" + $.param(filtros),
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

function obtenerFichaConsignaciones(id){
    $.ajax({
        type: "GET",
        url: "/ajaxDetallesConsignacionesPendientes/" + id,
        dataType: "json",

        success: function(response){
            html = '';

            const requerimiento = response[0];

            console.log("Listado de Consignaciones:", response);
            console.log("Nombre Residente:", requerimiento.nombres_residente);
            console.log("Observaciones consignación:", requerimiento.observaciones);

            // Datos personales
            $("#ficha_cedula").text(requerimiento.cedula_residente);
            $("#ficha_nombres").text(requerimiento.nombres_residente + " " + requerimiento.apellidos_residente);

            response.forEach((element) => {
                html += `
                <tr class="text-center">
                    <td colspan = "3">${element.nombre_articulo}</td>
                    <td colspan = "2">${element.cantidad_requerida}</td>
                    <td colspan = "2">${element.cantidad_entregada}</td>
                    <td colspan = "2">${element.cantidad_pendiente}</td>
                    <td colspan = "2">${formatearFecha(element.fecha_periodo)}</td>
                    <td colspan = "2">${formatearFecha(element.fecha_siguiente_periodo)}</td>
                    <td colspan = "2">${element.estado}</td>
                </tr>
                `
            });
            $("#tbodyModalArticulos").html(html); //id del tbody de la tabla

            obtenerTlfRepresentante(requerimiento.id_residente);

            // Mostrar modal
            $("#modalConsignacionesResidente").modal("show");
        },

        error: function(req, status, error){
            const err = req.responseText;
            console.log(err);

            Swal.fire({
                icon: "error",
                title: "Error",
                text: "No se pudo obtener la información del requerimiento."
            });
        }
    });
}

function normalizarTelefono(telefono){

    telefono = telefono.replace(/\D/g, "");

    if(telefono.startsWith("0")){
        telefono = "58" + telefono.substring(1);
    }

    return telefono;
}

function obtenerTlfRepresentante(id){
    $.ajax({
        type: "GET",
        url: "/ajaxResidentes",
        dataType: "json",
        success: function (response){
            if(id > 0){
                response.forEach((element) => {
                    if(element.id == id){
                        $(".contactar-whatsapp").attr("data-tlf", normalizarTelefono(element.telefono));
                        $(".contactar-whatsapp").attr("data-cedula", element.cedula);
                        $(".contactar-whatsapp").attr("data-nombres", element.nombres);
                        $(".contactar-whatsapp").attr("data-apellidos", element.apellidos);

                        console.log(element.nombres);
                        console.log(element.apellidos);

                        true;
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

function guardarConsignacionesPendientes(params){
    $.ajax({
        type: "POST",
        url: "/ajaxConsignacionesPendientes",
        data: params,
        dataType: "json",
        success: function (response){
            listadoConsignacionesPendientes();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function modificarConsignacionesPendientes(id, params){
    $.ajax({
        type: "PUT",
        url: "/ajaxConsignacionesPendientes/" + id,
        data: params,
        dataType: "json",
        success: function (response){
            listadoConsignacionesPendientes();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function obtenerConsignacionesPendientes(id){
    $.ajax({
        type: "GET",
        url: "/ajaxConsignacionesPendientes/" + id,
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

function eliminarConsignacionesPendientes(id){
    $.ajax({
        type: "DELETE",
        url: "/ajaxConsignacionesPendientes/" + id,
        dataType: "json",
        success: function (response){
            listadoConsignacionesPendientes();
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
