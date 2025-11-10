<!DOCTYPE html>
<html lang="en">

<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/head.php'); ?>
    <link rel="stylesheet" href="../includes/backdrop.css">
    <title>Parroquia</title>
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
                        <h4>Parroquia <img src="/liceo/icons/people.svg">
                            <button type="button" class="btn btn-primary float-end btn-success" data-toggle="modal" data-target="#insertdata">
                                Agregar
                            </button>
                        </h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped" id="myTable">
                            <thead>
                                <tr class="table-secondary">
                                    <th style="display: none;" scope="col">#</th>
                                    <th scope="col">Parroquia</th>
                                    <th scope="col">Municipio</th>
                                    <th scope="col" class="action">Acción</th>
                                    <th scope="col" class="action"></th>
                                    <th scope="col" class="action"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($materias && mysqli_num_rows($materias) > 0) {
                                    while ($row = mysqli_fetch_array($materias)) {
                                ?>
                                        <tr>
                                            <td class="id" style="display: none;"> <?php echo $row['id_parroquia'] ?> </td>
                                            <td> <?php echo $row['parroquia'] ?> </td>
                                            <td> <?php echo $row['municipio'] ?> </td>
                                            <td><a href="#" class="btn btn-warning btn-sm view-data">Consultar</a></td>
                                            <td><a href="#" class="btn btn-primary btn-sm edit-data">Modificar</a></td>
                                            <td><a href="#" class="btn btn-danger btn-sm delete-data">Eliminar</a></td>
                                        </tr>
                                    <?php }
                                } else { ?>
                                    <tr>
                                        <td></td>
                                        <td>No se encontraron registros de parroquias.</td>
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
                    <h4 class="modal-title">Modificar Parroquía</h4>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="edit-form" action="/liceo/controladores/parroquia_controlador.php" method="POST">
                    <input type="hidden" name="action" value="actualizar">
                    <div class="modal-body">
                        <input type="hidden" id="idEdit" class="form-control" name="idEdit">
                        <div class="form-group mb-3">
                            <label>Nombre de la parroquia</label>
                            <input type="text" id="parroquia_edit" class="form-control" name="parroquia_edit" required pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+" title="Solo se permiten letras y espacios">
                        </div>
                        <div class="form-group mb-3">
                            <label>Municipio</label>
                            <select id="id_municipio_edit" class="form-control" name="id_municipio_edit" required>
                                <option value="">Seleccione un municipio</option>
                                <?php if (isset($municipios)) {
                                    mysqli_data_seek($municipios, 0);
                                    while ($m = mysqli_fetch_array($municipios)) { ?>
                                        <option value="<?php echo $m['id_municipio']; ?>"><?php echo $m['municipio']; ?></option>
                                <?php }
                                } ?>
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
                    <h4 class="modal-title">Datos de la Parroquia</h4>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="view_user_data"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- modulo crear -->
    <div class="modal" id="insertdata" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Agregar una nueva parroquia</h4>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/liceo/controladores/parroquia_controlador.php" method="POST">
                    <input type="hidden" name="action" value="crear">
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label>Nombre de la parroquia</label>
                            <input type="text" name="parroquia" class="form-control" required pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+" title="Solo se permiten letras y espacios">
                        </div>
                        <div class="form-group mb-3">
                            <label>Municipio</label>
                            <select name="id_municipio" class="form-control" required>
                                <option value="">Seleccione un municipio</option>
                                <?php if (isset($municipios)) {
                                    mysqli_data_seek($municipios, 0);
                                    while ($m2 = mysqli_fetch_array($municipios)) { ?>
                                        <option value="<?php echo $m2['id_municipio']; ?>"><?php echo $m2['municipio']; ?></option>
                                <?php }
                                } ?>
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
                zeroRecords: '0 resultados encontrados'
            },
            columnDefs: [{
                    width: '93px',
                    targets: [3, 4, 5]
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
                    url: "/liceo/controladores/parroquia_controlador.php",
                    data: {
                        'action': 'ver',
                        'id': id
                    },
                    success: function(response) {
                        $('.view_user_data').html(response);
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
                    url: "/liceo/controladores/parroquia_controlador.php",
                    data: {
                        'action': 'editar',
                        'id': id
                    },
                    dataType: 'json',
                    success: function(response) {
                        var data = response[0];
                        $('#idEdit').val(data.id_parroquia);
                        $('#parroquia_edit').val(data.parroquia);
                        $('#id_municipio_edit').val(data.id_municipio);
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
                    text: '¡Esta acción eliminará la parroquia permanentemente!',
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
                            url: "/liceo/controladores/parroquia_controlador.php",
                            data: {
                                'action': 'eliminar',
                                'id': id
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