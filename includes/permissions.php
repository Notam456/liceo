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
            'manual_sistema' => '/liceo/imgs/manual.png'
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
    'ayuda' => 'Ayuda',
    'manual_sistema' => 'Manual del sistema'
];
// Obtén el rol del usuario de la sesión
$rol_usuario = $_SESSION['rol'] ?? 'default';

// Verifica si el rol existe en la configuración de módulos
$modulos_visibles_por_rol = $modulos_por_rol[$rol_usuario] ?? [];

// Obtener una lista plana de todos los módulos visibles para el rol actual
function obtener_modulos_visibles_flat($modulos_por_rol, $rol_usuario) {
    $modulos_visibles = $modulos_por_rol[$rol_usuario] ?? [];
    $modulos_flat = [];

    foreach ($modulos_visibles as $categoria) {
        foreach ($categoria as $key => $value) {
            if (is_array($value)) {
                // Es una sub-categoría
                foreach ($value as $nombre_modulo => $imagen_url) {
                    $modulos_flat[] = $nombre_modulo;
                }
            } else {
                // Es un módulo directo
                $modulos_flat[] = $key;
            }
        }
    }
    return $modulos_flat;
}

$modulos_visibles = obtener_modulos_visibles_flat($modulos_por_rol, $rol_usuario);
?>