<!DOCTYPE html>
<html lang="es">

<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/head.php'); ?>
    <title>Registro de Asistencia</title>
    <style>
        .justificado-note {
            display: none;
            margin-top: 5px;
        }
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/backdrop.css') ?>

    </style>
</head>

<body>
    <?php
    $today = date('Y-m-d');
    $min_date = '';
    if (isset($anio_activo) && $anio_activo) {
        $min_date = date('Y-m-d', strtotime($anio_activo['desde']));
    }
    ?>
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
                    <div class="card-header">
                        <h4>Registro de Asistencia <img src="/liceo/icons/calendar-check.svg">
                            <button type="button" class="btn btn-primary float-end btn-success" data-toggle="modal" data-target="#registrarAsistencia">
                                Registrar Asistencia
                            </button>
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="bi bi-funnel"></i> Filtros de Búsqueda
                                    <button class="btn btn-sm btn-outline-secondary float-end" type="button" onclick="limpiarFiltros()">
                                        <i class="bi bi-arrow-clockwise"></i> Limpiar
                                    </button>
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="filtroFecha" class="form-label">Fecha:</label>
                                        <input type="date" class="form-control" id="filtroFecha">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="filtroGrado" class="form-label">Grado:</label>
                                        <select class="form-select" id="filtroGrado">
                                            <option value="">Todos los grados</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="filtroSeccion" class="form-label">Sección:</label>
                                        <select class="form-select" id="filtroSeccion" disabled>
                                            <option value="">Todas las secciones</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 d-flex align-items-end">
                                        <button class="btn btn-primary w-100" id="aplicarFiltro">
                                            <i class="bi bi-search"></i> Buscar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <table class="table table-striped" id="tablaAsistencia" style="width:100%;">
                            <thead>
                                <tr class="table-secondary">
                                    <th>Fecha</th>
                                    <th>Sección</th>
                                    <th>Grado</th>
                                    <th>Total Estudiantes</th>
                                    <th>Presentes</th>
                                    <th>Inasistentes</th>
                                    <th>Justificados</th>
                                    <th class="action">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($asistencias && mysqli_num_rows($asistencias) > 0) {
                                    while ($row = mysqli_fetch_assoc($asistencias)) {
                                        $presentes = $row['total_estudiantes'] - $row['ausentes'] - $row['justificados'];
                                ?>
                                        <tr>
                                            <td><?= date('d/m/Y', strtotime($row['fecha'])); ?></td>
                                            <td><?= $row['numero_anio'] . '° ' . $row['letra']; ?></td>
                                            <td><?= $row['numero_anio'] . '° año'; ?></td>
                                            <td><span class="badge bg-info"><?= $row['total_estudiantes'] ?></span></td>
                                            <td><span class="badge bg-success"><?= $presentes ?></span></td>
                                            <td><span class="badge bg-danger"><?= $row['ausentes'] ?></span></td>
                                            <td><span class="badge bg-warning"><?= $row['justificados'] ?></span></td>
                                            <td>
                                                <button class="btn btn-warning btn-sm" onclick="consultarDetalle('<?= $row['fecha'] ?>', <?= $row['id_seccion'] ?>, '<?= $row['numero_anio'] ?>° <?= $row['letra'] ?>', '<?= $row['nombre_prof']. ' '. $row['apellido_prof'] ?>')" title="Ver detalle">
                                                    <i class="bi bi-eye"></i> Consultar
                                                </button>
                                                <button class="btn btn-primary btn-sm" onclick="modificarAsistencia('<?= $row['fecha'] ?>', <?= $row['id_seccion'] ?>, '<?= $row['numero_anio'] ?>° <?= $row['letra'] ?>', '<?= $row['nombre_prof'].' '. $row['apellido_prof'] ?>')" title="Modificar">
                                                    <i class="bi bi-pencil"></i> Modificar
                                                </button>

                                            </td>
                                        </tr>
                                    <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td class="text-center">No hay registros de asistencia</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Registrar Asistencia -->
    <div class="modal" id="registrarAsistencia" tabindex="-1" aria-labelledby="registrarAsistenciaLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="registrarAsistenciaLabel">Registrar Asistencia</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/liceo/controladores/asistencia_controlador.php" method="POST" id="formAsistencia">
                    <input type="hidden" name="action" value="registrar">
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="fechaAsistencia" class="form-label">Fecha:</label>
                                <input type="date" class="form-control" id="fechaAsistencia" name="fecha" required max="<?= $today ?>" <?php if ($min_date): ?> min="<?= $min_date ?>" <?php endif; ?>>
                            </div>
                            <div class="col-md-4">
                                <label for="gradoAsistencia" class="form-label">Grado:</label>
                                <select class="form-select" id="gradoAsistencia" required>
                                    <option value="">Seleccione un grado</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="seccionAsistencia" class="form-label">Sección:</label>
                                <select class="form-select" id="seccionAsistencia" name="seccion" required disabled>
                                    <option value="">Seleccione una sección</option>
                                </select>
                            </div>
                        </div>

                        <div id="listaEstudiantes" style="max-height: 400px; overflow-y: auto;">
                            <p class="text-muted">Seleccione una sección para ver los estudiantes</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="guardar_asistencia" class="btn btn-success">Guardar Asistencia</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar Asistencia -->
    <div class="modal" id="editarAsistenciaModal" tabindex="-1" aria-labelledby="editarAsistenciaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editarAsistenciaModalLabel">Editar Asistencia</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formEditarAsistencia" action="/liceo/controladores/asistencia_controlador.php" method="POST">
                    <input type="hidden" name="action" value="actualizar">
                    <div class="modal-body">
                        <input type="hidden" id="id_asistencia_edit" name="id_asistencia">

                        <div class="form-group mb-3">
                            <label for="fecha_edit">Fecha</label>
                            <input type="date" class="form-control" id="fecha_edit" name="fecha" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="estudiante_edit" disabled>Estudiante</label>
                            <input type="text" class="form-control" id="estudiante_edit" readonly>
                        </div>

                        <div class="form-group mb-3">
                            <label>Estado</label>
                            <div class="form-check">
                                <input class="form-check-input estado-radio" type="radio" name="estado" id="presente_edit" value="P" checked>
                                <label class="form-check-label" for="presente_edit">Presente</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input estado-radio" type="radio" name="estado" id="ausente_edit" value="A">
                                <label class="form-check-label" for="ausente_edit">Ausente</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input estado-radio" type="radio" name="estado" id="justificado_edit" value="J">
                                <label class="form-check-label" for="justificado_edit">Justificado</label>
                            </div>
                            <div class="justificado-note mt-2">
                                <label for="justificacion_edit" class="form-label">Nota de Justificación:</label>
                                <textarea class="form-control" id="justificacion_edit" name="justificacion" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="actualizar_asistencia" class="btn btn-primary">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Consultar Detalle -->
    <div class="modal" id="consultarDetalleModal" tabindex="-1" aria-labelledby="consultarDetalleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="consultarDetalleModalLabel">Detalle de Asistencia</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="detalleInfo" class="alert alert-info mb-3"></div>
                    <div id="detalleContainer">
                        <div class="text-center">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Modificar Asistencia Masiva -->
    <div class="modal fade" id="modificarAsistenciaModal" tabindex="-1" aria-labelledby="modificarAsistenciaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modificarAsistenciaModalLabel">Modificar Asistencia</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="modificarInfo" class="alert alert-info mb-3"></div>
                    <div id="modificarContainer">
                        <div class="text-center">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="guardarCambios">Guardar datos</button>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/footer.php') ?>
    </footer>

    <script>
        $(document).ready(function() {
            // Configuración de DataTables
            $('#tablaAsistencia').DataTable({
                columnDefs: [
                    {
                        targets: -1,
                        orderable: false
                    }
                ]
            });

            // Mostrar/ocultar nota de justificación
            $('body').on('change', '.estado-radio, .justificado-radio', function() {
                var form = $(this).closest('form');
                var justificadoNote = form.find('.justificado-note');
                if ($(this).val() === 'J' || $(this).is(':checked') && $(this).hasClass('justificado-radio')) {
                    justificadoNote.show();
                    justificadoNote.find('textarea').prop('required', true);
                } else {
                    justificadoNote.hide();
                    justificadoNote.find('textarea').prop('required', false);
                }
            });

            // Cargar grados al abrir modal
            $('#registrarAsistencia').on('show.bs.modal', function() {
                $.ajax({
                    url: '/liceo/controladores/asistencia_controlador.php',
                    type: 'POST',
                    data: { 'action': 'obtener_grados' },
                    success: function(response) {
                        $('#gradoAsistencia').html(response);
                    }
                });
            });

            // Cargar secciones al seleccionar grado
            $('#gradoAsistencia').change(function() {
                var grado = $(this).val();
                if (grado) {
                    $.ajax({
                        url: '/liceo/controladores/asistencia_controlador.php',
                        type: 'POST',
                        data: {
                            'action': 'obtener_secciones_por_grado',
                            'id_grado': grado
                        },
                        success: function(response) {
                            $('#seccionAsistencia').html(response).prop('disabled', false);
                        }
                    });
                } else {
                    $('#seccionAsistencia').html('<option value="">Seleccione una sección</option>').prop('disabled', true);
                    $('#listaEstudiantes').html('<p class="text-muted">Seleccione una sección para ver los estudiantes</p>');
                }
            });

            // Cargar estudiantes al seleccionar sección o cambiar fecha
            function cargarEstudiantes() {
                var seccion = $('#seccionAsistencia').val();
                var fecha = $('#fechaAsistencia').val();

                if (seccion && fecha) {
                    $('#listaEstudiantes').html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Cargando...</span></div></div>');
                    $.ajax({
                        url: '/liceo/controladores/asistencia_controlador.php',
                        type: 'POST',
                        data: {
                            'action': 'obtener_estudiantes_para_asistencia',
                            'seccion': seccion,
                            'fecha': fecha
                        },
                        success: function(response) {
                            $('#listaEstudiantes').html(response);
                        },
                        error: function() {
                            $('#listaEstudiantes').html('<div class="alert alert-danger">Error al cargar los estudiantes.</div>');
                        }
                    });
                } else {
                    $('#listaEstudiantes').html('<p class="text-muted">Seleccione una fecha y sección para ver los estudiantes</p>');
                }
            }

            $('#seccionAsistencia, #fechaAsistencia').change(cargarEstudiantes);

            // Mostrar/ocultar justificación basado en checkboxes de materias
            $('body').on('change', '.materia-checkbox', function() {
                var row = $(this).closest('tr');
                var checkboxes = row.find('.materia-checkbox');
                var justificacionInput = row.find('.justificacion-input');

                if (checkboxes.filter(':checked').length === 0) {
                    justificacionInput.show();
                } else {
                    justificacionInput.hide().val('');
                }
            });

            // Manejar el botón Guardar Cambios
            $('#guardarCambios').click(function() {
                var formData = $('#formModificarAsistencia').serialize();
                formData += '&action=actualizar_asistencia_masiva';

                $.ajax({
                    url: '/liceo/controladores/asistencia_controlador.php',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        Swal.fire('¡Actualizado!', response, 'success').then(() => {
                            $('#modificarAsistenciaModal').modal('hide');
                            location.reload();
                        });
                    },
                    error: function() {
                        Swal.fire('Error', 'No se pudo actualizar la asistencia.', 'error');
                    }
                });
            });

            // Cargar grados para filtros al cargar la página
            $.ajax({
                url: '/liceo/controladores/asistencia_controlador.php',
                type: 'POST',
                data: { 'action': 'obtener_grados' },
                success: function(response) {
                    $('#filtroGrado').html('<option value="">Todos los grados</option>' + response.replace('<option value="">Seleccione un grado</option>', ''));
                }
            });

            // Cargar secciones al seleccionar grado en filtros
            $('#filtroGrado').change(function() {
                var grado = $(this).val();
                if (grado) {
                    $.ajax({
                        url: '/liceo/controladores/asistencia_controlador.php',
                        type: 'POST',
                        data: {
                            'action': 'obtener_secciones_por_grado',
                            'id_grado': grado
                        },
                        success: function(response) {
                            $('#filtroSeccion').html('<option value="">Todas las secciones</option>' + response.replace('<option value="">Seleccione una sección</option>', '')).prop('disabled', false);
                        }
                    });
                } else {
                    $('#filtroSeccion').html('<option value="">Todas las secciones</option>').prop('disabled', true);
                }
            });

            // Aplicar filtros
            $('#aplicarFiltro').click(function() {
                var seccion = $('#filtroSeccion').val();
                var fecha = $('#filtroFecha').val();
                var grado = $('#filtroGrado').val();

                $.ajax({
                    url: '/liceo/controladores/asistencia_controlador.php',
                    type: 'POST',
                    data: {
                        'action': 'filtrar',
                        'seccion': seccion,
                        'fecha': fecha,
                        'grado': grado
                    },
                    success: function(response) {
                        $('#tablaAsistencia tbody').html(response);
                    }
                });
            });

            // Editar asistencia
            $('#tablaAsistencia').on('click', '.edit-asistencia', function(e) {
                e.preventDefault();

                var id = $(this).closest('tr').find('td:first').text();
                console.log(id);
                $.ajax({
                    url: '/liceo/controladores/asistencia_controlador.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        'action': 'obtener_para_editar',
                        'id_asistencia': id
                    },
                    success: function(response) {
                        console.log(response);
                        $('#id_asistencia_edit').val(response.id_asistencia);
                        $('#fecha_edit').val(response.fecha);
                        $('#estudiante_edit').val(response.nombre + ' ' + response.apellido);
                        $('input[name="estado"][value="' + response.estado + '"]').prop('checked', true).trigger('change');
                        if (response.estado === 'J') {
                            $('#justificacion_edit').val(response.justificacion);
                        }
                        $('#editarAsistenciaModal').modal('show');
                    },
                    error: function(request, status, errorThrown) {
                        console.log(request)
                        console.log(status)
                        console.log(errorThrown)
                    }
                });
            });

            // Eliminar asistencia
            $('#tablaAsistencia').on('click', '.delete-asistencia', function(e) {
                e.preventDefault();
                var id = $(this).siblings('.delete_id_asistencia').val();
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: '¡Esta acción eliminará el registro de asistencia permanentemente!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/liceo/controladores/asistencia_controlador.php',
                            type: 'POST',
                            data: {
                                'action': 'eliminar',
                                'id_asistencia': id
                            },
                            success: function(response) {
                                Swal.fire(
                                    '¡Eliminado!',
                                    response,
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            }
                        });
                    }
                });
            });
        });

        // Función para consultar detalle (definida fuera del document.ready)
        function consultarDetalle(fecha, idSeccion, nombreSeccion, profesor) {
                $('#detalleInfo').html('<strong>Fecha:</strong> ' + fecha + ' - <strong>Sección:</strong> ' + nombreSeccion  + ' - <strong>Cargada por:</strong> ' + profesor);
                $('#consultarDetalleModal').modal('show');
                
                $.ajax({
                    url: '/liceo/controladores/asistencia_controlador.php',
                    type: 'POST',
                    data: {
                        'action': 'consultar_detalle',
                        'fecha': fecha,
                        'id_seccion': idSeccion
                    },
                    success: function(response) {
                        $('#detalleContainer').html(response);
                    }
                });
            }

            // Función para modificar asistencia
            function modificarAsistencia(fecha, idSeccion, nombreSeccion, profesor) {
                $('#modificarInfo').html('<strong>Fecha:</strong> ' + fecha + ' - <strong>Sección:</strong> ' + nombreSeccion + ' - <strong>Cargada por:</strong> ' + profesor);
                $('#modificarAsistenciaModal').modal('show');
                
                // Cargar estudiantes para modificar (versión editable)
                $.ajax({
                    url: '/liceo/controladores/asistencia_controlador.php',
                    type: 'POST',
                    data: {
                        'action': 'consultar_detalle_editable',
                        'fecha': fecha,
                        'id_seccion': idSeccion
                    },
                    success: function(response) {
                        $('#modificarContainer').html(response);
                    }
                });
            }

            // Función para eliminar asistencia por fecha
            function eliminarAsistenciaFecha(fecha, idSeccion) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: '¡Esta acción eliminará todos los registros de asistencia de esta fecha y sección!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/liceo/controladores/asistencia_controlador.php',
                            type: 'POST',
                            data: {
                                'action': 'eliminar_por_fecha',
                                'fecha': fecha,
                                'id_seccion': idSeccion
                            },
                            success: function(response) {
                                Swal.fire('¡Eliminado!', response, 'success').then(() => {
                                    location.reload();
                                });
                            }
                        });
                    }
                });
            }

        // Función para modificar asistencia (definida fuera del document.ready)
        function modificarAsistencia(fecha, idSeccion, nombreSeccion, profesor) {
            $('#modificarInfo').html('<strong>Fecha:</strong> ' + fecha + ' - <strong>Sección:</strong> ' + nombreSeccion  + ' - <strong>Cargada por:</strong> ' + profesor);
            $('#modificarAsistenciaModal').modal('show');
            
            // Cargar estudiantes para modificar (versión editable)
            $.ajax({
                url: '/liceo/controladores/asistencia_controlador.php',
                type: 'POST',
                data: {
                    'action': 'consultar_detalle_editable',
                    'fecha': fecha,
                    'id_seccion': idSeccion
                },
                success: function(response) {
                    $('#modificarContainer').html(response);
                }
            });
        }

        // Función para eliminar asistencia por fecha (definida fuera del document.ready)
        function eliminarAsistenciaFecha(fecha, idSeccion) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: '¡Esta acción eliminará todos los registros de asistencia de esta fecha y sección!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/liceo/controladores/asistencia_controlador.php',
                        type: 'POST',
                        data: {
                            'action': 'eliminar_por_fecha',
                            'fecha': fecha,
                            'id_seccion': idSeccion
                        },
                        success: function(response) {
                            Swal.fire('¡Eliminado!', response, 'success').then(() => {
                                location.reload();
                            });
                        }
                    });
                }
            });
        }

        // Función para limpiar filtros
        function limpiarFiltros() {
            $('#filtroFecha').val('');
            $('#filtroGrado').val('');
            $('#filtroSeccion').html('<option value="">Todas las secciones</option>').prop('disabled', true);
            
            // Recargar tabla completa
            location.reload();
        }
    </script>
</body>

</html>
