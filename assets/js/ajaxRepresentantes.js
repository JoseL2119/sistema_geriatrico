$(document).ready( function() {
    mostrarListado();
    listadoRepresentantes();

    // Ir al formulario desde el listado
    $(document).on("click", "#nuevo", function (e){
        mostrarFormulario();
        limpiarFormulario();
    });

    // Ir al listado desde el formulario
    $(document).on("click", "#volver", function (e){
        mostrarListado();
    });

    // Cuando se haga submit en el formulario...
    $("#formulario").submit( function(e){
        e.preventDefault();
        const id = $("#id").val();
        const cedula = $("#cedula").val();
        const nombres = $("#nombres").val();
        const apellidos = $("#apellidos").val();
        const tlf = $("#tlf").val();
        const fecha = $("#fecha").val();
        const domicilio = $("#domicilio").val();
        const fechaPago = $("#fechaPago").val();
        const familiar_alt = $("#familiar_alt").val();
        const telefono_alt = $("#telefono_alt").val();
        const observaciones = $("#observaciones").val();
        
        const info = {
            cedula: cedula,
            nombres: nombres,
            apellidos: apellidos,
            telefono: tlf,
            fecha_nacimiento: fecha,
            domicilio: domicilio,
            fecha_pago: fechaPago,
            familiar_alternativo: familiar_alt,
            telefono_familiar_alt: telefono_alt,
            observaciones: observaciones
        }
        if(id == "0"){
            guardarRepresentantes(info);
        } else{
            modificarRepresentantes(id, info);
        }
        mostrarListado();
    });

    $(document).on("click", "#verFicha", function(e){
        const id = $(this).attr("value");
        obtenerFichaRepresentante(id);
    });

    $(document).on("click", "#editar", function(e){
        const id = $(this).attr("value");
        obtenerRepresentantes(id);
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
                eliminarRepresentantes(id);
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
function listadoRepresentantes(){
    $.ajax({
        type: "GET", 
        url: "/ajaxRepresentantes",
        dataType: "json",
        success: function(response) {
            html = '';
            response.forEach((element) => {
                html += `
                <tr class="text-center">
                    <td colspan = "3">${element.cedula}</td>
                    <td colspan = "3">${element.nombres} ${element.apellidos}</td>
                    <td colspan = "2">${element.telefono}</td>
                    <td colspan = "2">${formatearFecha(element.fecha_nacimiento)}</td>
                    <td colspan = "2">${element.fecha_pago}</td>
                    <td colspan = "3">
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

function obtenerFichaRepresentante(id){
    $.ajax({
        type: "GET",
        url: "/ajaxRepresentantes/" + id,
        dataType: "json",

        success: function(response){

            const representante = response[0];

            console.log("Datos de la ficha:", representante);

            // Datos personales
            $("#ficha_cedula").text(representante.cedula);
            $("#ficha_nombres").text(representante.nombres);
            $("#ficha_apellidos").text(representante.apellidos);
            $("#ficha_fecha_nacimiento").text(formatearFecha(representante.fecha_nacimiento));
            $("#ficha_domicilio").text(representante.domicilio);
            $("#ficha_fecha_pago").text(representante.fecha_pago);

            $("#ficha_familiar_alt").text(
                representante.familiar_alternativo 
                    ? representante.familiar_alternativo 
                    : "No aplica"
            );

            $("#ficha_telefono_alt").text(
                representante.telefono_familiar_alt 
                    ? representante.telefono_familiar_alt 
                    : "No aplica"
            );
            
            // Observaciones
            $("#ficha_observaciones").text(
                representante.observaciones || "Sin observaciones"
            );

            // Mostrar modal
            $("#modalFichaRepresentante").modal("show");
        },

        error: function(req, status, error){
            const err = req.responseText;
            console.log(err);

            Swal.fire({
                icon: "error",
                title: "Error",
                text: "No se pudo obtener la información del representante."
            });
        }
    });
}

function guardarRepresentantes(params){
    $.ajax({
        type: "POST",
        url: "/ajaxRepresentantes",
        data: params,
        dataType: "json",
        success: function (response){
            listadoRepresentantes();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function modificarRepresentantes(id, params){
    $.ajax({
        type: "PUT",
        url: "/ajaxRepresentantes/" + id,
        data: params,
        dataType: "json",
        success: function (response){
            listadoRepresentantes();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function obtenerRepresentantes(id){
    $.ajax({
        type: "GET",
        url: "/ajaxRepresentantes/" + id,
        dataType: "json",
        success: function (response){
            response = response[0];
            $("#id").val(response.id);
            $("#cedula").val(response.cedula);
            $("#nombres").val(response.nombres);
            $("#apellidos").val(response.apellidos);
            $("#tlf").val(response.telefono);
            $("#fecha").val(response.fecha_nacimiento);
            $("#domicilio").val(response.domicilio);
            $("#fechaPago").val(response.fecha_pago);
            $("#familiar_alt").val(response.familiar_alternativo);
            $("#telefono_alt").val(response.telefono_familiar_alternativo);
            $("#observaciones").val(response.observaciones);
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function eliminarRepresentantes(id){
    $.ajax({
        type: "DELETE",
        url: "/ajaxRepresentantes/" + id,
        dataType: "json",
        success: function (response){
            listadoRepresentantes();
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
    $("#tlf").val("");
    $("#fecha").val("");
    $("#domicilio").val("");
    $("#fechaPago").val(0);
    $("#familiar_alt").val("");
    $("#telefono_alt").val("");
    $("#observaciones").val("");
}
