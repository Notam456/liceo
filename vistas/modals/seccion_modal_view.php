<?php if (!empty($row)): ?>
<div id="seccion-contenido-<?= (int)$row['id_seccion'] ?>" class="container-fluid float-md-end">
    <div class="row mb-3">
        <div class="col-md-6">
            <strong>Sección:</strong> <?= htmlspecialchars($row['numero_anio']) . '° ' . htmlspecialchars($row['letra']) ?>
        </div>
        <div class="col-md-6">
            <strong>Tutor:</strong>
            <?php if (!empty($row['nombre_tutor'])): ?>
                <?= htmlspecialchars($row['nombre_tutor']) . ' ' . htmlspecialchars($row['apellido_tutor']) ?>
            <?php else: ?>
                <span class="text-muted">No asignado</span>
            <?php endif; ?>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-12 d-flex gap-2">
            <!-- <a class="btn btn-primary" href="/liceo/controladores/horario_controlador.php?secc= $row['id_seccion']" role="button">Crear/modificar Horario</a> -->
            <button class="btn btn-success" onclick="abrirAsignacionEstudiantes(<?= (int)$row['id_seccion'] ?>, '<?= $row['numero_anio'] ?>° <?= $row['letra'] ?>')">Asignar Estudiantes</button>
        </div>
    </div>
    <hr>
    <div class="row mb-2">
        <div class="col-md-6">
            <label class="form-label">Buscar en listado</label>
            <input type="text" class="form-control" id="filtroEstudiantes-<?= (int)$row['id_seccion'] ?>" placeholder="Filtrar por nombre o apellido...">
        </div>
    </div>
    <h6>Listado de estudiantes</h6>
    <?php if ($estudiantes && mysqli_num_rows($estudiantes) > 0): ?>
        <div class="table-responsive" style="max-height: 360px; overflow-y: auto;">
            <table class="table table-sm table-striped" id="tablaEstudiantes-<?= (int)$row['id_seccion'] ?>">
                <thead>
                    <tr>
                        <th>Apellidos</th>
                        <th>Nombres</th>
                        <th>C.I</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($estu = mysqli_fetch_assoc($estudiantes)): ?>
                        <tr>
                            <td><?= htmlspecialchars($estu['apellido']) ?></td>
                            <td><?= htmlspecialchars($estu['nombre']) ?></td>
                            <td><?= htmlspecialchars($estu['cedula']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <script>
            (function() {
                var input = document.getElementById('filtroEstudiantes-<?= (int)$row['id_seccion'] ?>');
                var table = document.getElementById('tablaEstudiantes-<?= (int)$row['id_seccion'] ?>');
                if (!input || !table) return;
                input.addEventListener('keyup', function() {
                    var term = this.value.toLowerCase();
                    var rows = table.querySelectorAll('tbody tr');
                    rows.forEach(function(tr) {
                        var apellido = tr.cells[0].textContent.toLowerCase();
                        var nombre = tr.cells[1].textContent.toLowerCase();
                        var ci = tr.cells[2].textContent.toLowerCase();
                        var match = apellido.indexOf(term) !== -1 || nombre.indexOf(term) !== -1 || ci.indexOf(term) !== -1;
                        tr.style.display = match ? '' : 'none';
                    });
                });
            })();
        </script>
    <?php else: ?>
        <p class="text-muted">No hay estudiantes asignados a esta sección.</p>
    <?php endif; ?>
</div>
<?php else: ?>
<div class="alert alert-warning" role="alert">
    <h4>No se han encontrado datos de la sección.</h4>
</div>
<?php endif; ?>
