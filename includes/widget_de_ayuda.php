<?php

$vistaActual = basename($_SERVER['PHP_SELF']);

$ayudas = [
    'main.php' => [
        "¿Como acceder a un modulo?",
        "Haga click en el modulo desado para que el sistema le redirija a su pantalla."
    ]
];

$ayudaVista = $ayudas[$vistaActual] ?? [
    "Bienvenido al sistema de gestión de inasistencias estudiantiles.",
    "Inicie sesión para guiarle a través del sistema."
];
?>


<div class="position-fixed bottom-0 end-0 me-5 mb-5 z-3 d-flex flex-column align-items-end">
    <div id="panelAyuda" class="card shadow border-0 mb-3 widget-ayuda-panel" style="width: 20rem;">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <span>Ayuda rápida</span>
            <button type="button" class="btn-close btn-close-white" id="cerrarAyuda" aria-label="Cerrar"></button>
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                <?php foreach ($ayudaVista as $item): ?>
                    <li class="list-group-item small"><?= htmlspecialchars($item) ?></li>
                <?php endforeach; ?>
            </ul>
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
