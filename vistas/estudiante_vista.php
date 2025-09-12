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
                <?php unset($_SESSION['status']);
                } ?>
                <div class="card">
                    <div class="card-header">
                        <h4>Estudiantes <img src="/liceo/icons/people.svg">
                            <button type="button" class="btn btn-primary float-end btn-success" data-bs-toggle="modal" data-bs-target="#insertdata">
                                Inscribir
                            </button>
                        </h4>
                    </div>
                    <div class="card-body">
                        <table  class="table table-striped" id="myTable">
                            <thead>
                                <tr class="table-secondary">
                                    <th scope="col">#</th>
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
                                            <td class="id_estudiante"> <?php echo $row['id_estudiante'] ?> </td>
                                            <td> <?php echo $row['nombre'] ?> </td>
                                            <td> <?php echo $row['apellido'] ?> </td>
                                            <td> <?php echo $row['cedula'] ?> </td>
                                            <td> <?php echo $row['contacto'] ?> </td>
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
                                    <tr>
                                        <td> </td>
                                        <td> No se encontraron registros</td>
                                        <td> </td>
                                        <td> </td>
                                        <td> </td>
                                        <td> </td>
                                        <td> </td>
                                        <td> </td>
                                        <td> </td>
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
                            <input type="text" id="nombre_estudiante_edit" class="form-control" name="nombre_estudiante" required pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+" title="Solo se permiten letras y espacios">
                        </div>
                        <div class="form-group mb-3">
                            <label>Apellidos</label>
                            <input type="text" id="apellido_estudiante_edit" class="form-control" name="apellido_estudiante" required pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+" title="Solo se permiten letras y espacios">
                        </div>
                        <div class="form-group mb-3">
                            <label>Cédula</label>
                            <input type="text" id="cedula_estudiante_edit" class="form-control" name="cedula_estudiante" required pattern="\d{7,8}" title="La cédula debe contener entre 7 y 8 dígitos numéricos">
                        </div>
                        <div class="form-group mb-3">
                            <label>Contacto</label>
                            <input type="text" id="contacto_estudiante_edit" class="form-control" name="contacto_estudiante" required pattern="\d{11}" title="El número de contacto debe contener 11 dígitos numéricos (ej: 04141234567)">
                        </div>
                        <div class="form-group mb-3">
                            <label>Fecha de Nacimiento</label>
                            <input type="date" id="fecha_nacimiento_edit" name="fecha_nacimiento" class="form-control" required>
                        </div>
                        <div class="form-group mb-3"><label>Parroquia</label>
                            <select id='parroquia_edit' name="parroquia" class="form-control" required>
                                <?php $parroquias = $parroquiaModelo->obtenerTodasLasParroquias();
                                while ($row = mysqli_fetch_array($parroquias)) {
                                    echo '<option value="' . $row["id_parroquia"] . '"> ' . $row["parroquia"];
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group mb-3"><label>Grado a cursar</label>
                            <select id="grado_edit" name="grado" class="form-control" required>
                                <?php $grados = $gradoModelo->obtenerTodosLosGrados();
                                while ($row = mysqli_fetch_array($grados)) {
                                    echo '<option value="' . $row["id_grado"] . '"> ' . $row["numero_anio"] . '° año';
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

    <!-- Modulo mostrar -->
    <div class="modal fade" id="viewmodal" tabindex="-1" aria-labelledby="viewmodalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="viewmodalLabel">Datos del Estudiante</h1>
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
                        <div class="form-group mb-3"><label>Nombres</label><input type="text" name="nombre_estudiante" class="form-control" required pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+" title="Solo se permiten letras y espacios"></div>
                        <div class="form-group mb-3"><label>Apellidos</label><input type="text" name="apellido_estudiante" class="form-control" required pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+" title="Solo se permiten letras y espacios"></div>
                        <div class="form-group mb-3"><label>Cédula</label><input type="text" name="cedula_estudiante" class="form-control" required pattern="\d{7,8}" title="La cédula debe contener entre 7 y 8 dígitos numéricos"></div>
                        <div class="form-group mb-3"><label>Contacto</label><input type="text" name="contacto_estudiante" class="form-control" required pattern="\d{11}" title="El número de contacto debe contener 11 dígitos numéricos (ej: 04141234567)"></div>
                        <div class="form-group mb-3"><label>Fecha de Nacimiento</label><input type="date" name="fecha_nacimiento" class="form-control" required></div>
                        <div class="form-group mb-3"><label>Parroquia</label>
                            <select name="parroquia" class="form-control" required>
                                <?php $parroquias = $parroquiaModelo->obtenerTodasLasParroquias();
                                while ($row = mysqli_fetch_array($parroquias)) {
                                    echo '<option value="' . $row["id_parroquia"] . '"> ' . $row["parroquia"];
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group mb-3"><label>Grado a cursar</label>
                            <select name="grado" class="form-control" required>
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
                zeroRecords: '0 resultados encontrados'
            },
            columnDefs: [{
                    width: '93px',
                    targets: [5, 6, 7, 8]
                },
                {
                    visible: false,
                    target: 0
                }
            ]
        });

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
                    url: "/liceo/controladores/estudiante_controlador.php",
                    data: {
                        'action': 'ver',
                        'id_estudiante': id
                    },
                    success: function(response) {
                        $('.view_estudiante_data').html(response);
                        $('#viewmodal').modal('show');
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
                    url: "/liceo/controladores/estudiante_controlador.php",
                    data: {
                        'action': 'editar',
                        'id_estudiante': id
                    },
                    dataType: 'json',
                    success: function(response) {
                        var data = response[0];
                        $('#id_estudiante_edit').val(data.id_estudiante);
                        $('#nombre_estudiante_edit').val(data.nombre);
                        $('#apellido_estudiante_edit').val(data.apellido);
                        $('#cedula_estudiante_edit').val(data.cedula);
                        $('#contacto_estudiante_edit').val(data.contacto);
                        $('#parroquia_edit').val(data.id_parroquia);
                        $('#grado_edit').val(data.id_grado);
                        $('#fecha_nacimiento_edit').val(data.fecha_nacimiento);

                        $('#editmodal').modal('show');
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
                            data: {
                                'action': 'eliminar',
                                'id_estudiante': id
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