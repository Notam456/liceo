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
                minDate: new Date(new Date().setHours(0, 0, 0, 0)),
                maxDate: new Date('<?= $anio_hasta ?>'),
                showDaysInNextAndPreviousMonths: true,

                toString: function(date) {
                    var day = date.getDate();
                    var month = date.getMonth();
                    var year = date.getFullYear();
                    
                    // Usar los meses traducidos del i18n
                    var monthName = this.i18n.months[month];
                    
                    return day + ' de ' + monthName + ' de ' + year;
                }
            };

                // Inicializar Pikaday para el modal de CREAR
                var pickerCrear = new Pikaday(
                    // Fusiona un objeto vacío, pikadayConfig, y el objeto con la propiedad 'field'
                    Object.assign({}, pikadayConfig, {
                        field: document.getElementById('fecha_realizada')
                    })
                );

                // Mostrar datepicker cuando se abra el modal de CREAR
                $('#insertdata').on('shown.bs.modal', function() {
                    if (pickerCrear) {
                        pickerCrear.show();
                    }
                });

                // Mostrar datepicker cuando se abra el modal de EDITAR
                $('#editmodal').on('shown.bs.modal', function() {
                    if (pickerEditar) {
                        pickerEditar.show();
                    }
                });

                // Cuando se cargan datos en el modal de editar, establecer la fecha en Pikaday
                $(document).on('ajaxComplete', function(event, xhr, settings) {
                    if (settings.url === '/liceo/controladores/estudiante_controlador.php' &&
                        settings.data.includes("action=editar")) {

                        // Esperar un momento para que los datos se carguen en los campos
                        setTimeout(function() {
                            var fechaInput = document.getElementById('fecha_nacimiento_edit');
                            var fechaPicker = document.getElementById('fecha_nacimiento_edit_picker');

                            if (fechaInput && fechaInput.value && pickerEditar) {
                                // Convertir fecha de YYYY-MM-DD a DD-MM-YYYY para Pikaday
                                var fechaParts = fechaInput.value.split('-');
                                if (fechaParts.length === 3) {
                                    var fechaFormateada = fechaParts[2] + '-' + fechaParts[1] + '-' + fechaParts[0];
                                    fechaPicker.value = fechaFormateada;
                                    pickerEditar.setDate(new Date(fechaInput.value));
                                }
                            }
                        }, 100);
                    }
                });

            } else {
                console.error('Pikaday no está cargado');
            }
        });
    </script>