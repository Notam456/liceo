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


    <!-- INTERFAZ -->
    <div class="container" style="margin-top: 30px;">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <?php
                if (isset($_SESSION['status']) && $_SESSION['status'] != '') {

                ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Hey!</strong> <?php echo $_SESSION['status']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php
                    unset($_SESSION['status']);
                }

                ?>

                <div class="card">
                    <div class="card-header">
                        <h4>Estudiantes <img src="/liceo/icons/people.svg">
                            <!-- Boton modulo crear -->
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
                                    <th scope="col">Numero</th>
                                    <th scope="col" class="action">Acción</th>
                                    <th scope="col" class="action"></th>
                                    <th scope="col" class="action"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $conn = mysqli_connect("localhost", "root", "", "liceo");

                                $fetch_query = "SELECT * FROM estudiante";
                                $fetch_query_run = mysqli_query($conn, $fetch_query);

                                if (mysqli_num_rows($fetch_query_run) > 0) {
                                    while ($row = mysqli_fetch_array($fetch_query_run)) {
                                        // echo $row['id_estudiante'];
                                ?>
                                        <tr>
                                            <td class="id_estudiante" style="display: none;"> <?php echo $row['id_estudiante'] ?> </td>
                                            <td> <?php echo $row['nombre_estudiante'] ?> </td>
                                            <td> <?php echo $row['apellido_estudiante'] ?> </td>
                                            <td> <?php echo $row['cedula_estudiante'] ?> </td>
                                            <td> <?php echo $row['contacto_estudiante'] ?> </td>

                                            <td>
                                                <a href="" class="btn btn-warning btn-sm view-data">Consultar</a>
                                            </td>

                                            <td>
                                                <a href="" class="btn btn-primary btn-sm edit-data">Modificar</a>
                                            </td>

                                            <td>
                                                <input type="hidden" class="delete_id_sala" value=" <?php echo $row['id_estudiante'] ?> ">
                                                <a href="" id="delete-sala" class="btn btn-danger btn-sm delete-data">Eliminar</a>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                } else {
                                    ?>
                                    <tr colspan="4">No Record Found</tr>
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

    <!-- Modulo editar -->
    <div class="modal fade" id="editmodal" tabindex="-1" aria-labelledby="editmodalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editmodalLabel">Editar</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="edit-form" action="conn_estudiantes.php" method="POST">
                    <div class="modal-body">

                        <div class="form-group mb-3">
                            <input type="hidden" id="id" class="form-control" name="id_estudiante">
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Nombres</label>
                            <input type="text" id="nombre_estudianteEdit" class="form-control" name="nombre_estudiante"
                                pattern="[A-Za-záéíóúÁÉÍÓÚÑñ\s]+" maxlength="50" minlength="5"
                                placeholder="Edite los Nombres del estudiante" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Apellidos</label>
                            <input type="text" id="apellido_estudianteEdit" class="form-control" name="apellido_estudiante"
                                pattern="[A-Za-záéíóúÁÉÍÓÚÑñ\s]+" maxlength="50" minlength="5"
                                placeholder="Edite los Apellidos del Estudiante" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Cedula del Estudiante</label>
                            <input type="text" id="cedula_estudianteEdit" class="form-control" name="cedula_estudiante"
                                pattern="0|[1-9][0-9]*" maxlength="50" minlength="5"
                                placeholder="Edite la Cedula del Estudiante" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Contacto del Estudiante</label>
                            <input type="text" id="contacto_estudianteEdit" class="form-control" name="contacto_estudiante"
                                pattern="0|[1-9][0-9]*" minlength="5"
                                placeholder="Edite el contacto del estudiante" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Municipio</label>
                            <input type="text" id="municipioEdit" class="form-control" name="Municipio"
                                pattern="[A-Za-záéíóúÁÉÍÓÚÑñ\s]+" minlength="5"
                                placeholder="Edite el Municipio" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Parroquia</label>
                            <input type="text" id="parroquiaEdit" class="form-control" name="Parroquia"
                                pattern="[A-Za-záéíóúÁÉÍÓÚÑñ\s]+" minlength="5"
                                placeholder="Edite la Parroquia" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Año Academico</label>
                            <input type="text" id="año_academicoEdit" class="form-control" name="año_academico"
                                pattern="0|[1-9][0-9]*" maxlength="50" minlength="1"
                                placeholder="Edite el Año Academico" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Seccion del Estudiante</label>
                            <input type="text" id="seccion_estudianteEdit" class="form-control" name="seccion_estudiante"
                                pattern="[A-Za-záéíóúÁÉÍÓÚÑñ\s]+" maxlength="50" minlength="1"
                                placeholder="Edite la Seccion del Estudiante" required>

                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="update-btn" name="update-data" class="btn btn-primary btn-success">Editar datos</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modudlo mostrar -->
    <div class="modal fade" id="viewmodal" tabindex="-1" aria-labelledby="viewmodalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="viewmodalLabel">Datos</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="view_estudiante_data">

                    </div>
                </div>
                <div class="modal-footer">
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
                <form action="conn_estudiantes.php" method="POST">
                    <?php

                    if (isset($_POST['id_estudiante'])) {

                        $nombre_estudiante = $_POST['nombre_estudiante'];
                        $apellido_estudiante = $_POST['apellido_estudiante'];
                        $cedula_estudiante = $_POST['cedula_estudiante'];
                        $contacto_estudiante = $_POST['contacto_estudiante'];
                        $contacto_estudiante = $_POST['Municipio'];
                        $contacto_estudiante = $_POST['Parroquia'];
                        $año_academico = $_POST['año_academico'];
                        $seccion_estudiante = $_POST['seccion_estudiante'];

                        $campos = array();

                        if ($nombre == "") {
                            array_push($campos, "Este campo no puede estar vacío");
                        }
                    }

                    ?>
                    <div class="modal-body">

                        <div class="form-group mb-3">
                            <input type="hidden" id="id_estudiante" class="form-control" name="id_estudiante">
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Nombres</label>
                            <input type="text" id="nombre_estudiante" class="form-control" name="nombre_estudiante"
                                pattern="[A-Za-záéíóúÁÉÍÓÚÑñ\s]+" maxlength="50" minlength="5"
                                placeholder="Edite los Nombres del estudiante" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Apellidos</label>
                            <input type="text" id="apellido_estudiante" class="form-control" name="apellido_estudiante"
                                pattern="[A-Za-záéíóúÁÉÍÓÚÑñ\s]+" maxlength="50" minlength="5"
                                placeholder="Edite los Apellidos del Estudiante" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Cedula del Estudiante</label>
                            <input type="text" id="cedula_estudiante" class="form-control" name="cedula_estudiante"
                                pattern="0|[1-9][0-9]*" maxlength="50" minlength="5"
                                placeholder="Edite la Cedula del Estudiante" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Contacto del Estudiante</label>
                            <input type="text" id="contacto_estudiante" class="form-control" name="contacto_estudiante"
                                pattern="0|[1-9][0-9]*" minlength="5"
                                placeholder="Edite el contacto del estudiante" required>
                        </div>

                        
                        <div class="form-group mb-3">
                            <label for="">Municipio</label>
                            <input type="text" id="Municipio" class="form-control" name="Municipio"
                                pattern="[A-Za-záéíóúÁÉÍÓÚÑñ\s]+" minlength="5"
                                placeholder="Edite el Municipio" required>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="">Parroquia</label>
                            <input type="text" id="Parroquia" class="form-control" name="Parroquia"
                                pattern="[A-Za-záéíóúÁÉÍÓÚÑñ\s]+" minlength="5"
                                placeholder="Edite la Parroquia" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Año Academico</label>
                            <input type="text" id="año_academico" class="form-control" name="año_academico"
                                pattern="0|[1-9][0-9]*" maxlength="50" minlength="1"
                                placeholder="Edite el Año Academico" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Seccion del Estudiante</label>
                            <input type="text" id="seccion_estudiante" class="form-control" name="seccion_estudiante"
                                pattern="[A-Za-záéíóúÁÉÍÓÚÑñ\s]+" maxlength="50" minlength="1"
                                placeholder="Edite la Seccion del Estudiante" required>

                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="save_data" class="btn btn-success">Guardar datos</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modudlo delete -->
    <div class="modal fade" id="deletemodal" tabindex="-1" aria-labelledby="deletemodalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="deletemodalLabel">Estudiantesss</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="post">
                    <div class="modal-body">
                        <input type="hidden" class="form-control" id="confirm_id_sala" name="confirm_id_sala">
                        <h4>¿Estas seguro de querer eliminar este Estudiante?</h4>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="delete_data" class="btn btn-primary btn-warning">Eliminar datos</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="load_salas"></div>

    <script>
        // tabla
        new DataTable('#myTable', {
            language: {
                //url: '//cdn.datatables.net/plug-ins/2.1.2/i18n/es-ES.json',
                search: 'Buscar',
                info: 'Mostrando pagina _PAGE_ de _PAGES_',
                infoEmpty: 'No se han encontrado resultados',
                infoFiltered: '(se han encontrado _MAX_ resultados)',
                lengthMenu: 'Mostrar _MENU_ por pagina',
                zeroRecords: '0 resultados encontrados'
            }
            , columnDefs: [{ width: '93px', targets: [5,6,7] }]
        });

        // Mostrar script
        $(document).ready(function() {
            $('#myTable').on('click', '.view-data', function(e) {
                e.preventDefault();

                var id = $(this).closest('tr').find('.id_estudiante').text();

                $.ajax({
                    type: "POST",
                    url: "conn_estudiantes.php",
                    data: {
                        'click-view-btn': true,
                        'id_estudiante': id,
                    },
                    success: function(response) {
                        $('.view_estudiante_data').html(response);
                        $('#viewmodal').modal('show');
                    }
                });
            });
        });

        // Editar script
        $(document).ready(function() {
            $('#myTable').on('click', '.edit-data', function(e) {
                e.preventDefault();

                var id = $(this).closest('tr').find('.id_estudiante').text();
                console.log(id);
                $.ajax({
                    type: "POST",
                    url: "conn_estudiantes.php",
                    data: {
                        'click-edit-btn': true,
                        'id_estudiante': id,
                    },
                    success: function(response) {
                        $.each(response, function(Key, value) {
                            console.log(response)
                            $('#id').val(value['id_estudiante']);
                            $('#nombre_estudianteEdit').val(value['nombre_estudiante']);
                            $('#apellido_estudianteEdit').val(value['apellido_estudiante']);
                            $('#cedula_estudianteEdit').val(value['cedula_estudiante']);
                            $('#contacto_estudianteEdit').val(value['contacto_estudiante']);
                            $('#municipioEdit').val(value['Municipio']);
                            $('#parroquiaEdit').val(value['Parroquia']);
                            $('#año_academicoEdit').val(value['año_academico']);
                            $('#seccion_estudianteEdit').val(value['seccion_estudiante']);
                        });

                        $('#editmodal').modal('show');
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        console.log(thrownError)
                        alert(xhr.status);
                        alert(thrownError);
                    }
                });
            });
        });

        // update script

        //eliminar script
        $(document).ready(function() {
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
                            url: "conn_estudiantes.php",
                            data: {
                                "click-delete-btn": true,
                                "id_estudiante": id,
                            },
                            success: function(response) {
                                Swal.fire(
                                    '¡Eliminado!',
                                    'El estudiante ha sido eliminado correctamente.',
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

    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/footer.php') ?>
    </footer>

</body>

</html>