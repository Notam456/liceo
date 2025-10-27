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
                        <h4>Usuario <img src="/liceo/icons/people.svg">
                            <!-- Boton modulo crear -->
                            <button type="button" class="btn btn-primary float-end btn-success" data-bs-toggle="modal" data-bs-target="#insertdata">
                                Agregar
                            </button>
                        </h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped" id="myTable">
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
                                            <td class="id" style="display: none;"> <?php echo $row['id_usuario'] ?> </td>
                                            <td> <?php echo $row['usuario'] ?> </td>
                                            <td> <?php echo $row['rol'] ?> </td>

                                            <td>
                                                <a href="#" class="btn btn-warning btn-sm view-data">Consultar</a>
                                            </td>

                                            <td>
                                                <a href="#" class="btn btn-primary btn-sm edit-data">Modificar</a>
                                            </td>

                                            <td>
                                                <a href="#" id="delete-sala" class="btn btn-danger btn-sm delete-data">Eliminar</a>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td></td>
                                        <td>No se encontraron registros de usuarios.</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>

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

    <!-- Modulo editar -->
    <div class="modal fade" id="editmodal" tabindex="-1" aria-labelledby="editmodalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editmodalLabel">Modificar</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="edit-form" action="/liceo/controladores/usuario_controlador.php" method="POST">
                    <input type="hidden" name="action" value="actualizar">
                    <div class="modal-body">

                        <div class="form-group mb-3">
                            <input type="hidden" id="idEdit" class="form-control" name="id">
                        </div>

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
                                id="rolEdit" required>
                                <option selected value="">Seleccione un rol...</option>
                                <option value="admin">Administrador</option>
                                <option value="coordinador">Coordinador</option>
                                <option value="user">Usuario</option>
                            </select>
                        </div>

                        <div class="form-group mb-3" name="profesor-group">
                            <label for="">Profesor</label>
                            <select
                                class="form-select form-select-lg"
                                name="profesor"
                                id="profesorEdit">
                                <option selected value="">Seleccione un profesor...</option>
                                <?php
                                foreach ($profesores as $row) {
                                ?>
                                    <option value="<?php echo $row['id_profesor']; ?>"><?php echo $row['nombre'] . " " . $row['apellido']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="update-btn" name="update-data" class="btn btn-primary btn-success">Guardar datos</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modudlo mostrar -->
    <div class="modal fade" id="viewmodal" tabindex="-1" aria-labelledby="viewmodalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
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
                    <h1 class="modal-title fs-5" id="insertdataLabel">Agregar un nuevo usuario</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/liceo/controladores/usuario_controlador.php" method="POST">
                    <input type="hidden" name="action" value="crear">
                    <div class="modal-body">

                        <div class="form-group mb-3">
                            <input type="hidden" id="id" class="form-control" name="id">
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Nombre de usuario</label>
                            <input type="text" id="usuario" class="form-control" name="usuario"
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
                                id="rol"
                                required>
                                <option selected value="">Seleccione un rol...</option>
                                <option value="admin">Administrador</option>
                                <option value="coordinador">Coordinador</option>
                                <option value="user">Usuario</option>
                            </select>
                        </div>

                        <div class="form-group mb-3" name="profesor-group">
                            <label for="">Profesor</label>
                            <select
                                class="form-select form-select-lg"
                                name="profesor"
                                id="profesor">
                                <option selected value="">Seleccione un profesor...</option>
                                <?php
                                foreach ($profesores as $row) {
                                ?>
                                    <option value="<?php echo $row['id_profesor']; ?>"><?php echo $row['nombre'] . " " . $row['apellido']; ?></option>
                                <?php } ?>
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
        // tabla
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
                    targets: [3, 4, 5]
                },
                {
                    visible: false,
                    target: 0
                }
            ]
        });
        $('#rol').on('change', function() {
            if ($('#rol option:selected').val() == 'user' || $('#rol option:selected').val() == 'coordinador') {
                $('select[name="profesor"').prop('required', true);
                $('div[name="profesor-group"').show();
            } else {
                $('select[name="profesor"').prop('required', false);
                $('div[name="profesor-group"').hide();
                $('select[name="profesor"').val("");
            }
        }).trigger("change");
        $('#rolEdit').on('change', function() {
            if ($('#rolEdit option:selected').val() == 'user' || $('#rolEdit option:selected').val() == 'coordinador') {
                $('select[name="profesor"').prop('required', true);
                $('div[name="profesor-group"').show();
            } else {
                $('select[name="profesor"').prop('required', false);
                $('div[name="profesor-group"').hide();
                $('select[name="profesor"').val("");
            }
        }).trigger("change");
        // Mostrar script
        $(document).ready(function() {
            $('#myTable').on('click', '.view-data', function(e) {
                e.preventDefault();
                var id = $(this).closest('tr').find('.id').text();
                console.log(id);
                var tabla = $('#myTable').DataTable();

                // obtenemos la fila DataTables desde el botón clicado
                var fila = tabla.row($(this).closest('tr'));

                // traemos los datos de esa fila (array con todas las columnas)
                var data = fila.data();


                var id = data[0];
                $.ajax({
                    type: "POST",
                    url: "/liceo/controladores/usuario_controlador.php",
                    data: {
                        'action': 'ver',
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
                var tabla = $('#myTable').DataTable();

                // obtenemos la fila DataTables desde el botón clicado
                var fila = tabla.row($(this).closest('tr'));

                // traemos los datos de esa fila (array con todas las columnas)
                var data = fila.data();


                var id = data[0];
                $.ajax({
                    type: "POST",
                    url: "/liceo/controladores/usuario_controlador.php",
                    data: {
                        'action': 'editar',
                        'id': id,
                    },
                    success: function(response) {
                        var data = response;
                        $('#idEdit').val(data.id_usuario);
                        $('#usuarioEdit').val(data.usuario);
                        $('#contrasenaEdit').val(data.contrasena);
                        $('#rolEdit').val(data.rol).trigger('change');
                        $('#profesorEdit').val(data.id_profesor);
                        $('#editmodal').modal('show');
                    }
                });
            });
        });

        //eliminar script
        $(document).ready(function() {
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
                    text: '¡Esta acción eliminará el usuario permanentemente!',
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
                            url: "/liceo/controladores/usuario_controlador.php",
                            data: {
                                "action": "eliminar",
                                "id": id,
                            },
                            success: function(response) {
                                Swal.fire(
                                    '¡Eliminado!',
                                    'El usuario ha sido eliminado correctamente.',
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
    <script>
        const input = document.getElementById("contrasena");
        const info = document.getElementById("info-contrasena");
        const inputEdit = document.getElementById("contrasenaEdit");
        const infoEdit = document.getElementById("info-contrasenaEdit");

        input.addEventListener("focus", () => {
            info.style.display = "block";
        });

        input.addEventListener("blur", () => {
            info.style.display = "none";
        });

        inputEdit.addEventListener("focus", () => {
            infoEdit.style.display = "block";
        });

        inputEdit.addEventListener("blur", () => {
            infoEdit.style.display = "none";
        });
    </script>

</body>

</html>