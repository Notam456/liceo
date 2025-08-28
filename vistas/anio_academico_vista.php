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
                        <table style="margin-left: 40px; width:109.2%;" class="table table-striped" id="myTable">
                            <thead>
                                <tr class="table-secondary">
                                    <th style="display: none;" scope="col">#</th>
                                    <th scope="col">Período</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col" class="action">Acción</th>
                                    <th scope="col" class="action"></th>
                                    <th scope="col" class="action"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($anios_academicos && mysqli_num_rows($anios_academicos) > 0) {
                                    while ($row = mysqli_fetch_array($anios_academicos)) {
                                ?>
                                        <tr>
                                            <td class="id_anio" style="display: none;"> <?php echo $row['id_anio'] ?> </td>
                                            <td> <?php echo $row['periodo'] ?> </td>
                                            <td> <?php if ((bool)$row['estado']) {
                                                        echo "Activo";
                                                    } else {
                                                        echo 'Inactivo <br> <a
    name="btn-set"
    id="btn-set"
    class="btn btn-primary btn-sm btn-set"
    role="button"
    >Establecer Activo</a
>
';
                                                    } ?> </td>

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
                                        <td colspan="6">No se encontraron registros de Años academicos.</td>
                                        <td></td>
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


    <div class="modal fade" id="editmodal" tabindex="-1" aria-labelledby="editmodalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editmodalLabel">Editar Año Academico</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="edit-form" action="/liceo/controladores/anio_academico_controlador.php" method="POST">
                    <input type="hidden" name="action" value="actualizar">
                    <div class="modal-body">

                        <div class="form-group mb-3">
                            <input type="hidden" id="id_anio_edit" class="form-control" name="id_anio">
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Fecha de inicio del año academico</label>
                            <input type="date" id="inicio_edit" class="form-control" name="inicio" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Fecha del fin del año academico</label>
                            <input type="date" id="fin_edit" class="form-control" name="fin" required>
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
                    <h1 class="modal-title fs-5" id="viewmodalLabel">Datos del Año Academico</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="view_anio_data"></div>
                </div>

                <div class="modal-footer"></div>
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
                <form id="formAnio" action="/liceo/controladores/anio_academico_controlador.php" method="POST">
                    <input type="hidden" name="action" value="crear">
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="inicio">Fecha de inicio del año académico</label>
                            <input type="date" class="form-control" name="inicio" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="fin">Fecha de fin del año académico</label>
                            <input type="date" class="form-control" name="fin" required>
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
            }]
        });

        $(document).ready(function() {
            // Ver
            $(document).on('click', '.view-data', function(e) {
                e.preventDefault();
                var id_anio = $(this).closest('tr').find('.id_anio').text();
                $.ajax({
                    type: "POST",
                    url: "/liceo/controladores/anio_academico_controlador.php",
                    data: {
                        'action': 'ver',
                        'id_anio': id_anio
                    },
                    success: function(response) {
                        $('.view_anio_data').html(response);
                        $('#viewmodal').modal('show');
                    }
                });
            });

            // Cargar para editar
            $(document).on('click', '.edit-data', function(e) {
                e.preventDefault();
                var id_anio = $(this).closest('tr').find('.id_anio').text();
                $.ajax({
                    type: "POST",
                    url: "/liceo/controladores/anio_academico_controlador.php",
                    data: {
                        'action': 'editar',
                        'id_anio': id_anio
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.length > 0) {
                            var data = response[0];
                            $('#id_anio_edit').val(data.id_anio);
                            $('#inicio_edit').val(data.desde);
                            $('#fin_edit').val(data.hasta);
                            $('#editmodal').modal('show');
                        }
                    }
                });
            });

            // Eliminar
            $(document).on('click', '.delete-data', function(e) {
                e.preventDefault();
                var id_anio = $(this).closest('tr').find('.id_anio').text();
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: '¡Esta acción eliminará el registro permanentemente!',
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
                            url: "/liceo/controladores/anio_academico_controlador.php",
                            data: {
                                'action': 'eliminar',
                                'id_anio': id_anio
                            },
                            success: function(response) {
                                Swal.fire('¡Eliminado!', response, 'success').then(() => location.reload());
                            }
                        });
                    }
                });
            });
            $(document).on('click', '.btn-set', function(e) {
                e.preventDefault();
                var id_anio = $(this).closest('tr').find('.id_anio').text();

                $.ajax({
                    type: "POST",
                    url: "/liceo/controladores/anio_academico_controlador.php",
                    data: {
                        'action': 'establecerActivo',
                        'id_anio': id_anio
                    },
                    success: function(response) {
                     location.reload();
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