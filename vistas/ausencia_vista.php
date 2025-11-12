<?php
if (!isset($reporte)) {
    header("Location: /liceo/error.php");
    exit();
}

// Calcular estudiantes en alerta para determinar si abrir el modal automáticamente
<<<<<<< Updated upstream
$estudiantesAlerta = array_filter($reporte, function($item) {
    return $item['total_nuevas'] >= 3 && !$item['tiene_visita_agendada'];
=======
$estudiantesAlerta = array_filter($reporte, function ($item) {
    return $item['total'] >= 3 && !$item['tiene_visita_agendada'];
>>>>>>> Stashed changes
});
$totalAlertas = count($estudiantesAlerta);
$abrirModalAutomaticamente = $totalAlertas > 0;
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

        /* Estilos para el modal de alertas */
        .seccion-group {
            margin-bottom: 20px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            height: 100%;
        }

        .seccion-header {
            background-color: #e9ecef;
            padding: 10px 15px;
            border-bottom: 1px solid #dee2e6;
            font-weight: bold;
        }

        .seccion-body {
            padding: 15px;
        }

        .estudiante-alerta-item {
            display: flex;
            justify-content: between;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #f8f9fa;
        }

        .estudiante-alerta-item:last-child {
            border-bottom: none;
        }

        .estudiante-info {
            flex: 1;
        }

        .estudiante-acciones {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-direction: column;
        }

        .contador-alertas {
            background-color: #dc3545;
            color: white;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            margin-left: 5px;
        }

        /* Animación para el modal automático */
        @keyframes pulse-alert {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.02);
            }

            100% {
                transform: scale(1);
            }
        }

        .modal-alerta-automatica .modal-content {
            animation: pulse-alert 2s ease-in-out;
            border: 3px solid #ffc107;
        }

        /* Estilos para el grid de cards */
        .cards-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .seccion-card {
            flex: 1 1 calc(50% - 15px);
            min-width: 300px;
            margin-bottom: 15px;
        }

        @media (max-width: 768px) {
            .seccion-card {
                flex: 1 1 100%;
            }
        }

        <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/backdrop.css'); ?>
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
                    <div class="alert alert-warning alert-dismissible n show" role="alert">
                        <strong>Hey!</strong> <?php echo $_SESSION['status']; ?>
                        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['status']); ?>
                <?php endif; ?>

                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h4 class="mb-0">Inasistencia<i class="bi bi-clipboard2-pulse"></i></h4>
                        <div>
                            <button class="btn btn-warning btn-sm me-2" id="verAlertasAusencias" data-toggle="modal" data-target="#alertasModal">
                                <i class="bi bi-exclamation-triangle-fill"></i> Alertas
                                <?php if ($totalAlertas > 0): ?>
                                    <span class="contador-alertas"><?= $totalAlertas ?></span>
                                <?php endif; ?>
                            </button>
                            <button class="btn btn-secondary btn-sm" id="generarReporteGeneral">
                                Reporte General por Sección
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php
                        $totalEstudiantes = count($reporte);
                        $totalAusencias = array_sum(array_map(function ($i) {
                            return $i['ausencias'];
                        }, $reporte));
                        $totalJustificados = array_sum(array_map(function ($i) {
                            return  $i['justificadas'];
                        }, $reporte));
                        ?>
                        <div class="resumen">
                            <div class="stat">
                                Total estudiantes: <span class="badge bg-secondary"><?php echo $totalEstudiantes; ?></span>
                            </div>
                            <div class="stat">
                                Inasistencias: <span class="badge bg-danger"><?php echo $totalAusencias; ?></span>
                            </div>
                            <div class="stat">
                                Justificados: <span class="badge bg-warning text-dark"><?php echo $totalJustificados; ?></span>
                            </div>
                            <div class="stat">
                                En alerta: <span class="badge bg-danger"><?= $totalAlertas ?></span>
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
                                    <!-- <input type="text" class="form-control" id="filtroDesde" name="inicio" placeholder="AAAA-MM-DD" required readonly> -->
                                    <input type="text" class="form-control" id="filtroDesde" placeholder="AAAA-MM-DD" required readonly min="<?= $anio_desde ?>" max="<?= $anio_hasta ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="filtroHasta" class="form-label">Hasta:</label>
                                    <input type="text" class="form-control" id="filtroHasta" placeholder="AAAA-MM-DD" required readonly min="<?= $anio_desde ?>" max="<?= $anio_hasta ?>">
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

                        <div class="table-responsive">
                            <table class="table table-striped" id="tablaReportes">
                                <thead>
                                    <tr class="table-secondary">
                                        <th style="display: none;">#</th>
                                        <th>Estudiante</th>
                                        <th>Sección</th>
                                        <th>Contacto</th>
                                        <th>Cédula</th>
                                        <th title="Inasistencias"><i class="bi bi-person-dash-fill"></i> Inasistencias</th>
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
                                                <?php if ($item['total_nuevas'] >= 3): ?>
                                                    <?php if ($item['tiene_visita_agendada']): ?>
                                                        <button type="button" class="btn btn-secondary btn-sm" disabled>Visita Agendada</button>
                                                    <?php else: ?>
                                                        <button type="button" class="btn btn-primary btn-sm schedule-visit" data-toggle="modal" data-target="#visitaModal" data-id-estudiante="<?= $item['id_estudiante'] ?>">Agendar Visita</button>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($item['total'] > 0): ?>
                                                    <a href="/liceo/controladores/ausencia_controlador.php?action=generar_reporte_ausencias&id_estudiante=<?= $item['id_estudiante'] ?>&desde=<?= $anio_desde ?>&hasta=<?= $anio_hasta ?>"
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

    <!-- Modal para Alertas de Ausencias -->
    <div class="modal <?= $abrirModalAutomaticamente ? 'modal-alerta-automatica' : '' ?>" id="alertasModal" tabindex="-1" aria-labelledby="alertasModalLabel" aria-hidden="true" data-bs-backdrop="<?= $abrirModalAutomaticamente ? 'static' : 'true' ?>" data-bs-keyboard="<?= $abrirModalAutomaticamente ? 'false' : 'true' ?>">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" id="alertasModalLabel">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <?= $abrirModalAutomaticamente ? 'ALERTA: ' : '' ?>Estudiantes con 3 o más Ausencias
                    </h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    <?php if (!$abrirModalAutomaticamente): ?>
                        <!-- <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button> -->
                    <?php endif; ?>
                </div>
                <div class="modal-body">
                    <?php if ($abrirModalAutomaticamente): ?>
                        <div class="alert alert-warning">
                            <i class="bi bi-bell-fill"></i>
                            <strong>Se detectaron <?= $totalAlertas ?> estudiante(s) con 3 o más ausencias que requieren atención inmediata.</strong>
                        </div>
                    <?php endif; ?>

                    <div id="contenedor-alertas">
                        <!-- Aquí se cargarán las alertas agrupadas por sección -->
                        <?php
                        // Agrupar estudiantes en alerta por sección
                        $agrupadosPorSeccion = [];
                        foreach ($estudiantesAlerta as $estudiante) {
                            $seccion = $estudiante['seccion'] ?: 'Sin Sección';
                            if (!isset($agrupadosPorSeccion[$seccion])) {
                                $agrupadosPorSeccion[$seccion] = [];
                            }
                            $agrupadosPorSeccion[$seccion][] = $estudiante;
                        }

                        if (empty($agrupadosPorSeccion)): ?>
                            <div class="text-center py-4">
                                <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                                <h5 class="mt-3">¡No hay alertas!</h5>
                                <p class="text-muted">No hay estudiantes con 3 o más ausencias sin visita agendada.</p>
                            </div>
                        <?php else: ?>
                            <div class="cards-container">
                                <?php foreach ($agrupadosPorSeccion as $seccion => $estudiantes): ?>
                                    <div class="seccion-card">
                                        <div class="card h-100">
                                            <div class="card-header bg-warning d-flex justify-content-between align-items-center">
                                                <strong><?= htmlspecialchars($seccion) ?></strong>
                                                <span class="badge bg-danger"><?= count($estudiantes) ?> estudiantes</span>
                                            </div>
                                            <div class="card-body">
                                                <?php foreach ($estudiantes as $estudiante): ?>
                                                    <div class="estudiante-alerta-item">
                                                        <div class="estudiante-info">
                                                            <strong><?= htmlspecialchars($estudiante['nombre']) ?></strong>
                                                            <br>
                                                            <small class="text-muted">
                                                                Cédula: <?= htmlspecialchars($estudiante['cedula']) ?> |
                                                                Contacto: <?= htmlspecialchars($estudiante['contacto']) ?>
                                                            </small>
                                                            <br>
                                                            <div class="mt-2">
                                                                <span class="badge bg-danger"><?= $estudiante['ausencias'] ?> ausencias</span>
                                                                <span class="badge bg-warning text-dark"><?= $estudiante['justificadas'] ?> justificadas</span>
                                                                <span class="badge bg-secondary">Total: <?= $estudiante['total'] ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="estudiante-acciones">
                                                            <button type="button"
                                                                class="btn btn-primary btn-sm schedule-visit-modal"
                                                                data-toggle="modal"
                                                                data-target="#visitaModal"
                                                                data-id-estudiante="<?= $estudiante['id_estudiante'] ?>"
                                                                data-dismiss="modal">
                                                                Agendar Visita
                                                            </button>
                                                            <a href="/liceo/controladores/ausencia_controlador.php?action=generar_reporte_ausencias&id_estudiante=<?= $estudiante['id_estudiante'] ?>&desde=<?= $anio_desde ?>&hasta=<?= $anio_hasta ?>"
                                                                class="btn btn-secondary btn-sm mt-1" target="_blank">
                                                                Generar Reporte
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <?php if ($estudiante !== end($estudiantes)): ?>
                                                        <hr class="my-2">
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/footer.php') ?>
    </footer>

    <script>
        $(document).ready(function() {
            // Verificar que Pikaday esté cargado
            if (typeof Pikaday !== 'undefined') {
                console.log('Pikaday cargado correctamente');

                // Configuración común para ambos datepickers
                var pikadayConfig = {
                    format: 'YYYY-MM-DD',
                    toString: function(date, format) {
                        // Forzar el formato YYYY-MM-DD
                        var year = date.getFullYear();
                        var month = ('0' + (date.getMonth() + 1)).slice(-2);
                        var day = ('0' + date.getDate()).slice(-2);
                        return year + '-' + month + '-' + day;
                    },
                    parse: function(dateString, format) {
                        // Parsear desde YYYY-MM-DD
                        var parts = dateString.split('-');
                        if (parts.length === 3) {
                            return new Date(parts[0], parts[1] - 1, parts[2]);
                        }
                        return new Date(dateString);
                    },
                    i18n: {
                        previousMonth: 'Mes anterior',
                        nextMonth: 'Siguiente mes',
                        months: [
                            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
                        ],
                        weekdays: [
                            'Domingo', 'Lunes', 'Martes', 'Miércoles',
                            'Jueves', 'Viernes', 'Sábado'
                        ],
                        weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb']
                    },
                    yearRange: [1950, new Date().getFullYear()],
                    minDate: new Date('<?= $anio_desde ?>'),
                    maxDate: new Date('<?= $anio_hasta ?>'),
                    showDaysInNextAndPreviousMonths: true
                };

                var pikadayConfigVisita = {
                    format: 'YYYY-MM-DD',
                    toString: function(date, format) {
                        // Forzar el formato YYYY-MM-DD
                        var year = date.getFullYear();
                        var month = ('0' + (date.getMonth() + 1)).slice(-2);
                        var day = ('0' + date.getDate()).slice(-2);
                        return year + '-' + month + '-' + day;
                    },
                    parse: function(dateString, format) {
                        // Parsear desde YYYY-MM-DD
                        var parts = dateString.split('-');
                        if (parts.length === 3) {
                            return new Date(parts[0], parts[1] - 1, parts[2]);
                        }
                        return new Date(dateString);
                    },
                    i18n: {
                        previousMonth: 'Mes anterior',
                        nextMonth: 'Siguiente mes',
                        months: [
                            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
                        ],
                        weekdays: [
                            'Domingo', 'Lunes', 'Martes', 'Miércoles',
                            'Jueves', 'Viernes', 'Sábado'
                        ],
                        weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb']
                    },
                    yearRange: [1950, new Date().getFullYear()],
                    minDate: new Date(new Date().setHours(0, 0, 0, 0)),
                    maxDate: new Date('<?= $anio_hasta ?>'),
                    showDaysInNextAndPreviousMonths: true


                };


                // Inicializar Pikaday para el modal de CREAR
                var pickerCrear = new Pikaday(
                    // Fusiona un objeto vacío, pikadayConfig, y el objeto con la propiedad 'field'
                    Object.assign({}, pikadayConfig, {
                        field: document.getElementById('filtroDesde')
                    })
                );

                // Inicializar Pikaday para el modal de EDITAR
                var pickerEditar = new Pikaday(
                    // Fusiona un objeto vacío, pikadayConfig, y el objeto con la propiedad 'field'
                    Object.assign({}, pikadayConfig, {
                        field: document.getElementById('filtroHasta')
                    })
                );

                var pickerEditar = new Pikaday(
                    // Fusiona un objeto vacío, pikadayConfig, y el objeto con la propiedad 'field'
                    Object.assign({}, pikadayConfigVisita, {
                        field: document.getElementById('fecha_visita')
                    })
                );

                // Mostrar datepicker cuando se abra el modal de CREAR
                $('#insertdata').on('shown.bs.modal', function() {
                    if (pickerCrear) {
                        pickerCrear.show();
                    }
                });

                // Mostrar datepicker cuando se abra el modal de EDITAR
                $('#editmodal').on('shown.bs.modal', function() {
                    if (pickerEditar) {
                        pickerEditar.show();
                    }
                });

                // Cuando se cargan datos en el modal de editar, establecer la fecha en Pikaday
                $(document).on('ajaxComplete', function(event, xhr, settings) {
                    if (settings.url === '/liceo/controladores/estudiante_controlador.php' &&
                        settings.data.includes("action=editar")) {

                        // Esperar un momento para que los datos se carguen en los campos
                        setTimeout(function() {
                            var fechaInput = document.getElementById('fecha_nacimiento_edit');
                            var fechaPicker = document.getElementById('fecha_nacimiento_edit_picker');

                            if (fechaInput && fechaInput.value && pickerEditar) {
                                // Convertir fecha de YYYY-MM-DD a DD-MM-YYYY para Pikaday
                                var fechaParts = fechaInput.value.split('-');
                                if (fechaParts.length === 3) {
                                    var fechaFormateada = fechaParts[2] + '-' + fechaParts[1] + '-' + fechaParts[0];
                                    fechaPicker.value = fechaFormateada;
                                    pickerEditar.setDate(new Date(fechaInput.value));
                                }
                            }
                        }, 100);
                    }
                });

            } else {
                console.error('Pikaday no está cargado');
            }
        });
    </script>

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
<<<<<<< Updated upstream
                {
                    visible: false,
                    target: 0
                }
            ]
        });

        // Abrir modal automáticamente si hay alertas
        <?php if ($abrirModalAutomaticamente){ ?>
        $(document).ready( function() {
            console.log("asas");
            // Pequeño delay para asegurar que todo esté cargado
            setTimeout(function() {
                $('#alertasModal').modal('show');
                
                // Agregar clase para la animación especial
                $('#alertasModal').addClass('modal-alerta-automatica');
            }, 500);
        });
        <?php
     } ?>

        $('#filtroCedula').keyup(function() {
            table.column(4).search(this.value).draw();
        });

        // Función para actualizar el contador de alertas
        function actualizarContadorAlertas() {
            var totalAlertas = $('.estudiante-alerta-item').length;
            var $contador = $('.contador-alertas');
            
            if (totalAlertas > 0) {
                if ($contador.length === 0) {
                    $('#verAlertasAusencias').append('<span class="contador-alertas">' + totalAlertas + '</span>');
                } else {
                    $contador.text(totalAlertas);
                }
            } else {
                $contador.remove();
            }
        }

        // Inicializar contador
        actualizarContadorAlertas();

        $(document).on('click', '.schedule-visit, .schedule-visit-modal', function() {
            var studentId = $(this).data('id-estudiante');
            $('#visitaModal #id_estudiante_visita').val(studentId);
        });

        // Botón especial para el modal automático
        $('#entenderYContinuar').click(function() {
            // Aquí podrías agregar lógica adicional si necesitas
            // como marcar las alertas como vistas, etc.
            console.log('Usuario confirmó entender las alertas');
        });

        function cargarReporte() {
            var desde = $('#filtroDesde').val();
            var hasta = $('#filtroHasta').val();
            var id_seccion = $('#filtroSeccion').val();

            var url = `/liceo/controladores/ausencia_controlador.php?desde=${desde}&hasta=${hasta}&id_seccion=${id_seccion}`;

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        var table = $('#tablaReportes').DataTable();
                        table.clear();

                        var estudiantesAlerta = [];
                        var totalEstudiantes = 0;
                        var totalAusencias = 0;
                        var totalJustificados = 0;

                        response.data.forEach(function(item) {
                            if (item.total_nuevas >= 3 && !item.tiene_visita_agendada) {
                                estudiantesAlerta.push(item);
                            }

                            // Actualizar estadísticas
                            totalEstudiantes++;
                            totalAusencias += item.ausencias;
                            totalJustificados += item.justificadas;

                            var actionButton = '';
                            if (item.total_nuevas >= 3) {
                                if (item.tiene_visita_agendada) {
                                    actionButton = '<button type="button" class="btn btn-secondary btn-sm" disabled>Visita Agendada</button>';
                                } else {
                                    actionButton = '<button type="button" class="btn btn-primary btn-sm schedule-visit" data-toggle="modal" data-target="#visitaModal" data-id-estudiante="' + item.id_estudiante + '">Agendar Visita</button>';
                                }
                            }

                            var reportButton = '';
                            if (item.total > 0) {
                                reportButton = '<a href="/liceo/controladores/ausencia_controlador.php?action=generar_reporte_ausencias&id_estudiante=' + item.id_estudiante + '&desde=' + desde + '&hasta=' + hasta + '" class="btn btn-secondary btn-sm" target="_blank">Generar Reporte</a>';
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
                        $('.resumen .stat:nth-child(4) .badge').text(estudiantesAlerta.length);

                        // ACTUALIZAR CONTADOR DE ALERTAS
                        actualizarContadorAlertas();

                    } else {
                        console.error('Error fetching report data:', response.error);
                        alert('Error al cargar los datos: ' + response.error);
=======
                columnDefs: [{
                        width: '120px',
                        targets: [8, 9]
                    },
                    {
                        visible: false,
                        target: 0
>>>>>>> Stashed changes
                    }
                ]
            });

            // Abrir modal automáticamente si hay alertas
            <?php if ($abrirModalAutomaticamente) { ?>
                $(document).ready(function() {
                    console.log("asas");
                    // Pequeño delay para asegurar que todo esté cargado
                    setTimeout(function() {
                        $('#alertasModal').modal('show');

                        // Agregar clase para la animación especial
                        $('#alertasModal').addClass('modal-alerta-automatica');
                    }, 500);
                });
            <?php
            } ?>

            $('#filtroCedula').keyup(function() {
                table.column(4).search(this.value).draw();
            });

            // Función para actualizar el contador de alertas
            function actualizarContadorAlertas() {
                var totalAlertas = $('.estudiante-alerta-item').length;
                var $contador = $('.contador-alertas');

                if (totalAlertas > 0) {
                    if ($contador.length === 0) {
                        $('#verAlertasAusencias').append('<span class="contador-alertas">' + totalAlertas + '</span>');
                    } else {
                        $contador.text(totalAlertas);
                    }
                } else {
                    $contador.remove();
                }
            }

            // Inicializar contador
            actualizarContadorAlertas();

            $(document).on('click', '.schedule-visit, .schedule-visit-modal', function() {
                var studentId = $(this).data('id-estudiante');
                $('#visitaModal #id_estudiante_visita').val(studentId);
            });

            // Botón especial para el modal automático
            $('#entenderYContinuar').click(function() {
                // Aquí podrías agregar lógica adicional si necesitas
                // como marcar las alertas como vistas, etc.
                console.log('Usuario confirmó entender las alertas');
            });

            function cargarReporte() {
                var desde = $('#filtroDesde').val();
                var hasta = $('#filtroHasta').val();
                var id_seccion = $('#filtroSeccion').val();

                var url = `/liceo/controladores/ausencia_controlador.php?desde=${desde}&hasta=${hasta}&id_seccion=${id_seccion}`;

                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            var table = $('#tablaReportes').DataTable();
                            table.clear();

                            var estudiantesAlerta = [];
                            var totalEstudiantes = 0;
                            var totalAusencias = 0;
                            var totalJustificados = 0;

                            response.data.forEach(function(item) {
                                if (item.total >= 3 && !item.tiene_visita_agendada) {
                                    estudiantesAlerta.push(item);
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
                                        actionButton = '<button type="button" class="btn btn-primary btn-sm schedule-visit" data-toggle="modal" data-target="#visitaModal" data-id-estudiante="' + item.id_estudiante + '">Agendar Visita</button>';
                                    }
                                }

                                var reportButton = '';
                                if (item.total > 0) {
                                    reportButton = '<a href="/liceo/controladores/ausencia_controlador.php?action=generar_reporte_ausencias&id_estudiante=' + item.id_estudiante + '&desde=' + desde + '&hasta=' + hasta + '" class="btn btn-secondary btn-sm" target="_blank">Generar Reporte</a>';
                                }

                                // AGREGAR LA FILA CORRECTAMENTE CON TODAS LAS COLUMNAS
                                table.row.add([
                                    item.id_estudiante, // Columna 0 - oculta
                                    item.nombre, // Columna 1
                                    item.seccion, // Columna 2
                                    item.contacto, // Columna 3
                                    item.cedula, // Columna 4
                                    '<span class="badge bg-danger">' + item.ausencias + '</span>', // Columna 5
                                    '<span class="badge bg-warning text-dark">' + item.justificadas + '</span>', // Columna 6
                                    '<span class="badge ' + (item.total >= 3 ? 'bg-danger' : 'bg-secondary') + '">' + item.total + '</span>', // Columna 7
                                    actionButton, // Columna 8
                                    reportButton // Columna 9
                                ]);
                            });

                            table.draw();

                            // ACTUALIZAR ESTADÍSTICAS
                            $('.resumen .stat:nth-child(1) .badge').text(totalEstudiantes);
                            $('.resumen .stat:nth-child(2) .badge').text(totalAusencias);
                            $('.resumen .stat:nth-child(3) .badge').text(totalJustificados);
                            $('.resumen .stat:nth-child(4) .badge').text(estudiantesAlerta.length);

                            // ACTUALIZAR CONTADOR DE ALERTAS
                            actualizarContadorAlertas();

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

                var url = `/liceo/controladores/ausencia_controlador.php?action=generar_reporte_general_seccion&id_seccion=${id_seccion}&desde=${desde}&hasta=${hasta}`;
                window.open(url, '_blank');
            });
        });
    </script>
</body>

</html>