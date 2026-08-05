<nav class="navbar navbar-expand-lg navbar-app">
    <div class="container-fluid">
        <a class="navbar-brand" href="landing"><img src="../../src/logo.PNG" alt="Logo del geriátrico"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" 
            aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <!-- Dropdown para lo relacionado con los Residentes -->
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="residentesDropdown" role="button" 
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Residentes
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="residentesDropdown">
                        <li><a class="dropdown-item" href="/residentes">Residentes</a></li>
                        <li><a class="dropdown-item" href="/tarifas">Administrar Tarifas</a></li>
                        <li><a class="dropdown-item" href="/status">Status</a></li>
                        <li><a class="dropdown-item" href="/movilidad">Movilidad</a></li>
                        <li><a class="dropdown-item" href="/centrosMedicos">Centros Médicos de Evaluación</a></li>
                        <li><a class="dropdown-item" href="/medicos">Médicos</a></li>
                    </ul>
                </div>

                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="representantesDropdown" role="button" 
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Representantes
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="representantesDropdown">
                        <li><a class="dropdown-item" href="/representantes">Representantes</a></li>
                        <li><a class="dropdown-item" href="/parentesco">Parentesco</a></li>
                    </ul>
                </div>

                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="pagosDropdown" role="button" 
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Finanzas
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="pagosDropdown">
                        <li><a class="dropdown-item" href="/pagos">Registrar Pago</a></li>
                        <li><a class="dropdown-item" href="/cargos">Cargos</a></li>
                        <li><a class="dropdown-item" href="/cargosPendientes">Cargos por Pagar</a></li>
                        <li><a class="dropdown-item" href="/metodoPago">Administrar Métodos de pago</a></li>
                    </ul>
                </div>

                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="consignacionesDropdown" role="button" 
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Consignaciones
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="consignacionesDropdown">
                        <li><a class="dropdown-item" href="/consignacionesPendientes">Consignaciones Pendientes</a></li>
                        <li><a class="dropdown-item" href="/consignaciones">Registrar Consignación</a></li>
                        <li><a class="dropdown-item" href="/requerimientos">Requerimientos por Residente</a></li>
                        <li><a class="dropdown-item" href="/articulos">Lista de Artículos</a></li>
                    </ul>
                </div>
            </div>

            <div class="navbar-nav ms-auto align-items-lg-center">
                <span class="nav-link text-muted">
                    <i class="fas fa-user-circle me-1"></i><?php echo htmlspecialchars($_SESSION['usuario_nombre'] ?? ''); ?>
                </span>
                <a class="nav-link" href="/auth?action=logout">
                    <i class="fas fa-sign-out-alt me-1"></i>Cerrar sesión
                </a>
            </div>
        </div>
    </div>
</nav>

