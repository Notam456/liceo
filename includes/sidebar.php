<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$modulos_por_rol = [
    'admin' => [
        'usuario' => 'bi-people',
        'profesor' => 'bi-person-workspace',
        'seccion' => 'bi-diagram-3',
        'materia' => 'bi-journal-bookmark',
        'grado' => 'bi-collection',
        'anio_academico' => 'bi-calendar',
        'parroquia' => 'bi-geo-alt',
        'municipio' => 'bi-geo-alt',
        'asigna_cargo' => 'bi-box-arrow-in-down-right',
        'visita' => 'bi-calendar-check',
    ],
    'user' => [
        'estudiante' => 'bi-person-badge',
        'profesor' => 'bi-person-workspace',
        'asistencia' => 'bi-card-checklist',
        'reporte' => 'bi-file-check',
        'visita' => 'bi-calendar-check',
        'materia' => 'bi-journal-bookmark',
        'seccion' => 'bi-diagram-3',
        'asigna_materia' => 'bi-box-arrow-in-down-right',
        'grado' => 'bi-collection',
    ],
    'profesor' => []
];

// Excepciones de nombres más legibles
$nombre_legible = [
    'anio_academico' => 'Año académico',
    'asigna_cargo' => 'Asignación de cargo',
    'asigna_materia' => 'Asignación de materia'
];

$rol_usuario = $_SESSION['rol'] ?? null;
$modulos_visibles = $modulos_por_rol[$rol_usuario] ?? [];
?>

<style>
    .sidebar {
        position: fixed;
        top: 0;
        left: -250px;
        width: 250px;
        height: 100%;
        background-color: #212529;
        color: white;
        padding-top: 70px;
        transition: left 0.3s ease-in-out;
        z-index: 1040;
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.3);
    }

    .sidebar.active {
        left: 0;
    }

    .sidebar a {
        color: white;
        display: flex;
        align-items: center;
        padding: 0.75rem 1rem;
        text-decoration: none;
        transition: background-color 0.2s ease;
    }

    .sidebar a:hover {
        background-color: #343a40;
        text-decoration: none;
    }

    #mainContent {
        transition: margin-left 0.3s ease-in-out;
        margin-left: 0;
    }

    #mainContent.shifted {
        margin-left: 250px;
    }
</style>

<div id="sidebar" class="sidebar">
    <a href="/liceo/main.php"><i class="bi bi-house-door me-2"></i> Inicio</a>

    <?php foreach ($modulos_visibles as $nombre_modulo => $icono):
        $ruta = isset($rutas_controladores[$nombre_modulo])
            ? $rutas_controladores[$nombre_modulo]
            : '/liceo/controladores/' . $nombre_modulo . '_controlador.php';

        // Si existe un nombre legible definido, úsalo
        if (isset($nombre_legible[$nombre_modulo])) {
            $texto = $nombre_legible[$nombre_modulo];
        } else {
            // Si no, reemplaza guiones bajos y capitaliza
            $texto = ucfirst(str_replace('_', ' ', $nombre_modulo));
        }
    ?>
        <a href="<?php echo $ruta; ?>">
            <i class="bi <?php echo $icono ?> me-2"></i> <?php echo $texto; ?>
        </a>
    <?php endforeach; ?>

    <a href="/liceo/logout.php"><i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión</a>
</div>

<script>
    const toggleButton = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');

    toggleButton?.addEventListener('click', (e) => {
        e.stopPropagation();
        sidebar.classList.toggle('active');
        mainContent?.classList.toggle('shifted');
    });

    document.addEventListener('click', function(event) {
        const isClickInside = sidebar.contains(event.target) || toggleButton?.contains(event.target);
        if (!isClickInside && sidebar.classList.contains('active')) {
            sidebar.classList.remove('active');
            mainContent?.classList.remove('shifted');
        }
    });
</script>