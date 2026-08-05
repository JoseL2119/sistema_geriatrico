$(document).ready(function(){

    obtenerEstadisticas();

});


function obtenerEstadisticas(){

    $.ajax({

        type: "GET",

        url: "/ajaxLanding",

        dataType: "json",

        success: function(response){

            console.log("Estadísticas recibidas:", response);


            // --------------------------------
            // RESUMEN GENERAL
            // --------------------------------

            $("#total_residentes")
                .text(response.total_residentes || 0);

            $("#total_representantes")
                .text(response.total_representantes || 0);

            $("#residentes_con_requerimientos")
                .text(response.residentes_con_requerimientos || 0);

            $("#residentes_con_deuda")
                .text(response.residentes_con_deuda || 0);


            // --------------------------------
            // CONSIGNACIONES
            // --------------------------------

            $("#total_requerimientos_pendientes")
                .text(response.total_requerimientos_pendientes || 0);

            $("#total_requerimientos_parciales")
                .text(response.total_requerimientos_parciales || 0);

            $("#total_articulos_pendientes")
                .text(response.total_articulos_pendientes || 0);


            // --------------------------------
            // PAGOS
            // --------------------------------

            $("#total_cargos_pendientes")
                .text(response.total_cargos_pendientes || 0);

            $("#residentes_con_deuda_2")
                .text(response.residentes_con_deuda || 0);


            // --------------------------------
            // ALERTAS
            // --------------------------------

            $("#alert_articulos")
                .text(response.total_articulos_pendientes || 0);

            $("#alert_cargos")
                .text(response.total_cargos_pendientes || 0);

        },


        error: function(req, status, error){

            console.log(
                "Error al obtener estadísticas:",
                req.responseText
            );

        }

    });

}