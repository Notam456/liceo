<?php
session_start(); // Asegúrate de que siempre se inicia la sesión

// Tiempo máximo de inactividad (en segundos)
$tiempo_inactividad = 1800; // 30 minutos

// Verificar si hay una sesión activa
if (isset($_SESSION['usuario'])) {

    // Si existe registro de la última actividad, calcular el tiempo transcurrido
    if (isset($_SESSION['ultimo_movimiento'])) {
        $inactividad = time() - $_SESSION['ultimo_movimiento'];

        // Si supera el límite, destruir la sesión y redirigir
        if ($inactividad > $tiempo_inactividad) {
            session_unset();
            session_destroy();
            header("Location: /liceo/logout.php?motivo=inactividad");
            exit();
        }
    }

    // Actualizar el timestamp de última actividad
    $_SESSION['ultimo_movimiento'] = time();

    // Obtener el año académico activo
    include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/conn.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/anio_academico_modelo.php');
    
    $anioModelo = new AnioAcademicoModelo($conn);
    
    $anio_activo = null;
    $resultado = $anioModelo->obtenerAnioActivo();
    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $anio = mysqli_fetch_assoc($resultado);
        $anio_activo = date('Y', strtotime($anio['desde'])) . '-' . date('Y', strtotime($anio['hasta']));
    }
}
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow">
    <div class="container-fluid">

        <?php if (isset($_SESSION['usuario'])) { ?>
            <button class="btn btn-outline-light me-3" id="toggleSidebar" title="Menú lateral">
                ☰
            </button>
        <?php } ?>

        <a class="navbar-brand d-flex align-items-center" href="#">
            <img src="/liceo/imgs/escudo.jpg" alt="Escudo" width="30" height="30" class="me-2">
            Liceo Bolivariano Prof. Fernando Ramírez
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item d-flex align-items-center">
                    <?php if (isset($_SESSION['usuario'])) { ?>
                        <div class="text-light me-3">
                            <p class="mb-0 fw-bold"><?= $_SESSION['nombre_prof'] ?></p>
                            <?php if ($anio_activo): ?>
                                <p class="mb-0 small" style="color: #adb5bd;"><?= $anio_activo ?></p>
                            <?php endif; ?>
                        </div>
                        <a href="/liceo/logout.php" class="btn btn-outline-danger">
                            Cerrar Sesión
                        </a>
                    <?php } elseif (basename($_SERVER['PHP_SELF']) !== 'index.php') {
                        header("Location: /liceo/index.php");
                        exit();
                    } else { ?>
                        <button
                            type="button"
                            class="btn btn-outline-light ms-lg-3 mt-2 mt-lg-0"
                            data-toggle="modal"
                            data-target="#modalId">
                            Iniciar Sesión
                        </button>
                    <?php } ?>
                </li>
            </ul>
        </div>
    </div>
</nav>