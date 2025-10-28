<?php

$vistaActual = basename($_SERVER['PHP_SELF']);

// Definimos las ayudas con su información y enlace de referencia
$ayudas = [
    'main.php' => [
        "titulo" => "¿Cómo acceder a un módulo?",
        "descripcion" => "Haga click en el módulo deseado para que el sistema le redirija a su pantalla.",
        "seccionAyuda" => "#introduccion"
    ],
    'anio_academico_controlador.php' => [
        "titulo" => "Gestión de Años Escolares",
        "descripcion" => "Desde aquí puede crear, editar o eliminar años escolares y gestionar su estado.",
        "seccionAyuda" => "#anio-escolar"
    ],
    'profesor_controlador.php' => [
        "titulo" => "Gestión de Profesores",
        "descripcion" => "En este módulo puede registrar, consultar, actualizar o eliminar profesores del sistema.",
        "seccionAyuda" => "#profesor"
    ],
    'usuario_controlador.php' => [
        "titulo" => "Gestión de Usuarios",
        "descripcion" => "Permite administrar las cuentas de usuario, asignando roles y permisos para acceder al sistema.",
        "seccionAyuda" => "#usuario"
    ],
    'estudiante_controlador.php' => [
        "titulo" => "Gestión de Estudiantes",
        "descripcion" => "Registre, consulte, modifique y elimine la información de los estudiantes.",
        "seccionAyuda" => "#estudiantes"
    ],
    'grado_controlador.php' => [
        "titulo" => "Gestión de Grados",
        "descripcion" => "Administre los grados académicos que ofrece la institución.",
        "seccionAyuda" => "#grado"
    ],
    'materia_controlador.php' => [
        "titulo" => "Gestión de Materias",
        "descripcion" => "Defina las materias que se imparten, las cuales podrán ser asignadas a los profesores.",
        "seccionAyuda" => "#materias"
    ],
    'seccion_controlador.php' => [
        "titulo" => "Gestión de Secciones",
        "descripcion" => "Cree y administre las secciones por grado, asigne estudiantes y consulte horarios.",
        "seccionAyuda" => "#secciones"
    ],
    'cargo_controlador.php' => [
        "titulo" => "Gestión de Cargos",
        "descripcion" => "Administre los diferentes cargos que puede ocupar el personal en la institución.",
        "seccionAyuda" => "#cargos"
    ],
    'municipio_controlador.php' => [
        "titulo" => "Gestión de Municipios",
        "descripcion" => "Administre los municipios para el registro de direcciones.",
        "seccionAyuda" => "#municipio"
    ],
    'parroquia_controlador.php' => [
        "titulo" => "Gestión de Parroquias",
        "descripcion" => "Administre las parroquias asociadas a los municipios.",
        "seccionAyuda" => "#parroquia"
    ],
    'sector_controlador.php' => [
        "titulo" => "Gestión de Sectores",
        "descripcion" => "Administre los sectores para completar la información de ubicación.",
        "seccionAyuda" => "#sector"
    ],
    'asigna_materia_controlador.php' => [
        "titulo" => "Asignación de Materias",
        "descripcion" => "Asigne las materias que impartirá cada profesor en el año escolar activo.",
        "seccionAyuda" => "#asignacion-materia"
    ],
    'asigna_cargo_controlador.php' => [
        "titulo" => "Asignación de Cargos",
        "descripcion" => "Asigne los cargos administrativos o académicos al personal de la institución.",
        "seccionAyuda" => "#asignacion-cargo"
    ],
    'asistencia_controlador.php' => [
        "titulo" => "Registro de Asistencia",
        "descripcion" => "Registre la asistencia diaria de los estudiantes por sección y materia.",
        "seccionAyuda" => "#asistencia"
    ],
    'ausencia_controlador.php' => [
        "titulo" => "Gestión de Ausencias",
        "descripcion" => "Consulte las ausencias de los estudiantes y agende visitas domiciliarias si es necesario.",
        "seccionAyuda" => "#ausencias"
    ],
    'visita_controlador.php' => [
        "titulo" => "Gestión de Visitas",
        "descripcion" => "Programe y gestione las visitas a los hogares de los estudiantes con ausencias recurrentes.",
        "seccionAyuda" => "#visita"
    ],
    'reporte_controlador.php' => [
        "titulo" => "Generación de Reportes",
        "descripcion" => "Genere reportes académicos y de asistencia en formato PDF.",
        "seccionAyuda" => "#reporte"
    ]
];

// Si no se encuentra la vista actual, se usa una ayuda por defecto
$ayudaVista = $ayudas[$vistaActual] ?? [
    "titulo" => "Bienvenido al sistema de gestión de inasistencias estudiantiles.",
    "descripcion" => "Aquí encontrará información sobre todos los módulos del sistema.",
    "seccionAyuda" => "#general"
];
?>

<div class="position-fixed bottom-0 end-0 me-5 mb-5 z-3 d-flex flex-column align-items-end">
    <div id="panelAyuda" class="card shadow border-0 mb-3 widget-ayuda-panel" style="width: 20rem;">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <span>Ayuda rápida</span>
            <button type="button" class="btn-close btn-close-white" id="cerrarAyuda" aria-label="Cerrar"></button>
        </div>
        <div class="card-body">
            <h6 class="fw-bold"><?= htmlspecialchars($ayudaVista["titulo"]) ?></h6>
            <p class="small mb-3"><?= htmlspecialchars($ayudaVista["descripcion"]) ?></p>

            <a href="/liceo/controladores/ayuda_controlador.php<?= htmlspecialchars($ayudaVista['seccionAyuda']) ?>" 
               class="btn btn-outline-primary btn-sm w-100">
                Más información
            </a>
        </div>
    </div>

    <button class="btn btn-primary rounded-circle shadow" 
            type="button" 
            id="btnAyuda"
            style="width: 50px; height: 50px; font-size: 1.5rem;">
        ?
    </button>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const btnAyuda = document.getElementById("btnAyuda");
    const panelAyuda = document.getElementById("panelAyuda");
    const cerrarAyuda = document.getElementById("cerrarAyuda");

    btnAyuda.addEventListener("click", (e) => {
        e.stopPropagation();
        panelAyuda.classList.toggle("show");
    });

    cerrarAyuda.addEventListener("click", () => {
        panelAyuda.classList.remove("show");
    });

    document.addEventListener("click", (e) => {
        if (!panelAyuda.contains(e.target) && panelAyuda.classList.contains("show")) {
            panelAyuda.classList.remove("show");
        }
    });
});
</script>
