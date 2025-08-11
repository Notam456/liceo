<?php
session_start();
?>

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
                    <div class="card-header">
                        <h4>Registro de Asistencia <img src="/liceo/icons/calendar-check.svg">
                            <button type="button" class="btn btn-primary float-end btn-success" data-bs-toggle="modal" data-bs-target="#registrarAsistencia">
                                Registrar Asistencia
                            </button>
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="filtroSeccion" class="form-label">Filtrar por Sección:</label>
                                <select class="form-select" id="filtroSeccion">
                                    <option value="">Todas las secciones</option>
                                    <?php
                                    include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/conn.php');
                                    $query = "SELECT DISTINCT seccion_estudiante FROM estudiante";
                                    $result = mysqli_query($conn, $query);
                                    
                                    while($row = mysqli_fetch_array($result)) {
                                        echo '<option value="'.$row['seccion_estudiante'].'">'.$row['seccion_estudiante'].'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="filtroFecha" class="form-label">Filtrar por Fecha:</label>
                                <input type="date" class="form-control" id="filtroFecha">
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button class="btn btn-primary" id="aplicarFiltro">Aplicar Filtro</button>
                            </div>
                        </div>

                        <table class="table table-striped" id="tablaAsistencia" style="width:100%;">
                            <thead>
                                <tr class="table-secondary">
                                    <th style="display: none;">ID</th>
                                    <th>Fecha</th>
                                    <th>Sección</th>
                                    <th>Estudiante</th>
                                    <th>Estado</th>
                                    <th>Justificación</th>
                                    <th class="action">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT a.id_asistencia, a.fecha, a.estado, a.justificacion, 
                                          e.nombre_estudiante, e.apellido_estudiante, e.seccion_estudiante
                                          FROM asistencia a
                                          JOIN estudiante e ON a.id_estudiante = e.id_estudiante
                                          ORDER BY a.fecha DESC";
                                $result = mysqli_query($conn, $query);

                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $estado = '';
                                        switch($row['estado']) {
                                            case 'P': $estado = 'Presente'; break;
                                            case 'A': $estado = 'Ausente'; break;
                                            case 'J': $estado = 'Justificado'; break;
                                        }
                                ?>
                                        <tr>
                                            <td style="display: none;"><?= $row['id_asistencia'] ?></td>
                                            <td><?= date('d/m/Y', strtotime($row['fecha'])) ?></td>
                                            <td><?= $row['seccion_estudiante'] ?></td>
                                            <td><?= $row['nombre_estudiante'].' '.$row['apellido_estudiante'] ?></td>
                                            <td><?= $estado ?></td>
                                            <td><?= $row['justificacion'] ?: 'N/A' ?></td>
                                            <td>
                                                <a href="#" class="btn btn-primary btn-sm edit-asistencia">Modificar</a>
                                                <input type="hidden" class="delete_id_asistencia" value="<?= $row['id_asistencia'] ?>">
                                                <a href="#" class="btn btn-danger btn-sm delete-asistencia">Eliminar</a>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                } else {
                                ?>
                                    <tr>
                                        <td colspan="7">No hay registros de asistencia</td>
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
    <div class="modal fade" id="registrarAsistencia" tabindex="-1" aria-labelledby="registrarAsistenciaLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="registrarAsistenciaLabel">Registrar Asistencia</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="conn_asistencia.php" method="POST" id="formAsistencia">
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="fechaAsistencia" class="form-label">Fecha:</label>
                                <input type="date" class="form-control" id="fechaAsistencia" name="fecha" required>
                            </div>
                            <div class="col-md-6">
                                <label for="seccionAsistencia" class="form-label">Sección:</label>
                                <select class="form-select" id="seccionAsistencia" name="seccion" required>
                                    <option value="">Seleccione una sección</option>
                                    <?php
                                    $query = "SELECT DISTINCT seccion_estudiante FROM estudiante";
                                    $result = mysqli_query($conn, $query);
                                    
                                    while($row = mysqli_fetch_array($result)) {
                                        echo '<option value="'.$row['seccion_estudiante'].'">'.$row['seccion_estudiante'].'</option>';
                                    }
                                    ?>
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
    <div class="modal fade" id="editarAsistenciaModal" tabindex="-1" aria-labelledby="editarAsistenciaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editarAsistenciaModalLabel">Editar Asistencia</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formEditarAsistencia" action="conn_asistencia.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" id="id_asistencia_edit" name="id_asistencia">
                        
                        <div class="form-group mb-3">
                            <label for="fecha_edit">Fecha</label>
                            <input type="date" class="form-control" id="fecha_edit" name="fecha" required>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="estudiante_edit">Estudiante</label>
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

    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/footer.php') ?>
    </footer>

    <script>
        $(document).ready(function() {
            // Configuración de DataTables
            $('#tablaAsistencia').DataTable({
                columnDefs: [
                    { targets: 0, visible: false },
                    { targets: -1, orderable: false }
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                }
            });

            // Mostrar/ocultar nota de justificación
            $('body').on('change', '.estado-radio', function() {
                if ($(this).val() === 'J') {
                    $('.justificado-note').show();
                    $('#justificacion_edit').prop('required', true);
                } else {
                    $('.justificado-note').hide();
                    $('#justificacion_edit').prop('required', false);
                }
            });

            // Cargar estudiantes al seleccionar sección
            $('#seccionAsistencia').change(function() {
                var seccion = $(this).val();
                
                if(seccion) {
                    $.ajax({
                        url: 'conn_asistencia.php',
                        type: 'POST',
                        data: { 
                            'obtener_estudiantes': true,
                            'seccion': seccion 
                        },
                        success: function(response) {
                            $('#listaEstudiantes').html(response);
                        }
                    });
                } else {
                    $('#listaEstudiantes').html('<p class="text-muted">Seleccione una sección para ver los estudiantes</p>');
                }
            });

            // Aplicar filtros
            $('#aplicarFiltro').click(function() {
                var seccion = $('#filtroSeccion').val();
                var fecha = $('#filtroFecha').val();
                
                $.ajax({
                    url: 'conn_asistencia.php',
                    type: 'POST',
                    data: { 
                        'filtrar_asistencia': true,
                        'seccion': seccion,
                        'fecha': fecha
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
                
                $.ajax({
                    url: 'conn_asistencia.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        'obtener_asistencia': true,
                        'id_asistencia': id
                    },
                    success: function(response) {
                        $('#id_asistencia_edit').val(response.id_asistencia);
                        $('#fecha_edit').val(response.fecha);
                        $('#estudiante_edit').val(response.nombre_estudiante + ' ' + response.apellido_estudiante);
                        
                        // Establecer el estado correcto
                        $('input[name="estado"][value="' + response.estado + '"]').prop('checked', true);
                        
                        // Mostrar/ocultar campo de justificación
                        if(response.estado === 'J') {
                            $('.justificado-note').show();
                            $('#justificacion_edit').val(response.justificacion);
                        } else {
                            $('.justificado-note').hide();
                        }
                        
                        $('#editarAsistenciaModal').modal('show');
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
                            url: 'conn_asistencia.php',
                            type: 'POST',
                            data: {
                                'eliminar_asistencia': true,
                                'id_asistencia': id
                            },
                            success: function(response) {
                                Swal.fire(
                                    '¡Eliminado!',
                                    'El registro de asistencia ha sido eliminado correctamente.',
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
    </script>
</body>
</html>