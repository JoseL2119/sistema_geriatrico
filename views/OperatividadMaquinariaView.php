<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reportes - Operatividad Maquinaria Pesada</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        /* Estilos optimizados */
        .table thead th {
            white-space: nowrap;
            vertical-align: middle;
            border: 1px solid #a5d6a7;  /* Borde verde */
            font-weight: 600;
            padding: 8px 4px;  /* Padding más compacto */
        }
        
        .table tbody td {
            vertical-align: middle;
            border: 1px solid #e0e0e0;
            padding: 6px 4px;  /* Padding más compacto */
        }
        
        .table-bordered {
            border: 2px solid #4db6ac;  /* Borde turquesa más intenso */
        }
        
        /* Efecto hover para filas */
        .table-hover tbody tr:hover {
            background-color: rgba(200, 230, 201, 0.5);  /* Hover más visible */
        }
        
        /* Asegurar que la tabla ocupe todo el ancho */
        .container-fluid {
            max-width: 99vw;  /* Ocupa casi todo el ancho de la ventana */
            padding-right: 5px;
            padding-left: 5px;
        }
        
        /* Scroll muy sutil solo si es absolutamente necesario */
        .table-responsive {
            overflow-x: auto;
            padding-bottom: 2px;
        }
        
        /* Color para filas pares/impares opcional */
        .table tbody tr:nth-child(even) {
            background-color: #f5f5f5;
        }

        #nuevo {
            padding: 0.5rem 1.25rem;
            font-weight: 500;
            transition: all 0.2s;
            background-color: #2e7d32;
            border: none;
        }
        
        #nuevo:hover {
            transform: translateY(-1px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            background-color: #1b5e20;
        }

        /* Ajusta el título */
        #lista h5 {
            color: #2e7d32;
            font-size: 1.25rem;
        }
    </style>
  </head>
  <body>
    <div class="container">
        
    <?php include "fragments/navbarLanding.php" ?>

    <section id="lista">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0  fw-bold" id="titulo-seccion">
                <i class="fas fa-clipboard-list me-2"></i>
                
            </h5>
            
            <!-- Botón con espaciado y sombra -->
            <button type="button" class="btn btn-primary shadow-sm" id="nuevo">
                <i class="fas fa-plus-circle me-2"></i>Nuevo Reporte
            </button>
        </div>
        
        <!-- Línea divisoria estilizada -->
        <hr class="border-2 border-top border-primary opacity-25 mb-4">

        <div style="display: flex; justify-content: center; gap: 20px; align-items: center; margin: 20px 0; background: #f5f5f5; padding: 15px; border-radius: 8px;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <label for="filtro_fecha" class="form-label">Filtrar por fecha</label>
                <input type="date" name="" id="filtro_fecha">
            </div>
        </div>

        <button id="pdf-link" 
                style="background-color: #4CAF50; /* Verde */
                    color: white;
                    border: none;
                    padding: 10px 20px;
                    text-align: center;
                    text-decoration: none;
                    display: inline-block;
                    font-size: 14px;
                    margin: 4px 2px;
                    cursor: pointer;
                    border-radius: 4px;
                    transition: background-color 0.3s;
                    box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <i class="fas fa-file-pdf" style="margin-right: 8px;"></i> Generar PDF
        </button>

        <div class="container-fluid px-0">  <!-- Contenedor fluido sin padding horizontal -->
            <div class="table-responsive rounded-3 shadow-sm" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                <table class="table table-bordered m-0" style="font-size: 0.88rem; width: 100%; min-width: 100%;">
                    <thead>
                        <!-- Fila de encabezado principal -->
                        <tr class="text-center" style="background-color: #c8e6c9;">  <!-- Verde más intenso -->
                        <th colspan="1" class="align-middle px-2" style="background-color: #ffd54f; color:rgb(0, 0, 0); width: 5%; min-width: 60px;">Maquinaria</th>
                        <th colspan="3" class="align-middle px-2" style="background-color:rgba(100, 165, 94, 0.85); color:rgb(0, 0, 0); width: 6%; min-width: 70px;">Tiempo Operatividad</th>
                        <th rowspan="2" style="background-color: #ffd54f; color:rgb(0, 0, 0); width: 18%;">Palas agregadas</th>  <!-- Amarillo más intenso -->
                        <th rowspan="2" class="align-middle px-2" style="background-color: #ffd54f; color:rgb(0, 0, 0); width: 5%; min-width: 60px;">Cod. Arenas</th>
                        <th colspan="3" class="align-middle px-2" style="background-color:rgba(100, 165, 94, 0.85); color:rgb(0, 0, 0); width: 8%; min-width: 80px;">Observaciones</th>
                        <th rowspan="2" class="align-middle px-2" style="background-color: #ffd54f; color:rgb(0, 0, 0); width: 10%; min-width: 100px;">Opciones</th>
                        </tr>

                        <!-- Fila de subencabezados -->
                        <tr class="text-center" style="background-color: #dcedc8;">  <!-- Verde claro más intenso -->
                        <th style="background-color: #ffd54f; width: 6%;">Tipo/Características</th>
                        <th style="background-color:rgba(100, 165, 94, 0.85); width: 6%;">Hora inicio</th>
                        <th style="background-color:rgba(100, 165, 94, 0.85); width: 6%;">Hora final</th>
                        <th style="background-color:rgba(100, 165, 94, 0.85); width: 6%;">Horas trabajadas</th>
                        <th colspan="2" style="background-color:rgba(100, 165, 94, 0.85); width: 6%;">Actividades</th>
                        <th style="background-color:rgba(100, 165, 94, 0.85); width: 6%;">Grupo</th>
                        </tr>
                    </thead>
                    <tbody id="tbody" class="table-group-divider" style="border-top: 2px solid #80cbc4;">
                        <!-- Las filas se generarán dinámicamente aquí -->
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <section id="form" class="d-none">
        <h5>Formulario para insertar nuevo reporte</h5>
        <hr />
        <form id="formulario">
            <input type="hidden" id="id" />

            <div class="mb-3">
                <label for="fecha" class="form-label">Fecha</label>
                <input 
                    type="date" 
                    class="form-control" 
                    id="fecha" 
                    value=""
                    required
                />
            </div>

            <div class="mb-3">
                <label for="tipo" class="form-label">Tipo de máquina</label>
                <select class="form-select" id="tipo">
                    <option selected>Elegir Máquina</option>
                </select>
            </div>

            <hr />
            <h5>TIEMPO OPERATIVIDAD</h5>

            <div class="mb-3">
                <label for="horaI" class="form-label">Hora Inicio</label>
                <input 
                    type="time" 
                    class="form-control" 
                    id="horaI" 
                    required
                    step="60"
                    oninput="calcularHorasTrabajadas()" 
                />
            </div>

            <div class="mb-3">
                <label for="horaF" class="form-label">Hora Final</label>
                <input 
                    type="time" 
                    class="form-control" 
                    id="horaF" 
                    required
                    step="60" 
                    oninput="calcularHorasTrabajadas()"
                />
            </div>

            <div class="mb-3">
                <label for="horaT" class="form-label">Horas Trabajadas</label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="horaT" 
                    pattern="^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$"
                    placeholder="HH:MM"
                    required
                    readonly
                />
                <small class="form-text text-muted">Formato: 00:00 a 23:59</small>
            </div>

            <hr />
            <div class="mb-3">
                <label for="palas" class="form-label">Palas agregadas</label>
                <input 
                    type="number" 
                    class="form-control" 
                    id="palas" 
                    value=""
                    required
                />
            </div>


            <hr />

            <div class="mb-3">
                <label for="cArena" class="form-label">Código de Arena</label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="cArena" 
                    value=""
                />
            </div>

            <hr />
            <h5>OBSERVACIONES</h5>

            <div class="mb-3">
                <label for="actividad" class="form-label">Actividades</label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="actividad" 
                    value=""
                />
            </div>

            <div class="mb-3">
                <label for="grupo" class="form-label">Grupo</label>
                <input 
                    type="number" 
                    class="form-control" 
                    id="grupo" 
                    value=""
                    required
                />
            </div>
            
            
            <div>
                <button type="submit" class="btn btn-primary">Guardar</button>
                <button type="reset" class="btn btn-danger" id="volver">Volver</button>
            </div>
        </form>
    </section>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="../assets/js/ajaxOperatividadMaquinaria.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <script type="text/javascript">
        function calcularHorasTrabajadas(){
            // Obtener valores de los inputs de tiempo
            const horaInicio = document.getElementById("horaI").value;
            const horaFinal = document.getElementById("horaF").value;
            
            // Validar que ambos campos tengan valor
            if (!horaInicio || !horaFinal) {
                document.getElementById("horaT").value = "";
                return;
            }
            
            // Función para convertir HH:MM a minutos totales
            const convertirAMinutos = (hora) => {
                const [horas, minutos] = hora.split(':').map(num => parseInt(num, 10));
                return horas * 60 + minutos;
            };
            
            // Convertir ambas horas a minutos
            const minutosInicio = convertirAMinutos(horaInicio);
            let minutosFinal = convertirAMinutos(horaFinal);
            
            // Manejar caso cuando la hora final es menor que la inicial (turno nocturno)
            if (minutosFinal < minutosInicio) {
                minutosFinal += 24 * 60; // Sumar 24 horas en minutos
            }
            
            // Calcular diferencia en minutos
            const diferenciaMinutos = minutosFinal - minutosInicio;
            
            // Validar que la diferencia no sea negativa (por si acaso)
            if (diferenciaMinutos < 0) {
                throw new Error("La hora final no puede ser menor que la hora inicial");
            }
            
            // Convertir minutos a formato HH:MM
            const horas = Math.floor(diferenciaMinutos / 60);
            const minutos = diferenciaMinutos % 60;
            
            // Formatear con dos dígitos siempre
            const formatoHHMM = `${String(horas).padStart(2, '0')}:${String(minutos).padStart(2, '0')}`;
            
            // Asignar resultado al campo (que es type="text")
            document.getElementById("horaT").value = formatoHHMM;

        }

        document.getElementById('pdf-link').addEventListener('click', function(e) {
            e.preventDefault();
            const filtro_fecha = document.getElementById('filtro_fecha').value;
            
            // Construye la URL con parámetros
            let url = '/views/reportes/ReporteOperatividadMaquinaria.php';
            const params = new URLSearchParams();
            
            if(filtro_fecha) params.append('filtro_fecha', filtro_fecha);
            
            if(params.toString()) {
                url += '?' + params.toString();
            }

            console.log("URL generada: ", url);
            
            window.open(url, '_blank');
        });
        

    </script>

  </body>
</html>