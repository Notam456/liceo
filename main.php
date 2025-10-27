<?php
session_start();
define('ROOT_PATH', __DIR__ . '/');
?>

<!doctype html>
<html lang="en">

<head>
    <?php include(ROOT_PATH . 'includes/head.php'); ?>
    <link rel="stylesheet" href="/liceo/css/Estilos.css">
    <link rel="stylesheet" href="/liceo/css/style.css">
    <title>Liceo Profesor Fernando Ramirez</title>
</head>

<body data-bs-spy="scroll" data-bs-target=".navbar" data-bs-offset="70">
    <div id="mainContent">
        <nav>
            <?php include(ROOT_PATH . 'includes/navbar.php') ?>
        </nav>
        <?php include(ROOT_PATH . 'includes/sidebar.php') ?>
        <main>
            <h4 class="d-flex justify-content-center mt-4 mb-3 fw-bold">¡Bienvenido de vuelta, <?= $_SESSION['nombre_prof'] ?>!</h4>
            <section class="cabecera">
                <h1>Menu</h1>
                <img src="/liceo/imgs/cuadricula.png" alt="">
            </section>
            <?php
            // Define los módulos visibles para cada rol organizados por categorías
            $modulos_por_rol = [
                'admin' => [
                    'Registro de datos' => [
                        'Usuario' => [
                            'usuario' => '/liceo/imgs/agregar-usuario.png',
                        ],
                        'Gestión Académica' => [
                            'profesor' => '/liceo/imgs/masculino.png',
                            'estudiante' => '/liceo/imgs/estudiando.png',
                            'anio_academico' => '/liceo/imgs/calendario.png',
                            'grado' => '/liceo/imgs/sombrero-de-graduacion.png',
                            'seccion' => '/liceo/imgs/secciones.png',
                            'materia' => '/liceo/imgs/libros.png',
                            'cargo' => '/liceo/imgs/suitcase-lg.svg',
                        ],
                        'Ubicación' => [
                            'municipio' => '/liceo/imgs/cataluna.png',
                            'parroquia' => '/liceo/imgs/casas.png',
                            'sector' => '/liceo/imgs/pueblo.png',
                        ]
                    ],
                    'Gestión Operativa' => [
                        'asistencia' => '/liceo/imgs/lista-de-verificacion.png',
                        'ausencia' => '/liceo/imgs/ausencia.png',
                        'visita' => '/liceo/imgs/calendar-check.svg',
                        'asigna_cargo' => '/liceo/imgs/asignacion-de-recursos.png',
                        'asigna_materia' => '/liceo/imgs/asignacion-de-recursos.png',
                    ],
                    'Estadisticas' => [
                        'reporte' => '/liceo/imgs/reporte.png',
                    ],
                    'Soporte' => [
                        'ayuda' => '/liceo/imgs/ayudar.png',
                    ]

                ],
                'coordinador' => [
                    'Registro de datos' => [
                        'estudiante' => '/liceo/imgs/estudiando.png',
                        'profesor' => '/liceo/imgs/masculino.png',
                        'materia' => '/liceo/imgs/libros.png',
                        'seccion' => '/liceo/imgs/secciones.png',
                    ],
                    'Gestión Operativa' => [
                        'asistencia' => '/liceo/imgs/lista-de-verificacion.png',
                        'asigna_materia' => '/liceo/imgs/asignacion-de-recursos.png',
                        'visita' => '/liceo/imgs/calendar-check.svg',
                    ],
                    'Estadisticas' => [
                        'reporte' => '/liceo/imgs/reporte.png',
                    ],
                    'Soporte' => [
                        'ayuda' => '/liceo/imgs/ayudar.png',
                    ]
                ],
                'user' => [
                    'Gestión Operativa' => [
                        'asistencia' => '/liceo/imgs/lista-de-verificacion.png',
                        'visita' => '/liceo/imgs/calendar-check.svg',
                        'seccion' => '/liceo/imgs/secciones.png'
                    ],
                     'Estadisticas' => [
                        'reporte' => '/liceo/imgs/reporte.png',
                    ],
                    'Soporte' => [
                        'ayuda' => '/liceo/imgs/ayudar.png',
                    ]
                ],
                'profesor' => [
                    'Gestión Operativa' => [
                        'asistencia' => '/liceo/imgs/lista-de-verificacion.png',
                        'visita' => '/liceo/imgs/calendar-check.svg',
                    ],
                    'Estadisticas' => [
                        'reporte' => '/liceo/imgs/reporte.png',
                    ],
                    'Soporte' => [
                        'ayuda' => '/liceo/imgs/ayudar.png',
                    ]
                ]
            ];
            $nombre_legible = [
                'anio_academico' => 'Año académico',
                'asigna_cargo' => 'Asignación de cargos',
                'asigna_materia' => 'Asignación de materias',
                'ausencias' => 'Ausencias',
                'usuario' => 'Usuarios',
                'estudiante' => 'Estudiantes',
                'profesor' => 'Profesores',
                'materia' => 'Materias',
                'seccion' => 'Secciones',
                'grado' => 'Grados',
                'cargo' => 'Cargos',
                'municipio' => 'Municipios',
                'parroquia' => 'Parroquias',
                'sector' => 'Sectores',
                'asistencia' => 'Asistencia',
                'visita' => 'Visita',
                'reporte' => 'Reporte',
                'ayuda' => 'Ayuda'
            ];
            // Obtén el rol del usuario de la sesión
            $rol_usuario = $_SESSION['rol'] ?? 'default';

            // Verifica si el rol existe en la configuración de módulos
            $modulos_visibles = $modulos_por_rol[$rol_usuario] ?? [];


            // Función para renderizar un módulo individual
            function render_modulo($nombre_modulo, $imagen_url, $nombre_legible) {
                $ruta = 'controladores/' . $nombre_modulo . '_controlador.php';
                $texto = $nombre_legible[$nombre_modulo] ?? ucfirst(str_replace('_', ' ', $nombre_modulo));
                
                echo '<div class="modulo" data-url="' . htmlspecialchars($ruta) . '">';
                echo '<img src="' . htmlspecialchars($imagen_url) . '" alt="' . htmlspecialchars($texto) . '">';
                echo '<h2>' . htmlspecialchars($texto) . '</h2>';
                echo '</div>';
            }
            ?>

            <div class="container-fluid">
                <?php foreach ($modulos_visibles as $categoria => $modulos): ?>
                    <div class="categoria-section">
                        <h2 class="categoria-titulo"><?php echo htmlspecialchars($categoria); ?></h2>
                        <hr class="categoria-linea">
                        
                        <?php
                        $has_sub_categories = false;
                        if (!empty($modulos)) {
                            // Revisa si hay sub-categorías (arrays anidados)
                            foreach ($modulos as $item) {
                                if (is_array($item)) {
                                    $has_sub_categories = true;
                                    break;
                                }
                            }
                        }

                        if ($has_sub_categories) {
                            // Renderiza con sub-categorías
                            foreach ($modulos as $sub_categoria => $sub_modulos) {
                                echo '<h4 class="sub-categoria-titulo mt-3 mb-2">' . htmlspecialchars($sub_categoria) . '</h4>';
                                echo '<section class="modulos">';
                                foreach ($sub_modulos as $nombre_modulo => $imagen_url) {
                                    render_modulo($nombre_modulo, $imagen_url, $nombre_legible);
                                }
                                echo '</section>';
                            }
                        } else {
                            // Renderiza como una lista plana
                            echo '<section class="modulos">';
                            foreach ($modulos as $nombre_modulo => $imagen_url) {
                                render_modulo($nombre_modulo, $imagen_url, $nombre_legible);
                            }
                            echo '</section>';
                        }
                        ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </main>
        <script>
            document.querySelectorAll('.modulo').forEach(modulo => {
                modulo.addEventListener('click', () => {
                    const destino = modulo.getAttribute('data-url');
                    if (destino) {
                        window.location.href = destino;
                    }
                });
            });
        </script>

        <?php include(ROOT_PATH . 'includes/widget_de_ayuda.php') ?>

        <footer>
            <?php include(ROOT_PATH . 'includes/footer.php') ?>
        </footer>
    </div>
</body>

</html>