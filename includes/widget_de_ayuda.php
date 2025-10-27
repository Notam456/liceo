<?php

$vistaActual = basename($_SERVER['PHP_SELF']);

// Definimos las ayudas con su información y enlace de referencia
$ayudas = [
    'main.php' => [
        "titulo" => "¿Cómo acceder a un módulo?",
        "descripcion" => "Haga click en el módulo deseado para que el sistema le redirija a su pantalla.",
        "seccionAyuda" => "#inicio" // ancla dentro de ayuda.php
    ],
    'anioAcademico.php' => [
        "titulo" => "Gestión de Años Académicos",
        "descripcion" => "Desde aquí puede crear, editar o eliminar años académicos, además de gestionar trayectos, materias, secciones y aulas.",
        "seccionAyuda" => "#anio-academico"
    ],
    'docentes.php' => [
        "titulo" => "Gestión de Docentes",
        "descripcion" => "En este módulo puede registrar, actualizar o eliminar docentes del sistema.",
        "seccionAyuda" => "#docentes"
    ],
    'asignacion.php' => [
        "titulo" => "Asignación de Carga Horaria",
        "descripcion" => "Permite asignar horas académicas a los docentes según el período, materia y sección.",
        "seccionAyuda" => "#asignacion"
    ]
];

// Si no se encuentra la vista actual, se usa una ayuda por defecto
$ayudaVista = $ayudas[$vistaActual] ?? [
    "titulo" => "Bienvenido al sistema de gestión de inasistencias estudiantiles.",
    "descripcion" => "Inicie sesión para guiarle a través del sistema.",
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

            <a href="ayuda.php<?= htmlspecialchars($ayudaVista['seccionAyuda']) ?>" 
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
