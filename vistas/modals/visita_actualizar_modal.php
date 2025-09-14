<?php
if (!isset($row)) {
    echo "Error: Los datos de la visita no están disponibles.";
    return;
}
?>

<form id="updateVisitaForm" method="POST">
    <input type="hidden" name="id_visita" value="<?php echo htmlspecialchars($row['id_visita']); ?>">
    <input type="hidden" name="estado" value="<?php echo htmlspecialchars($action); ?>">
    <div class="mb-3">
        <label for="observaciones" class="form-label">Observaciones</label>
        <textarea class="form-control" id="observaciones" name="observaciones" rows="3" required></textarea>
    </div>
    <div class="mb-3">
        <label for="fecha_realizada" class="form-label">Fecha de Realización</label>
        <input type="date" class="form-control" id="fecha_realizada" name="fecha_realizada" value="<?php echo date('Y-m-d'); ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
</form>
