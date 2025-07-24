<?php
session_start();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/head.php'); ?>

    <title>Materia</title>
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
                        <h4>Materias <img src="/liceo/icons/people.svg">
                            <!-- Boton modulo crear -->
                            <button type="button" class="btn btn-primary float-end btn-success" data-bs-toggle="modal" data-bs-target="#insertdata">
                                Crear
                            </button>
                        </h4>
                    </div>
                    <div class="card-body">
                        <table style="margin-left: 40px; width: 109.2%;" class="table table-striped" id="myTable">
                            <thead>
                                <tr class="table-secondary">
                                    <th style="display: none;" scope="col">#</th>
                                    <th scope="col">Materia</th>
                                    <th scope="col">Descripción</th>
                                    <th scope="col" class="action">Acción</th>
                                    <th scope="col" class="action"></th>
                                    <th scope="col" class="action"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $conn = mysqli_connect("localhost", "root", "", "liceo");

                                $fetch_query = "SELECT * FROM materia";
                                $fetch_query_run = mysqli_query($conn, $fetch_query);

                                if (mysqli_num_rows($fetch_query_run) > 0) {
                                    while ($row = mysqli_fetch_array($fetch_query_run)) {
                                        // echo $row['id_estudiante'];
                                ?>
                                        <tr>
                                            <td class="id" style="display: none;"> <?php echo $row['id'] ?> </td>
                                            <td> <?php echo $row['nombre_materia'] ?> </td>
                                            <td> <?php echo $row['info_materia'] ?> </td>

                                            <td>
                                                <a href="" class="btn btn-warning btn-sm view-data">Consultar</a>
                                            </td>

                                            <td>
                                                <a href="" class="btn btn-primary btn-sm edit-data">Modificar</a>
                                            </td>

                                            <td>
                                                <input type="hidden" class="delete_id_sala" value=" <?php echo $row['id'] ?> ">
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
                <form id="edit-form" action="conn_materia.php" method="POST">
                    <div class="modal-body">

                        <div class="form-group mb-3">
                            <input type="hidden" id="idEdit" class="form-control" name="idEdit">
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Nombre de la Materia</label>
                            <input type="text" id="nombre_materia_edit" class="form-control" name="nombre_materia_edit"
                                pattern="[A-Za-záéíóúÁÉÍÓÚÑñ\s]+" maxlength="50" minlength="5"
                                placeholder="Edite el nombre de la Materia" required>
                        </div>


                        <div class="form-group mb-3">
                            <label for="">Descripción de la Materia</label>
                            <input type="text" id="info_materia_edit" class="form-control" name="info_materia_edit"
                                minlength="5"
                                placeholder="Edite la descripción de la Materia" required>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" id="update-btn" name="update-data" class="btn btn-primary btn-success">Editar datos</button>
                        </div>
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
                    <div class="view_user_data">

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
                    <h1 class="modal-title fs-5" id="insertdataLabel">Crea una nueva materia</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="conn_materia.php" method="POST">
                    <?php

                    if (isset($_POST['id'])) {

                        $nombre_estudiante = $_POST['nombre_materia'];
                        $apellido_estudiante = $_POST['info_materia'];

                        $campos = array();

                        if ($nombre == "") {
                            array_push($campos, "Este campo no puede estar vacío");
                        }
                    }

                    ?>
                    <div class="modal-body">

                        <div class="form-group mb-3">
                            <input type="hidden" id="id" class="form-control" name="id">
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Nombre de la Materia</label>
                            <input type="text" id="nombre_materia" class="form-control" name="nombre_materia"
                                pattern="[A-Za-záéíóúÁÉÍÓÚÑñ\s]+" maxlength="50" minlength="5"
                                placeholder="Ingrese el nombre de la materia" required>
                        </div>



                        <div class="form-group mb-3">
                            <label for="">Descripción de la Materia</label>
                            <input type="text" id="info_materia" class="form-control" name="info_materia"
                                minlength="5"
                                placeholder="Ingrese la descripción informativa de esta Materia" required>
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

            },
            columnDefs: [{
                width: '93px',
                targets: [3, 4, 5]
            }]
        });

        // Mostrar script
        $(document).ready(function() {
            $('#myTable').on('click', '.view-data', function(e) {
                e.preventDefault();

                var id = $(this).closest('tr').find('.id').text();

                $.ajax({
                    type: "POST",
                    url: "conn_materia.php",
                    data: {
                        'click-view-btn': true,
                        'id': id,
                    },
                    success: function(response) {
                        $('.view_user_data').html(response);
                        $('#viewmodal').modal('show');
                    }
                });
            });
        });

        // Editar script
        $(document).ready(function() {
            $('#myTable').on('click', '.edit-data', function(e) {
                e.preventDefault();

                var id = $(this).closest('tr').find('.id').text();

                $.ajax({
                    type: "POST",
                    url: "conn_materia.php",
                    data: {
                        'click-edit-btn': true,
                        'id': id,
                    },
                    success: function(response) {
                        $.each(response, function(Key, value) {
                            $('#idEdit').val(value['id']);
                            $('#nombre_materia_edit').val(value['nombre_materia']);
                            $('#info_materia_edit').val(value['info_materia']);
                        });

                        $('#editmodal').modal('show');
                    }
                });
            });
        });

        // update script

        //eliminar script
        $(document).ready(function() {
            $('#myTable').on('click', '.delete-data', function(e) {
                e.preventDefault();

                var id = $(this).closest('tr').find('.id').text();

                swal({
                    title: "¿Estas seguro?",
                    text: "Cuando elimines esta materia lo borraras permanentemente de la base de datos!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            type: "POST",
                            url: "conn_materia.php",
                            data: {
                                "click-delete-btn": true,
                                "id": id,
                            },
                            success: function(response) {
                                swal("Materia Eliminado Correctamente.!", {
                                    icon: "success",
                                }).then((result) => {
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