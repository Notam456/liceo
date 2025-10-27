

<div class="modal fade" id="visitaModal" tabindex="-1" aria-labelledby="visitaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="visitaModalLabel">Agendar Visita</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/liceo/controladores/visita_controlador.php" method="POST">
                <input type="hidden" name="action" value="crear">
                <input type="hidden" name="id_estudiante_visita" id="id_estudiante_visita">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="fecha_visita">Fecha de la Visita</label>
                        <input type="date" class="form-control" id="fecha_visita" name="fecha_visita" required min="<?= $today ?>" <?php if ($min_date): ?> max="<?= $min_date ?>" <?php endif; ?>>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Agendar</button>
                </div>
            </form>
        </div>
    </div>
</div>