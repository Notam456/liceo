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
                </div>
                <hr>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Cargos que ejerce:</strong>
                        <?php if (!empty($cargos)): ?>
                            <ul>
                                <?php foreach ($cargos as $cargo): ?>
                                    <li><?= htmlspecialchars($cargo['nombre']) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p>No tiene cargos asignados.</p>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <strong>Materias que imparte:</strong>
                        <?php if (!empty($materias)): ?>
                            <ul>
                                <?php foreach ($materias as $materia): ?>
                                    <li><?= htmlspecialchars($materia['nombre']) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p>No tiene materias asignadas.</p>
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
                    <h4>No se han encontrado datos del profesor.</h4>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>