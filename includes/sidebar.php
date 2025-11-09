<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Estructura de menús por rol
$modulos_por_rol = [
    'admin' => [
        'Registro de datos' => [
            'usuario' => ['icon' => 'bi-people', 'ruta' => 'usuario'],
            'Gestión Académica' => [
                'estudiante' => ['icon' => 'bi-person-badge', 'ruta' => 'estudiante'],
                'profesor' => ['icon' => 'bi-person-workspace', 'ruta' => 'profesor'],
                'anio_academico' => ['icon' => 'bi-calendar', 'ruta' => 'anio_academico'],
                'grado' => ['icon' => 'bi-collection', 'ruta' => 'grado'],
                'seccion' => ['icon' => 'bi-diagram-3', 'ruta' => 'seccion'],
                'materia' => ['icon' => 'bi-journal-bookmark', 'ruta' => 'materia'],
                'cargo' => ['icon' => 'bi-briefcase', 'ruta' => 'cargo'],
            ],
            'Ubicación' => [
                'municipio' => ['icon' => 'bi-geo-alt', 'ruta' => 'municipio'],
                'parroquia' => ['icon' => 'bi-geo-alt', 'ruta' => 'parroquia'],
                'sector' => ['icon' => 'bi-geo-alt', 'ruta' => 'sector'],
            ]
        ],
        'Gestión Operativa' => [
            'asistencia' => ['icon' => 'bi-card-checklist', 'ruta' => 'asistencia'],
            'ausencia' => ['icon' => 'bi-card-list', 'ruta' => 'ausencia'],
            'asigna_cargo' => ['icon' => 'bi-box-arrow-in-down-right', 'ruta' => 'asigna_cargo'],
            'asigna_materia' => ['icon' => 'bi-box-arrow-in-down-right', 'ruta' => 'asigna_materia'],
            'visita' => ['icon' => 'bi-calendar-check', 'ruta' => 'visita'],
        ],
        'Estadisticas' => [
            'reporte' => ['icon' => 'bi-file-check', 'ruta' => 'reporte']
        ],
        'Soporte' => [
            'ayuda' => ['icon' => 'bi-people', 'ruta' => 'ayuda'],
            'manual_sistema' => ['icon' => 'bi-person-gear', 'ruta' => 'manual_sistema']
        ]
    ],
    'coordinador' => [
        'Registro de datos' => [
            'estudiante' => ['icon' => 'bi-person-badge', 'ruta' => 'estudiante'],
            'profesor' => ['icon' => 'bi-person-workspace', 'ruta' => 'profesor'],
            'materia' => ['icon' => 'bi-journal-bookmark', 'ruta' => 'materia'],
            'grado' => ['icon' => 'bi-collection', 'ruta' => 'grado'],
            'seccion' => ['icon' => 'bi-diagram-3', 'ruta' => 'seccion'],
        ],
        'Gestión Operativa' => [
            'asistencia' => ['icon' => 'bi-card-checklist', 'ruta' => 'asistencia'],
            'ausencia' => ['icon' => 'bi-card-list', 'ruta' => 'ausencia'],
            'visita' => ['icon' => 'bi-calendar-check', 'ruta' => 'visita'],
            'asigna_materia' => ['icon' => 'bi-box-arrow-in-down-right', 'ruta' => 'asigna_materia'],
        ],
        'Estadisticas' => [
            'reporte' => ['icon' => 'bi-file-check', 'ruta' => 'reporte']
        ],
        'Soporte' => [
            'ayuda' => ['icon' => 'bi-people', 'ruta' => 'ayuda']
        ]
    ],
    'user' => [
        'seccion' => ['icon' => 'bi-diagram-3', 'ruta' => 'seccion'],
        'visita' => ['icon' => 'bi-calendar-check', 'ruta' => 'visita'],
        'reporte' => ['icon' => 'bi-file-check', 'ruta' => 'reporte'],
        'ayuda' => ['icon' => 'bi-people', 'ruta' => 'ayuda']
    ],
    'profesor' => [
        'seccion' => ['icon' => 'bi-diagram-3', 'ruta' => 'seccion'],
        'visita' => ['icon' => 'bi-calendar-check', 'ruta' => 'visita'],
        'reporte' => ['icon' => 'bi-file-check', 'ruta' => 'reporte'],
        'ayuda' => ['icon' => 'bi-people', 'ruta' => 'ayuda']
    ]
];

// Nombres legibles para los módulos
$nombre_legible = [
    'anio_academico' => 'Año académico',
    'asigna_cargo' => 'Asignación de cargo',
    'asigna_materia' => 'Asignación de materia',
    'usuario' => 'Usuario',
    'ausencia' => 'Inasistencia',
    'estudiante' => 'Estudiante',
    'profesor' => 'Profesor',
    'materia' => 'Materia',
    'seccion' => 'Sección',
    'grado' => 'Grado',
    'cargo' => 'Cargo',
    'municipio' => 'Municipio',
    'parroquia' => 'Parroquia',
    'sector' => 'Sector',
    'asistencia' => 'Asistencia',
    'visita' => 'Visita',
    'reporte' => 'Reporte',
    'ayuda' => 'Ayuda',
    'manual_sistema' => 'Manual del sistema'
];

$rol_usuario = $_SESSION['rol'] ?? null;
$modulos_accesibles = $modulos_por_rol[$rol_usuario] ?? [];
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
        transition: all 0.2s ease;
    }

    .sidebar a:hover {
        background-color: #343a40;
        text-decoration: none;
    }

    .sidebar-category {
        cursor: pointer;
        padding: 0.75rem 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        user-select: none;
        transition: all 0.2s ease;
    }
    
    .sidebar-category:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }

    .sidebar-category i {
        transition: transform 0.3s ease;
        font-size: 0.8rem;
    }

    .sidebar-category.active i {
        transform: rotate(180deg);
    }

    .sidebar-submenu {
        max-height: 0;
        overflow: hidden;
        background-color: rgba(0, 0, 0, 0.1);
        transition: max-height 0.3s ease-in-out;
    }
    
    .sidebar-submenu.show {
        max-height: 2000px;
    }
    
    .sidebar-submenu .sidebar-submenu {
        background-color: rgba(0, 0, 0, 0.1);
    }
    
    .sidebar-submenu a {
        padding-left: 2rem;
        font-size: 0.9rem;
        position: relative;
    }
    
    /* Solo mostrar puntos para los menús desplegables */
    .sidebar-submenu:not(.show) a:before {
        content: '';
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        width: 5px;
        height: 5px;
        background-color: rgba(255, 255, 255, 0.5);
        border-radius: 50%;
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
    // Función para renderizar los ítems del menú
    function renderMenuItems($items, $level = 0, $isStatic = false) {
        global $nombre_legible;
        $output = '';
        
        foreach ($items as $key => $item) {
            if (isset($item['icon'])) {
                // Es un ítem de menú simple
                $ruta = '/liceo/controladores/' . $item['ruta'] . '_controlador.php';
                $texto = $nombre_legible[$item['ruta']] ?? ucfirst(str_replace('_', ' ', $item['ruta']));
                
                $output .= sprintf(
                    '<a href="%s"><i class="bi %s me-2"></i>%s</a>',
                    htmlspecialchars($ruta),
                    htmlspecialchars($item['icon']),
                    htmlspecialchars($texto)
                );
            } else {
                // Es una categoría con submenús
                $isStaticCategory = in_array($key, ['Gestión Académica', 'Ubicación']) || $isStatic;
                
                if (!$isStaticCategory) {
                    // Menú desplegable normal
                    $output .= '<div class="sidebar-category" onclick="toggleSubmenu(this, event)">';
                    $output .= htmlspecialchars($key) . ' <i class="bi bi-chevron-down"></i>';
                    $output .= '</div>';
                    $output .= '<div class="sidebar-submenu">';
                    $output .= renderMenuItems($item, $level + 1);
                    $output .= '</div>';
                } else {
                    // Menú estático (para Gestión Académica y Ubicación)
                    $output .= '<div class="sidebar-category">';
                    $output .= htmlspecialchars($key);
                    $output .= '</div>';
                    $output .= '<div class="sidebar-submenu show">';
                    
                    // Mostrar todos los subítems directamente
                    foreach ($item as $subkey => $subitem) {
                        if (is_array($subitem) && isset($subitem['icon'])) {
                            $ruta = '/liceo/controladores/' . $subitem['ruta'] . '_controlador.php';
                            $texto = $nombre_legible[$subitem['ruta']] ?? ucfirst(str_replace('_', ' ', $subitem['ruta']));
                            
                            $output .= sprintf(
                                '<a href="%s" class="ps-4"><i class="bi %s me-2"></i>%s</a>',
                                htmlspecialchars($ruta),
                                htmlspecialchars($subitem['icon']),
                                htmlspecialchars($texto)
                            );
                        }
                    }
                    
                    $output .= '</div>';
                }
            }
        }
        return $output;
    }

    // Renderizar el menú según el rol
    if ($rol_usuario && !empty($modulos_accesibles)) {
        echo renderMenuItems($modulos_accesibles);
    }
    ?>
    <a href="/liceo/logout.php"><i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión</a>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleButton = document.getElementById('toggleSidebar');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');

        // Función para alternar submenús
        window.toggleSubmenu = function(element, event) {
            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            const submenu = element.nextElementSibling;
            const isActive = element.classList.contains('active');
            
            // Si el menú ya está activo, lo cerramos
            if (isActive) {
                element.classList.remove('active');
                if (submenu) submenu.classList.remove('show');
                return;
            }
            
            // Cerrar otros submenús del mismo nivel
            const parentMenu = element.closest('.sidebar-submenu');
            if (parentMenu) {
                parentMenu.querySelectorAll('> .sidebar-category').forEach(cat => {
                    if (cat !== element) {
                        cat.classList.remove('active');
                        const subSubmenu = cat.nextElementSibling;
                        if (subSubmenu && subSubmenu.classList.contains('sidebar-submenu')) {
                            subSubmenu.classList.remove('show');
                        }
                    }
                });
            }
            
            // Abrir el submenú actual
            if (submenu && submenu.classList.contains('sidebar-submenu')) {
                element.classList.add('active');
                submenu.classList.add('show');
                
                // Asegurarse de que los menús padres también estén abiertos
                let parent = element.parentElement.closest('.sidebar-submenu');
                while (parent) {
                    const parentCategory = parent.previousElementSibling;
                    if (parentCategory && parentCategory.classList.contains('sidebar-category')) {
                        parentCategory.classList.add('active');
                        parent.classList.add('show');
                    }
                    parent = parent.parentElement.closest('.sidebar-submenu');
                }
            }
        };

        // Toggle del sidebar
        if (toggleButton) {
            toggleButton.addEventListener('click', function(e) {
                e.stopPropagation();
                sidebar.classList.toggle('active');
                if (mainContent) {
                    mainContent.classList.toggle('shifted');
                }
            });
        }

        // Cerrar sidebar al hacer clic fuera
        document.addEventListener('click', function(event) {
            const isClickInside = sidebar.contains(event.target) || 
                                (toggleButton && toggleButton.contains(event.target));
            
            if (!isClickInside && sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
                if (mainContent) {
                    mainContent.classList.remove('shifted');
                }
            }
        });

        // Abrir automáticamente el menú activo
        const currentPath = window.location.pathname;
        const menuLinks = document.querySelectorAll('.sidebar a[href]');
        
        menuLinks.forEach(link => {
            if (link.getAttribute('href') === currentPath) {
                // Marcar el enlace activo
                link.classList.add('active');
                
                // Abrir los menús padres
                let parent = link.parentElement;
                while (parent && parent !== sidebar) {
                    if (parent.classList.contains('sidebar-submenu')) {
                        parent.classList.add('show');
                        const category = parent.previousElementSibling;
                        if (category && category.classList.contains('sidebar-category')) {
                            category.classList.add('active');
                        }
                    }
                    parent = parent.parentElement;
                }
            }
        });
    });
</script>