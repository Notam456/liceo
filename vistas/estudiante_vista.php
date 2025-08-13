<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/head.php'); ?>
    <title>Estudiantes</title>
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
                        <h4>Estudiantes <img src="/liceo/icons/people.svg">
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
                                    <th scope="col" class="action">Constancia</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($estudiantes && mysqli_num_rows($estudiantes) > 0) {
                                    while ($row = mysqli_fetch_array($estudiantes)) {
                                ?>
                                        <tr>
                                            <td class="id_estudiante" style="display: none;"> <?php echo $row['id_estudiante'] ?> </td>
                                            <td> <?php echo $row['nombre_estudiante'] ?> </td>
                                            <td> <?php echo $row['apellido_estudiante'] ?> </td>
                                            <td> <?php echo $row['cedula_estudiante'] ?> </td>
                                            <td> <?php echo $row['contacto_estudiante'] ?> </td>
                                            <td><a href="#" class="btn btn-warning btn-sm view-data">Consultar</a></td>
                                            <td><a href="#" class="btn btn-primary btn-sm edit-data">Modificar</a></td>
                                            <td><a href="#" class="btn btn-danger btn-sm delete-data">Eliminar</a></td>
                                            <td>
                                                <a target="_blank" href="/liceo/controladores/estudiante_controlador.php?action=generar_constancia&id=<?php echo $row['id_estudiante'] ?>"
                                                class="btn btn-secondary btn-sm">Generar</a>
                                            </td>
                                        </tr>
                                    <?php }
                                } else { ?>
                                    <tr><td colspan="9">No se encontraron registros</td></tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modulo editar -->
    <div class="modal fade" id="editmodal" tabindex="-1" aria-labelledby="editmodalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editmodalLabel">Editar</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="edit-form" action="/liceo/controladores/estudiante_controlador.php" method="POST">
                    <input type="hidden" name="action" value="actualizar">
                    <div class="modal-body">
                        <input type="hidden" id="id_estudiante_edit" class="form-control" name="id_estudiante">
                        <div class="form-group mb-3">
                            <label>Nombres</label>
                            <input type="text" id="nombre_estudiante_edit" class="form-control" name="nombre_estudiante" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Apellidos</label>
                            <input type="text" id="apellido_estudiante_edit" class="form-control" name="apellido_estudiante" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Cédula</label>
                            <input type="text" id="cedula_estudiante_edit" class="form-control" name="cedula_estudiante" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Contacto</label>
                            <input type="text" id="contacto_estudiante_edit" class="form-control" name="contacto_estudiante" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Municipio</label>
                            <input type="text" id="municipio_edit" class="form-control" name="Municipio" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Parroquia</label>
                            <input type="text" id="parroquia_edit" class="form-control" name="Parroquia" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Año Académico</label>
                            <input type="text" id="anio_academico_edit" class="form-control" name="año_academico" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Sección</label>
                            <input type="text" id="seccion_estudiante_edit" class="form-control" name="seccion_estudiante" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="update-data" class="btn btn-primary btn-success">Editar datos</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modulo mostrar -->
    <div class="modal fade" id="viewmodal" tabindex="-1" aria-labelledby="viewmodalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="viewmodalLabel">Datos</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="view_estudiante_data"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- modulo crear -->
    <div class="modal fade" id="insertdata" tabindex="-1" aria-labelledby="insertdataLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="insertdataLabel">Inscribe a un Nuevo Estudiante</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/liceo/controladores/estudiante_controlador.php" method="POST">
                    <input type="hidden" name="action" value="crear">
                    <div class="modal-body">
                        <div class="form-group mb-3"><label>Nombres</label><input type="text" name="nombre_estudiante" class="form-control" required></div>
                        <div class="form-group mb-3"><label>Apellidos</label><input type="text" name="apellido_estudiante" class="form-control" required></div>
                        <div class="form-group mb-3"><label>Cédula</label><input type="text" name="cedula_estudiante" class="form-control" required></div>
                        <div class="form-group mb-3"><label>Contacto</label><input type="text" name="contacto_estudiante" class="form-control" required></div>
                        <div class="form-group mb-3"><label>Municipio</label><input type="text" name="Municipio" class="form-control" required></div>
                        <div class="form-group mb-3"><label>Parroquia</label><input type="text" name="Parroquia" class="form-control" required></div>
                        <div class="form-group mb-3"><label>Año Académico</label><input type="text" name="año_academico" class="form-control" required></div>
                        <div class="form-group mb-3"><label>Sección</label><input type="text" name="seccion_estudiante" class="form-control" required></div>
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
            columnDefs: [{ width: '93px', targets: [5, 6, 7, 8] }]
        });

        $(document).ready(function() {
            // Mostrar
            $('#myTable').on('click', '.view-data', function(e) {
                e.preventDefault();
                var id = $(this).closest('tr').find('.id_estudiante').text();
                $.ajax({
                    type: "POST",
                    url: "/liceo/controladores/estudiante_controlador.php",
                    data: { 'action': 'ver', 'id_estudiante': id },
                    success: function(response) {
                        $('.view_estudiante_data').html(response);
                        $('#viewmodal').modal('show');
                    }
                });
            });

            // Cargar para Editar
            $('#myTable').on('click', '.edit-data', function(e) {
                e.preventDefault();
                var id = $(this).closest('tr').find('.id_estudiante').text();
                $.ajax({
                    type: "POST",
                    url: "/liceo/controladores/estudiante_controlador.php",
                    data: { 'action': 'editar', 'id_estudiante': id },
                    dataType: 'json',
                    success: function(response) {
                        var data = response[0];
                        $('#id_estudiante_edit').val(data.id_estudiante);
                        $('#nombre_estudiante_edit').val(data.nombre_estudiante);
                        $('#apellido_estudiante_edit').val(data.apellido_estudiante);
                        $('#cedula_estudiante_edit').val(data.cedula_estudiante);
                        $('#contacto_estudiante_edit').val(data.contacto_estudiante);
                        $('#municipio_edit').val(data.Municipio);
                        $('#parroquia_edit').val(data.Parroquia);
                        $('#anio_academico_edit').val(data.año_academico);
                        $('#seccion_estudiante_edit').val(data.seccion_estudiante);
                        $('#editmodal').modal('show');
                    }
                });
            });

            // Eliminar
            $('#myTable').on('click', '.delete-data', function(e) {
                e.preventDefault();
                var id = $(this).closest('tr').find('.id_estudiante').text();
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: '¡Esta acción eliminará al estudiante permanentemente!',
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
                            url: "/liceo/controladores/estudiante_controlador.php",
                            data: { 'action': 'eliminar', 'id_estudiante': id },
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
