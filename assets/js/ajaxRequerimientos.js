$(document).ready( function() {
    mostrarListado();
    listadoRequerimientos();

    // Ir al formulario desde el listado
    $(document).on("click", "#nuevo", function (e){
        obtenerResidente(0);
        obtenerListaArticulos(0);
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
        const residente = $("#residente").val();

        const requerimientos = [];

        $(".articulo-residente").each(function(){

            const fila = $(this);

            const idArticulo = fila.data("id-articulo");

            const cantidad = parseFloat(
                fila.find(".cantidad").val()
            ) || 0;

            const frecuenciaMeses = parseInt(
                fila.find(".frecuencia").val()
            ) || 0;

            const fechaInicio = fila.find(".fecha_inicio").val();

            const fechaFin = fila.find(".fecha_fin").val();

            const observaciones = fila.find(".observaciones").val();

            requerimientos.push({
                id_articulo: idArticulo,
                cantidad: cantidad,
                frecuencia_meses: frecuenciaMeses,
                fecha_inicio: fechaInicio,
                fecha_fin: fechaFin,
                observaciones: observaciones
            });

            

        });
        
        const info = {
            id_residente: residente,
            requerimientos: requerimientos
        }
        if(id == "0"){
            guardarRequerimientos(info);
        } else{
            modificarRequerimientos(id, info);
        }
        mostrarListado();
    });

    $(document).on("change", "#residente", function(){

        const idResidente = $(this).val();

        console.log("Residente seleccionado:", idResidente);

        if(idResidente === "Elegir Residente"){
            obtenerListaArticulos(0);
            return;
        }

        obtenerListaArticulos(idResidente);

    });

    $(document).on("click", "#verFicha", function(e){
        const id = $(this).attr("value");
        obtenerFichaRequerimientos(id);
    });

    $(document).on("click", "#editar", function(e){
        const id = $(this).attr("value");
        obtenerRequerimientos(id);
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
                eliminarRequerimientos(id);
            }
        })
    });

    $(document).on("click", ".eliminar-articulo", function(){

        const fila = $(this).closest(".articulo-residente");

        fila.remove();

    });

});

function formatearFecha(fechaISO) {
    if (!fechaISO) return '';
    const partes = fechaISO.split('-');
    return `${partes[2]}-${partes[1]}-${partes[0]}`;
}

// Funciones de AJAX
function listadoRequerimientos(){
    $.ajax({
        type: "GET", 
        url: "/ajaxRequerimientos",
        dataType: "json",
        success: function(response) {
            html = '';
            response.forEach((element) => {
                html += `
                <tr class="text-center">
                    <td colspan = "1">${element.id_residente}</td>
                    <td colspan = "3">${element.nombres_residente} ${element.apellidos_residente}</td>
                    <td colspan = "3">
                        <a class="btn btn-info" id="verFicha" value="${element.id_residente}">Ver Requerimientos</a>
                        <a class="btn btn-success" id="editar" value="${element.id_residente}">Editar</a>
                        <a class="btn btn-danger" id="eliminar" value="${element.id_residente}">Eliminar</a>
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

function obtenerFichaRequerimientos(id){
    $.ajax({
        type: "GET",
        url: "/ajaxRequerimientos/" + id,
        dataType: "json",

        success: function(response){
            html = '';

            const requerimiento = response[0];

            console.log("Listado de Requerimientos:", response);
            console.log("Nombre Residente:", requerimiento.nombres_residente);

            // Datos personales
            $("#ficha_cedula").text(requerimiento.cedula_residente);
            $("#ficha_nombres").text(requerimiento.nombres_residente + " " + requerimiento.apellidos_residente);

            response.forEach((element) => {
                html += `
                <tr class="text-center">
                    <td colspan = "3">${element.nombre_articulo}</td>
                    <td colspan = "2">${element.cantidad}</td>
                    <td colspan = "2">${element.frecuencia_meses}</td>
                    <td colspan = "2">${formatearFecha(element.fecha_inicio)}</td>
                    <td colspan = "2">${element.fecha_fin ? formatearFecha(element.fecha_fin) : "Requerimiento Activo"}</td>
                    <td colspan = "2">${element.observaciones ? element.observaciones : "Sin observaciones"}</td>
                </tr>
                `
            });
            $("#tbodyModalArticulos").html(html); //id del tbody de la tabla

            

            // Mostrar modal
            $("#modalRequerimientosResidente").modal("show");
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

function obtenerResidente(id){
    $.ajax({
        type: "GET",
        url: "/ajaxResidentes",
        dataType: "json",
        success: function (response){
            $("#residente").empty();
            valor='';
            html = '<option selected>Elegir Residente</option>';
            if(id==0){
                $("#residente").append(html);

                response.forEach((element) => {
                    html = '<option value="' + element.id + '" data-representante="' + element.id_representante + '">' + element.nombres + ' ' + element.apellidos + '</option>';
                    $('#residente').append(html);
                });
            } 
            
            if(id > 0){
                response.forEach((element) => {
                    if(element.id == id){
                        html = '<option selected value="' + element.id + '" data-representante="' + element.id_representante + '">' + element.nombres + ' ' + element.apellidos + '</option>';
                        $('#residente').append(html);
                        true;
                    } 
                    else{
                        html = '<option value="' + element.id + '" data-representante="' + element.id_representante + '">' + element.nombres + ' ' + element.apellidos + '</option>';
                        $('#residente').append(html);
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

function obtenerListaArticulos(id, requerimientos = []){

    $.ajax({
        type: "GET",
        url: "/ajaxArticulos",
        dataType: "json",

        success: function(response){

            $("#tbodyArticulos").empty();

            let valor = 0;
            let html = '';

            // -----------------------------------------
            // SIN RESIDENTE SELECCIONADO
            // -----------------------------------------

            if(id == 0){

                html = `
                    <tr class="text-center">
                        <td colspan="15">
                            Selecciona un residente para cargar sus artículos
                        </td>
                    </tr>
                `;

                $("#tbodyArticulos").html(html);

                return;
            }

            // -----------------------------------------
            // ARTICULOS ASOCIADOS AL RESIDENTE
            // -----------------------------------------
            if(requerimientos){
                requerimientos.forEach((element) =>{

                    html += `
                        <tr 
                            class="text-center articulo-residente"
                            data-id-articulo="${element.id_articulo}"
                        >

                            <td colspan="3">
                                ${element.nombre_articulo}
                            </td>

                            <td colspan="2">

                                <input 
                                    type="number"
                                    class="form-control cantidad"

                                    data-id-articulo="${element.id_articulo}"

                                    id="cantidad"

                                    value="${element.cantidad}"

                                    min="0"

                                    max=""

                                    step="0.01"
                                >

                            </td>

                            <td colspan="2">

                                <input 
                                    type="number"
                                    class="form-control frecuencia"

                                    data-id-articulo="${element.id_articulo}"

                                    id="frecuencia"

                                    value="${element.frecuencia_meses}"

                                    min="0"

                                    max=""

                                >

                            </td>

                            <td colspan="2">

                                <input 
                                    type="date"
                                    class="form-control fecha_inicio"

                                    data-id-articulo="${element.id_articulo}"

                                    id="fecha_inicio"

                                    value="${element.fecha_inicio}"

                                >

                            </td>

                            <td colspan="2">

                                <input 
                                    type="date"
                                    class="form-control fecha_fin"

                                    data-id-articulo="${element.id_articulo}"

                                    id="fecha_fin"

                                    value="${element.fecha_fin ? element.fecha_fin : ""}"

                                >

                            </td>

                            <td colspan="2">

                                <input 
                                    type="text"
                                    class="form-control observaciones"

                                    data-id-articulo="${element.id_articulo}"

                                    id="observaciones"

                                    value="${element.observaciones}"

                                >

                            </td>

                            <td colspan="2">
                                <button
                                    type="button"
                                    class="btn btn-danger eliminar-articulo">
                                    Quitar
                                </button>
                            </td>

                        </tr>
                    `;


                });

                $("#tbodyArticulos").html(html);
                return;
            }

            // -----------------------------------------
            // ARTICULOS DEL RESIDENTE
            // -----------------------------------------

            response.forEach((element) => {

                html += `
                    <tr 
                        class="text-center articulo-residente"
                        data-id-articulo="${element.id}"
                    >

                        <td colspan="3">
                            ${element.nombre}
                        </td>

                        <td colspan="2">

                            <input 
                                type="number"
                                class="form-control cantidad"

                                data-id-articulo="${element.id}"

                                id="cantidad"

                                value=""

                                min="0"

                                max=""

                                step="0.01"
                            >

                        </td>

                        <td colspan="2">

                            <input 
                                type="number"
                                class="form-control frecuencia"

                                data-id-articulo="${element.id}"

                                id="frecuencia"

                                value=""

                                min="0"

                                max=""

                            >

                        </td>

                        <td colspan="2">

                            <input 
                                type="date"
                                class="form-control fecha_inicio"

                                data-id-articulo="${element.id}"

                                id="fecha_inicio"

                                value=""

                            >

                        </td>

                        <td colspan="2">

                            <input 
                                type="date"
                                class="form-control fecha_fin"

                                data-id-articulo="${element.id}"

                                id="fecha_fin"

                                value=""

                            >

                        </td>

                        <td colspan="2">

                            <input 
                                type="text"
                                class="form-control observaciones"

                                data-id-articulo="${element.id}"

                                id="observaciones"

                                value=""

                            >

                        </td>

                        <td colspan="2">
                            <button
                                type="button"
                                class="btn btn-danger eliminar-articulo">
                                Quitar
                            </button>
                        </td>

                    </tr>
                `;

                valor++;

                

            });


            $("#tbodyArticulos").html(html);

        },

        error: function(req, status, error){

            const err = req.responseText;

            console.log(err);

        }

    });

}

function guardarRequerimientos(params){
    $.ajax({
        type: "POST",
        url: "/ajaxRequerimientos",
        data: params,
        dataType: "json",
        success: function (response){
            listadoRequerimientos();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function modificarRequerimientos(id, params){
    $.ajax({
        type: "PUT",
        url: "/ajaxRequerimientos/" + id,
        data: params,
        dataType: "json",
        success: function (response){
            listadoRequerimientos();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}
function obtenerRequerimientos(id){

    $.ajax({
        type: "GET",
        url: "/ajaxRequerimientos/" + id,
        dataType: "json",
        success: function(response){

            const residente = response[0].id_residente;
            const requerimientos = response;

            console.log("Residente:", residente);

            console.log(
                "Requerimientos:",
                requerimientos
            );


            // -----------------------------------------
            // DATOS DEL PAGO
            // -----------------------------------------

            $("#id").val(residente);
            obtenerResidente(residente);


            // -----------------------------------------
            // CARGAR CARGOS
            // -----------------------------------------

            obtenerListaArticulos(
                residente,
                requerimientos
            );

        },

        error: function(req, status, error){

            const err = req.responseText;

            console.log(err);

        }

    });

}

function eliminarRequerimientos(id){
    $.ajax({
        type: "DELETE",
        url: "/ajaxRequerimientos/" + id,
        dataType: "json",
        success: function (response){
            listadoRequerimientos();
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
    $("#residente").val(obtenerResidente(0));
}
