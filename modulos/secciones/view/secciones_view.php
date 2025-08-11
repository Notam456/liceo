<!DOCTYPE html>
<html lang="en">

<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/head.php'); ?>

    <title>Secciones</title>

    <style>
        .tooltip .tooltip-inner {
            background-color: #ffffff;
            border: 1px solid #343a40;
            color: #000000;

            font-size: 14px;
            padding: 8px 12px;
            border-radius: 8px;
            max-width: 220px;
            text-align: center;
        }

    </style>
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
                                if (mysqli_num_rows($secciones) > 0) {
                                    while ($row = mysqli_fetch_array($secciones)) {
                                ?>
                                        <tr>
                                            <td class="id_seccion" style="display: none;"> <?php echo $row['id_seccion'] ?> </td>
                                            <td> <?php echo $row['nombre'];
                                                    $horario = $this->model->getHorario($row['id_seccion']);
                                                    if (mysqli_num_rows($horario) == 0) {
                                                        echo ' <i class="bi bi-exclamation-triangle-fill text-danger" 
                                                        data-bs-toggle="tooltip" 
                                                        data-bs-placement="top" 
                                                        title="Esta sección no cuenta con un horario. Por favor, pulse el botón consultar y posteriormente Agregar Horario">
                                                        </i>';
                                                    }
                                                    ?> </td>
                                            <td> <?php echo $row['año'] ?> </td>

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

                        <input type="hidden" id="idEdit" name="idEdit">

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
                <form action="index.php?action=create" method="POST">
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="">Nombre</label>
                            <input type="text" class="form-control" name="nombre"
                                pattern="[A-Z]" maxlength="1" minlength="1"
                                placeholder="Escriba la letra de la sección" title="Debe ser una sola letra mayúscula" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Año</label>

                            <select
                                class="form-select form-select-lg"
                                name="año"
                                id="año" required>
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

    <script src="/liceo/script/secciones.js"></script>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/footer.php') ?>
    </footer>
</body>

</html>
