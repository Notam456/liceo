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
                        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['status']); ?>
                <?php endif; ?>

                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h4 class="mb-0">Reporte de Ausencias <i class="bi bi-clipboard2-pulse"></i></h4>
                        <button class="btn btn-secondary btn-sm" id="generarReporteGeneral">
                            Reporte General por Sección
                        </button>
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
                                <div class="col-md-3">
                                    <label for="filtroSeccion" class="form-label">Filtrar por Sección:</label>
                                    <select class="form-select" id="filtroSeccion">
                                        <option value="">Todas las Secciones</option>
                                        <?php 
                                        if (isset($secciones) && is_array($secciones)):
                                            foreach ($secciones as $seccion): ?>
                                            <option value="<?= $seccion['id_seccion'] ?>">
                                                <?= htmlspecialchars($seccion['grado']) ?>
                                            </option>
                                        <?php endforeach; 
                                        endif; ?>
                                    </select>
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

                        <div class="table-responsive">
                            <table class="table table-striped" id="tablaReportes">
                                <thead>
                                    <tr class="table-secondary">
                                        <th style="display: none;">#</th>
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
                                            <td style="display: none;"><?= $item['id_estudiante'] ?></td>
                                            <td><?= htmlspecialchars($item['nombre']) ?></td>
                                            <td><?= htmlspecialchars($item['seccion']) ?></td>
                                            <td><?= htmlspecialchars($item['contacto']) ?></td>
                                            <td><?= htmlspecialchars($item['cedula']) ?></td>
                                            <td><span class="badge bg-danger" title="Ausencias no justificadas"><?= $item['ausencias'] ?></span></td>
                                            <td><span class="badge bg-warning text-dark" title="Ausencias justificadas"><?= $item['justificadas'] ?></span></td>
                                            <td>
                                                <span class="badge <?= $item['total'] >= 3 ? 'bg-danger' : 'bg-secondary' ?>" title="Total de inasistencias (A + J)">
                                                    <?= $item['total'] ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($item['total'] >= 3): ?>
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
                                                        class="btn btn-secondary btn-sm" target="_blank">
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
                [7, 'desc']
            ],
            language: {
                search: 'Buscar',
                info: 'Mostrando pagina _PAGE_ de _PAGES_',
                infoEmpty: 'No se han encontrado resultados',
                infoFiltered: '(se han encontrado _MAX_ resultados)',
                lengthMenu: 'Mostrar _MENU_ por pagina',
                zeroRecords: '0 resultados encontrados'
            },
            columnDefs: [{
                    width: '120px',
                    targets: [8, 9]
                },
                {
                    visible: false,
                    target: 0
                }
            ]
        });

        $('#filtroCedula').keyup(function() {
            table.column(4).search(this.value).draw();
        });

        var alertas = <?= json_encode(array_filter($reporte, function ($item) {
                            return $item['total'] >= 3 && !$item['tiene_visita_agendada'];
                        })) ?>;
        if (alertas.length > 0) {
            $('#alert-ausencias').show();
            $('#lista-alertas').html(
                alertas.map(item =>
                    `<div class="card-alumno alert d-flex justify-content-between align-items-center">
                    <div>
                        ${item.nombre} (${item.cedula}) -
                        <span class="badge bg-danger">${item.total} ausencias en el rango de tiempo especificado</span>
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
            var desde = $('#filtroDesde').val();
            var hasta = $('#filtroHasta').val();
            var id_seccion = $('#filtroSeccion').val();

            var url = `/liceo/controladores/reporte_controlador.php?desde=${desde}&hasta=${hasta}&id_seccion=${id_seccion}`;

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        var table = $('#tablaReportes').DataTable();
                        table.clear();

                        var alertas = [];
                        var totalEstudiantes = 0;
                        var totalAusencias = 0;
                        var totalJustificados = 0;

                        response.data.forEach(function(item) {
                            if (item.total >= 3 && !item.tiene_visita_agendada) {
                                alertas.push(item);
                            }

                            // Actualizar estadísticas
                            totalEstudiantes++;
                            totalAusencias += item.ausencias;
                            totalJustificados += item.justificadas;

                            var actionButton = '';
                            if (item.total >= 3) {
                                if (item.tiene_visita_agendada) {
                                    actionButton = '<button type="button" class="btn btn-secondary btn-sm" disabled>Visita Agendada</button>';
                                } else {
                                    actionButton = '<button type="button" class="btn btn-primary btn-sm schedule-visit" data-bs-toggle="modal" data-bs-target="#visitaModal" data-id-estudiante="' + item.id_estudiante + '">Agendar Visita</button>';
                                }
                            }

                            var reportButton = '';
                            if (item.total > 0) {
                                reportButton = '<a href="/liceo/controladores/reporte_controlador.php?action=generar_reporte_ausencias&id_estudiante=' + item.id_estudiante + '&desde=' + desde + '&hasta=' + hasta + '" class="btn btn-secondary btn-sm" target="_blank">Generar Reporte</a>';
                            }

                            // AGREGAR LA FILA CORRECTAMENTE CON TODAS LAS COLUMNAS
                            table.row.add([
                                item.id_estudiante, // Columna 0 - oculta
                                item.nombre,        // Columna 1
                                item.seccion,       // Columna 2
                                item.contacto,      // Columna 3
                                item.cedula,        // Columna 4
                                '<span class="badge bg-danger">' + item.ausencias + '</span>', // Columna 5
                                '<span class="badge bg-warning text-dark">' + item.justificadas + '</span>', // Columna 6
                                '<span class="badge ' + (item.total >= 3 ? 'bg-danger' : 'bg-secondary') + '">' + item.total + '</span>', // Columna 7
                                actionButton,       // Columna 8
                                reportButton        // Columna 9
                            ]);
                        });

                        table.draw();

                        // ACTUALIZAR ESTADÍSTICAS
                        $('.resumen .stat:nth-child(1) .badge').text(totalEstudiantes);
                        $('.resumen .stat:nth-child(2) .badge').text(totalAusencias);
                        $('.resumen .stat:nth-child(3) .badge').text(totalJustificados);

                        // ACTUALIZAR ALERTAS
                        if (alertas.length > 0) {
                            $('#alert-ausencias').show();
                            $('#lista-alertas').html(
                                alertas.map(item =>
                                    `<div class="card-alumno alert d-flex justify-content-between align-items-center">
                                    <div>
                                        ${item.nombre} (${item.cedula}) -
                                        <span class="badge bg-danger">${item.total} ausencias en el rango de tiempo especificado</span>
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
                        alert('Error al cargar los datos: ' + response.error);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', status, error);
                    alert('Error al cargar los datos. Por favor, intente nuevamente.');
                }
            });
        }

        // ASIGNAR EVENTOS PARA LOS FILTROS
        $('#filtroDesde, #filtroHasta, #filtroSeccion').on('change', function() {
            cargarReporte();
        });

        $('#limpiarFiltros').click(function() {
            $('#filtroDesde').val('<?= $anio_desde ?>');
            $('#filtroHasta').val('<?= $anio_hasta ?>');
            $('#filtroSeccion').val('');
            $('#filtroCedula').val('');
            table.column(4).search('').draw();
            cargarReporte();
        });

        // CARGAR REPORTE INICIAL SI HAY FILTROS APLICADOS
        if ($('#filtroSeccion').val() || $('#filtroDesde').val() !== '<?= $anio_desde ?>' || $('#filtroHasta').val() !== '<?= $anio_hasta ?>') {
            cargarReporte();
        }

        // NUEVO: Generar reporte general por sección
        $('#generarReporteGeneral').click(function() {
            var id_seccion = $('#filtroSeccion').val();
            var desde = $('#filtroDesde').val();
            var hasta = $('#filtroHasta').val();
            
            if (!id_seccion) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Seleccione una sección',
                    text: 'Por favor, seleccione una sección para generar el reporte general.'
                });
                return;
            }
            
            var url = `/liceo/controladores/reporte_controlador.php?action=generar_reporte_general_seccion&id_seccion=${id_seccion}&desde=${desde}&hasta=${hasta}`;
            window.open(url, '_blank');
        });
    });
    </script>
</body>

</html>