<!DOCTYPE html>
<html lang="en">

<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/head.php'); ?>
    <link rel="stylesheet" href="../includes/backdrop.css">
    <title>Secciones</title>
    <style>
        .tooltip .tooltip-inner {
            background-color: #ffffff;
            border: 1px solid #343a40;
            color: #000000;
            font-size: 14px;
            padding: 8px 12px;
            border-radius: 8px;
            max-width: 220px;
            text-align: center;
        }
        
        /* SOLUCIÓN: Asegurar que SweetAlert2 se muestre por encima de los modales */
        .swal2-container {
            z-index: 99999 !important;
        }
        
        .swal2-backdrop-show {
            z-index: 99998 !important;
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
            <div class="col-md-8">
                <?php if (isset($_SESSION['status']) && $_SESSION['status'] != '') { ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Hey!</strong> <?php echo $_SESSION['status']; ?>
                        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php unset($_SESSION['status']);
                } ?>
                <div class="card">
                    <div class="card-header">
                        <h4>Sección <img src="/liceo/icons/people.svg">
                            <button type="button" class="btn btn-primary float-end btn-success" data-toggle="modal" data-target="#insertdata">
                                Agregar
                            </button>
                            
                            <a href="/liceo/controladores/seccion_controlador.php?action=generar_reporte_inasistencias" 
                                class="float-end btn btn-secondary me-2" target="_blank">
                                    Reporte General de Inasistencias
                                </a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped" id="myTable">
                            <thead>
                                <tr class="table-secondary">
                                    <th style="display: none;" scope="col">#</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Año</th>
                                    <th scope="col" class="action">Acción</th>
                                    <th scope="col" class="action"></th>
                                    <th scope="col" class="action"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($secciones && mysqli_num_rows($secciones) > 0) {
                                    foreach ($secciones_copy as $row) {
                                ?>
                                        <tr>
                                            <td class="id_seccion" style="display: none;"> <?php echo $row['id_seccion'] ?> </td>
                                            <td>
                                                <?php echo $row['numero_anio'] . "° " . $row['letra'];
                                                /*if (isset($horarios_status[$row['id_seccion']]) && !$horarios_status[$row['id_seccion']]) {
                                                    echo ' <i class="bi bi-exclamation-triangle-fill text-danger"
                                                        data-toggle="tooltip"
                                                        data-placement="top"
                                                        title="Esta sección no cuenta con un horario. Por favor, pulse el botón consultar y posteriormente Agregar Horario">
                                                        </i>';
                                                }*/
                                                ?>
                                            </td>
                                            <td> <?php echo $row['numero_anio'] ?> </td>
                                            <td><a href="#" class="btn btn-warning btn-sm view-data">Consultar</a></td>
                                            <td><a href="#" class="btn btn-primary btn-sm edit-data">Modificar</a></td>
                                            <td><a href="#" class="btn btn-danger btn-sm delete-data">Eliminar</a></td>
                                        </tr>
                                    <?php }
                                } else { ?>
                                    <tr>
                                        <td></td>
                                        <td>No se encontraron registros de secciones.</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modulo editar -->
    <div class="modal" id="editmodal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Modificar Sección</h4>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="edit-form" action="/liceo/controladores/seccion_controlador.php" method="POST">
                    <input type="hidden" name="action" value="actualizar">
                    <div class="modal-body">
                        <input type="hidden" id="idEdit" class="form-control" name="idEdit">
                        <div class="form-group mb-3">
                            <label>Letra de la sección</label>
                            <input type="text" id="nombreEdit" class="form-control" name="nombreEdit" pattern="[A-Z]" maxlength="1" minlength="1" title="Debe ser una sola letra mayúscula" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Año</label>
                            <select class="form-control" name="añoEdit" id="añoEdit">
                                <option selected value="">Seleccione el año</option>
                                <?php $grados = $gradoModelo->obtenerTodosLosGrados();
                                while ($row = mysqli_fetch_array($grados)) {
                                    echo '<option value="' . $row["id_grado"] . '"> ' . $row["numero_anio"] . '° año';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label>Tutor</label>
                            <select class="form-control" name="tutorEdit" id="tutorEdit">
                                <option selected value="">Seleccione un tutor...</option>
                                <?php
                                include_once($_SERVER['DOCUMENT_ROOT'] . '/liceo/modelos/profesor_modelo.php');
                                $profesorModelo = new ProfesorModelo($conn);
                                $profesores = $profesorModelo->obtenerTodosLosProfesores();
                                while ($row = mysqli_fetch_array($profesores)) {
                                    echo '<option value="' . $row["id_profesor"] . '"> ' . $row["nombre"] . ' ' . $row['apellido'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="update-data" class="btn btn-primary btn-success">Guardar datos</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modulo mostrar -->
    <div class="modal" id="viewmodal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Datos de la Sección</h4>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="view_seccion_data"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Asignación de Estudiantes -->
    <div class="modal" id="asignacionModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Asignar Estudiantes a Sección</h4>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="seccion-info" class="alert alert-info mb-3"></div>
                    <div id="estudiantes-container">
                        <div class="text-center">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                        </div>
                    </div>
                    <div id="tutor-container" class="mt-3">
                        <!-- El dropdown del tutor se cargará aquí -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="btnAsignar" onclick="asignarEstudiantesSeleccionados()">Asignar Seleccionados</button>
                </div>
            </div>
        </div>
    </div>

    <!-- modulo crear -->
    <div class="modal" id="insertdata" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Agregar secciones para un grado</h4>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/liceo/controladores/seccion_controlador.php" method="POST">
                    <input type="hidden" name="action" value="crear">
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label>Cantidad de secciones a crear</label>
                            <input type="number" class="form-control" name="cantidad" pattern="" min="1" max="7" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Grado</label>
                            <select class="form-control" name="grado" required>
                                <option selected value="">Seleccione el año</option>
                                <?php $grados = $gradoModelo->obtenerTodosLosGrados();
                                while ($row = mysqli_fetch_array($grados)) {
                                    echo '<option value="' . $row["id_grado"] . '"> ' . $row["numero_anio"] . '° año';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="save_data" class="btn btn-success">Guardar datos</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        new DataTable('#myTable', {
            language: {
                search: 'Buscar',
                info: 'Mostrando pagina _PAGE_ de _PAGES_',
                infoEmpty: 'No se han encontrado resultados',
                infoFiltered: '(se han encontrado _MAX_ resultados)',
                lengthMenu: 'Mostrar _MENU_ por pagina',
                zeroRecords: '0 resultados encontrados',
            },
            columnDefs: [{
                    width: '93px',
                    targets: [2, 3, 4]
                },
                {
                    visible: false,
                    target: 0
                }
            ],
            order: [2, 'asc'],
        });

        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-toggle="tooltip"]'));
            tooltipTriggerList.forEach(function(tooltipTriggerEl) {
                new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });

        // Función universal para abrir modales
        function abrirModal(modalId) {
            // Cerrar cualquier modal abierto
            $('.modal').removeClass('in').hide();
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
            
            // Abrir el modal solicitado
            $('#' + modalId).addClass('in').show();
            $('body').addClass('modal-open');
            
            // Forzar backdrop manualmente si es necesario
            if (!$('.modal-backdrop').length) {
                $('body').append('<div class="modal-backdrop in"></div>');
            }
        }
        
        // Cerrar modales
        function cerrarModal(modalId) {
            $('#' + modalId).removeClass('in').hide();
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
        }

        $(document).ready(function() {
            // Mostrar
            $('#myTable').on('click', '.view-data', function(e) {
                e.preventDefault();
                var tabla = $('#myTable').DataTable();

                // obtenemos la fila DataTables desde el botón clicado
                var fila = tabla.row($(this).closest('tr'));

                // traemos los datos de esa fila (array con todas las columnas)
                var data = fila.data();

                var id = data[0];
                $.ajax({
                    type: "POST",
                    url: "/liceo/controladores/seccion_controlador.php",
                    data: {
                        'action': 'ver',
                        'id_seccion': id
                    },
                    success: function(response) {
                        $('.view_seccion_data').html(response);
                        abrirModal('viewmodal');
                    }
                });
            });

            // Cargar para Editar
            $('#myTable').on('click', '.edit-data', function(e) {
                e.preventDefault();
                 var tabla = $('#myTable').DataTable();

                // obtenemos la fila DataTables desde el botón clicado
                var fila = tabla.row($(this).closest('tr'));

                // traemos los datos de esa fila (array con todas las columnas)
                var data = fila.data();

                
                var id = data[0];
                $.ajax({
                    type: "POST",
                    url: "/liceo/controladores/seccion_controlador.php",
                    data: {
                        'action': 'editar',
                        'id_seccion': id
                    },
                    dataType: 'json',
                    success: function(response) {
                        var data = response[0];
                        $('#idEdit').val(data.id_seccion);
                        $('#nombreEdit').val(data.letra);
                        $('#añoEdit').val(data.id_grado);
                        $('#tutorEdit').val(data.id_tutor);
                        abrirModal('editmodal');
                    }
                });
            });

            // Eliminar
            $('#myTable').on('click', '.delete-data', function(e) {
                e.preventDefault();
                var tabla = $('#myTable').DataTable();

                // obtenemos la fila DataTables desde el botón clicado
                var fila = tabla.row($(this).closest('tr'));

                // traemos los datos de esa fila (array con todas las columnas)
                var data = fila.data();

                
                var id = data[0];
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: '¡Esta acción eliminará la sección permanentemente!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "POST",
                            url: "/liceo/controladores/seccion_controlador.php",
                            data: {
                                'action': 'eliminar',
                                'id_seccion': id
                            },
                            success: function(response) {
                                Swal.fire('¡Eliminado!', response, 'success').then(() => location.reload());
                            }
                        });
                    }
                });
            });

            // Cerrar modales con botones
            $('[data-dismiss="modal"]').on('click', function() {
                var modal = $(this).closest('.modal');
                cerrarModal(modal.attr('id'));
            });
        });

        // Función para abrir modal de asignación de estudiantes
        function abrirAsignacionEstudiantes(idSeccion, nombreSeccion) {
            $('#seccion-info').html('<strong>Sección seleccionada:</strong> ' + nombreSeccion);
            abrirModal('asignacionModal');

            // Cargar estudiantes sin sección
            $.ajax({
                type: "POST",
                url: "/liceo/controladores/asignacion_estudiantes_controlador.php",
                data: {
                    'action': 'obtener_estudiantes',
                    'id_seccion': idSeccion
                },
                success: function(response) {
                    $('#estudiantes-container').html(response);

                    // Funcionalidad para seleccionar todos
                    $('#selectAll').change(function() {
                        $('.estudiante-checkbox').prop('checked', this.checked);
                    });

                    // Actualizar estado del checkbox "Seleccionar todos"
                    $('.estudiante-checkbox').change(function() {
                        var total = $('.estudiante-checkbox').length;
                        var checked = $('.estudiante-checkbox:checked').length;
                        $('#selectAll').prop('checked', total === checked);
                    });
                }
            });
             // Cargar profesores para el dropdown de tutores
            $.ajax({
                type: "POST",
                url: "/liceo/controladores/asignacion_estudiantes_controlador.php",
                data: {
                    'action': 'obtener_profesores'
                },
                success: function(response) {
                    $('#tutor-container').html(response);
                }
            });
        }

        // Función para asignar estudiantes seleccionados
        function asignarEstudiantesSeleccionados() {
            var estudiantesSeleccionados = [];
            $('.estudiante-checkbox:checked').each(function() {
                estudiantesSeleccionados.push($(this).val());
            });

            if (estudiantesSeleccionados.length === 0) {
                Swal.fire('Atención', 'Debe seleccionar al menos un estudiante', 'warning');
                return;
            }

            var idSeccion = $('#seccion_asignar').val();
            var idTutor = $('#tutor_id').val();

            if (!idTutor) {
                Swal.fire('Atención', 'Debe seleccionar un tutor para la sección', 'warning');
                return;
            }

            Swal.fire({
                title: '¿Confirmar asignación?',
                text: 'Se asignarán ' + estudiantesSeleccionados.length + ' estudiante(s) a esta sección',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, asignar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "/liceo/controladores/asignacion_estudiantes_controlador.php",
                        data: {
                            'action': 'asignar_masiva',
                            'estudiantes': estudiantesSeleccionados,
                            'id_seccion': idSeccion,
                            'id_tutor': idTutor
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('¡Éxito!', 'Estudiantes asignados correctamente', 'success').then(() => {
                                    cerrarModal('asignacionModal');
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Error', 'Hubo un problema al asignar los estudiantes', 'error');
                            }
                        }
                    });
                }
            });
        }
    </script>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/footer.php') ?>
    </footer>
</body>

</html>