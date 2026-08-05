<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Dashboard Principal</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="../assets/css/app.css" rel="stylesheet">

    <style>
        .dashboard-container {
            padding-top: 30px;
            padding-bottom: 40px;
        }
    </style>

</head>

<body>

    <?php include "fragments/navbarLanding.php" ?>


    <main class="container dashboard-container">


        <!-- ==========================================
             ENCABEZADO
        =========================================== -->

        <section class="dashboard-header text-center" style="--header-image: url('../src/geriatrico.jpeg');">

            <h1>
                Sistema de Gestión
            </h1>

            <p class="lead mb-0">
                Hogar Clínica Madre Santa Teresa
            </p>

            <p class="mt-2 mb-0">
                Resumen general de la información del geriátrico
            </p>

        </section>



        <!-- ==========================================
             RESUMEN GENERAL
        =========================================== -->

        <h4 class="dashboard-section-title">
            Resumen general
        </h4>


        <div class="row g-4">


            <!-- RESIDENTES -->

            <div class="col-md-6 col-lg-3">

                <div class="stat-card">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="stat-title">
                                Residentes
                            </div>

                            <div 
                                class="stat-value"
                                id="total_residentes">
                                0
                            </div>

                            <div class="stat-description">
                                Residentes registrados
                            </div>

                        </div>

                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>

                    </div>

                </div>

            </div>



            <!-- REPRESENTANTES -->

            <div class="col-md-6 col-lg-3">

                <div class="stat-card">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="stat-title">
                                Representantes
                            </div>

                            <div 
                                class="stat-value"
                                id="total_representantes">
                                0
                            </div>

                            <div class="stat-description">
                                Representantes registrados
                            </div>

                        </div>

                        <div class="stat-icon">
                            <i class="fas fa-user-friends"></i>
                        </div>

                    </div>

                </div>

            </div>



            <!-- RESIDENTES CON REQUERIMIENTOS -->

            <div class="col-md-6 col-lg-3">

                <div class="stat-card">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="stat-title">
                                Requerimientos
                            </div>

                            <div 
                                class="stat-value"
                                id="residentes_con_requerimientos">
                                0
                            </div>

                            <div class="stat-description">
                                Residentes con artículos requeridos
                            </div>

                        </div>

                        <div class="stat-icon">
                            <i class="fas fa-clipboard-list"></i>
                        </div>

                    </div>

                </div>

            </div>



            <!-- RESIDENTES CON DEUDA -->

            <div class="col-md-6 col-lg-3">

                <div class="stat-card">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="stat-title">
                                Residentes con deuda
                            </div>

                            <div 
                                class="stat-value"
                                id="residentes_con_deuda">
                                0
                            </div>

                            <div class="stat-description">
                                Residentes con cargos pendientes
                            </div>

                        </div>

                        <div class="stat-icon">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>



        <!-- ==========================================
             CONSIGNACIONES
        =========================================== -->

        <h4 class="dashboard-section-title">
            Estado de consignaciones
        </h4>


        <div class="row g-4">


            <!-- PENDIENTES -->

            <div class="col-md-4">

                <div class="stat-card">

                    <div class="stat-title">
                        Requerimientos pendientes
                    </div>

                    <div 
                        class="stat-value"
                        id="total_requerimientos_pendientes">
                        0
                    </div>

                    <div class="stat-description">
                        Sin ninguna entrega registrada
                    </div>

                </div>

            </div>



            <!-- PARCIALES -->

            <div class="col-md-4">

                <div class="stat-card">

                    <div class="stat-title">
                        Requerimientos parciales
                    </div>

                    <div 
                        class="stat-value"
                        id="total_requerimientos_parciales">
                        0
                    </div>

                    <div class="stat-description">
                        Entregas incompletas
                    </div>

                </div>

            </div>



            <!-- ARTÍCULOS PENDIENTES -->

            <div class="col-md-4">

                <div class="stat-card">

                    <div class="stat-title">
                        Artículos pendientes
                    </div>

                    <div 
                        class="stat-value"
                        id="total_articulos_pendientes">
                        0
                    </div>

                    <div class="stat-description">
                        Unidades pendientes por entregar
                    </div>

                </div>

            </div>

        </div>



        <!-- ==========================================
             PAGOS
        =========================================== -->

        <h4 class="dashboard-section-title">
            Estado de pagos
        </h4>


        <div class="row g-4">


            <!-- CARGOS PENDIENTES -->

            <div class="col-md-6">

                <div class="stat-card">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="stat-title">
                                Cargos pendientes
                            </div>

                            <div 
                                class="stat-value"
                                id="total_cargos_pendientes">
                                0
                            </div>

                            <div class="stat-description">
                                Cargos pendientes de pago
                            </div>

                        </div>

                        <div class="stat-icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>

                    </div>

                </div>

            </div>



            <!-- DEUDORES -->

            <div class="col-md-6">

                <div class="stat-card">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="stat-title">
                                Residentes con deuda
                            </div>

                            <div 
                                class="stat-value"
                                id="residentes_con_deuda_2">
                                0
                            </div>

                            <div class="stat-description">
                                Residentes con cargos pendientes
                            </div>

                        </div>

                        <div class="stat-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>



        <!-- ==========================================
             RESUMEN DE ALERTAS
        =========================================== -->

        <h4 class="dashboard-section-title">
            Resumen de pendientes
        </h4>


        <div class="row g-4">


            <div class="col-md-6">

                <div class="alert-card">

                    <h5 class="mb-3">
                        <i class="fas fa-box-open me-2"></i>
                        Consignaciones
                    </h5>

                    <div class="alert-item">

                        <div class="d-flex justify-content-between">

                            <span>
                                Artículos pendientes por entregar
                            </span>

                            <span 
                                class="alert-number"
                                id="alert_articulos">
                                0
                            </span>

                        </div>

                    </div>

                </div>

            </div>



            <div class="col-md-6">

                <div class="alert-card">

                    <h5 class="mb-3">
                        <i class="fas fa-credit-card me-2"></i>
                        Pagos
                    </h5>

                    <div class="alert-item">

                        <div class="d-flex justify-content-between">

                            <span>
                                Cargos pendientes de pago
                            </span>

                            <span 
                                class="alert-number"
                                id="alert_cargos">
                                0
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>


    </main>



    <footer class="app-footer text-white text-center py-3">

        <p class="mb-0">
            &copy; 2025 CVM - Todos los derechos reservados.
        </p>

    </footer>



    <!-- SCRIPTS -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script src="../assets/js/ajaxLanding.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.all.min.js"></script>

</body>

</html>