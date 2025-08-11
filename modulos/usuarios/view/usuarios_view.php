<!DOCTYPE html>
<html lang="en">

<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/head.php'); ?>

    <title>Usuarios del sistema</title>
</head>

<style>
    .info-contrasena {
        display: none;
        background-color: #f8f9fa;
        border: 1px solid #ccc;
        padding: 10px;
        border-radius: 6px;
        margin-top: 5px;
        font-size: 14px;
        max-width: 400px;
    }
</style>

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
                        <h4>Usuarios <img src="/liceo/icons/people.svg">
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
                                    <th scope="col">Nombre de usuario</th>
                                    <th scope="col">Rol</th>
                                    <th scope="col" class="action">Acción</th>
                                    <th scope="col" class="action"></th>
                                    <th scope="col" class="action"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (mysqli_num_rows($usuarios) > 0) {
                                    while ($row = mysqli_fetch_array($usuarios)) {
                                ?>
                                        <tr>
                                            <td class="id" style="display: none;"> <?php echo $row['id'] ?> </td>
                                            <td> <?php echo $row['usuario'] ?> </td>
                                            <td> <?php echo $row['rol'] ?> </td>

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

                        <input type="hidden" id="idEdit" name="id">

                        <div class="form-group mb-3">
                            <label for="">Nombre de usuario</label>
                            <input type="text" id="usuarioEdit" class="form-control" name="usuario"
                                pattern="[A-Za-záéíóúÁÉÍÓÚÑñ\s]+" maxlength="50" minlength="5"
                                placeholder="Edite el nombre de usuario" required>
                        </div>


                        <div class="form-group mb-3">
                            <label for="">Contraseña</label>
                            <input type="text" id="contrasenaEdit" class="form-control" name="contrasena"
                                pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}" maxlength="50" minlength="5"
                                placeholder="Ingrese una contraseña" required>
                            <div id="info-contrasenaEdit" class="info-contrasena">
                                La contraseña debe cumplir con los siguientes requisitos:<br>
                                • Al menos <strong>8 caracteres</strong> de longitud.<br>
                                • Incluir <strong>una letra mayúscula</strong> (A-Z).<br>
                                • Incluir <strong>una letra minúscula</strong> (a-z).<br>
                                • Incluir <strong>un número</strong> (0-9).<br>
                                • Incluir <strong>un carácter especial</strong> como @, #, $, !, etc.
                            </div>
                        </div>



                        <div class="form-group mb-3">
                            <label for="">Rol del usuario</label>
                            <select
                                class="form-select form-select-lg"
                                name="rol"
                                id="rolEdit">
                                <option selected>Seleccione un rol...</option>
                                <option value="admin">Administrador</option>
                                <option value="user">Usuario</option>
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
                    <h1 class="modal-title fs-5" id="insertdataLabel">Crea un nuevo usuario</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="index.php?action=create" method="POST">
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="">Nombre de usuario</label>
                            <input type="text" class="form-control" name="usuario"
                                pattern="[A-Za-záéíóúÁÉÍÓÚÑñ\s]+" maxlength="50" minlength="5"
                                placeholder="Ingrese un nombre de usuario" required>
                        </div>



                        <div class="form-group mb-3">
                            <label for="">Contraseña</label>
                            <input type="text" id="contrasena" class="form-control" name="contrasena"
                                pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}" maxlength="50" minlength="5"
                                placeholder="Ingrese una contraseña" required>
                            <div id="info-contrasena" class="info-contrasena">
                                La contraseña debe cumplir con los siguientes requisitos:<br>
                                • Al menos <strong>8 caracteres</strong> de longitud.<br>
                                • Incluir <strong>una letra mayúscula</strong> (A-Z).<br>
                                • Incluir <strong>una letra minúscula</strong> (a-z).<br>
                                • Incluir <strong>un número</strong> (0-9).<br>
                                • Incluir <strong>un carácter especial</strong> como @, #, $, !, etc.
                            </div>
                        </div>



                        <div class="form-group mb-3">
                            <label for="">Rol del usuario</label>
                            <select
                                class="form-select form-select-lg"
                                name="rol"
                                id="rol">
                                <option selected>Seleccione un rol...</option>
                                <option value="admin">Administrador</option>
                                <option value="user">Usuario</option>
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

    <script src="/liceo/script/usuarios.js"></script>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/footer.php') ?>
    </footer>
</body>

</html>
