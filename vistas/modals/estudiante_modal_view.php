<?php if (!empty($row)): ?>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-body">
            <div class="container-fluid float-md-end">
                <!-- jose yajure, AÑADIR NUEVA ROW CADA DOS DATOS!! -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Nombres:</strong> <?= htmlspecialchars($row['nombre']) ?>
                    </div>
                    <div class="col-md-6">
                        <strong>Apellidos:</strong> <?= htmlspecialchars($row['apellido']) ?>
                    </div>
                </div>
                <!-- jose yajure, AÑADIR NUEVA ROW CADA DOS DATOS!! -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Cédula:</strong> <?= htmlspecialchars($row['cedula']) ?>
                    </div>
                    <div class="col-md-6">
                        <strong>Teléfono:</strong> <?= htmlspecialchars($row['contacto']) ?>
                    </div>
                </div>
                <!-- jose yajure, AÑADIR NUEVA ROW CADA DOS DATOS!! -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <strong>Dirección:</strong> <?= htmlspecialchars($row['municipio']) ?>, <?= htmlspecialchars($row['parroquia']) ?>, <?= htmlspecialchars($row['sector']) ?>, <?= htmlspecialchars($row['punto_referencia']) ?>, <?= htmlspecialchars($row['direccion_exacta']) ?>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Fecha de Nacimiento:</strong> <?= $row['fecha_nacimiento']?>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Sección:</strong> 
                            <?php if (!empty($row['numero_anio_seccion']) && !empty($row['letra'])): ?>
                                <?= htmlspecialchars($row['numero_anio_seccion'])."°".htmlspecialchars($row['letra']); ?>
                            <?php else: ?>
                                <?= htmlspecialchars($row['numero_anio'])."° año (Sin sección asignada)" ?>
                            <?php endif; ?>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>
<?php else: ?>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Error</h5>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning" role="alert">
                    <h4>No se han encontrado datos del estudiante.</h4>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>