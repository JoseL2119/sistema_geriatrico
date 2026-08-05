$(document).ready( function() {
    mostrarListado();
    listadoAlimentacion();

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
        const nombre = $("#nombre").val();
        const info = {
            nombre: nombre
        }
        if(id == "0"){
            guardarAlimentacion(info);
        } else{
            modificarAlimentacion(id, info);
        }
        mostrarListado();
    });

    $(document).on("click", "#editar", function(e){
        const id = $(this).attr("value");
        obtenerAlimentacion(id);
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
                eliminarAlimentacion(id);
            }
        })
    });

});

// Funciones de AJAX
function listadoAlimentacion(){
    $.ajax({
        type: "GET", 
        url: "/ajaxAlimentacionTurno",
        dataType: "json",
        success: function(response) {
            console.log("Respuesta recibida:", response); // Para depuración
            html = '';
            let totalHumedas = 0, promedioHumedad = 0, totalSecas = 0, cont = 0;
            response.forEach((element) => {
                const humedad = Number(element.humedad_promedio);
                totalHumedas += (element.toneladas_procesadas);
                promedioHumedad += humedad;
                totalSecas += (element.toneladas_procesadas - ((element.toneladas_procesadas)*(humedad/100)));
                html += `
                <tr class="text-center">
                    <td>${element.fecha}</td>
                    <td>${element.nombre_turno}</td>
                    <td>${(element.toneladas_procesadas).toFixed(2)} Tn</td>
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
                    <td>${(totalHumedas).toFixed(2)} Tn</td>
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

function guardarAlimentacion(params){
    $.ajax({
        type: "POST",
        url: "/ajaxAlimentacion",
        data: params,
        dataType: "json",
        success: function (response){
            listadoAlimentacion();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function modificarAlimentacion(id, params){
    $.ajax({
        type: "PUT",
        url: "/ajaxAlimentacion/" + id,
        data: params,
        dataType: "json",
        success: function (response){
            listadoAlimentacion();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function obtenerAlimentacion(id){
    $.ajax({
        type: "GET",
        url: "/ajaxAlimentacion/" + id,
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

function eliminarAlimentacion(id){
    $.ajax({
        type: "DELETE",
        url: "/ajaxAlimentacion/" + id,
        dataType: "json",
        success: function (response){
            listadoAlimentacion();
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
