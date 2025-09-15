<?php
if (!isset($reporte)) {
    header("Location: /liceo/error.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/head.php'); ?>
    <title>Reporte de Ausencias</title>
    <style>
        .card-alumno {
            margin-bottom: 10px;
            transition: all 0.3s;
        }

        .card-alumno.alert {
            border-left: 5px solid #dc3545;
            background-color: #fff8f8;
        }

        .badge-ausencias {
            font-size: 1rem;
        }

        .filtros {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .tabla-ausencias {
            margin-top: 10px;
        }

        .tabla-ausencias th {
            white-space: nowrap;
        }

        .resumen {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 10px;
        }

        .resumen .stat {
            background: #f8f9fa;
            border-radius: 6px;
            padding: 8px 12px;
        }
    </style>
</head>

<body>
    <nav>
        <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/navbar.php') ?>
    </nav>

    <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/sidebar.php') ?>

    <div class="container" style="margin-top: 30px;">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <?php if (isset($_SESSION['status']) && $_SESSION['status'] != '') : ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Hey!</strong> <?php echo $_SESSION['status']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['status']); ?>
                <?php endif; ?>

                <div class="card">
                    <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
                        <h4 class="mb-0"><i class="bi bi-clipboard2-pulse"></i> Reporte de Ausencias</h4>
                        <span class="small">Período actual</span>
                    </div>
                    <div class="card-body">
                        <?php
                        $totalEstudiantes = count($reporte);
                        $totalAusencias = array_sum(array_map(fn($i) => $i['ausencias'], $reporte));
                        $totalJustificados = array_sum(array_map(fn($i) => $i['justificadas'], $reporte));
                        ?>
                        <div class="resumen">
                            <div class="stat">
                                Total estudiantes: <span class="badge bg-secondary"><?php echo $totalEstudiantes; ?></span>
                            </div>
                            <div class="stat">
                                Ausencias: <span class="badge bg-danger"><?php echo $totalAusencias; ?></span>
                            </div>
                            <div class="stat">
                                Justificados: <span class="badge bg-warning text-dark"><?php echo $totalJustificados; ?></span>
                            </div>
                        </div>

                        <div class="filtros">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="filtroCedula" class="form-label">Buscar por cédula:</label>
                                    <input type="text" class="form-control" id="filtroCedula" placeholder="Ej: 30426270">
                                </div>
                                <div class="col-md-3">
                                    <label for="filtroDesde" class="form-label">Desde:</label>
                                    <input type="date" class="form-control" id="filtroDesde" value="<?= $anio_desde ?>" min="<?= $anio_desde ?>" max="<?= $anio_hasta ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="filtroHasta" class="form-label">Hasta:</label>
                                    <input type="date" class="form-control" id="filtroHasta" value="<?= $anio_hasta ?>" min="<?= $anio_desde ?>" max="<?= $anio_hasta ?>">
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button class="btn btn-secondary" id="limpiarFiltros">Limpiar</button>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-danger" id="alert-ausencias" style="display: none;">
                            <h6 class="mb-1"><i class="bi bi-exclamation-triangle-fill"></i> Estudiantes con 3 o más ausencias</h6>
                            <div id="lista-alertas" class="mt-2"></div>
                        </div>

                        <div class="table-responsive tabla-ausencias">
                            <table class="table table-striped table-hover align-middle" id="tablaReportes">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Estudiante</th>
                                        <th>Sección</th>
                                        <th>Contacto</th>
                                        <th>Cédula</th>
                                        <th title="Inasistencias"><i class="bi bi-person-dash-fill"></i> Ausencias</th>
                                        <th title="Justificados"><i class="bi bi-journal-check"></i> Justificados</th>
                                        <th>Total</th>
                                        <th>Acción</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($reporte as $item): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($item['nombre']) ?></td>
                                            <td><?= htmlspecialchars($item['seccion']) ?></td>
                                            <td><?= htmlspecialchars($item['contacto']) ?></td>
                                            <td><?= htmlspecialchars($item['cedula']) ?></td>
                                            <td><span class="badge bg-danger" title="Ausencias no justificadas"><?= $item['ausencias'] ?></span></td>
                                            <td><span class="badge bg-warning text-dark" title="Ausencias justificadas"><?= $item['justificadas'] ?></span></td>
                                            <td>
                                                <span class="badge <?= $item['total_ultima_semana'] >= 3 ? 'bg-danger' : 'bg-secondary' ?>" title="Total de inasistencias (A + J)">
                                                    <?= $item['total'] ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($item['total_ultima_semana'] >= 3): ?>
                                                    <?php if ($item['tiene_visita_agendada']): ?>
                                                        <button type="button" class="btn btn-secondary btn-sm" disabled>Visita Agendada</button>
                                                    <?php else: ?>
                                                        <button type="button" class="btn btn-primary btn-sm schedule-visit" data-bs-toggle="modal" data-bs-target="#visitaModal" data-id-estudiante="<?= $item['id_estudiante'] ?>">Agendar Visita</button>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </td>

                                            <td>
                                                <?php if ($item['total'] > 0): ?>
                                                    <a href="/liceo/controladores/reporte_controlador.php?action=generar_reporte_ausencias&id_estudiante=<?= $item['id_estudiante'] ?>&desde=<?= $anio_desde ?>&hasta=<?= $anio_hasta ?>"
                                                        class="btn btn-info btn-sm" target="_blank">
                                                        Generar Reporte
                                                    </a>
                                                <?php endif; ?>
                                            </td>

                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/modals/visita_modal_view.php'); ?>

    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/footer.php') ?>
    </footer>

    <script>
        $(document).ready(function() {
            var table = $('#tablaReportes').DataTable({
                order: [
                    [6, 'desc']
                ],
                dom: '<"top"f>rt<"bottom"lip><"clear">',
                language: {
                    search: 'Buscar:',
                    lengthMenu: 'Mostrar _MENU_ registros',
                    info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
                    infoEmpty: 'Mostrando 0 a 0 de 0 registros',
                    paginate: {
                        first: 'Primero',
                        last: 'Último',
                        next: 'Siguiente',
                        previous: 'Anterior'
                    }
                }
            });

            $('#filtroCedula').keyup(function() {
                table.column(3).search(this.value).draw();
            });

            var alertas = <?= json_encode(array_filter($reporte, function ($item) {
                                return $item['total_ultima_semana'] >= 3 && !$item['tiene_visita_agendada'];
                            })) ?>;
            if (alertas.length > 0) {
                $('#alert-ausencias').show();
                $('#lista-alertas').html(
                    alertas.map(item =>
                        `<div class="card-alumno alert d-flex justify-content-between align-items-center">
                        <div>
                            ${item.nombre} (${item.cedula}) -
                            <span class="badge bg-danger">${item.total_ultima_semana} ausencias en la última semana</span>
                        </div>
                        ${item.tiene_visita_agendada
                            ? `<button type="button" class="btn btn-secondary btn-sm" disabled>Visita Agendada</button>`
                            : `<button type="button" class="btn btn-primary btn-sm schedule-visit" data-bs-toggle="modal" data-bs-target="#visitaModal" data-id-estudiante="${item.id_estudiante}">Agendar Visita</button>`
                        }
                    </div>`
                    ).join('')
                );
            }

            $(document).on('click', '.schedule-visit', function() {
                var studentId = $(this).data('id-estudiante');
                $('#visitaModal #id_estudiante_visita').val(studentId);
            });

            function cargarReporte() {
                var actionButton = '';
                if (item.total > 0) {
                    actionButton += '<a href="/liceo/controladores/reporte_controlador.php?action=generar_reporte_ausencias&id_estudiante=' + item.id_estudiante + '&desde=' + desde + '&hasta=' + hasta + '" class="btn btn-info btn-sm" target="_blank">Generar Reporte</a> ';
                }

                if (item.total_ultima_semana >= 3) {
                    if (item.tiene_visita_agendada) {
                        actionButton += '<button type="button" class="btn btn-secondary btn-sm" disabled>Visita Agendada</button>';
                    } else {
                        actionButton += '<button type="button" class="btn btn-primary btn-sm schedule-visit" data-bs-toggle="modal" data-bs-target="#visitaModal" data-id-estudiante="' + item.id_estudiante + '">Agendar Visita</button>';
                    }
                }

                var url = `/liceo/controladores/reporte_controlador.php?desde=${desde}&hasta=${hasta}`;

                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            var table = $('#tablaReportes').DataTable();
                            table.clear();

                            var alertas = [];

                            response.data.forEach(function(item) {
                                if (item.total_ultima_semana >= 3 && !item.tiene_visita_agendada) {
                                    alertas.push(item);
                                }

                                var actionButton = '';
                                if (item.total_ultima_semana >= 3) {
                                    if (item.tiene_visita_agendada) {
                                        actionButton = '<button type="button" class="btn btn-secondary btn-sm" disabled>Visita Agendada</button>';
                                    } else {
                                        actionButton = '<button type="button" class="btn btn-primary btn-sm schedule-visit" data-bs-toggle="modal" data-bs-target="#visitaModal" data-id-estudiante="' + item.id_estudiante + '">Agendar Visita</button>';
                                    }
                                }

                                table.row.add([
                                    item.nombre,
                                    item.seccion,
                                    item.contacto,
                                    item.cedula,
                                    '<span class="badge bg-danger">' + item.ausencias + '</span>',
                                    '<span class="badge bg-warning text-dark">' + item.justificadas + '</span>',
                                    '<span class="badge ' + (item.total_ultima_semana >= 3 ? 'bg-danger' : 'bg-secondary') + '">' + item.total + '</span>',
                                    actionButton
                                ]).draw(false);
                            });

                            if (alertas.length > 0) {
                                $('#alert-ausencias').show();
                                $('#lista-alertas').html(
                                    alertas.map(item =>
                                        `<div class="card-alumno alert d-flex justify-content-between align-items-center">
                                        <div>
                                            ${item.nombre} (${item.cedula}) -
                                            <span class="badge bg-danger">${item.total_ultima_semana} ausencias en la última semana</span>
                                        </div>
                                        ${item.tiene_visita_agendada
                                            ? `<button type="button" class="btn btn-secondary btn-sm" disabled>Visita Agendada</button>`
                                            : `<button type="button" class="btn btn-primary btn-sm schedule-visit" data-bs-toggle="modal" data-bs-target="#visitaModal" data-id-estudiante="${item.id_estudiante}">Agendar Visita</button>`
                                        }
                                    </div>`
                                    ).join('')
                                );
                            } else {
                                $('#alert-ausencias').hide();
                            }
                        } else {
                            console.error('Error fetching report data:', response.error);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX error:', status, error);
                    }
                });
            }

            $('#filtroDesde, #filtroHasta').change(cargarReporte);

            $('#limpiarFiltros').click(function() {
                $('#filtroDesde').val('<?= $anio_desde ?>');
                $('#filtroHasta').val('<?= $anio_hasta ?>');
                cargarReporte();
            });
        });
    </script>
</body>

</html>