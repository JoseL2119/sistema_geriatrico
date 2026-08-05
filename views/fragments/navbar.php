<!-- <nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Quinielas</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <a class="nav-link" aria-current="page" href="/equipos">Equipos</a>
                <a class="nav-link" href="/jornadas">Jornadas</a>
                <a class="nav-link" href="/partidos">Partidos</a>
                <a class="nav-link" href="/excavacion">Excavación CD Piar</a>
                <a class="nav-link" href="/empresa">Empresas</a>
                <a class="nav-link" href="/mina">Minas</a>
                <a class="nav-link" href="/material">Materiales</a>
                <a class="nav-link" href="/planta">Plantas</a>
                <a class="nav-link" href="/mineralProcesado">Mineral Procesado</a>
                <a class="nav-link" href="/procesamiento">Procesamiento en Planta</a>
            </div>
        </div>
    </div>
</nav> -->


<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="/landing">Producción Ferrominera</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" 
            aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <!-- <a class="nav-link" href="/equipos">Equipos</a>
                <a class="nav-link" href="/jornadas">Jornadas</a>
                <a class="nav-link" href="/partidos">Partidos</a> -->

                <!-- Dropdown para los relacionado con el proceso de Excavación -->
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="excavacionDropdown" role="button" 
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Excavación CD Piar
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="excavacionDropdown">
                        <li><a class="dropdown-item" href="/excavacion">Excavación CD Piar</a></li>
                        <li><a class="dropdown-item" href="/material">Materiales</a></li>
                        <li><a class="dropdown-item" href="/empresa">Empresas</a></li>
                        <li><a class="dropdown-item" href="/mina">Minas</a></li>
                    </ul>
                </div>

                <!-- Dropdown para lo relacionado con el Procesamiento en Planta -->
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="procesamientoDropdown" role="button" 
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Procesamiento CD Piar
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="minasDropdown">
                    <li><a class="dropdown-item" href="/procesamiento">Procesamiento en Planta</a></li>
                    <li><a class="dropdown-item" href="/mineralProcesado">Mineral Procesado</a></li>
                    <li><a class="dropdown-item" href="/planta">Plantas</a></li>
                    </ul>
                </div>

                <!-- Dropdown para lo relacionado con la Carga de Vagones -->
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="vagonesDropdown" role="button" 
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Carga de Vagones CD Piar
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="minasDropdown">
                    <li><a class="dropdown-item" href="/cargaVagones">Carga de Vagones</a></li>
                    <li><a class="dropdown-item" href="/gondolasTEU">Carga de Gondolas con TEU</a></li>
                    <li><a class="dropdown-item" href="/gondolasFg">Carga de Gondolas con Fino y Grueso</a></li>
                    <li><a class="dropdown-item" href="/tolvas">Carga de Tolvas</a></li>
                    <!-- <li><a class="dropdown-item" href="/mineralProcesado">Mineral Procesado</a></li> -->
                    <li><a class="dropdown-item" href="/vagones">Tipos de Vagones</a></li>
                    </ul>
                </div>

                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="vagonesDropdown" role="button" 
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Inventario CD Piar
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="minasDropdown">
                    <li><a class="dropdown-item" href="/inventarioCdPiar">Inventario General</a></li>
                    <li><a class="dropdown-item" href="/inventarioExc">Inventario Material Excavado</a></li>
                    <li><a class="dropdown-item" href="/inventarioProc">Inventario Mineral Procesado</a></li>
                    </ul>
                </div>

                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="vagonesDropdown" role="button" 
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Carga Barco
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="minasDropdown">
                    <li><a class="dropdown-item" href="/cargaBarco">Carga a Barco</a></li>
                    <li><a class="dropdown-item" href="/empresaEx">Empresas Extranjeras</a></li>
                    </ul>
                </div>

                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="vagonesDropdown" role="button" 
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Inventario Pto Ordaz
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="minasDropdown">
                    <li><a class="dropdown-item" href="/inventarioPzo">Inventario</a></li>
                    <li><a class="dropdown-item" href="/tiposMineralesPzo">Tipos de Minerales</a></li>
                    <li><a class="dropdown-item" href="/almacenesMineralesPzo">Almacenes de Minerales</a></li>
                    </ul>
                </div>

                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="vagonesDropdown" role="button" 
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Producción
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="minasDropdown">
                    <li><a class="dropdown-item" href="/produccion">Producción</a></li>
                    </ul>
                </div>

                

                
            </div>
        </div>
    </div>
</nav>

