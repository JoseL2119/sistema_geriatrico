$(document).ready( function() {
    let jornada = 0;
    let idlocal = 0;
    let idvisitante = 0;
    mostrarListado();
    listadoPartidos();

    // Ir al formulario desde el listado
    $(document).on("click", "#nuevo", function (e){
        obtenerJornadas(0);
        obtenerEquipos(0);
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
        const jornada = $("#jornada").val();
        const fecha = $("#jornada option:selected").text();
        const orden = $("#orden").val();
        const idlocal = $("#idlocal").val();
        const idvisitante = $("#idvisitante").val();
        const goleslocal = $("#goleslocal").val();
        const golesvisitante = $("#golesvisitante").val();
        const info = {
            jornada: jornada,
            fecha: fecha,
            orden: orden,
            idlocal: idlocal,
            idvisitante: idvisitante,
            goleslocal: goleslocal,
            golesvisitante: golesvisitante
        }
        if(id == "0"){
            guardarPartido(info);
        } else{
            modificarPartido(id, info);
        }
        mostrarListado();
    });

    $(document).on("click", "#editar", function(e){
        const id = $(this).attr("value");
        obtenerPartido(id);
        obtenerJornadas(1);
        obtenerEquipos(1);
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
                eliminarPartido(id);
            }
        })
    });

});

// Funciones de AJAX
function listadoPartidos(){
    $.ajax({
        type: "GET", 
        url: "/ajaxPartidos",
        dataType: "json",
        success: function(response) {
            html = '';
            response.forEach((element) => {
                // aaaa-mm-dd
                fecha = element.fecha.substr(8, 2) + "/" + element.fecha.substr(5, 2) + "/" + element.fecha.substr(0, 4);
                html += `
                <tr>
                    <td>${element.id}</td>
                    <td>${element.jornada}-${element.orden}</td>
                    <td>${fecha}</td>
                    <td>${element.idlocal} ${element.local}</td>
                    <td>${element.idvisitante} ${element.visitante}</td>
                    <td>${element.goleslocal}-${element.golesvisitante}</td>
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

function guardarPartido(params){
    $.ajax({
        type: "POST",
        url: "/ajaxPartidos",
        data: params,
        dataType: "json",
        success: function (response){
            listadoPartidos();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function modificarPartido(id, params){
    $.ajax({
        type: "PUT",
        url: "/ajaxPartidos/" + id,
        data: params,
        dataType: "json",
        success: function (response){
            listadoPartidos();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function obtenerPartido(id){
    $.ajax({
        type: "GET",
        url: "/ajaxPartidos/" + id,
        dataType: "json",
        success: function (response){
            response = response[0];
            $("#id").val(response.id);
            $("#jornada").val(response.jornada);
            $("#fecha").val(response.fecha);
            $("#orden").val(response.orden);
            $("#idlocal").val(response.idlocal);
            $("#idvisitante").val(response.idvisitante);
            $("#goleslocal").val(response.goleslocal);
            $("#golesvisitante").val(response.golesvisitante);
            jornada = response.jornada;
            idlocal = response.idlocal;
            idvisitante = response.idvisitante;
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function eliminarPartido(id){
    $.ajax({
        type: "DELETE",
        url: "/ajaxPartidos/" + id,
        dataType: "json",
        success: function (response){
            listadoPartidos();
        },
        error: function (req, status, error){
            const err = req.responseText;
            console.log(err);
        }
    })
}

function obtenerJornadas(id){
    $.ajax({
        type: "GET",
        url: "/ajaxJornadas",
        dataType: "json",
        success: function (response){
            $("#jornada").empty();
            html = '<option selected>Elegir Jornada</option>';
            if(id==0) $("#jornada").append(html);
            response.forEach((element) => {
                fecha = element.fecha.substr(8, 2) + "/" + element.fecha.substr(5, 2) + "/" + element.fecha.substr(0, 4);
                html = '<option value="' + element.jornada + '">' + fecha + '</option>';
                $('#jornada').append(html);
            });
            if(id > 0) $('#jornada').val(jornada);
        },
        error: function (req, status, error){
            var err = req.responseText;
            console.log(err);
            alert(err.Message);
        }
    });
}

function obtenerEquipos(id){
    $.ajax({
        type: "GET",
        url: "/ajaxEquipos",
        dataType: "json",
        success: function(response){
            //local
            $('#idlocal').empty();
            html = '<option selected>Elegir equipo local</option>';
            if(id == 0) $('#idlocal').append(html);
            response.forEach((element) => {
                html = '<option value=' + element.id + '">' + element.nombre + '</option>';
                $('#idlocal').append(html);
            });
            if(id > 0) $('#idlocal').val(idlocal);

            //visitante
            $('#idvisitante').empty();
            html = '<option selected>Elegir equipo visitante</option>';
            if(id == 0) $('#idvisitante').append(html);
            response.forEach((element) => {
                html = '<option value=' + element.id + '">' + element.nombre + '</option>';
                $('#idvisitante').append(html);
            });
            if(id > 0) $('#idvisitante').val(idvisitante);
        },
        error: function (req, status, error){
            var err = req.responseText;
            console.log(err);
            alert(err.Message);
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
    $("#jornada").val("");
    $("#orden").val("");
    $("#idlocal").val("");
    $("#idvisitante").val("");
    $("#goleslocal").val("");
    $("#golesvisitante").val("");
}
