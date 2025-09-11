<?php if (!empty($row)): ?>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div class="container-fluid float-md-end">
                    <!-- jose yajure, AÑADIR NUEVA ROW CADA DOS DATOS!! -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Usuario:</strong> <?= htmlspecialchars($row['usuario']) ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Contraseña:</strong> <?= htmlspecialchars($row['contrasena']) ?>
                        </div>
                    </div>
                    <!-- jose yajure, AÑADIR NUEVA ROW CADA DOS DATOS!! -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Rol:</strong> <?= htmlspecialchars($row['rol']) ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Profesor a quien pertenece:</strong> <?php if (!empty($row['id_profesor'])) {
                                                                                echo htmlspecialchars($row['nombre']) . ' ' . htmlspecialchars($row['apellido']);
                                                                            } else {
                                                                                echo "Administrador";
                                                                            }  ?>
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
                    <h4>No se han encontrado datos del usuario.</h4>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>