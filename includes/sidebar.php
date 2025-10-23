<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Estructura organizada por categorías con acordeón
$modulos_por_rol = [
    'admin' => [
        'Gestión de Usuarios' => [
            'usuario' => 'bi-people',
            'profesor' => 'bi-person-workspace',
            'estudiante' => 'bi-person-badge',
        ],
        'Gestión Académica' => [
            'anio_academico' => 'bi-calendar',
            'grado' => 'bi-collection',
            'seccion' => 'bi-diagram-3',
            'materia' => 'bi-journal-bookmark',
        ],
        'Asignaciones' => [
            'cargo' => 'bi-briefcase',
            'asigna_cargo' => 'bi-box-arrow-in-down-right',
            'asigna_materia' => 'bi-box-arrow-in-down-right',
        ],
        'Ubicación' => [
            'sector' => 'bi-geo-alt',
            'parroquia' => 'bi-geo-alt',
            'municipio' => 'bi-geo-alt',
        ],
        'Control y Reportes' => [
            'asistencia' => 'bi-card-checklist',
            'ausencia' => 'bi-file-check',
            'visita' => 'bi-calendar-check',
        ]
    ],
    'coordinador' => [
        'Gestión de Usuarios' => [
            'estudiante' => 'bi-person-badge',
            'profesor' => 'bi-person-workspace',
        ],
        'Gestión Académica' => [
            'materia' => 'bi-journal-bookmark',
            'seccion' => 'bi-diagram-3',
            'asigna_materia' => 'bi-box-arrow-in-down-right',
        ],
        'Control y Reportes' => [
            'asistencia' => 'bi-card-checklist',
            'reporte' => 'bi-file-check',
            'visita' => 'bi-calendar-check',
        ]
    ],
    'user' => [
        'seccion' => 'bi-diagram-3',
        'visita' => 'bi-calendar-check',
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
        padding-top: 1rem;
        transition: left 0.3s ease-in-out;
        z-index: 1050;
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.3);
        overflow-y: auto;
        overflow-x: hidden;
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

    .sidebar-category {
        color: #fff;
        font-size: 0.9rem;
        font-weight: 600;
        padding: 0.75rem 1rem;
        margin-top: 0.25rem;
        cursor: pointer;
        transition: background-color 0.2s ease;
        display: flex;
        justify-content: space-between;
        align-items: center;
        user-select: none;
    }

    .sidebar-category:hover {
        background-color: #343a40;
    }

    .sidebar-category i {
        transition: transform 0.3s ease;
    }

    .sidebar-category.active i {
        transform: rotate(180deg);
    }

    .sidebar-submenu {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease;
    }

    .sidebar-submenu.show {
        max-height: 500px;
    }

    .sidebar-submenu a {
        padding-left: 2.5rem;
        font-size: 0.9rem;
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

    <?php 
    // Para el rol 'user' que no tiene categorías, mostrar directamente los módulos
    if ($rol_usuario === 'user' && !empty($modulos_visibles)): 
        foreach ($modulos_visibles as $nombre_modulo => $icono):
            $ruta = isset($rutas_controladores[$nombre_modulo])
                ? $rutas_controladores[$nombre_modulo]
                : '/liceo/controladores/' . $nombre_modulo . '_controlador.php';

            if (isset($nombre_legible[$nombre_modulo])) {
                $texto = $nombre_legible[$nombre_modulo];
            } else {
                $texto = ucfirst(str_replace('_', ' ', $nombre_modulo));
            }
        ?>
            <a href="<?php echo $ruta; ?>">
                <i class="bi <?php echo $icono ?> me-2"></i> <?php echo $texto; ?>
            </a>
        <?php endforeach; ?>
    <?php else: ?>
        <?php foreach ($modulos_visibles as $categoria => $modulos): ?>
            <div class="sidebar-category" onclick="toggleSubmenu(this)">
                <?php echo $categoria; ?>
                <i class="bi bi-chevron-down"></i>
            </div>
            <div class="sidebar-submenu">
                <?php foreach ($modulos as $nombre_modulo => $icono):
                    $ruta = isset($rutas_controladores[$nombre_modulo])
                        ? $rutas_controladores[$nombre_modulo]
                        : '/liceo/controladores/' . $nombre_modulo . '_controlador.php';

                    if (isset($nombre_legible[$nombre_modulo])) {
                        $texto = $nombre_legible[$nombre_modulo];
                    } else {
                        $texto = ucfirst(str_replace('_', ' ', $nombre_modulo));
                    }
                ?>
                    <a href="<?php echo $ruta; ?>">
                        <i class="bi <?php echo $icono ?> me-2"></i> <?php echo $texto; ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <a href="/liceo/logout.php"><i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión</a>
</div>

<script>
    const toggleButton = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');

    // Función para el acordeón del sidebar
    function toggleSubmenu(element) {
        const submenu = element.nextElementSibling;
        const isActive = element.classList.contains('active');
        
        // Cerrar todos los submenús
        document.querySelectorAll('.sidebar-category').forEach(cat => {
            cat.classList.remove('active');
        });
        document.querySelectorAll('.sidebar-submenu').forEach(sub => {
            sub.classList.remove('show');
        });
        
        // Abrir el submenú clickeado si no estaba activo
        if (!isActive) {
            element.classList.add('active');
            submenu.classList.add('show');
        }
    }

    // Toggle del sidebar
    toggleButton?.addEventListener('click', (e) => {
        e.stopPropagation();
        sidebar.classList.toggle('active');
        mainContent?.classList.toggle('shifted');
    });

    // Cerrar sidebar al hacer clic fuera
    document.addEventListener('click', function(event) {
        const isClickInside = sidebar.contains(event.target) || toggleButton?.contains(event.target);
        if (!isClickInside && sidebar.classList.contains('active')) {
            sidebar.classList.remove('active');
            mainContent?.classList.remove('shifted');
        }
    });
</script>