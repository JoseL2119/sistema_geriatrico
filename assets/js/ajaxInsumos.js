$(document).ready( function() {
    mostrarListado();
    listadoInsumos();

    // Ir al formulario desde el listado
    $(document).on("click", "#nuevo", function (e){
        mostrarFormulario();
        limpiarFormulario();
        obtenerDepartamento();
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
        const unidad = $("#unidad").val();
        const info = {
            nombre: nombre,
            unidad: unidad
        }
        if(id == "0"){
            guardarInsumo(info);
        } else{
            modificarInsumo(id, info);
        }
        mostrarListado();
    });

    $(document).on("click", "#editar", function(e){
        const id = $(this).attr("value");
        obtenerInsumo(id);
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
                eliminarInsumo(id);
            }
        })
    });

});

// Funciones de AJAX
function listadoInsumos(){
    $.ajax({
        type: "GET", 
        url: "/ajaxInsumos",
        dataType: "json",
        success: function(response) {
            html = '';
            response.forEach((element) => {
                html += `
                <tr>
                    <td class="text-center">${element.nombre}</td>
                    <td class="text-center">${element.unidad}</td>
                    <td class="text-center">${element.departamento}</td>
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

function guardarInsumo(params){
    $.ajax({
        type: "POST",
        url: "/ajaxInsumos",
        data: params,
        dataType: "json",
        success: function (response){
            listadoInsumos();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function modificarInsumo(id, params){
    $.ajax({
        type: "PUT",
        url: "/ajaxInsumos/" + id,
        data: params,
        dataType: "json",
        success: function (response){
            listadoInsumos();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function obtenerInsumo(id){
    $.ajax({
        type: "GET",
        url: "/ajaxInsumos/" + id,
        dataType: "json",
        success: function (response){
            response = response[0];
            $("#id").val(response.id);
            $("#nombre").val(response.nombre);
            $("#unidad").val(response.unidad);
            $("#departamento").val(response.departamento);
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function obtenerDepartamento(id){
    $.ajax({
        type: "GET",
        url: "/ajaxDepartamento",
        dataType: "json",
        success: function (response){
            $("#departamento").empty();
            valor='';
            html = '<option selected>Seleccionar...</option>';
            if(id==0){
                $("#departamento").append(html);

                response.forEach((element) => {
                    // fecha = element.fecha.substr(8, 2) + "/" + element.fecha.substr(5, 2) + "/" + element.fecha.substr(0, 4);
                    html = '<option value="' + element.id + '">' + element.nombre + '</option>';
                    $('#departamento').append(html);
                    // if(element.id == id) valor = element.nombre;
                });
            } 
            
            // empresaName = '<option selected>' + valor + '</option>';
            if(id > 0){
                response.forEach((element) => {
                    if(element.id == id){
                        // valor = element.nombre;
                        html = '<option selected value="' + element.id + '">' + element.nombre + '</option>';
                        $('#departamento').append(html);
                        true;
                    } 
                    else{
                        html = '<option value="' + element.id + '">' + element.nombre + '</option>';
                        $('#departamento').append(html);
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

function eliminarInsumo(id){
    $.ajax({
        type: "DELETE",
        url: "/ajaxInsumos/" + id,
        dataType: "json",
        success: function (response){
            listadoInsumos();
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
    $("#unidad").val("");
}
