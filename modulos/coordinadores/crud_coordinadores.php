<?php
session_start();
?>

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
                                $conn = mysqli_connect("localhost", "root", "", "liceo");

                                $fetch_query = "SELECT * FROM coordinadores";
                                $fetch_query_run = mysqli_query($conn, $fetch_query);

                                if (mysqli_num_rows($fetch_query_run) > 0) {
                                    while ($row = mysqli_fetch_array($fetch_query_run)) {
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
                                                <input type="hidden" class="delete_id_coordinador" value=" <?php echo $row['id_coordinadores'] ?> ">
                                                <a href="#" id="delete-coordinador" class="btn btn-danger btn-sm delete-data">Eliminar</a>
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
                <form id="edit-form" action="conn_coordinadores.php" method="POST">
                    <div class="modal-body">

                        <div class="form-group mb-3">
                            <input type="hidden" id="id_coordinadores" class="form-control" name="id_coordinadores">
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Nombres</label>
                            <input type="text" id="nombre_coordinadores" class="form-control" name="nombre_coordinadores"
                                pattern="[A-Za-záéíóúÁÉÍÓÚÑñ\s]+" maxlength="50" minlength="5"
                                placeholder="Edite los Nombres del Coordinador" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Apellidos</label>
                            <input type="text" id="apellido_coordinadores" class="form-control" name="apellido_coordinadores"
                                pattern="[A-Za-záéíóúÁÉÍÓÚÑñ\s]+" maxlength="50" minlength="5"
                                placeholder="Edite los Apellidos del Coordinador" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Cédula del Coordinador</label>
                            <input type="text" id="cedula_coordinadores" class="form-control" name="cedula_coordinadores"
                                maxlength="11" minlength="8"
                                placeholder="Edite la Cédula del Coordinador" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Contacto del Coordinador</label>
                            <input type="text" id="contacto_coordinadores" class="form-control" name="contacto_coordinadores"
                                minlength="5"
                                placeholder="Cambie el contacto del Coordinador" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Área de Coordinación</label>
                            <input type="text" id="area_coordinacion" class="form-control" name="area_coordinacion"
                                pattern="[A-Za-záéíóúÁÉÍÓÚÑñ\s]+" maxlength="50" minlength="5"
                                placeholder="Área de Coordinación" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Sección Coordinada</label>
                            <input type="text" id="seccion_coordinadores" class="form-control" name="seccion_coordinadores"
                                maxlength="50" minlength="5"
                                placeholder="Edite la Sección Coordinada" required>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="update-btn" name="update-data" class="btn btn-primary btn-success">Editar datos</button>
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
                <form id="formCoordinador" action="conn_coordinadores.php" method="POST">
                    <?php
                    // Este bloque PHP para validación en el lado del servidor antes de enviar el formulario
                    // no es ideal para este lugar, ya que $_POST['id_profesores'] no estaría definido aquí
                    // al cargar la página. Es mejor manejar la validación en 'conn_coordinadores.php' o con JavaScript.
                    ?>
                    <div class="modal-body">

                        <div class="form-group mb-3">
                            <input type="hidden" id="id_coordinadores_insert" class="form-control" name="id_coordinadores">
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Nombres</label>
                            <input type="text" id="nombre_coordinadores_insert" class="form-control" name="nombre_coordinadores"
                                pattern="[A-Za-záéíóúÁÉÍÓÚÑñ\s]+" maxlength="50" minlength="5"
                                placeholder="Ingrese los Nombres del Coordinador" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Apellidos</label>
                            <input type="text" id="apellido_coordinadores_insert" class="form-control" name="apellido_coordinadores"
                                pattern="[A-Za-záéíóúÁÉÍÓÚÑñ\s]+" maxlength="50" minlength="5"
                                placeholder="Ingrese los Apellidos del Coordinador" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Cédula del Coordinador</label>
                            <input type="text" id="cedula_coordinadores_insert" class="form-control" name="cedula_coordinadores"
                                maxlength="11" minlength="6"
                                placeholder="Ingrese la Cédula del Coordinador" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Contacto del Coordinador</label>
                            <input type="text" id="contacto_coordinadores_insert" class="form-control" name="contacto_coordinadores"
                                minlength="5"
                                placeholder="Ingrese el contacto del Coordinador" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Área de Coordinación</label>
                            <input type="text" id="area_coordinacion_insert" class="form-control" name="area_coordinacion"
                                pattern="[A-Za-záéíóúÁÉÍÓÚÑñ\s]+" maxlength="50" minlength="5"
                                placeholder="Ingrese el Área de Coordinación" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Sección Coordinada</label>
                            <input type="text" id="seccion_coordinadores_insert" class="form-control" name="seccion_coordinadores"
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



    <div class="modal fade" id="deletemodal" tabindex="-1" aria-labelledby="deletemodalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="deletemodalLabel">Eliminar Coordinador</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="conn_coordinadores.php" method="post">
                    <div class="modal-body">
                        <input type="text" class="form-control" id="confirm_id_coordinador" name="confirm_id_coordinador">
                        <h4>¿Estás seguro de querer eliminar a este coordinador?</h4>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="click-delete-btn" class="btn btn-danger">Eliminar datos</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
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
                targets: [5, 6, 7]
            }]
        });
        $(document).ready(function() {
            // Función para ver datos
            $(document).on('click', '.view-data', function(e) {
                e.preventDefault(); // Evita el comportamiento predeterminado del enlace
                var id_coordinadores = $(this).closest('tr').find('.id_coordinadores').text();

                $.ajax({
                    type: "POST",
                    url: "conn_coordinadores.php",
                    data: {
                        'click-view-btn': true,
                        'id_coordinadores': id_coordinadores
                    },
                    success: function(response) {
                        $('.view_coordinadores_data').html(response);
                        $('#viewmodal').modal('show');
                    }
                });
            });

            // Función para cargar datos en el modal de edición
            $(document).on('click', '.edit-data', function(e) {
                e.preventDefault(); // Evita el comportamiento predeterminado del enlace
                var id_coordinadores = $(this).closest('tr').find('.id_coordinadores').text();

                $.ajax({
                    type: "POST",
                    url: "conn_coordinadores.php",
                    data: {
                        'click-edit-btn': true,
                        'id_coordinadores': id_coordinadores
                    },
                    dataType: "json", // Esperamos una respuesta JSON
                    success: function(response) {
                        if (response.length > 0) {
                            var data = response[0];
                            $('#id_coordinadores').val(data.id_coordinadores);
                            $('#nombre_coordinadores').val(data.nombre_coordinadores);
                            $('#apellido_coordinadores').val(data.apellido_coordinadores);
                            $('#cedula_coordinadores').val(data.cedula_coordinadores);
                            $('#contacto_coordinadores').val(data.contacto_coordinadores);
                            $('#area_coordinacion').val(data.area_coordinacion); // Campo cambiado
                            $('#seccion_coordinadores').val(data.seccion_coordinadores);
                            $('#editmodal').modal('show');
                        } else {
                            alert('No se encontraron datos para editar.');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error al cargar datos para edición:", status, error);
                        alert("Error al cargar datos para edición. Consulta la consola para más detalles.");
                    }
                });
            });

            // Función para preparar la eliminación


            // Si tenías lógica específica para el formulario de eliminación con AJAX, iría aquí.
            // Actualmente, el formulario de eliminación se envía directamente vía POST al conn_coordinadores.php
            // por lo que no requiere AJAX adicional en este script, solo la preparación del modal.
        });
    </script>

    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/footer.php') ?>
        <script src="../../script/coordinadores.js"></script>
    </footer>

</body>

</html>