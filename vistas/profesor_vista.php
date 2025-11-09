<!DOCTYPE html>
<html lang="en">

<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/head.php'); ?>
    <link rel="stylesheet" href="../includes/backdrop.css">
    <title>Profesores</title>
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
                        <h4>Profesor <img src="/liceo/icons/people.svg">
                            <button type="button" class="btn btn-primary float-end btn-success" data-toggle="modal" data-target="#insertdata">
                                Agregar
                            </button>
                            <a href="/liceo/controladores/profesor_controlador.php?action=generar_reporte_profesores"
                                target="_blank"
                                class="btn btn-secondary float-end me-2">
                                Reporte de Profesores
                            </a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped" id="myTable">
                            <thead>
                                <tr class="table-secondary">
                                    <th style="display: none;" scope="col">#</th>
                                    <th scope="col">Nombres</th>
                                    <th scope="col">Apellidos</th>
                                    <th scope="col">C.I</th>
                                    <th scope="col" class="action">Acción</th>
                                    <th scope="col" class="action"></th>
                                    <th scope="col" class="action"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($profesores && mysqli_num_rows($profesores) > 0) {
                                    while ($row = mysqli_fetch_array($profesores)) {
                                ?>
                                        <tr>
                                            <td class="id_profesor" style="display: none;"> <?php echo $row['id_profesor'] ?> </td>
                                            <td> <?php echo $row['nombre'] ?> </td>
                                            <td> <?php echo $row['apellido'] ?> </td>
                                            <td> <?php echo $row['cedula'] ?> </td>
                                            <td><a href="#" class="btn btn-warning btn-sm view-data">Consultar</a></td>
                                            <td><a href="#" class="btn btn-primary btn-sm edit-data">Modificar</a></td>
                                            <td><a href="#" class="btn btn-danger btn-sm delete-data">Eliminar</a></td>
                                        </tr>
                                    <?php }
                                } else { ?>
                                    <tr>
                                        <td style="display: none;"></td>
                                        <td>No se encontraron registros de profesores.</td>
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
                    <h4 class="modal-title">Modificar Profesor</h4>
                </div>
                <form id="edit-form" action="/liceo/controladores/profesor_controlador.php" method="POST">
                    <input type="hidden" name="action" value="actualizar">
                    <div class="modal-body">
                        <input type="hidden" id="id_profesor_edit" name="id_profesor">
                        <div class="form-group mb-3">
                            <label>Cédula</label>
                            <input type="text" id="cedula_profesor_edit" class="form-control" name="cedula_profesor" required pattern="\d{7,8}" title="La cédula debe contener entre 7 y 8 dígitos numéricos">
                        </div>
                        <div class="form-group mb-3">
                            <label>Nombres</label>
                            <input type="text" id="nombre_profesor_edit" class="form-control" name="nombre_profesor" required pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+" title="Solo se permiten letras y espacios">
                        </div>
                        <div class="form-group mb-3">
                            <label>Apellidos</label>
                            <input type="text" id="apellido_profesor_edit" class="form-control" name="apellido_profesor" required pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+" title="Solo se permiten letras y espacios">
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
                    <h4 class="modal-title">Datos del Profesor</h4>
                </div>
                <div class="modal-body">
                    <div class="view_profesor_data"></div>
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
                    <h4 class="modal-title">Agregar Nuevo Profesor</h4>
                </div>
                <form action="/liceo/controladores/profesor_controlador.php" method="POST">
                    <input type="hidden" name="action" value="crear">
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label>Cédula</label>
                            <input type="text" class="form-control" name="cedula_profesor" required pattern="\d{7,8}" title="La cédula debe contener entre 7 y 8 dígitos numéricos">
                        </div>
                        <div class="form-group mb-3">
                            <label>Nombres</label>
                            <input type="text" class="form-control" name="nombre_profesor" required pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+" title="Solo se permiten letras y espacios">
                        </div>
                        <div class="form-group mb-3">
                            <label>Apellidos</label>
                            <input type="text" class="form-control" name="apellido_profesor" required pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+" title="Solo se permiten letras y espacios">
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
                    url: "/liceo/controladores/profesor_controlador.php",
                    data: {
                        'action': 'ver',
                        'id_profesor': id
                    },
                    success: function(response) {
                        $('.view_profesor_data').html(response);
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
                    url: "/liceo/controladores/profesor_controlador.php",
                    data: {
                        'action': 'editar',
                        'id_profesor': id
                    },
                    dataType: "json",
                    success: function(response) {
                        var data = response[0];
                        $('#id_profesor_edit').val(data.id_profesor);
                        $('#nombre_profesor_edit').val(data.nombre);
                        $('#apellido_profesor_edit').val(data.apellido);
                        $('#cedula_profesor_edit').val(data.cedula);
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
                    text: '¡Esta acción eliminará el registro permanentemente!',
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
                            url: "/liceo/controladores/profesor_controlador.php",
                            data: {
                                'action': 'eliminar',
                                'id_profesor': id
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