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
        <input type="text" class="form-control" id="fecha_realizada" name="fecha_realizada" placeholder="AAAA-MM-DD" value="<?php echo htmlspecialchars($row['fecha_visita']); ?>" required readonly>
    </div>
    <?php else: ?>
        <input type="hidden" name="fecha_realizada" value="<?php echo date('Y-m-d'); ?>">
    <?php endif; ?>
    <button type="submit" id="submitButton" class="btn <?php if ($action=='realizada') { ?> btn-success <?php } else {?> btn-danger <?php } ?> float-end">Guardar datos</button>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var btn = document.getElementById('submitButton');
        if (btn) {
            btn.addEventListener('click', function(event) {
                var fechaRealizada = document.getElementById('fecha_realizada');
                if (fechaRealizada && fechaRealizada.value.trim() === '') {
                    event.preventDefault();
                    fechaRealizada.setCustomValidity('Este campo es obligatorio');
                    fechaRealizada.readOnly = false;
                    pickerCrear.show();
                    fechaRealizada.readOnly = true;
                } else if (fechaRealizada) {
                    fechaRealizada.setCustomValidity('');
                }
            });
        }
    });

        $(document).ready(function() {
            // Verificar que Pikaday esté cargado
            if (typeof Pikaday !== 'undefined') {
                console.log('Pikaday cargado correctamente');

                // Configuración común para ambos datepickers
                var pikadayConfig = {
                format: 'YYYY-MM-DD',
                    toString: function(date, format) {
                        // Forzar el formato YYYY-MM-DD
                        var year = date.getFullYear();
                        var month = ('0' + (date.getMonth() + 1)).slice(-2);
                        var day = ('0' + date.getDate()).slice(-2);
                        return year + '-' + month + '-' + day;
                    },
                    parse: function(dateString, format) {
                        // Parsear desde YYYY-MM-DD
                        var parts = dateString.split('-');
                        if (parts.length === 3) {
                            return new Date(parts[0], parts[1] - 1, parts[2]);
                        }
                        return new Date(dateString);
                    },
                    i18n: {
                        previousMonth: 'Mes anterior',
                        nextMonth: 'Siguiente mes',
                        months: [
                            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
                        ],
                        weekdays: [
                            'Domingo', 'Lunes', 'Martes', 'Miércoles',
                            'Jueves', 'Viernes', 'Sábado'
                        ],
                        weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb']
                    },
                    yearRange: [1950, new Date().getFullYear()],
                    minDate: new Date('<?= $anio_desde ?>'),
                    maxDate: new Date('<?= $anio_hasta ?>'),
                    showDaysInNextAndPreviousMonths: true,
                };

                // Inicializar Pikaday para el modal de CREAR
                var pickerCrear = new Pikaday(
                    // Fusiona un objeto vacío, pikadayConfig, y el objeto con la propiedad 'field'
                    Object.assign({}, pikadayConfig, {
                        field: document.getElementById('fecha_realizada'),
                        onSelect: function() {
                            document.getElementById('fecha_realizada').setCustomValidity('');
                        }
                    })
                );

                // Mostrar datepicker cuando se abra el modal de CREAR
                $('#insertdata').on('shown.bs.modal', function() {
                    if (pickerCrear) {
                        pickerCrear.show();
                    }
                });


                

            } else {
                console.error('Pikaday no está cargado');
            }
        });
    </script>