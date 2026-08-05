$(document).ready( function() {
    mostrarListado();
    listadoConsignaciones();

    // Ir al formulario desde el listado
    $(document).on("click", "#nuevo", function (e){
        obtenerResidente(0);
        obtenerRepresentante(0);
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
        const fecha = $("#fecha").val();
        const residente = $("#residente").val();
        const representante = $("#representante").val();
        const observaciones = $("#observaciones").val();

        const detalles = [];

        $(".articulo-residente").each(function(){

            const fila = $(this);

            const idArticulo = fila.data("id-articulo");

            const cantidad = parseFloat(
                fila.find(".cantidad").val()
            ) || 0;

            const observaciones = fila.find(".observaciones").val();

            detalles.push({
                id_articulo: idArticulo,
                cantidad: cantidad,
                observaciones: observaciones
            });

            

        });
        
        const info = {
            fecha: fecha,
            id_residente: residente,
            id_representante: representante,
            observaciones: observaciones,
            detalles: detalles
        }
        if(id == "0"){
            guardarConsignaciones(info);
        } else{
            modificarConsignaciones(id, info);
        }
        mostrarListado();
    });

    $(document).on("change", "#residente", function(){

        const idResidente = $(this).val();

        const opcion = $(this).find(":selected");

        const idRepresentante = opcion.data("representante");

        console.log("Residente seleccionado:", idResidente);
        console.log("Representante asociado:", idRepresentante);

        if(idResidente === "Elegir Residente"){
            obtenerListaArticulos(0);
            return;
        }

        obtenerArticulosSegunResidente(idResidente);
        obtenerRepresentanteSegunResidente(idResidente);

    });

    $(document).on("click", "#verFicha", function(e){
        const id = $(this).attr("value");
        obtenerFichaConsignaciones(id);
    });

    $(document).on("click", "#editar", function(e){
        const id = $(this).attr("value");
        obtenerConsignaciones(id);
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
                eliminarConsignaciones(id);
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
function listadoConsignaciones(){
    $.ajax({
        type: "GET", 
        url: "/ajaxConsignaciones",
        dataType: "json",
        success: function(response) {
            html = '';
            response.forEach((element) => {
                html += `
                <tr class="text-center">
                    <td colspan = "2">${formatearFecha(element.fecha)}</td>
                    <td colspan = "3">${element.nombres_residente} ${element.apellidos_residente}</td>
                    <td colspan = "3">${element.nombres_representante} ${element.apellidos_representante}</td>
                    <td colspan = "3">${element.observaciones ? element.observaciones : "Sin observaciones"} </td>
                    <td colspan = "3">
                        <a class="btn btn-info" id="verFicha" value="${element.id}">Ver Consignación</a>
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

function obtenerFichaConsignaciones(id){
    $.ajax({
        type: "GET",
        url: "/ajaxConsignaciones/" + id,
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
            $("#ficha_representante").text(requerimiento.nombres_representante + " " + requerimiento.apellidos_representante);
            $("#ficha_fecha").text(formatearFecha(requerimiento.fecha));
            $("#ficha_observacion").text(requerimiento.observaciones ? requerimiento.observaciones : "Sin observaciones");

            response.forEach((element) => {
                html += `
                <tr class="text-center">
                    <td colspan = "3">${element.nombre_articulo}</td>
                    <td colspan = "2">${element.cantidad}</td>
                    <td colspan = "2">${element.observaciones_detalle ? element.observaciones_detalle : "Sin observaciones"}</td>
                </tr>
                `
            });
            $("#tbodyModalArticulos").html(html); //id del tbody de la tabla

            

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
                    html = '<option value="' + element.id  + '">' + element.nombres + ' ' + element.apellidos + '</option>';
                    $('#representante').append(html);
                });
            } 
            
            if(id > 0){
                response.forEach((element) => {
                    if(element.id == id){
                        html = '<option selected value="' + element.id  + '">' + element.nombres + ' ' + element.apellidos + '</option>';
                        $('#representante').append(html);
                        true;
                    } 
                    else{
                        html = '<option value="' + element.id  + '">' + element.nombres + ' ' + element.apellidos + '</option>';
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

function obtenerArticulosSegunResidente(id){

    $.ajax({
        type: "GET",
        url: "/ajaxRequerimientos/" + id,
        dataType: "json",
        success: function(response){

            const residente = response[0].id_residente;
            const articulos = response;

            console.log("Residente:", residente);

            console.log(
                "Articulos:",
                articulos
            );

            // -----------------------------------------
            // CARGAR LISTA DE ARTÍCULOS
            // -----------------------------------------

            obtenerListaArticulos(
                residente,
                articulos
            );

        },

        error: function(req, status, error){

            const err = req.responseText;

            console.log(err);

        }

    });

}

function obtenerListaArticulos(id, articulos = []){

    $.ajax({
        type: "GET",
        url: "/ajaxArticulos",
        dataType: "json",

        success: function(response){

            $("#tbodyArticulos").empty();
            console.log(articulos);

            let valor = 0;
            let html = '';

            // -----------------------------------------
            // SIN RESIDENTE SELECCIONADO
            // -----------------------------------------

            if(id == 0){

                html = `
                    <tr class="text-center">
                        <td colspan="15">
                            Selecciona un residente para cargar los artículos que debe recibir
                        </td>
                    </tr>
                `;

                $("#tbodyArticulos").html(html);

                return;
            }

            // -----------------------------------------
            // ARTICULOS ASOCIADOS AL RESIDENTE
            // -----------------------------------------
            if(articulos){
                articulos.forEach((element) =>{

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

                                    placeholder="${element.cantidad}"

                                    value=""

                                    min="0"

                                    max=""

                                    step="0.01"
                                >

                            </td>

                            <td colspan="2">

                                <input 
                                    type="text"
                                    class="form-control observaciones"

                                    data-id-articulo="${element.id_articulo}"

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


                });

                $("#tbodyArticulos").html(html);
                return;
            }
            
            else{
                html = `
                    <tr class="text-center">
                        <td colspan="15">
                            El residente seleccionado no tiene una lista de artículos requeridos.
                        </td>
                    </tr>
                `;

                $("#tbodyArticulos").html(html);
                return;
            }


            $("#tbodyArticulos").html(html);

        },

        error: function(req, status, error){

            const err = req.responseText;

            console.log(err);

        }

    });

}

function obtenerRepresentanteSegunResidente(id){
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
                    html = '<option value="' + element.id + '">' + element.nombres + ' ' + element.apellidos + '</option>';
                    $('#representante').append(html);
                });
            } 
            
            if(id > 0){
                response.forEach((element) => {
                    if(element.id == id){
                        html = '<option selected value="' + element.id + '">' + element.nombres + ' ' + element.apellidos + '</option>';
                        $('#representante').append(html);
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

function guardarConsignaciones(params){
    $.ajax({
        type: "POST",
        url: "/ajaxConsignaciones",
        data: params,
        dataType: "json",
        success: function (response){
            listadoConsignaciones();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function modificarConsignaciones(id, params){
    $.ajax({
        type: "PUT",
        url: "/ajaxConsignaciones/" + id,
        data: params,
        dataType: "json",
        success: function (response){
            listadoConsignaciones();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function obtenerConsignaciones(id){

    $.ajax({
        type: "GET",
        url: "/ajaxConsignaciones/" + id,
        dataType: "json",
        success: function(response){
            let html = '';

            const fecha = response[0].fecha;
            const residente = response[0].id_residente;
            const representante = response[0].id_representante;
            const observaciones = response[0].observaciones;
            const consignaciones = response;

            console.log("Residente:", residente);

            console.log(
                "consignaciones:",
                consignaciones
            );


            // -----------------------------------------
            // DATOS DE LA CONSIGNACIÓN
            // -----------------------------------------

            $("#id").val(response[0].id);
            $("#fecha").val(fecha);
            obtenerResidente(residente);
            obtenerRepresentante(representante);
            $("#observaciones").val(observaciones);


            // -----------------------------------------
            // CARGAR DETALLES DE LA CONSIGNACIÓN
            // -----------------------------------------
            if(consignaciones){
                consignaciones.forEach((element) =>{

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
                                    type="text"
                                    class="form-control observaciones"

                                    data-id-articulo="${element.id_articulo}"

                                    id="observaciones"

                                    value="${element.observaciones_detalle}"

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

            $("#tbodyArticulos").html(html);
            

        },

        error: function(req, status, error){

            const err = req.responseText;

            console.log(err);

        }

    });

}

function eliminarConsignaciones(id){
    $.ajax({
        type: "DELETE",
        url: "/ajaxConsignaciones/" + id,
        dataType: "json",
        success: function (response){
            listadoConsignaciones();
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
    $("#representante").val(obtenerRepresentante(0));
    $("#fecha").val("");
}
