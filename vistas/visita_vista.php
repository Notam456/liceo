<!DOCTYPE html>
<html lang="en">

<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/head.php'); ?>
    <title>Visitas</title>
</head>

<body>
    <nav>
        <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/navbar.php') ?>
    </nav>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/sidebar.php') ?>
    <div class="container" style="margin-top: 30px;">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <?php if (isset($_SESSION['status']) && $_SESSION['status'] != '') { ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Hey!</strong> <?php echo $_SESSION['status']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php unset($_SESSION['status']);
                } ?>
                <div class="card">
                    <div class="card-header">
                        <h4>Visitas Agendadas <i class="bi bi-calendar-check"></i></h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped" id="myTable">
                            <thead>
                                <tr class="table-secondary">
                                    <th style="display: none;">#</th>
                                    <th>Estudiante</th>
                                    <th>Cédula</th>
                                    <th>Fecha de Visita</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($visitas && mysqli_num_rows($visitas) > 0) {
                                    while ($row = mysqli_fetch_array($visitas)) {
                                ?>
                                        <tr data-id-visita="<?php echo $row['id_visita'] ?>">
                                            <td style="display: none;" class="id_visita"> <?php echo $row['id_visita'] ?> </td>
                                            <td> <?php echo $row['nombre'] . ' ' . $row['apellido'] ?> </td>
                                            <td> <?php echo $row['cedula'] ?> </td>
                                            <td> <?php echo date("d/m/Y", strtotime($row['fecha_visita'])) ?> </td>
                                            <td>
                                                <?php
                                                    $estado = htmlspecialchars($row['estado']);
                                                    $badge_class = 'bg-secondary';
                                                    if ($estado == 'agendada') {
                                                        $badge_class = 'bg-primary';
                                                    } elseif ($estado == 'realizada') {
                                                        $badge_class = 'bg-success';
                                                    } elseif ($estado == 'cancelada') {
                                                        $badge_class = 'bg-danger';
                                                    }
                                                ?>
                                                <span class="badge <?php echo $badge_class; ?>"><?php echo ucfirst($estado); ?></span>
                                            </td>
                                            <td>
                                                <div class="d-flex" style="gap: 5px;">
                                                    <?php if ($row['estado'] == 'agendada'): ?>
                                                        <a href="#" class="btn btn-success btn-sm update-data" data-action="realizada">Realizar</a>
                                                        <a href="#" class="btn btn-danger btn-sm update-data" data-action="cancelada">Cancelar</a>
                                                    <?php else: ?>
                                                        <a href="#" class="btn btn-success btn-sm disabled" data-action="realizada">Realizar</a>
                                                        <a href="#" class="btn btn-danger btn-sm disabled" data-action="cancelada">Cancelar</a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="#" class="btn btn-warning btn-sm view-data">Consultar</a>
                                            </td>
                                            <td>
                                                <a href="#" class="btn btn-danger btn-sm delete-data">Eliminar</a>
                                            </td>
                                        </tr>
                                    <?php }
                                } else { ?>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td>No hay visitas agendadas.</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modulo Ver -->
    <div class="modal fade" id="viewVisitaModal" tabindex="-1" aria-labelledby="viewVisitaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="viewVisitaModalLabel">Datos de la Visita</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="view_visita_data"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modulo Actualizar -->
    <div class="modal fade" id="updateVisitaModal" tabindex="-1" aria-labelledby="updateVisitaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="updateVisitaModalLabel">Actualizar Visita</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="update_visita_data"></div>
                </div>
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
                    targets: [5, 6]
                },
                {
                    visible: false,
                    target: 0
                }
            ]
        });

        $(document).ready(function() {
            // Show view modal
            $('#myTable').on('click', '.view-data', function(e) {
                e.preventDefault();
                var tabla = $('#myTable').DataTable();
                var fila = tabla.row($(this).closest('tr'));
                var data = fila.data();
                var id = data[0];
                $.ajax({
                    type: "POST",
                    url: "/liceo/controladores/visita_controlador.php",
                    data: {
                        'action': 'ver',
                        'id_visita': id
                    },
                    success: function(response) {
                        $('.view_visita_data').html(response);
                        $('#viewVisitaModal').modal('show');
                    }
                });
            });

            // Show update modal
            $('#myTable').on('click', '.update-data', function(e) {
                e.preventDefault();
                var tabla = $('#myTable').DataTable();
                var fila = tabla.row($(this).closest('tr'));
                var data = fila.data();
                var id = data[0];
                var action = $(this).data('action');
                $.ajax({
                    type: "POST",
                    url: "/liceo/controladores/visita_controlador.php",
                    data: {
                        'action': 'ver_para_actualizar',
                        'id_visita': id,
                        'action_type': action
                    },
                    success: function(response) {
                        $('.update_visita_data').html(response);
                        $('#updateVisitaModal').modal('show');
                    }
                });
            });

            // Submit update form
            $(document).on('submit', '#updateVisitaForm', function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: "POST",
                    url: "/liceo/controladores/visita_controlador.php?action=actualizar_visita",
                    data: formData,
                    success: function(response) {
                        $('#updateVisitaModal').modal('hide');
                        Swal.fire('¡Éxito!', response, 'success').then(() => {
                            location.reload();
                        });
                    }
                });
            });

            // Delete
            $('#myTable').on('click', '.delete-data', function(e) {
                e.preventDefault();
                var tabla = $('#myTable').DataTable();
                var fila = tabla.row($(this).closest('tr'));
                var data = fila.data();
                var id = data[0];
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: '¡Esta acción eliminará la visita permanentemente!',
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
                            url: "/liceo/controladores/visita_controlador.php",
                            data: {
                                'action': 'eliminar',
                                'id_visita': id
                            },
                            success: function(response) {
                                Swal.fire('¡Eliminada!', response, 'success').then(() => location.reload());
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