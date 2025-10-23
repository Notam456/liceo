<?php if (!empty($row)): ?>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-body">
            <div class="container-fluid float-md-end">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Fecha Asignación:</strong> <?= date('d/m/Y H:i', strtotime($row['fecha_asignacion'])) ?>
                    </div>
                </div>
                <hr>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Profesor:</strong> <?= htmlspecialchars($row['apellido'] . ', ' . $row['nombre']) ?>
                    </div>
                    <div class="col-md-6">
                        <strong>Cédula:</strong> <?= htmlspecialchars($row['cedula']) ?>
                    </div>
                </div>
                <hr>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Materia Asignada:</strong> <?= htmlspecialchars($row['nombre_materia']) ?>
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
                    <h4>No se han encontrado datos de la asignación.</h4>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
