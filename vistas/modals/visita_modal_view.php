

<div class="modal" id="visitaModal" tabindex="-1" aria-labelledby="visitaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="visitaModalLabel">Agendar Visita</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/liceo/controladores/visita_controlador.php" method="POST">
                <input type="hidden" name="action" value="crear">
                <input type="hidden" name="id_estudiante_visita" id="id_estudiante_visita">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="fecha_visita">Fecha de la Visita</label>
                        <input type="date" class="form-control" id="fecha_visita" name="fecha_visita" placeholder="AAAA-MM-DD" required readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Agendar</button>
                </div>
            </form>
        </div>
    </div>
</div>
