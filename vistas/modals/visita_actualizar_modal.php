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
    <?php if ($action == 'realizada'): ?>
    <div class="mb-3">
        <label for="fecha_realizada" class="form-label">Fecha de Realización</label>
        <input type="text" class="form-control" id="fecha_realizada" name="fecha_realizada" value="<?php echo htmlspecialchars($row['fecha_visita']); ?>" required readonly>
    </div>
    <?php else: ?>
        <input type="hidden" name="fecha_realizada" value="<?php echo date('Y-m-d'); ?>">
    <?php endif; ?>
    <button type="submit" class="btn <?php if ($action=='realizada') { ?> btn-success <?php } else {?> btn-danger <?php } ?> float-end">Guardar datos</button>
</form>

<script>
    $(document).ready(function() {
        if (typeof Pikaday !== 'undefined') {
            var pikadayConfig = {
                format: 'YYYY-MM-DD',
                i18n: {
                    previousMonth: 'Mes anterior',
                    nextMonth: 'Siguiente mes',
                    months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                    weekdays: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                    weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb']
                },
                yearRange: [1950, new Date().getFullYear()],
                minDate: new Date(new Date().setHours(0, 0, 0, 0)),
                maxDate: new Date('<?php echo isset($anio_hasta) ? $anio_hasta : date("Y-m-d"); ?>'),
                showDaysInNextAndPreviousMonths: true
            };

            var pickerRealizada = new Pikaday(
                Object.assign({}, pikadayConfig, {
                    field: document.getElementById('fecha_realizada'),
                    onSelect: function() {
                        document.getElementById('fecha_realizada').setCustomValidity('');
                    }
                })
            );
        } else {
            console.error('Pikaday no está cargado');
        }

        $('#updateVisitaForm').on('submit', function(event) {
            var fechaRealizada = $('#fecha_realizada');
            if (fechaRealizada.length && fechaRealizada.val().trim() === '') {
                fechaRealizada[0].setCustomValidity('Este campo es obligatorio');
            } else if (fechaRealizada.length) {
                fechaRealizada[0].setCustomValidity('');
            }

            if (this.checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
                var firstInvalidField = this.querySelector(':invalid');
                if (firstInvalidField) {
                    firstInvalidField.reportValidity();
                }
            }

            $(this).addClass('was-validated');
        });
    });
</script>