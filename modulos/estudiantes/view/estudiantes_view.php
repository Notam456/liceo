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
                                    <th scope="col" class="action">Constancia</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (mysqli_num_rows($estudiantes) > 0) {
                                    while ($row = mysqli_fetch_array($estudiantes)) {
                                ?>
                                        <tr>
                                            <td class="id_estudiante" style="display: none;"> <?php echo $row['id_estudiante'] ?> </td>
                                            <td> <?php echo $row['nombre_estudiante'] ?> </td>
                                            <td> <?php echo $row['apellido_estudiante'] ?> </td>
                                            <td> <?php echo $row['cedula_estudiante'] ?> </td>
                                            <td> <?php echo $row['contacto_estudiante'] ?> </td>

                                            <td>
                                                <a href="#" class="btn btn-warning btn-sm view-data">Consultar</a>
                                            </td>

                                            <td>
                                                <a href="#" class="btn btn-primary btn-sm edit-data">Modificar</a>
                                            </td>

                                            <td>
                                                <a href="#" class="btn btn-danger btn-sm delete-data">Eliminar</a>
                                            </td>

                                            <td>
                                                <a target="_blank" href="constancia.php?id=<?php echo $row['id_estudiante'] ?>" 
                                                class="btn btn-secondary btn-sm">Generar</a>
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
                <form id="edit-form" action="index.php?action=update" method="POST">
                    <div class="modal-body">

                        <input type="hidden" id="edit_id_estudiante" name="id_estudiante">

                        <div class="form-group mb-3">
                            <label for="">Nombres</label>
                            <input type="text" id="edit_nombre_estudiante" class="form-control" name="nombre_estudiante"
                                pattern="[A-Za-záéíóúÁÉÍÓÚÑñ\s]+" maxlength="50" minlength="5"
                                placeholder="Edite los Nombres del estudiante" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Apellidos</label>
                            <input type="text" id="edit_apellido_estudiante" class="form-control" name="apellido_estudiante"
                                pattern="[A-Za-záéíóúÁÉÍÓÚÑñ\s]+" maxlength="50" minlength="5"
                                placeholder="Edite los Apellidos del Estudiante" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Cedula del Estudiante</label>
                            <input type="text" id="edit_cedula_estudiante" class="form-control" name="cedula_estudiante"
                                pattern="0|[1-9][0-9]*" maxlength="50" minlength="5"
                                placeholder="Edite la Cedula del Estudiante" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Contacto del Estudiante</label>
                            <input type="text" id="edit_contacto_estudiante" class="form-control" name="contacto_estudiante"
                                pattern="0|[1-9][0-9]*" minlength="5"
                                placeholder="Edite el contacto del estudiante" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Municipio</label>
                            <input type="text" id="edit_municipio" class="form-control" name="Municipio"
                                pattern="[A-Za-záéíóúÁÉÍÓÚÑñ\s]+" minlength="5"
                                placeholder="Edite el Municipio" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Parroquia</label>
                            <input type="text" id="edit_parroquia" class="form-control" name="Parroquia"
                                pattern="[A-Za-záéíóúÁÉÍÓÚÑñ\s]+" minlength="5"
                                placeholder="Edite la Parroquia" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Año Academico</label>
                            <input type="text" id="edit_año_academico" class="form-control" name="año_academico"
                                pattern="0|[1-9][0-9]*" maxlength="50" minlength="1"
                                placeholder="Edite el Año Academico" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Seccion del Estudiante</label>
                            <input type="text" id="edit_seccion_estudiante" class="form-control" name="seccion_estudiante"
                                pattern="[A-Za-záéíóúÁÉÍÓÚÑñ\s]+" maxlength="50" minlength="1"
                                placeholder="Edite la Seccion del Estudiante" required>

                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="update-data" class="btn btn-primary btn-success">Editar datos</button>
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
                <form action="index.php?action=create" method="POST">
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="">Nombres</label>
                            <input type="text" class="form-control" name="nombre_estudiante"
                                pattern="[A-Za-záéíóúÁÉÍÓÚÑñ\s]+" maxlength="50" minlength="5"
                                placeholder="Edite los Nombres del estudiante" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Apellidos</label>
                            <input type="text" class="form-control" name="apellido_estudiante"
                                pattern="[A-Za-záéíóúÁÉÍÓÚÑñ\s]+" maxlength="50" minlength="5"
                                placeholder="Edite los Apellidos del Estudiante" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Cedula del Estudiante</label>
                            <input type="text" class="form-control" name="cedula_estudiante"
                                pattern="0|[1-9][0-9]*" maxlength="50" minlength="5"
                                placeholder="Edite la Cedula del Estudiante" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Contacto del Estudiante</label>
                            <input type="text" class="form-control" name="contacto_estudiante"
                                pattern="0|[1-9][0-9]*" minlength="5"
                                placeholder="Edite el contacto del estudiante" required>
                        </div>

                        
                        <div class="form-group mb-3">
                            <label for="">Municipio</label>
                            <input type="text" class="form-control" name="Municipio"
                                pattern="[A-Za-záéíóúÁÉÍÓÚÑñ\s]+" minlength="5"
                                placeholder="Edite el Municipio" required>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="">Parroquia</label>
                            <input type="text" class="form-control" name="Parroquia"
                                pattern="[A-Za-záéíóúÁÉÍÓÚÑñ\s]+" minlength="5"
                                placeholder="Edite la Parroquia" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Año Academico</label>
                            <input type="text" class="form-control" name="año_academico"
                                pattern="0|[1-9][0-9]*" maxlength="50" minlength="1"
                                placeholder="Edite el Año Academico" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Seccion del Estudiante</label>
                            <input type="text" class="form-control" name="seccion_estudiante"
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

    <script src="/liceo/script/estudiantes.js"></script>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/footer.php') ?>
    </footer>

</body>

</html>
