$(document).ready( function() {
    // let idmaterial = 0;
    // let idempresa = 0;
    // let idplanta = 0;
    mostrarListado();
    listadoInventario();

    // Ir al formulario desde el listado
    $(document).on("click", "#nuevo", function (e){
        // obtenerProcesamiento(0);
        obtenerEmpresa(0);
        obtenerPlanta(0);
        obtenerMineral(0);
        obtenerTipoVagon(0);
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
        const tipo = $("#tipo").val();
        const cantidad = $("#cantidadV").val();
        const planta = $("#planta").val();
        const empresa = $("#empresa").val();
        const tipoC = $("#tipoC").val();
        const totalCarga = $("#total").val();
        const fecha = $("#fecha").val();
        const comentario = $("#comentario").val();
        const info = {
            id_gondola: tipo,
            cantidad_gon: cantidad,
            id_empresa: empresa,
            id_planta: planta,
            id_tipo_carga: tipoC,
            total_carga: totalCarga,
            fecha: fecha,
            comentario: comentario
        }

        // console.log("Datos a enviar:", info);

        if(id == "0"){
            guardarCarga(info);
        } else{
            modificarCarga(id, info);
        }
        mostrarListado();
    });

    $(document).on("click", "#editar", function(e){
        const id = $(this).attr("value");
        obtenerCarga(id);
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
                eliminarCarga(id);
            }
        })
    });

});

// Funciones de AJAX
function listadoInventario(){
    $.ajax({
        type: "GET", 
        url: "/ajaxInventarioCdPiar",
        dataType: "json",
        success: function(response) {
            html = '';
            response.forEach((element) => {
                fecha = element.fecha.substr(8, 2) + "/" + element.fecha.substr(5, 2) + "/" + element.fecha.substr(0, 4);
                html += `
                <tr>
                    <td>${element.id}</td>
                    <td>${element.material}</td>
                    <td>${element.origen}</td>
                    <td>${element.cantidad} Tn</td>
                    <td>${fecha}</td>
                    <td>${element.comentario}</td>
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

function guardarCarga(params){
    $.ajax({
        type: "POST",
        url: "/ajaxGondolasFg",
        data: params,
        dataType: "json",
        success: function (response){
            console.log("Respuesta del servidor:", response);
            listadoCarga();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function modificarCarga(id, params){
    $.ajax({
        type: "PUT",
        url: "/ajaxGondolasFg/" + id,
        data: params,
        dataType: "json",
        success: function (response){
            listadoCarga();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function obtenerCarga(id){
    $.ajax({
        type: "GET",
        url: "/ajaxGondolasFg/" + id,
        dataType: "json",
        success: function (response){
            response = response[0];
            $("#id").val(response.id);
            // $("#material").val(response.id_material);
            obtenerTipoVagon(id);
            $("#cantidadV").val(response.cantidad_gon);
            // $("#mina").val(response.id_mina);
            obtenerPlanta(id);
            // $("#empresa").val(response.id_empresa);
            obtenerEmpresa(id);
            obtenerMineral(id);
            $("#total").val(response.total_carga);
            $("#fecha").val(response.fecha);
            $("#comentario").val(response.comentario);
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function obtenerEmpresa(id){
    $.ajax({
        type: "GET",
        url: "/ajaxEmpresa",
        dataType: "json",
        success: function (response){
            $("#empresa").empty();
            html = '<option selected>Elegir Empresa</option>';
            if(id==0) $("#empresa").append(html);
            response.forEach((element) => {
                // fecha = element.fecha.substr(8, 2) + "/" + element.fecha.substr(5, 2) + "/" + element.fecha.substr(0, 4);
                html = '<option value="' + element.id + '">' + element.nombre + '</option>';
                $('#empresa').append(html);
            });
            if(id > 0) $('#empresa').val(empresa);
        },
        error: function (req, status, error){
            var err = req.responseText;
            console.log(err);
            alert(err.Message);
        }
    });
}

function obtenerPlanta(id){
    $.ajax({
        type: "GET",
        url: "/ajaxPlanta",
        dataType: "json",
        success: function (response){
            $("#planta").empty();
            html = '<option selected>Elegir Planta</option>';
            if(id==0) $("#planta").append(html);
            response.forEach((element) => {
                // fecha = element.fecha.substr(8, 2) + "/" + element.fecha.substr(5, 2) + "/" + element.fecha.substr(0, 4);
                html = '<option value="' + element.id + '">' + element.nombre + '</option>';
                $('#planta').append(html);
            });
            if(id > 0) $('#planta').val(planta);
        },
        error: function (req, status, error){
            var err = req.responseText;
            console.log(err);
            alert(err.Message);
        }
    });
}

function obtenerMineral(id){
    $.ajax({
        type: "GET",
        url: "/ajaxMineralProcesado",
        dataType: "json",
        success: function (response){
            $("#tipoC").empty();
            html = '<option selected>Elegir Tipo de Mineral</option>';
            if(id==0) $("#tipoC").append(html);
            response.forEach((element) => {
                // fecha = element.fecha.substr(8, 2) + "/" + element.fecha.substr(5, 2) + "/" + element.fecha.substr(0, 4);
                html = '<option value="' + element.id + '">' + element.tipo + '</option>';
                $('#tipoC').append(html);
            });
            if(id > 0) $('#tipoC').val(mineral);
        },
        error: function (req, status, error){
            var err = req.responseText;
            console.log(err);
            alert(err.Message);
        }
    });
}

function obtenerTipoVagon(id){
    $.ajax({
        type: "GET",
        url: "/ajaxVagones",
        dataType: "json",
        success: function (response){
            $("#tipo").empty();
            html = '<option selected>Elegir Tipo de Vagón</option>';
            if(id==0) $("#tipo").append(html);
            response.forEach((element) => {
                // fecha = element.fecha.substr(8, 2) + "/" + element.fecha.substr(5, 2) + "/" + element.fecha.substr(0, 4);
                html = '<option value="' + element.id + '">' + element.tipo + '</option>';
                $('#tipo').append(html);
            });
            if(id > 0) $('#tipo').val(gondola);
        },
        error: function (req, status, error){
            var err = req.responseText;
            console.log(err);
            alert(err.Message);
        }
    });
}

function eliminarCarga(id){
    $.ajax({
        type: "DELETE",
        url: "/ajaxGondolasTeu/" + id,
        dataType: "json",
        success: function (response){
            listadoProcesamiento();
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
