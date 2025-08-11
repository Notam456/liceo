<!DOCTYPE html>
<html lang="en">

<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/head.php'); ?>
    <title>Coordinadores</title>
</head>

<body>
    <nav>
        <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/navbar.php') ?>
    </nav>

    <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/sidebar.php') ?>
    <div class="container" style="margin-top: 30px;">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <?php
                if (isset($_SESSION['status']) && $_SESSION['status'] != '') {
                ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>¡Atención!</strong> <?php echo $_SESSION['status']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php
                    unset($_SESSION['status']);
                }
                ?>

                <div class="card">
                    <div class="card-header">
                        <h4>Coordinadores <img src="/liceo/icons/people.svg">
                            <button type="button" class="btn btn-primary float-end btn-success" data-bs-toggle="modal" data-bs-target="#insertdata">
                                Inscribir Coordinador
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
                                if (mysqli_num_rows($coordinadores) > 0) {
                                    while ($row = mysqli_fetch_array($coordinadores)) {
                                ?>
                                        <tr>
                                            <td class="id_coordinadores" style="display: none;"> <?php echo $row['id_coordinadores'] ?> </td>
                                            <td> <?php echo $row['nombre_coordinadores'] ?> </td>
                                            <td> <?php echo $row['apellido_coordinadores'] ?> </td>
                                            <td> <?php echo $row['cedula_coordinadores'] ?> </td>
                                            <td> <?php echo $row['contacto_coordinadores'] ?> </td>

                                            <td>
                                                <a href="#" class="btn btn-warning btn-sm view-data">Consultar</a>
                                            </td>

                                            <td>
                                                <a href="#" class="btn btn-primary btn-sm edit-data">Modificar</a>
                                            </td>

                                            <td>
                                                <a href="#" class="btn btn-danger btn-sm delete-data">Eliminar</a>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="6">No se encontraron registros de coordinadores.</td>
                                    </tr>
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


    <div class="modal fade" id="editmodal" tabindex="-1" aria-labelledby="editmodalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editmodalLabel">Editar Coordinador</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="edit-form" action="index.php?action=update" method="POST">
                    <div class="modal-body">

                        <input type="hidden" id="edit_id_coordinadores" name="id_coordinadores">

                        <div class="form-group mb-3">
                            <label for="">Nombres</label>
                            <input type="text" id="edit_nombre_coordinadores" class="form-control" name="nombre_coordinadores"
                                pattern="[A-Za-záéíóúÁÉÍÓÚÑñ\s]+" maxlength="50" minlength="5"
                                placeholder="Edite los Nombres del Coordinador" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Apellidos</label>
                            <input type="text" id="edit_apellido_coordinadores" class="form-control" name="apellido_coordinadores"
                                pattern="[A-Za-záéíóúÁÉÍÓÚÑñ\s]+" maxlength="50" minlength="5"
                                placeholder="Edite los Apellidos del Coordinador" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Cédula del Coordinador</label>
                            <input type="text" id="edit_cedula_coordinadores" class="form-control" name="cedula_coordinadores"
                                maxlength="11" minlength="8"
                                placeholder="Edite la Cédula del Coordinador" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Contacto del Coordinador</label>
                            <input type="text" id="edit_contacto_coordinadores" class="form-control" name="contacto_coordinadores"
                                minlength="5"
                                placeholder="Cambie el contacto del Coordinador" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Área de Coordinación</label>
                            <input type="text" id="edit_area_coordinacion" class="form-control" name="area_coordinacion"
                                pattern="[A-Za-záéíóúÁÉÍÓÚÑñ\s]+" maxlength="50" minlength="5"
                                placeholder="Área de Coordinación" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Sección Coordinada</label>
                            <input type="text" id="edit_seccion_coordinadores" class="form-control" name="seccion_coordinadores"
                                maxlength="50" minlength="5"
                                placeholder="Edite la Sección Coordinada" required>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="update-data" class="btn btn-primary btn-success">Editar datos</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <div class="modal fade" id="viewmodal" tabindex="-1" aria-labelledby="viewmodalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="viewmodalLabel">Datos del Coordinador</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="view_coordinadores_data">

                    </div>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="insertdata" tabindex="-1" aria-labelledby="insertdataLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="insertdataLabel">Inscribe a un Nuevo Coordinador</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formCoordinador" action="index.php?action=create" method="POST">
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="">Nombres</label>
                            <input type="text" class="form-control" name="nombre_coordinadores"
                                pattern="[A-Za-záéíóúÁÉÍÓÚÑñ\s]+" maxlength="50" minlength="5"
                                placeholder="Ingrese los Nombres del Coordinador" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Apellidos</label>
                            <input type="text" class="form-control" name="apellido_coordinadores"
                                pattern="[A-Za-záéíóúÁÉÍÓÚÑñ\s]+" maxlength="50" minlength="5"
                                placeholder="Ingrese los Apellidos del Coordinador" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Cédula del Coordinador</label>
                            <input type="text" class="form-control" name="cedula_coordinadores"
                                maxlength="11" minlength="6"
                                placeholder="Ingrese la Cédula del Coordinador" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Contacto del Coordinador</label>
                            <input type="text" class="form-control" name="contacto_coordinadores"
                                minlength="5"
                                placeholder="Ingrese el contacto del Coordinador" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Área de Coordinación</label>
                            <input type="text" class="form-control" name="area_coordinacion"
                                pattern="[A-Za-záéíóúÁÉÍÓÚÑñ\s]+" maxlength="50" minlength="5"
                                placeholder="Ingrese el Área de Coordinación" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Sección Coordinada</label>
                            <input type="text" class="form-control" name="seccion_coordinadores"
                                maxlength="50" minlength="5"
                                placeholder="Ingrese la Sección Coordinada" required>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="save_data" class="btn btn-success">Guardar datos</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="/liceo/script/coordinadores.js"></script>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/footer.php') ?>
    </footer>

</body>

</html>
