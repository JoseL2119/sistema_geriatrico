function cargarDatosYGraficar(titulo, titulo2, idContenedor, idContenedor2) {
    $.ajax({
        url: "/ajaxAlimentacion",
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            const datosParaGrafico = procesarDatos(response);
            Graficar(datosParaGrafico, titulo, idContenedor);
        },
        error: function(xhr, status, error) {
            console.error("Error al cargar datos:", error);
            document.getElementById(idContenedor).innerHTML = `
                <div class="alert alert-danger">
                    Error al cargar los datos: ${xhr.status} ${xhr.statusText}
                </div>`;
        }
    });

    $.ajax({
        url: "/ajaxAlimentacionTurno",
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            const datosParaGrafico2 = procesarDatos2(response);
            Graficar2(datosParaGrafico2, titulo2, idContenedor2);
        },
        error: function(xhr, status, error) {
            console.error("Error al cargar datos:", error);
            document.getElementById(idContenedor2).innerHTML = `
                <div class="alert alert-danger">
                    Error al cargar los datos: ${xhr.status} ${xhr.statusText}
                </div>`;
        }
    });
}

function procesarDatos(datosBD) {
    const datosGrafico = [
        ['Día', 'Toneladas Procesadas', { role: 'tooltip', type: 'string', p: { html: true } }]
    ];

    datosBD.sort((a, b) => a.fecha.localeCompare(b.fecha));

    datosBD.forEach(item => {
        const [anio, mesNum, dia] = item.fecha.split('-');
        const mes = obtenerNombreMes(mesNum);
        const toneladas = (item.toneladas_procesadas) - ((item.toneladas_procesadas)*(item.humedad_promedio/100));
        const toneladas_totales = toneladas.toFixed(2);
        
        datosGrafico.push([
            dia.toString(),
            Number(toneladas_totales),
            `<div style="padding: 8px; min-width: 100px">
               <strong>${parseInt(dia)} de ${mes}</strong><br>
               <span style="color:#666">${anio}</span><br>
               Producción: <b>${toneladas_totales} Tn</b>
             </div>`
        ]);
    });

    return datosGrafico;
}

function procesarDatos2(datosBD) {
    const datosGrafico = [
        ['Día', 'Diurno', 'Nocturno', { role: 'tooltip', type: 'string', p: { html: true } }]
    ];

    // Primero ordenamos por fecha y turno
    datosBD.sort((a, b) => {
        if (a.fecha === b.fecha) {
            return a.id_turno - b.id_turno; // Ordena Diurno (1) antes de Nocturno (2)
        }
        return a.fecha.localeCompare(b.fecha);
    });

    // Procesamos en pares (asumiendo que siempre hay 2 registros por fecha)
    for (let i = 0; i < datosBD.length; i += 2) {
        const diurno = datosBD[i];
        const nocturno = datosBD[i+1];
        
        // Validación de seguridad
        if (!nocturno || diurno.fecha !== nocturno.fecha) {
            console.warn(`Registro sin pareja en fecha: ${diurno.fecha}`);
            continue;
        }

        const [anio, mesNum, dia] = diurno.fecha.split('-');
        const mes = obtenerNombreMes(mesNum);
        
        const toneladasDiurno = calcularToneladasSecas(diurno.toneladas_procesadas, diurno.humedad_promedio);
        const toneladasNocturno = calcularToneladasSecas(nocturno.toneladas_procesadas, nocturno.humedad_promedio);

        datosGrafico.push([
            parseInt(dia).toString(),
            toneladasDiurno,
            toneladasNocturno,
            `<div style="padding: 8px; min-width: 150px">
               <strong>${parseInt(dia)} de ${mes}</strong><br>
               <span style="color:#666">${anio}</span><hr style="margin:4px 0">
               Diurno: <b>${toneladasDiurno.toFixed(2)} Tn</b> (${diurno.humedad_promedio}% humedad)<br>
               Nocturno: <b>${toneladasNocturno.toFixed(2)} Tn</b> (${nocturno.humedad_promedio}% humedad)<br>
               Total día: <b>${(toneladasDiurno + toneladasNocturno).toFixed(2)} Tn</b>
             </div>`
        ]);
    }

    return datosGrafico;
}

function calcularToneladasSecas(toneladas, humedad) {
    return Number(((toneladas) * (1 - (humedad / 100))).toFixed(2));
}

function obtenerNombreMes(mesNum) {
    switch(parseInt(mesNum)) {
        case 1: return 'Enero';
        case 2: return 'Febrero';
        case 3: return 'Marzo';
        case 4: return 'Abril';
        case 5: return 'Mayo';
        case 6: return 'Junio';
        case 7: return 'Julio';
        case 8: return 'Agosto';
        case 9: return 'Septiembre';
        case 10: return 'Octubre';
        case 11: return 'Noviembre';
        case 12: return 'Diciembre';
        default: return '';
    }
}

function Graficar(datos, titulo, id) {
    google.charts.load('current', { packages: ['corechart'] });
    
    google.charts.setOnLoadCallback(function() {
        try {
            const data = google.visualization.arrayToDataTable(datos);
            
            const options = {
                title: titulo,
                height: 500,
                legend: { position: 'top' },
                chartArea: { width: '80%', height: '70%' },
                tooltip: { isHtml: true },
                hAxis: {
                    title: 'Días'
                },
                vAxis: { 
                    title: 'Toneladas Procesadas',
                    minValue: 0
                },
                animation: {
                    duration: 1000,
                    easing: 'out',
                    startup: true
                }
            };
            
            const chart = new google.visualization.ColumnChart(document.getElementById(id));
            chart.draw(data, options);
            
            // Redibujar al cambiar tamaño de ventana (con debounce para mejor performance)
            let resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    chart.draw(data, options);
                }, 200);
            });
        } catch (error) {
            console.error('Error al dibujar:', error);
            document.getElementById(id).innerHTML = `
                <div class="alert alert-warning">
                    Error al renderizar el gráfico: ${error.message}
                </div>`;
        }
    });
}

function Graficar2(datos, titulo, id) {
    google.charts.load('current', { packages: ['corechart'] });
    
    google.charts.setOnLoadCallback(function() {
        try {
            const data = google.visualization.arrayToDataTable(datos);
            
            const options = {
                title: titulo,
                height: 500,
                legend: { position: 'top' },
                chartArea: { width: '80%', height: '70%' },
                tooltip: { isHtml: true },
                hAxis: {
                    title: 'Días'
                },
                vAxis: { 
                    title: 'Toneladas Procesadas',
                    minValue: 0
                },
                animation: {
                    duration: 1000,
                    easing: 'out',
                    startup: true
                }
            };
            
            const chart = new google.visualization.ColumnChart(document.getElementById(id));
            chart.draw(data, options);
            
            // Redibujar al cambiar tamaño de ventana (con debounce para mejor performance)
            let resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    chart.draw(data, options);
                }, 200);
            });
        } catch (error) {
            console.error('Error al dibujar:', error);
            document.getElementById(id).innerHTML = `
                <div class="alert alert-warning">
                    Error al renderizar el gráfico: ${error.message}
                </div>`;
        }
    });
}