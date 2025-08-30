
<!DOCTYPE html>
<html lang="es">
<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/head.php'); ?>
    <title>Constructor de horarios</title>
    <style>
        .col-hora { white-space: nowrap; width: 1%; }
        .table td, .table th { vertical-align: middle; text-align: center; }
        table { table-layout: auto; width: 100%; }
    </style>
</head>
<body>
    <nav><?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/navbar.php'); ?></nav>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/sidebar.php'); ?>

    <div class="container" style="margin-top: 30px;">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card mb-5">
                    <div class="card-header">
                        <h4>Constructor de horario</h4>
                    </div>
                    <div class="card-body">
                        <form id="form-horario" class="row g-2 mb-4">
                            <div class="col-md-3">
                                <label for="materia" class="form-label">Materia</label>
                                <select name="materia" id="materia" class="form-select">
                                    <option value="">Seleccione...</option>
                                    <?php foreach ($materias as $mat): ?>
                                        <option value="<?= htmlspecialchars($mat['id_materia']) ?>"><?= htmlspecialchars($mat['nombre_materia']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="profesor" class="form-label">Profesor</label>
                                <select name="profesor" id="profesor" class="form-select">
                                    <option value="">Seleccione...</option>
                                    <?php foreach ($profesores as $prof): ?>
                                        <option value="<?= htmlspecialchars($prof['id_profesores']) ?>"><?= htmlspecialchars($prof['nombre_profesor']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="dia" class="form-label">Día</label>
                                <select name="dia" id="dia" class="form-select">
                                    <option value="">Seleccione...</option>
                                    <?php foreach ($dias as $index => $dia): ?>
                                        <option value="<?= $index ?>"><?= htmlspecialchars($dia) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="inicio" class="form-label">Desde</label>
                                <select name="inicio" id="inicio" class="form-select">
                                    <option value="">Seleccione...</option>
                                    <?php foreach ($horas as $i => $hora): ?>
                                        <option value="<?= $i ?>"><?= htmlspecialchars($hora) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="fin" class="form-label">Hasta</label>
                                <select name="fin" id="fin" class="form-select">
                                    <option value="">Seleccione...</option>
                                    <?php foreach ($horas as $i => $hora): ?>
                                        <option value="<?= $i ?>"><?= htmlspecialchars($hora) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-12 text-end">
                                <button type="button" id="btn-agregar" class="btn btn-success mt-2">Agregar al horario</button>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-bordered text-center table-striped mb-5" id="tabla-horario">
                                <thead class="table-secondary">
                                    <tr>
                                        <th scope="col">Hora</th>
                                        <?php foreach ($dias as $dia): ?>
                                            <th><?= htmlspecialchars($dia) ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($horas as $i => $hora): ?>
                                        <tr>
                                            <td width='18%'><strong><?= htmlspecialchars($hora) ?></strong></td>
                                            <?php foreach ($dias as $j => $dia): ?>
                                                <td data-dia="<?= $j ?>" data-hora="<?= $i ?>"></td>
                                            <?php endforeach; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <button onclick="window.location.href='/liceo/controladores/seccion_controlador.php'" class="btn btn-primary mb-2">Regresar a Secciones</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    const horarioCargado = <?= json_encode($horario_existente) ?>;
    const seccionId = <?= json_encode($seccion_id) ?>;

    document.addEventListener('DOMContentLoaded', function () {
        const diasMap = { 'lunes': 0, 'martes': 1, 'miércoles': 2, 'jueves': 3, 'viernes': 4 };
        const horaInicioMap = { "07:20:00": 0, "08:10:00": 1, "08:50:00": 2, "09:05:00": 3, "09:45:00": 4, "10:25:00": 5, "10:30:00": 6, "11:45:00": 7, "12:10:00": 8, "12:50:00": 9 };
        const horaFinMap = { "08:10:00": 0, "08:50:00": 1, "09:05:00": 2, "09:45:00": 3, "10:25:00": 4, "10:30:00": 5, "11:45:00": 6, "12:10:00": 7, "12:50:00": 8, "13:30:00": 9 };

        horarioCargado.forEach(item => {
            const diaIdx = diasMap[item.dia.toLowerCase()];
            const horaInicioIdx = horaInicioMap[item.hora_inicio];
            const horaFinIdx = horaFinMap[item.hora_fin];

            if (horaInicioIdx !== undefined && horaFinIdx !== undefined && diaIdx !== undefined) {
                for (let h = horaInicioIdx; h <= horaFinIdx; h++) {
                    const cell = document.querySelector(`td[data-dia="${diaIdx}"][data-hora="${h}"]`);
                    if (cell) {
                        cell.innerHTML = `
                            <div class="small fw-bold text-primary">${item.nombre_materia}</div>
                            <div class="small text-muted">${item.nombre_profesor}</div>
                            <button class="btn btn-sm btn-danger btn-eliminar mt-1" data-dia="${diaIdx}" data-hora="${h}" title="Eliminar bloque">
                                <i class="bi bi-trash3"></i>
                            </button>`;
                    }
                }
            }
        });
    });

    document.getElementById('btn-agregar').addEventListener('click', function () {
        const materia = document.getElementById('materia');
        const profesor = document.getElementById('profesor');
        const diaSelect = document.getElementById('dia');
        const inicio = parseInt(document.getElementById('inicio').value);
        const fin = parseInt(document.getElementById('fin').value);

        if (!materia.value || !profesor.value || isNaN(inicio) || isNaN(fin) || !diaSelect.value) {
            alert('Por favor complete todos los campos correctamente.');
            return;
        }
        if (fin < inicio) {
            alert('La hora de fin debe ser después de la de inicio.');
            return;
        }

        for (let h = inicio; h <= fin; h++) {
            const cell = document.querySelector(`td[data-dia="${diaSelect.value}"][data-hora="${h}"]`);
            if (cell.innerHTML.trim() !== '') {
                alert(`Ya hay una clase asignada en estas horas. Para modificarla, elimine la existente`);
                return;
            }
        }

        for (let h = inicio; h <= fin; h++) {
            $.ajax({
                type: "POST",
                url: "/liceo/controladores/horario_controlador.php",
                data: {
                    'action': 'guardar',
                    'materia': materia.value,
                    'profesor': profesor.value,
                    'seccion': seccionId,
                    'dia': diaSelect.options[diaSelect.selectedIndex].text,
                    'inicio': h,
                    'fin': h
                },
                success: function (response) {
                    console.log(response);
                    const cell = document.querySelector(`td[data-dia="${diaSelect.value}"][data-hora="${h}"]`);
                    cell.innerHTML = `
                        <div class="small fw-bold text-primary">${materia.options[materia.selectedIndex].text}</div>
                        <div class="small text-muted">${profesor.options[profesor.selectedIndex].text}</div>
                        <button class="btn btn-sm btn-danger btn-eliminar mt-1" data-dia="${diaSelect.value}" data-hora="${h}" title="Eliminar bloque">
                            <i class="bi bi-trash3"></i>
                        </button>`;
                }
            });
        }
    });

    document.addEventListener('click', function (e) {
        const botonEliminar = e.target.closest('.btn-eliminar');
        if (botonEliminar) {
            const dia = botonEliminar.dataset.dia;
            const hora = botonEliminar.dataset.hora;

            $.ajax({
                type: 'POST',
                url: '/liceo/controladores/horario_controlador.php',
                data: {
                    action: 'eliminar',
                    seccion: seccionId,
                    dia: ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'][parseInt(dia)],
                    hora: hora
                },
                success: function (res) {
                    console.log("Eliminado:", res);
                    const td = document.querySelector(`td[data-dia="${dia}"][data-hora="${hora}"]`);
                    if (td) td.innerHTML = '';
                },
                error: function () {
                    alert("Ocurrió un error al eliminar el bloque.");
                }
            });
        }
    });
    </script>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/footer.php') ?>
    </footer>
</body>
</html>
