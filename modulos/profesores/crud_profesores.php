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
                        <h4>Profesores <img src="/liceo/icons/people.svg">
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
                                include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/conn.php');

                                $fetch_query = "SELECT * FROM profesores";
                                $fetch_query_run = mysqli_query($conn, $fetch_query);

                                if (mysqli_num_rows($fetch_query_run) > 0) {
                                    while ($row = mysqli_fetch_array($fetch_query_run)) {
                                        // echo $row['id_profesores'];
                                ?>
                                        <tr>
                                            <td class="id_profesores" style="display: none;"> <?php echo $row['id_profesores'] ?> </td>
                                            <td> <?php echo $row['nombre_profesores'] ?> </td>
                                            <td> <?php echo $row['apellido_profesores'] ?> </td>
                                            <td> <?php echo $row['cedula_profesores'] ?> </td>
                                            <td> <?php echo $row['cedula_profesores'] ?> </td>

                                            <td>
                                                <a href="" class="btn btn-warning btn-sm view-data">Consultar</a>
                                            </td>

                                            <td>
                                                <a href="" class="btn btn-primary btn-sm edit-data">Modificar</a>
                                            </td>

                                            <td>
                                                <input type="hidden" class="delete_id_sala" value=" <?php echo $row['id_profesores'] ?> ">
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

    <!-- Modulo Mostrar para editar -->
    <div class="modal fade" id="editmodal" tabindex="-1" aria-labelledby="editmodalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editmodalLabel">Editar</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="edit-form" action="conn_profesores.php" method="POST">
                    <div class="modal-body">

                        <div class="form-group mb-3">
                            <input type="hidden" id="id_profesores" class="form-control" name="id_profesores">
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Nombres</label>
                            <input type="text" id="nombre_profesores" class="form-control" name="nombre_profesores"
                                pattern="[A-Za-záéíóúÁÉÍÓÚÑñ\s]+" maxlength="50" minlength="5"
                                placeholder="Edite los Nombres del profesores" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Apellidos</label>
                            <input type="text" id="apellido_profesores" class="form-control" name="apellido_profesores"
                                pattern="[A-Za-záéíóúÁÉÍÓÚÑñ\s]+" maxlength="50" minlength="5"
                                placeholder="Edite los Apellidos del profesores" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Cedula del profesores</label>
                            <input type="text" id="cedula_profesores" class="form-control" name="cedula_profesores"
                                maxlength="50" minlength="11"
                                placeholder="Edite la Cedula del profesores" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Contacto del profesores</label>
                            <input type="text" id="contacto_profesores" class="form-control" name="contacto_profesores"
                                minlength="12"
                                placeholder="Cambie el contacto del profesores" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Materia Impartida</label>
                            <input type="text" id="materia_impartida" class="form-control" name="materia_impartida"
                                maxlength="20" minlength="5"
                                placeholder="Materia Impartida" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Seccion del profesores</label>
                            <input type="text" id="seccion_profesores" class="form-control" name="seccion_profesores"
                                placeholder="Edite la Seccion del profesores" required>

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
                    <div class="view_profesores_data">

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
                    <h1 class="modal-title fs-5" id="insertdataLabel">Inscribe a un Nuevo Profesor</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formProfesor" action="conn_profesores.php" method="POST">
                    <?php

                    if (isset($_POST['id_profesores'])) {

                        $nombre_profesores = $_POST['nombre_profesores'];
                        $apellido_profesores = $_POST['apellido_profesores'];
                        $cedula_profesores = $_POST['cedula_profesores'];
                        $contacto_profesores = $_POST['contacto_profesores'];
                        $materia_impartida = $_POST['materia_impartida'];
                        $seccion_profesores = $_POST['seccion_profesores'];

                        $campos = array();

                        if ($nombre == "") {
                            array_push($campos, "Este campo no puede estar vacío");
                        }
                    }

                    ?>
                    <div class="modal-body">

                        <div class="form-group mb-3">
                            <input type="hidden" id="id_profesores" class="form-control" name="id_profesores">
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Nombres</label>
                            <input type="text" id="nombre_profesores" class="form-control" name="nombre_profesores"
                                pattern="[A-Za-záéíóúÁÉÍÓÚÑñ\s]+" maxlength="50" minlength="5"
                                placeholder="Edite los Nombres del Profesor" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Apellidos</label>
                            <input type="text" id="apellido_profesores" class="form-control" name="apellido_profesores"
                                pattern="[A-Za-záéíóúÁÉÍÓÚÑñ\s]+" maxlength="50" minlength="5"
                                placeholder="Edite los Apellidos del Profesor" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Cedula del Profesor</label>
                            <input type="text" id="cedula_profesores" class="form-control" name="cedula_profesores"
                                 maxlength="8" minlength="1"
                                placeholder="Edite la Cedula del Profesor" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Contacto del Profesor</label>
                            <input type="text" id="contacto_profesores" class="form-control" name="contacto_profesores"
                                 minlength="5"
                                placeholder="Cambie el contacto del Profesor" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Materia Impartida</label>
                            <input type="text" id="materia_impartida" class="form-control" name="materia_impartida"
                                pattern="[A-Za-záéíóúÁÉÍÓÚÑñ\s]+" maxlength="50" minlength="5"
                                placeholder="Edite la materia impartida" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Secciones a Impartir</label>
                            <input type="text" id="seccion_profesores" class="form-control" name="seccion_profesores"
                             maxlength="50" minlength="5"
                                placeholder="Edite la Seccion que imparte el Profesor" required>

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
                    <h1 class="modal-title fs-5" id="deletemodalLabel">profesores</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="post">
                    <div class="modal-body">
                        <input type="hidden" class="form-control" id="confirm_id_sala" name="confirm_id_sala">
                        <h4>¿Estas seguro de querer eliminar este profesores?</h4>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="delete_data" class="btn btn-primary btn-warning">Eliminar datos</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="load_salas"></div>

    <script src="../../script/profesores.js"></script>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/footer.php') ?>
    </footer>

</body>

</html>