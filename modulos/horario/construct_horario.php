<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "liceo");

// Conseguir sección actual
if (isset($_GET['secc'])) {
    $seccion = $_GET['secc'];
    $query_horario = "SELECT h.*, m.nombre_materia, p.nombre_profesores 
                  FROM horario h
                  JOIN materia m ON h.id_materia = m.id_materia
                  JOIN profesores p ON h.id_profesores = p.id_profesores
                  WHERE h.id_seccion = $seccion";

    $result_horario = mysqli_query($conn, $query_horario);
    $horario_existente = [];

    while ($row = mysqli_fetch_assoc($result_horario)) {
        $horario_existente[] = $row;
    }
} else {
    echo '<script language="javascript">';
    echo 'alert("Por favor, seleccione una sección.");';
    echo 'window.location.href = "../secciones/crud_secciones.php"';
    echo '</script>';
}

// Conseguir lista de materias
$query_materias = "SELECT * FROM materia";
$result_materias = mysqli_query($conn, $query_materias);
$materias = [];
while ($row = mysqli_fetch_assoc($result_materias)) {
    $materias[] = $row;
}

// Conseguir lista de profesores
$query_profesores = "SELECT * FROM profesores";
$result_profesores = mysqli_query($conn, $query_profesores);
$profesores = [];
while ($row = mysqli_fetch_assoc($result_profesores)) {
    $profesores[] = $row;
}

// Definir horas y días
$horas = [
    "7:20am - 8:10am",
    "8:10am - 8:50am",
    "8:50am - 9:05am",
    "9:05am - 9:45am",
    "9:45am - 10:25am",
    "10:25am - 10:30am",
    "10:30am - 11:45am",
    "11:45am - 12:10am",
    "12:10am - 12:50am",
    "12:50am - 1:30am"
];

$dias = ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes"];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/head.php'); ?>
    <title>Constructor de horarios</title>
    <style>
        .col-hora {
            white-space: nowrap;
            width: 1%;
        }

        .table td,
        .table th {
            vertical-align: middle;
            text-align: center;
        }

        table {
            table-layout: auto;
            width: 100%;
        }
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
                        <h4>Constructor de horario
                        </h4>
                    </div>
                    <div class="card-body">


                        <form id="form-horario" class="row g-2 mb-4">
                            <div class="col-md-3">
                                <label for="materia" class="form-label">Materia</label>
                                <select name="materia" id="materia" class="form-select">
                                    <option value="">Seleccione...</option>
                                    <?php foreach ($materias as $mat): ?>
                                        <option value="<?= $mat['id_materia'] ?>"><?= $mat['nombre_materia'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="profesor" class="form-label">Profesor</label>
                                <select name="profesor" id="profesor" class="form-select">
                                    <option value="">Seleccione...</option>
                                    <?php foreach ($profesores as $prof): ?>
                                        <option value="<?= $prof['id_profesores'] ?>"><?= $prof['nombre_profesores'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="dia" class="form-label">Día</label>
                                <select name="dia" id="dia" class="form-select">
                                    <option value="">Seleccione...</option>
                                    <?php foreach ($dias as $index => $dia): ?>
                                        <option value="<?= $index ?>"><?= $dia ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="inicio" class="form-label">Desde</label>
                                <select name="inicio" id="inicio" class="form-select">
                                    <option value="">Seleccione...</option>
                                    <?php foreach ($horas as $i => $hora): ?>
                                        <option value="<?= $i ?>"><?= $hora ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="fin" class="form-label">Hasta</label>
                                <select name="fin" id="fin" class="form-select">
                                    <option value="">Seleccione...</option>
                                    <?php foreach ($horas as $i => $hora): ?>
                                        <option value="<?= $i ?>"><?= $hora ?></option>
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
                                            <th><?= $dia ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($horas as $i => $hora): ?>
                                        <tr>
                                            <td width='18%'><strong><?= $hora ?></strong></td>
                                            <?php foreach ($dias as $j => $dia): ?>
                                                <td data-dia="<?= $j ?>" data-hora="<?= $i ?>"></td>
                                            <?php endforeach; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>

<button onclick="volver()" class="btn btn-primary mb-2">Regresar</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    const horarioCargado = <?= json_encode($horario_existente) ?>;
    document.addEventListener('DOMContentLoaded', function () {
        horarioCargado.forEach(item => {
            const horaInicioIdx = {
                "07:20:00": 0, "08:10:00": 1, "08:50:00": 2, "09:05:00": 3,
                "09:45:00": 4, "10:25:00": 5, "10:30:00": 6, "11:45:00": 7,
                "12:10:00": 8, "12:50:00": 9
            }[item.hora_inicio];

            const horaFinIdx = {
                "08:10:00": 0, "08:50:00": 1, "09:05:00": 2, "09:45:00": 3,
                "10:25:00": 4, "10:30:00": 5, "11:45:00": 6, "12:10:00": 7,
                "12:50:00": 8, "13:30:00": 9
            }[item.hora_fin];

            const diasMap = {
                'lunes': 0, 'martes': 1, 'miércoles': 2, 'jueves': 3, 'viernes': 4
            };

            const diaIdx = diasMap[item.dia.toLowerCase()];

            if (horaInicioIdx !== undefined && horaFinIdx !== undefined && diaIdx !== undefined) {
                for (let h = horaInicioIdx; h <= horaFinIdx; h++) {
                    const cell = document.querySelector(`td[data-dia="${diaIdx}"][data-hora="${h}"]`);
                    if (cell) {
                        cell.innerHTML = `
                            <div class="small fw-bold text-primary">${item.nombre_materia}</div>
                            <div class="small text-muted">${item.nombre_profesores}</div>
                            <button class="btn btn-sm btn-danger btn-eliminar mt-1"
                                data-dia="${diaIdx}"
                                data-hora="${h}"
                                data-seccion="<?= $seccion ?>"
                                title="Eliminar bloque">
                                <i class="bi bi-trash3"></i>
                            </button>
                        `;
                    }
                }
            }
        });
    });

    document.getElementById('btn-agregar').addEventListener('click', function () {
        const materia = document.getElementById('materia');
        const profesor = document.getElementById('profesor');
        const dia = document.getElementById('dia');
        const inicio = parseInt(document.getElementById('inicio').value);
        const fin = parseInt(document.getElementById('fin').value);

        if (!materia.value || !profesor.value || isNaN(inicio) || isNaN(fin) || !dia.value) {
            alert('Por favor complete todos los campos correctamente.');
            return;
        }

        if (fin < inicio) {
            alert('La hora de fin debe ser después de la de inicio.');
            return;
        }

        for (let h = inicio; h <= fin; h++) {
            const cell = document.querySelector(`td[data-dia="${dia.value}"][data-hora="${h}"]`);
            if (cell.innerHTML.trim() !== '') {
                alert(`Ya hay una clase asignada en estas horas. Para modificarla, elimine la existente`);
                return;
            }
        }

        for (let h = inicio; h <= fin; h++) {
            const cell = document.querySelector(`td[data-dia="${dia.value}"][data-hora="${h}"]`);
            cell.innerHTML = `
                <div class="small fw-bold text-primary">${materia.options[materia.selectedIndex].text}</div>
                <div class="small text-muted">${profesor.options[profesor.selectedIndex].text}</div>
                <button class="btn btn-sm btn-danger btn-eliminar mt-1" title="Eliminar bloque"
                    data-dia="${dia.value}"
                    data-hora="${h}"
                    data-seccion="<?= $seccion ?>">
                    <i class="bi bi-trash3"></i>
                </button>
            `;

            $.ajax({
                type: "POST",
                url: "guardar_horario.php",
                data: {
                    'materia': materia.value,
                    'profesor': profesor.value,
                    'seccion': <?= $seccion ?>,
                    'dia': dia.options[dia.selectedIndex].text,
                    'inicio': h,
                    'fin': h
                },
                success: function (response) {
                    console.log(response)
                }
            });
        }
    });
    function volver() {
        window.location = '../secciones/crud_secciones.php';
    }
    document.addEventListener('click', function (e) {
        if (e.target.closest('.btn-eliminar')) {
            const boton = e.target.closest('.btn-eliminar');

            const dia = boton.dataset.dia;
            const hora = boton.dataset.hora;
            const seccion = boton.dataset.seccion;

           

            const td = document.querySelector(`td[data-dia="${dia}"][data-hora="${hora}"]`);
            if (td) td.innerHTML = '';

            $.ajax({
                type: 'POST',
                url: 'eliminar_horario.php',
                data: {
                    seccion: seccion,
                    dia: ['lunes', 'martes', 'miércoles', 'jueves', 'viernes'][parseInt(dia)],
                    hora: hora
                },
                success: function (res) {
                    console.log("Eliminado:", res);
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