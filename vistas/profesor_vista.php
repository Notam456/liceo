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
                <?php unset($_SESSION['status']);
                } ?>
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
                                            <td class="id_profesor" style="display: none;"> <?php echo $row['id_profesor'] ?> </td>
                                            <td> <?php echo $row['nombre_profesor'] ?> </td>
                                            <td> <?php echo $row['apellido_profesor'] ?> </td>
                                            <td> <?php echo $row['cedula_profesor'] ?> </td>
                                            <td> <?php echo $row['contacto_profesor'] ?> </td>
                                            <td><a href="#" class="btn btn-warning btn-sm view-data">Consultar</a></td>
                                            <td><a href="#" class="btn btn-primary btn-sm edit-data">Modificar</a></td>
                                            <td><a href="#" class="btn btn-danger btn-sm delete-data">Eliminar</a></td>
                                        </tr>
                                    <?php }
                                } else { ?>
                                    <tr>
                                        <td colspan="8">No Record Found</td>
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
                        <input type="hidden" id="id_profesor_edit" name="id_profesor">
                        <div class="form-group mb-3">
                            <label>Nombres</label>
                            <input type="text" id="nombre_profesor_edit" class="form-control" name="nombre_profesor" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Apellidos</label>
                            <input type="text" id="apellido_profesor_edit" class="form-control" name="apellido_profesor" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Cédula</label>
                            <input type="text" id="cedula_profesor_edit" class="form-control" name="cedula_profesor" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Contacto</label>
                            <input type="text" id="contacto_profesor_edit" class="form-control" name="contacto_profesor" required>
                        </div>
                        <div class="mb-3">
                            <label>Materia Impartida</label>
                            <select name="id_materia" id="id_materia_edit" class="form-control" required>
                                <option value="">Selecciona una materia</option>
                                <?php
                                if ($materias) {
                                    mysqli_data_seek($materias, 0);
                                    while ($materia = mysqli_fetch_array($materias)) {
                                        echo '<option value="' . $materia['id_materia'] . '">' . $materia['nombre_materia'] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Sección Impartida</label>
                            <select name="id_seccion" id="id_seccion_edit" class="form-control" required>
                                <option value="">Selecciona una sección</option>
                                <?php
                                if ($secciones) {
                                    mysqli_data_seek($secciones, 0);
                                    while ($seccion = mysqli_fetch_array($secciones)) {
                                        echo '<option value="' . $seccion['id_seccion'] . '">' . $seccion['nombre'] . '</option>';
                                    }
                                }
                                ?>
                            </select>
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
                    <div class="view_profesor_data"></div>
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
                            <input type="text" class="form-control" name="nombre_profesor" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Apellidos</label>
                            <input type="text" class="form-control" name="apellido_profesor" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Cédula</label>
                            <input type="text" class="form-control" name="cedula_profesor" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Contacto</label>
                            <input type="text" class="form-control" name="contacto_profesor" required>
                        </div>
                        <div class="mb-3">
                            <label>Materia Impartida</label>
                            <select name="id_materia" class="form-control" required>
                                <option value="">Selecciona una materia</option>
                                <?php
                                if ($materias && mysqli_num_rows($materias) > 0) {
                                    mysqli_data_seek($materias, 0);
                                    while ($materia = mysqli_fetch_array($materias)) {
                                        echo '<option value="' . $materia['id_materia'] . '">' . $materia['nombre_materia'] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Sección Impartida</label>
                            <select name="id_seccion" class="form-control" required>
                                <option value="">Selecciona una sección</option>
                                <?php
                                if ($secciones && mysqli_num_rows($secciones) > 0) {
                                    mysqli_data_seek($secciones, 0);
                                    while ($seccion = mysqli_fetch_array($secciones)) {
                                        echo '<option value="' . $seccion['id_seccion'] . '">' . $seccion['nombre'] . '</option>';
                                    }
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
                zeroRecords: '0 resultados encontrados'
            },
            columnDefs: [{
                width: '93px',
                targets: [5, 6, 7]
            }]
        });

        $(document).ready(function() {
            // Ver
            $(document).on('click', '.view-data', function(e) {
                e.preventDefault();
                var id = $(this).closest('tr').find('.id_profesor').text();
                $.ajax({
                    type: "POST",
                    url: "/liceo/controladores/profesor_controlador.php",
                    data: {
                        'action': 'ver',
                        'id_profesor': id
                    },
                    success: function(response) {
                        $('.view_profesor_data').html(response);
                        $('#viewmodal').modal('show');
                    }
                });
            });

            // Cargar para Editar
            $(document).on('click', '.edit-data', function(e) {
                e.preventDefault();
                var id = $(this).closest('tr').find('.id_profesor').text();
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
                        $('#nombre_profesor_edit').val(data.nombre_profesor);
                        $('#apellido_profesor_edit').val(data.apellido_profesor);
                        $('#cedula_profesor_edit').val(data.cedula_profesor);
                        $('#contacto_profesor_edit').val(data.contacto_profesor);
                        $('#id_materia_edit').val(data.id_materia);
                        $('#id_seccion_edit').val(data.id_seccion);
                        $('#editmodal').modal('show');
                    }
                });
            });

            // Eliminar
            $(document).on('click', '.delete-data', function(e) {
                e.preventDefault();
                var id = $(this).closest('tr').find('.id_profesor').text();
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
        });
    </script>

    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/footer.php') ?>
    </footer>
</body>

</html>