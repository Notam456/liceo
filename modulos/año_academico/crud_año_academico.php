<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/head.php'); ?>
    <title>Año Academico</title>
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
                        <h4>Año Academico <img src="/liceo/icons/people.svg">
                            <button type="button" class="btn btn-primary float-end btn-success" data-bs-toggle="modal" data-bs-target="#insertdata">
                                Crear
                            </button>
                        </h4>
                    </div>
                    <div class="card-body">
                        <table style="margin-left: 40px; width:100%;" class="table table-striped" id="myTable">
                            <thead>
                                <tr class="table-secondary">
                                    <th style="display: none;" scope="col">#</th>
                                    <th scope="col">Año</th>
                                    <th scope="col">Año Academico</th>
                                    <th scope="col" class="action">Acción</th>
                                    <th scope="col" class="action"></th>
                                    <th scope="col" class="action"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/conn.php');

                                $fetch_query = "SELECT * FROM anio_academico";
                                $fetch_query_run = mysqli_query($conn, $fetch_query);

                                if (mysqli_num_rows($fetch_query_run) > 0) {
                                    while ($row = mysqli_fetch_array($fetch_query_run)) {
                                ?>
                                        <tr>
                                            <td class="id_anio" style="display: none;"> <?php echo $row['id_anio'] ?> </td>
                                            <td> <?php echo $row['anio'] ?> </td>
                                            <td> <?php echo $row['anio_academico'] ?> </td>

                                            <td>
                                                <a href="#" class="btn btn-warning btn-sm view-data">Consultar</a>
                                            </td>

                                            <td>
                                                <a href="#" class="btn btn-primary btn-sm edit-data">Modificar</a>
                                            </td>

                                            <td>
                                                <input type="hidden" class="delete_id_anio" value=" <?php echo $row['id_anio'] ?> ">
                                                <a href="#" id="delete-anio" class="btn btn-danger btn-sm delete-data">Eliminar</a>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="6">No se encontraron registros de Años academicos.</td>
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
                    <h1 class="modal-title fs-5" id="editmodalLabel">Editar Año Academico</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="edit-form" action="conn_año_academico.php" method="POST">
                    <div class="modal-body">

                        <div class="form-group mb-3">
                            <input type="hidden" id="id_anio" class="form-control" name="id_anio">
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Año</label>
                            <input type="text" id="anio" class="form-control" name="anio"
                                pattern="[0-9!@#$%^&*()_+\-=\[\]{};':\,.<>/?|`~]+" maxlength="50" minlength="4"
                                placeholder="Edite el Año requerido del Año Academico" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Año Academico</label>
                            <input type="text" id="anio_academico" class="form-control" name="anio_academico"
                                pattern="[0-9!@#$%^&*()_+\-=\[\]{};':\,.<>/?|`~]+" maxlength="50" minlength="5"
                                placeholder="Edite el Año Academico" required>
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
                    <h1 class="modal-title fs-5" id="viewmodalLabel">Datos del Año Academico</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="view_anio_data">

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
                    <h1 class="modal-title fs-5" id="insertdataLabel">Crea un nuevo Año Academico</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formAnio" action="conn_año_academico.php" method="POST">
                    <?php
                    // Este bloque PHP para validación en el lado del servidor antes de enviar el formulario
                    // no es ideal para este lugar, ya que $_POST['id_profesores'] no estaría definido aquí
                    // al cargar la página. Es mejor manejar la validación en 'conn_coordinadores.php' o con JavaScript.
                    ?>
                    <div class="modal-body">

                        <div class="form-group mb-3">
                            <input type="hidden" id="id_anio_insert" class="form-control" name="id_anio">
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Año</label>
                            <input type="text" id="anio_insert" class="form-control" name="anio"
                                pattern="[0-9!@#$%^&*()_+\-=\[\]{};':\,.<>/?|`~]+" maxlength="50" minlength="4"
                                placeholder="Ingrese el Año" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Año Academico</label>
                            <input type="text" id="anio_academico_insert" class="form-control" name="anio_academico"
                                pattern="[0-9!@#$%^&*()_+\-=\[\]{};':\,.<>/?|`~]+" maxlength="50" minlength="4"
                                placeholder="Ingrese el Año Academico" required>
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
                    <h1 class="modal-title fs-5" id="deletemodalLabel">Eliminar Año Academico</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="conn_año_academico.php" method="post">
                    <div class="modal-body">
                        <input type="text" class="form-control" id="confirm_id_anio" name="confirm_id_anio">
                        <h4>¿Estás seguro de querer eliminar a este Año Academico?</h4>
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
               //ES YAJURE: QUITO ESTA LINEA POR QUE CAUSA UN ERROR EN MI PC, SI NO LO HACE EN LAS SUYAS LO PUEDEN DEJAR targets: [5, 6, 7]
            }]
        });
        $(document).ready(function() {
            // Función para ver datos
            $(document).on('click', '.view-data', function(e) {
                e.preventDefault(); // Evita el comportamiento predeterminado del enlace
                var id_anio = $(this).closest('tr').find('.id_anio').text();

                $.ajax({
                    type: "POST",
                    url: "conn_año_academico.php",
                    data: {
                        'click-view-btn': true,
                        'id_anio': id_anio
                    },
                    success: function(response) {
                        $('.view_anio_data').html(response);
                        $('#viewmodal').modal('show');
                    }
                });
            });

            // Función para cargar datos en el modal de edición
            $(document).on('click', '.edit-data', function(e) {
                e.preventDefault(); // Evita el comportamiento predeterminado del enlace
                var id_anio = $(this).closest('tr').find('.id_anio').text();

                $.ajax({
                    type: "POST",
                    url: "conn_año_academico.php",
                    data: {
                        'click-edit-btn': true,
                        'id_anio': id_anio
                    },
                    dataType: "json", // Esperamos una respuesta JSON
                    success: function(response) {
                        if (response.length > 0) {
                            var data = response[0];
                            $('#id_anio').val(data.id_anio);
                            $('#anio').val(data.anio);
                            $('#anio_academico').val(data.anio_academico);
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
        <script src="../../script/año_academico.js"></script>
    </footer>

</body>

</html>