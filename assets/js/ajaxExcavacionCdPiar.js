$(document).ready( function() {
    let idmaterial = 0;
    let idempresa = 0;
    let idmina = 0;
    mostrarListado();
    listadoExcavacion();

    // Ir al formulario desde el listado
    $(document).on("click", "#nuevo", function (e){
        // obtenerExcavacion(0);
        obtenerEmpresa(0);
        obtenerMina(0);
        obtenerMaterial(0);
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
        const material = $("#material").val();
        const cantidad = $("#cantidad").val();
        const mina = $("#mina").val();
        const empresa = $("#empresa").val();
        const fecha = $("#fecha").val();
        const comentario = $("#comentario").val();
        const info = {
            id_tipo_material: material,
            cantidad: cantidad,
            id_mina: mina,
            id_empresa: empresa,
            fecha: fecha,
            comentario: comentario
        }

        // console.log("Datos a enviar:", info);

        if(id == "0"){
            guardarExcavacion(info);
        } else{
            modificarExcavacion(id, info);
        }
        mostrarListado();
    });

    $(document).on("click", "#editar", function(e){
        const id = $(this).attr("value");
        obtenerExcavacion(id);
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
                eliminarExcavacion(id);
            }
        })
    });

});

// Funciones de AJAX
function listadoExcavacion(){
    $.ajax({
        type: "GET", 
        url: "/ajaxExcavacion",
        dataType: "json",
        success: function(response) {
            html = '';
            response.forEach((element) => {
                fecha = element.fecha.substr(8, 2) + "/" + element.fecha.substr(5, 2) + "/" + element.fecha.substr(0, 4);
                html += `
                <tr>
                    <td>${element.id}</td>
                    <td>${element.material}</td>
                    <td>${element.cantidad}</td>
                    <td>${element.mina}</td>
                    <td>${element.empresa}</td>
                    <td>${fecha}</td>
                    <td>${element.comentario}</td>
                    <td>
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

function guardarExcavacion(params){
    $.ajax({
        type: "POST",
        url: "/ajaxExcavacion",
        data: params,
        dataType: "json",
        success: function (response){
            console.log("Respuesta del servidor:", response);
            listadoExcavacion();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function modificarExcavacion(id, params){
    $.ajax({
        type: "PUT",
        url: "/ajaxExcavacion/" + id,
        data: params,
        dataType: "json",
        success: function (response){
            listadoExcavacion();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function obtenerExcavacion(id){
    $.ajax({
        type: "GET",
        url: "/ajaxExcavacion/" + id,
        dataType: "json",
        success: function (response){
            response = response[0];
            $("#id").val(response.id);
            // $("#material").val(response.id_material);
            obtenerMaterial(response.id_material);
            $("#cantidad").val(response.cantidad);
            // $("#mina").val(response.id_mina);
            obtenerMina(response.id_mina);
            // $("#empresa").val(response.id_empresa);
            obtenerEmpresa(response.id_empresa);
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
            valor='';
            html = '<option selected>Elegir Empresa</option>';
            if(id==0){
                $("#empresa").append(html);

                response.forEach((element) => {
                    // fecha = element.fecha.substr(8, 2) + "/" + element.fecha.substr(5, 2) + "/" + element.fecha.substr(0, 4);
                    html = '<option value="' + element.id + '">' + element.nombre + '</option>';
                    $('#empresa').append(html);
                    // if(element.id == id) valor = element.nombre;
                });
            } 
            
            // empresaName = '<option selected>' + valor + '</option>';
            if(id > 0){
                response.forEach((element) => {
                    if(element.id == id){
                        // valor = element.nombre;
                        html = '<option selected value="' + element.id + '">' + element.nombre + '</option>';
                        $('#empresa').append(html);
                        true;
                    } 
                    else{
                        html = '<option value="' + element.id + '">' + element.nombre + '</option>';
                        $('#empresa').append(html);
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

function obtenerMina(id){
    $.ajax({
        type: "GET",
        url: "/ajaxMina",
        dataType: "json",
        success: function (response){
            $("#mina").empty();
            html = '<option selected>Elegir Mina</option>';
            if(id==0){
                $("#mina").append(html);
                response.forEach((element) => {
                    // fecha = element.fecha.substr(8, 2) + "/" + element.fecha.substr(5, 2) + "/" + element.fecha.substr(0, 4);
                    html = '<option value="' + element.id + '">' + element.nombre + '</option>';
                    $('#mina').append(html);
                });
            }
            
            if(id > 0){
                response.forEach((element) => {
                    if(element.id == id){
                        html = '<option selected value="' + element.id + '">' + element.nombre + '</option>';
                        $('#mina').append(html);
                    }

                    else{
                        // fecha = element.fecha.substr(8, 2) + "/" + element.fecha.substr(5, 2) + "/" + element.fecha.substr(0, 4);
                        html = '<option value="' + element.id + '">' + element.nombre + '</option>';
                        $('#mina').append(html);
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

function obtenerMaterial(id){
    $.ajax({
        type: "GET",
        url: "/ajaxMaterial",
        dataType: "json",
        success: function (response){
            $("#material").empty();
            html = '<option selected>Elegir Material</option>';
            if(id==0){
                $("#material").append(html);
                response.forEach((element) => {
                    // fecha = element.fecha.substr(8, 2) + "/" + element.fecha.substr(5, 2) + "/" + element.fecha.substr(0, 4);
                    html = '<option value="' + element.id + '">' + element.tipo + '</option>';
                    $('#material').append(html);
                });
            } 

            if(id > 0){
                response.forEach((element) => {
                    if(element.id == id){
                        html = '<option selected value="' + element.id + '">' + element.tipo + '</option>';
                        $('#material').append(html);
                    }
                    
                    else{
                        // fecha = element.fecha.substr(8, 2) + "/" + element.fecha.substr(5, 2) + "/" + element.fecha.substr(0, 4);
                        html = '<option value="' + element.id + '">' + element.tipo + '</option>';
                        $('#material').append(html);
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

function eliminarExcavacion(id){
    $.ajax({
        type: "DELETE",
        url: "/ajaxExcavacion/" + id,
        dataType: "json",
        success: function (response){
            listadoExcavacion();
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
