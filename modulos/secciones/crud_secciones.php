<?php
session_start();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/head.php'); ?>

    <title>Secciones</title>
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
                        <h4>Secciones <img src="/liceo/icons/people.svg">
                            <!-- Boton modulo crear -->
                            <button type="button" class="btn btn-primary float-end btn-success" data-bs-toggle="modal" data-bs-target="#insertdata">
                                Crear
                            </button>
                        </h4>
                    </div>
                    <div class="card-body">
                        <table style="margin-left: 40px; width:130%;" class="table table-striped" id="myTable">
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
                                $conn = mysqli_connect("localhost", "root", "", "liceo");

                                $fetch_query = "SELECT * FROM seccion";
                                $fetch_query_run = mysqli_query($conn, $fetch_query);

                                if (mysqli_num_rows($fetch_query_run) > 0) {
                                    while ($row = mysqli_fetch_array($fetch_query_run)) {
                                        // echo $row['id_estudiante'];
                                ?>
                                        <tr>
                                            <td class="id_seccion" style="display: none;"> <?php echo $row['id'] ?> </td>
                                            <td> <?php echo $row['nombre'] ?> </td>
                                            <td> <?php echo $row['año'] ?> </td>

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
                <form id="edit-form" action="conn_secciones.php" method="POST">
                    <div class="modal-body">

                        <div class="form-group mb-3">
                            <input type="hidden" id="idEdit" class="form-control" name="idEdit">
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Nombre</label>
                            <input type="text" id="nombreEdit" class="form-control" name="nombreEdit"
                                pattern="[A-Z]" maxlength="1" minlength="1"
                                placeholder="Edite la letra de la seccion" title="Debe ser una sola letra mayúscula" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="">Año</label>

                            <select
                                class="form-select form-select-lg"
                                name="añoEdit"
                                id="añoEdit">
                                <option selected value="1">1ero</option>
                                <option value="2">2do</option>
                                <option value="3">3ero</option>
                                <option value="4">4to</option>
                                <option value="5">5to</option>
                            </select>


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
                    <div class="view_seccion_data">

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
                    <h1 class="modal-title fs-5" id="insertdataLabel">Crea una nueva sección</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="conn_secciones.php" method="POST">
                    <?php

                    if (isset($_POST['id'])) {

                        $nombre_seccion = $_POST['nombre'];
                        $año = $_POST['año'];

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
                            <label for="">Nombre</label>
                            <input type="text" id="nombre" class="form-control" name="nombre"
                                pattern="[A-Z]" maxlength="1" minlength="1"
                                placeholder="Escriba la letra de la sección" title="Debe ser una sola letra mayúscula" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Año</label>

                            <select
                                class="form-select form-select-lg"
                                name="año"
                                id="año" required >
                                <option selected value="">Seleccione el año</option>
                                <option value="1">1ero</option>
                                <option value="2">2do</option>
                                <option value="3">3ero</option>
                                <option value="4">4to</option>
                                <option value="5">5to</option>
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
            },
            columnDefs: [{
                width: '93px',
                targets: [2, 3, 4]
            }]
        });


        // Mostrar script
        $(document).ready(function() {
            $('#myTable').on('click', '.view-data', function(e) {
                e.preventDefault();

                var id = $(this).closest('tr').find('.id_seccion').text();

                $.ajax({
                    type: "POST",
                    url: "conn_secciones.php",
                    data: {
                        'click-view-btn': true,
                        'id_seccion': id,
                    },
                    success: function(response) {
                        $('.view_seccion_data').html(response);
                        $('#viewmodal').modal('show');
                    }
                });
            });
        });

        // Editar script
        $(document).ready(function() {
            $('#myTable').on('click', '.edit-data', function(e) {
                e.preventDefault();

                var id = $(this).closest('tr').find('.id_seccion').text();
                console.log(id)
                $.ajax({
                    type: "POST",
                    url: "conn_secciones.php",
                    data: {
                        'click-edit-btn': true,
                        'id': id,
                    },
                    success: function(response) {
                        $.each(response, function(Key, value) {
                            $('#idEdit').val(value['id']);
                            $('#nombreEdit').val(value['nombre'].slice(-1));
                            console.log(value['nombre'])
                            $('#añoEdit').val(value['año']);
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

                var id = $(this).closest('tr').find('.id_estudiante').text();

                swal({
                    title: "¿Estas seguro?",
                    text: "Cuando elimines este estudiante lo borraras permanentemente de la base de datos!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            type: "POST",
                            url: "conn_estudiantes.php",
                            data: {
                                "click-delete-btn": true,
                                "id_estudiante": id,
                            },
                            success: function(response) {
                                swal("Estudiante Eliminado Correctamente.!", {
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