<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/head.php'); ?>
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
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php unset($_SESSION['status']); } ?>
                <div class="card">
                    <div class="card-header">
                        <h4>Profesores <img src="/liceo/icons/people.svg">
                            <button type="button" class="btn btn-primary float-end btn-success" data-bs-toggle="modal" data-bs-target="#insertdata">
                                Inscribir
                            </button>
                        </h4>
                    </div>
                    <div class="card-body">
                        <table style="margin-left: 40px; width:100%;" class="table table-striped" id="myTable">
                            <thead>
                                <tr class="table-secondary">
                                    <th style="display: none;" scope="col">#</th>
                                    <th scope="col">Nombres</th>
                                    <th scope="col">Apellidos</th>
                                    <th scope="col">C.I</th>
                                    <th scope="col">Contacto</th>
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
                                            <td class="id_profesores" style="display: none;"> <?php echo $row['id_profesores'] ?> </td>
                                            <td> <?php echo $row['nombre_profesores'] ?> </td>
                                            <td> <?php echo $row['apellido_profesores'] ?> </td>
                                            <td> <?php echo $row['cedula_profesores'] ?> </td>
                                            <td> <?php echo $row['contacto_profesores'] ?> </td>
                                            <td><a href="#" class="btn btn-warning btn-sm view-data">Consultar</a></td>
                                            <td><a href="#" class="btn btn-primary btn-sm edit-data">Modificar</a></td>
                                            <td><a href="#" class="btn btn-danger btn-sm delete-data">Eliminar</a></td>
                                        </tr>
                                    <?php }
                                } else { ?>
                                    <tr><td colspan="8">No Record Found</td></tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modulo Editar -->
    <div class="modal fade" id="editmodal" tabindex="-1" aria-labelledby="editmodalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editmodalLabel">Editar Profesor</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="edit-form" action="/liceo/controladores/profesor_controlador.php" method="POST">
                    <input type="hidden" name="action" value="actualizar">
                    <div class="modal-body">
                        <input type="hidden" id="id_profesores_edit" name="id_profesores">
                        <div class="form-group mb-3">
                            <label>Nombres</label>
                            <input type="text" id="nombre_profesores_edit" class="form-control" name="nombre_profesores" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Apellidos</label>
                            <input type="text" id="apellido_profesores_edit" class="form-control" name="apellido_profesores" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Cédula</label>
                            <input type="text" id="cedula_profesores_edit" class="form-control" name="cedula_profesores" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Contacto</label>
                            <input type="text" id="contacto_profesores_edit" class="form-control" name="contacto_profesores" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Materia Impartida</label>
                            <input type="text" id="materia_impartida_edit" class="form-control" name="materia_impartida" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Sección</label>
                            <input type="text" id="seccion_profesores_edit" class="form-control" name="seccion_profesores" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="update-data" class="btn btn-primary btn-success">Editar datos</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modulo Mostrar -->
    <div class="modal fade" id="viewmodal" tabindex="-1" aria-labelledby="viewmodalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="viewmodalLabel">Datos del Profesor</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="view_profesores_data"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modulo Crear -->
    <div class="modal fade" id="insertdata" tabindex="-1" aria-labelledby="insertdataLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="insertdataLabel">Inscribe a un Nuevo Profesor</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/liceo/controladores/profesor_controlador.php" method="POST">
                    <input type="hidden" name="action" value="crear">
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label>Nombres</label>
                            <input type="text" class="form-control" name="nombre_profesores" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Apellidos</label>
                            <input type="text" class="form-control" name="apellido_profesores" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Cédula</label>
                            <input type="text" class="form-control" name="cedula_profesores" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Contacto</label>
                            <input type="text" class="form-control" name="contacto_profesores" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Materia Impartida</label>
                            <input type="text" class="form-control" name="materia_impartida" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Secciones a Impartir</label>
                            <input type="text" class="form-control" name="seccion_profesores" required>
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
            columnDefs: [{ width: '93px', targets: [5, 6, 7] }]
        });

        $(document).ready(function() {
            // Ver
            $(document).on('click', '.view-data', function(e) {
                e.preventDefault();
                var id = $(this).closest('tr').find('.id_profesores').text();
                $.ajax({
                    type: "POST",
                    url: "/liceo/controladores/profesor_controlador.php",
                    data: { 'action': 'ver', 'id_profesores': id },
                    success: function(response) {
                        $('.view_profesores_data').html(response);
                        $('#viewmodal').modal('show');
                    }
                });
            });

            // Cargar para Editar
            $(document).on('click', '.edit-data', function(e) {
                e.preventDefault();
                var id = $(this).closest('tr').find('.id_profesores').text();
                $.ajax({
                    type: "POST",
                    url: "/liceo/controladores/profesor_controlador.php",
                    data: { 'action': 'editar', 'id_profesores': id },
                    dataType: "json",
                    success: function(response) {
                        var data = response[0];
                        $('#id_profesores_edit').val(data.id_profesores);
                        $('#nombre_profesores_edit').val(data.nombre_profesores);
                        $('#apellido_profesores_edit').val(data.apellido_profesores);
                        $('#cedula_profesores_edit').val(data.cedula_profesores);
                        $('#contacto_profesores_edit').val(data.contacto_profesores);
                        $('#materia_impartida_edit').val(data.materia_impartida);
                        $('#seccion_profesores_edit').val(data.seccion_profesores);
                        $('#editmodal').modal('show');
                    }
                });
            });

            // Eliminar
            $(document).on('click', '.delete-data', function(e) {
                e.preventDefault();
                var id = $(this).closest('tr').find('.id_profesores').text();
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
                            data: { 'action': 'eliminar', 'id_profesores': id },
                            success: function(response) {
                                Swal.fire('¡Eliminado!', response, 'success').then(() => location.reload());
                            }
                        });
                    }
                });
            });
        });
    </script>

    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/footer.php') ?>
    </footer>
</body>
</html>
