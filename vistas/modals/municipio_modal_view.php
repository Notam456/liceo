<?php if (!empty($row)): ?>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-body">
            <div class="container-fluid float-md-end">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Municipio:</strong> <?= htmlspecialchars($row['municipio']) ?>
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
                    <h4>No se han encontrado datos del municipio.</h4>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
