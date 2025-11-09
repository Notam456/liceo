<!DOCTYPE html>
<html lang="en">

<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/head.php'); ?>
    <link rel="stylesheet" href="../includes/backdrop.css">
    <title>Asignar Cargos a Profesores</title>
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
                        <h4>Asignar Cargo a Profesor <img src="/liceo/icons/people.svg">
                            <button type="button" class="btn btn-primary float-end btn-success" data-toggle="modal" data-target="#insertdata">
                                Asignar
                            </button>
                        </h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped" id="myTable">
                            <thead>
                                <tr class="table-secondary">
                                    <th style="display: none;" scope="col">#</th>
                                    <th scope="col">Profesor</th>
                                    <th scope="col">Cargo</th>
                                    <th scope="col">Fecha Asignación</th>
                                    <th scope="col" class="action">Acción</th>
                                    <th scope="col" class="action"></th>
                                    <th scope="col" class="action"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($asignaciones)) {
                                    foreach ($asignaciones as $asignacion) {
                                ?>
                                        <tr>
                                            <td class="id_asignacion" style="display: none;"> <?php echo $asignacion['id_asignacion'] ?> </td>
                                            <td> 
                                                <strong><?php echo htmlspecialchars($asignacion['apellido'] . ', ' . $asignacion['nombre']) ?></strong>
                                            </td>
                                            <td> 
                                                <strong><?php echo htmlspecialchars($asignacion['nombre_cargo']) ?></strong>
                                            </td>
                                            <td> <?php echo date('d/m/Y H:i', strtotime($asignacion['fecha_asignacion'])) ?> </td>
                                            <td><a href="#" class="btn btn-warning btn-sm view-data">Consultar</a></td>
                                            <td><a href="#" class="btn btn-primary btn-sm edit-data">Modificar</a></td>
                                            <td><a href="#" class="btn btn-danger btn-sm delete-data">Eliminar</a></td>
                                        </tr>
                                    <?php }
                                } else { ?>
                                    <tr>
                                        <td style="display: none;"></td>
                                        <td>No se encontraron asignaciones de cargos.</td>
                                        <td></td>
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

    <!-- Modulo Editar -->
    <div class="modal" id="editmodal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Modificar Asignación de Cargo</h4>
                </div>
                <form id="edit-form" action="/liceo/controladores/asigna_cargo_controlador.php" method="POST">
                    <input type="hidden" name="action" value="actualizar">
                    <div class="modal-body">
                        <input type="hidden" id="id_asignacion_edit" name="id_asignacion">
                        <div class="form-group mb-3">
                            <label>Profesor</label>
                            <select id="id_profesor_edit" class="form-control" name="id_profesor" required>
                                <option value="">Seleccionar profesor</option>
                                <?php foreach ($profesores as $profesor): ?>
                                <option value="<?= $profesor['id_profesor'] ?>">
                                    <?= htmlspecialchars($profesor['apellido'] . ', ' . $profesor['nombre'] . ' (' . $profesor['cedula'] . ')') ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label>Cargo</label>
                            <select id="id_cargo_edit" class="form-control" name="id_cargo" required>
                                <option value="">Seleccionar cargo</option>
                                <?php foreach ($cargos as $cargo): ?>
                                <option value="<?= $cargo['id_cargo'] ?>">
                                    <?= htmlspecialchars($cargo['nombre']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        <button type="submit" name="update-data" class="btn btn-primary btn-success">Guardar datos</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modulo Mostrar -->
    <div class="modal" id="viewmodal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Datos de la Asignación</h4>
                </div>
                <div class="modal-body">
                    <div class="view_asignacion_data"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modulo Crear -->
    <div class="modal" id="insertdata" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Nueva Asignación de Cargo</h4>
                </div>
                <form action="/liceo/controladores/asigna_cargo_controlador.php" method="POST">
                    <input type="hidden" name="action" value="crear">
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label>Seleccionar Profesor</label>
                            <select class="form-control" name="id_profesor" required>
                                <option value="">Seleccionar profesor</option>
                                <?php foreach ($profesores as $profesor): ?>
                                <option value="<?= $profesor['id_profesor'] ?>">
                                    <?= htmlspecialchars($profesor['apellido'] . ', ' . $profesor['nombre'] . ' (' . $profesor['cedula'] . ')') ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label>Seleccionar Cargo</label>
                            <select class="form-control" name="id_cargo" required>
                                <option value="">Seleccionar cargo</option>
                                <?php foreach ($cargos as $cargo): ?>
                                <option value="<?= $cargo['id_cargo'] ?>">
                                    <?= htmlspecialchars($cargo['nombre']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
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
                zeroRecords: '0 resultados encontrados'
            },
            columnDefs: [{
                    width: '93px',
                    targets: [4, 5, 6]
                },
                {
                    visible: false,
                    target: 0
                }
            ]
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
            // Ver
            $(document).on('click', '.view-data', function(e) {
                e.preventDefault();
                var tabla = $('#myTable').DataTable();

                // obtenemos la fila DataTables desde el botón clicado
                var fila = tabla.row($(this).closest('tr'));

                // traemos los datos de esa fila (array con todas las columnas)
                var data = fila.data();

                var id = data[0];
                $.ajax({
                    type: "POST",
                    url: "/liceo/controladores/asigna_cargo_controlador.php",
                    data: {
                        'action': 'ver',
                        'id_asignacion': id
                    },
                    success: function(response) {
                        $('.view_asignacion_data').html(response);
                        abrirModal('viewmodal');
                    }
                });
            });

            // Cargar para Editar
            $(document).on('click', '.edit-data', function(e) {
                e.preventDefault();
                var tabla = $('#myTable').DataTable();

                // obtenemos la fila DataTables desde el botón clicado
                var fila = tabla.row($(this).closest('tr'));

                // traemos los datos de esa fila (array con todas las columnas)
                var data = fila.data();

                var id = data[0];
                $.ajax({
                    type: "POST",
                    url: "/liceo/controladores/asigna_cargo_controlador.php",
                    data: {
                        'action': 'editar',
                        'id_asignacion': id
                    },
                    dataType: "json",
                    success: function(response) {
                        var data = response[0];
                        $('#id_asignacion_edit').val(data.id_asignacion);
                        $('#id_profesor_edit').val(data.id_profesor);
                        $('#id_cargo_edit').val(data.id_cargo);
                        abrirModal('editmodal');
                    }
                });
            });

            // Eliminar
            $(document).on('click', '.delete-data', function(e) {
                e.preventDefault();
                var tabla = $('#myTable').DataTable();

                // obtenemos la fila DataTables desde el botón clicado
                var fila = tabla.row($(this).closest('tr'));

                // traemos los datos de esa fila (array con todas las columnas)
                var data = fila.data();

                var id = data[0];
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: '¡Esta acción eliminará la asignación permanentemente!',
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
                            url: "/liceo/controladores/asigna_cargo_controlador.php",
                            data: {
                                'action': 'eliminar',
                                'id_asignacion': id
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
    </script>

    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/footer.php') ?>
    </footer>
</body>

</html>