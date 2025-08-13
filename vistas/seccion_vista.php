<?php
session_start();
?>
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
    <div class="container" style="margin-top: 30px;">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <?php if (isset($_SESSION['status']) && $_SESSION['status'] != '') { ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Hey!</strong> <?php echo $_SESSION['status']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php unset($_SESSION['status']); } ?>
                <div class="card">
                    <div class="card-header">
                        <h4>Secciones <img src="/liceo/icons/people.svg">
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
                                if ($secciones && mysqli_num_rows($secciones) > 0) {
                                    while ($row = mysqli_fetch_array($secciones)) {
                                ?>
                                        <tr>
                                            <td class="id_seccion" style="display: none;"> <?php echo $row['id_seccion'] ?> </td>
                                            <td>
                                                <?php echo $row['nombre'];
                                                if (isset($horarios_status[$row['id_seccion']]) && !$horarios_status[$row['id_seccion']]) {
                                                    echo ' <i class="bi bi-exclamation-triangle-fill text-danger"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        title="Esta sección no cuenta con un horario. Por favor, pulse el botón consultar y posteriormente Agregar Horario">
                                                        </i>';
                                                }
                                                ?>
                                            </td>
                                            <td> <?php echo $row['año'] ?> </td>
                                            <td><a href="#" class="btn btn-warning btn-sm view-data">Consultar</a></td>
                                            <td><a href="#" class="btn btn-primary btn-sm edit-data">Modificar</a></td>
                                            <td><a href="#" class="btn btn-danger btn-sm delete-data">Eliminar</a></td>
                                        </tr>
                                    <?php }
                                } else { ?>
                                    <tr><td colspan="6">No Record Found</td></tr>
                                <?php } ?>
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
                <form id="edit-form" action="/liceo/controladores/seccion_controlador.php" method="POST">
                    <input type="hidden" name="action" value="actualizar">
                    <div class="modal-body">
                        <input type="hidden" id="idEdit" class="form-control" name="idEdit">
                        <div class="form-group mb-3">
                            <label>Nombre</label>
                            <input type="text" id="nombreEdit" class="form-control" name="nombreEdit" pattern="[A-Z]" maxlength="1" minlength="1" title="Debe ser una sola letra mayúscula" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Año</label>
                            <select class="form-select form-select-lg" name="añoEdit" id="añoEdit">
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

    <!-- Modulo mostrar -->
    <div class="modal fade" id="viewmodal" tabindex="-1" aria-labelledby="viewmodalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="viewmodalLabel">Datos</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="view_seccion_data"></div>
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
                <form action="/liceo/controladores/seccion_controlador.php" method="POST">
                    <input type="hidden" name="action" value="crear">
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label>Nombre</label>
                            <input type="text" class="form-control" name="nombre" pattern="[A-Z]" maxlength="1" minlength="1" title="Debe ser una sola letra mayúscula" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Año</label>
                            <select class="form-select form-select-lg" name="año" required>
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

    <script>
        new DataTable('#myTable', {
            language: {
                search: 'Buscar',
                info: 'Mostrando pagina _PAGE_ de _PAGES_',
                infoEmpty: 'No se han encontrado resultados',
                infoFiltered: '(se han encontrado _MAX_ resultados)',
                lengthMenu: 'Mostrar _MENU_ por pagina',
                zeroRecords: '0 resultados encontrados',
            },
            columnDefs: [{ width: '93px', targets: [2, 3, 4] }],
            order: [[2, 'asc']]
        });

        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.forEach(function(tooltipTriggerEl) {
                new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });

        $(document).ready(function() {
            // Mostrar
            $('#myTable').on('click', '.view-data', function(e) {
                e.preventDefault();
                var id = $(this).closest('tr').find('.id_seccion').text();
                $.ajax({
                    type: "POST",
                    url: "/liceo/controladores/seccion_controlador.php",
                    data: { 'action': 'ver', 'id_seccion': id },
                    success: function(response) {
                        $('.view_seccion_data').html(response);
                        $('#viewmodal').modal('show');
                    }
                });
            });

            // Cargar para Editar
            $('#myTable').on('click', '.edit-data', function(e) {
                e.preventDefault();
                var id = $(this).closest('tr').find('.id_seccion').text();
                $.ajax({
                    type: "POST",
                    url: "/liceo/controladores/seccion_controlador.php",
                    data: { 'action': 'editar', 'id_seccion': id },
                    dataType: 'json',
                    success: function(response) {
                        var data = response[0];
                        $('#idEdit').val(data.id_seccion);
                        $('#nombreEdit').val(data.nombre.slice(-1));
                        $('#añoEdit').val(data.año);
                        $('#editmodal').modal('show');
                    }
                });
            });

            // Eliminar
            $('#myTable').on('click', '.delete-data', function(e) {
                e.preventDefault();
                var id = $(this).closest('tr').find('.id_seccion').text();
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: '¡Esta acción eliminará la sección permanentemente!',
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
                            url: "/liceo/controladores/seccion_controlador.php",
                            data: { 'action': 'eliminar', 'id_seccion': id },
                            success: function(response) {
                                Swal.fire('¡Eliminado!', response, 'success').then(() => location.reload());
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
