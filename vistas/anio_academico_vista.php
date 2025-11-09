<!DOCTYPE html>
<html lang="en">

<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/head.php'); ?>
    <link rel="stylesheet" href="../includes/backdrop.css">
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
                
                $today = new DateTime();
                $today->format('Y-m-d');
                $yearLater = new DateTime();
                $yearLater->add(new DateInterval('P366D'));
                if (isset($_SESSION['status']) && $_SESSION['status'] != '') {
                ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>¡Atención!</strong> <?php echo $_SESSION['status']; ?>
                        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php
                    unset($_SESSION['status']);
                }
                ?>

                <div class="card">
                    <div class="card-header">
                        <h4>Año Academico <img src="/liceo/icons/people.svg">
                            <button type="button" class="btn btn-primary float-end btn-success" data-toggle="modal" data-target="#insertdata">
                                Agregar
                            </button>
                        </h4>
                        <!-- Pestañas de navegación -->
                        <ul class="nav nav-tabs mt-3" id="anioTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?php echo (!isset($_REQUEST['action']) || $_REQUEST['action'] == 'listar') ? 'active' : ''; ?>" 
                                        id="lista-tab" data-toggle="tab" data-target="#lista-pane" 
                                        type="button" role="tab" aria-controls="lista-pane" 
                                        aria-selected="<?php echo (!isset($_REQUEST['action']) || $_REQUEST['action'] == 'listar') ? 'true' : 'false'; ?>">
                                    Lista de Años Académicos
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?php echo (isset($_REQUEST['action']) && $_REQUEST['action'] == 'historialLogs') ? 'active' : ''; ?>" 
                                        id="historial-tab" data-toggle="tab" data-target="#historial-pane" 
                                        type="button" role="tab" aria-controls="historial-pane" 
                                        aria-selected="<?php echo (isset($_REQUEST['action']) && $_REQUEST['action'] == 'historialLogs') ? 'true' : 'false'; ?>">
                                    Historial de Cambios
                                </button>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="card-body">
                        <!-- Contenido de las pestañas -->
                        <div class="tab-content" id="anioTabsContent">
                            <!-- Pestaña Lista de Años Académicos -->
                            <div class="tab-pane fade <?php echo (!isset($_REQUEST['action']) || $_REQUEST['action'] == 'listar') ? 'show active' : ''; ?>" 
                                 id="lista-pane" role="tabpanel" aria-labelledby="lista-tab">
                                <table class="table table-striped" id="myTable">
                                    <thead>
                                        <tr class="table-secondary">
                                            <th scope="col">#</th>
                                            <th scope="col">Período</th>
                                            <th scope="col">Estado</th>
                                            <th scope="col" class="action">Acción</th>
                                           
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                <?php
                                if ($anios_academicos && mysqli_num_rows($anios_academicos) > 0) {
                                    while ($row = mysqli_fetch_array($anios_academicos)) {
                                ?>
                                        <tr>
                                            <td class="id_anio"> <?php echo $row['id_anio'] ?> </td>
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

                                            

                                        </tr>
                                    <?php
                                    }
                                } else {
                                    ?>
                                    <tr>

                                        <td></td>
                                        <td>No se encontraron registros de Años academicos.</td>
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
                            
                            <!-- Pestaña Historial de Cambios -->
                            <div class="tab-pane fade <?php echo (isset($_REQUEST['action']) && $_REQUEST['action'] == 'historialLogs') ? 'show active' : ''; ?>" 
                                 id="historial-pane" role="tabpanel" aria-labelledby="historial-tab">
                                
                                <!-- Filtros -->
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label for="filtro_usuario" class="form-label">Usuario:</label>
                                        <select class="form-control" id="filtro_usuario" name="filtro_usuario">
                                            <option value="">Todos los usuarios</option>
                                            <?php 
                                            if (isset($usuarios_filtro) && $usuarios_filtro && mysqli_num_rows($usuarios_filtro) > 0) {
                                                while ($usuario = mysqli_fetch_array($usuarios_filtro)) {
                                                    $selected = (isset($_GET['filtro_usuario']) && $_GET['filtro_usuario'] == $usuario['id_usuario']) ? 'selected' : '';
                                                    echo "<option value='{$usuario['id_usuario']}' $selected>{$usuario['usuario']}</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="filtro_anio" class="form-label">Año Académico:</label>
                                        <select class="form-control" id="filtro_anio" name="filtro_anio">
                                            <option value="">Todos los años</option>
                                            <?php 
                                            if (isset($anios_filtro) && $anios_filtro && mysqli_num_rows($anios_filtro) > 0) {
                                                while ($anio = mysqli_fetch_array($anios_filtro)) {
                                                    $selected = (isset($_GET['filtro_anio']) && $_GET['filtro_anio'] == $anio['id_anio']) ? 'selected' : '';
                                                    echo "<option value='{$anio['id_anio']}' $selected>{$anio['periodo']}</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="filtro_accion" class="form-label">Acción:</label>
                                        <select class="form-control" id="filtro_accion" name="filtro_accion">
                                            <option value="">Todas las acciones</option>
                                            <option value="activar" <?php echo (isset($_GET['filtro_accion']) && $_GET['filtro_accion'] == 'activar') ? 'selected' : ''; ?>>Activar</option>
                                            <option value="desactivar" <?php echo (isset($_GET['filtro_accion']) && $_GET['filtro_accion'] == 'desactivar') ? 'selected' : ''; ?>>Desactivar</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 d-flex align-items-end">
                                        <button type="button" class="btn btn-primary me-2" id="aplicarFiltros">Filtrar</button>
                                        <button type="button" class="btn btn-secondary" id="limpiarFiltros">Limpiar</button>
                                    </div>
                                </div>
                                
                                <!-- Tabla del historial -->
                                <div id="historial-container">
                                    <?php 
                                    if (isset($historial_logs)) {
                                        include($_SERVER['DOCUMENT_ROOT'] . '/liceo/vistas/historial_logs_tabla.php');
                                    } else {
                                        echo '<p class="text-center">Seleccione la pestaña "Historial de Cambios" para ver los registros.</p>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modulo editar -->
    <div class="modal" id="editmodal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Modificar Año Academico</h4>
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
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        <button type="submit" name="update-data" class="btn btn-primary btn-success">Guardar datos</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modulo mostrar -->
    <div class="modal" id="viewmodal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Datos del Año Academico</h4>
                </div>
                <div class="modal-body">
                    <div class="view_anio_data"></div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- modulo crear -->
    <div class="modal" id="insertdata" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Agregar Año Academico</h4>
                </div>
                <form id="formAnio" action="/liceo/controladores/anio_academico_controlador.php" method="POST">
                    <input type="hidden" name="action" value="crear">
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="inicio">Fecha de inicio del año académico</label>
                            <input type="date" class="form-control" name="inicio" min="<?= $today->format('Y-m-d');?>" max="<?= $yearLater->format('Y-m-d') ?>"  required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="fin">Fecha de fin del año académico</label>
                            <input type="date" class="form-control" name="fin" min="<?= $today->format('Y-m-d');?>" max="<?= $yearLater->format('Y-m-d') ?>" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
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
                    targets: [3]
                },
                {
                    visible: false,
                    target: 0
                }
            ]
        });

        // Función universal para abrir modales
        function abrirModal(modalId) {
            // Cerrar cualquier modal abierto
            $('.modal').removeClass('in').hide();
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
            
            // Abrir el modal solicitado
            $('#' + modalId).addClass('in').show();
            $('body').addClass('modal-open');
            
            // Forzar backdrop manualmente si es necesario
            if (!$('.modal-backdrop').length) {
                $('body').append('<div class="modal-backdrop in"></div>');
            }
        }
        
        // Cerrar modales
        function cerrarModal(modalId) {
            $('#' + modalId).removeClass('in').hide();
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
        }

        $(document).ready(function() {
            // Ver
            $(document).on('click', '.view-data', function(e) {
                e.preventDefault();
                var tabla = $('#myTable').DataTable();

                // obtenemos la fila DataTables desde el botón clicado
                var fila = tabla.row($(this).closest('tr'));

                // traemos los datos de esa fila (array con todas las columnas)
                var data = fila.data();

                // id_anio está en la primera columna (índice 0)
                var id_anio = data[0];
                $.ajax({
                    type: "POST",
                    url: "/liceo/controladores/anio_academico_controlador.php",
                    data: {
                        'action': 'ver',
                        'id_anio': id_anio
                    },
                    success: function(response) {
                        $('.view_anio_data').html(response);
                        abrirModal('viewmodal');
                    }
                });
            });

            // Cargar para editar
            $(document).on('click', '.edit-data', function(e) {
                e.preventDefault();
                var tabla = $('#myTable').DataTable();

                // obtenemos la fila DataTables desde el botón clicado
                var fila = tabla.row($(this).closest('tr'));

                // traemos los datos de esa fila (array con todas las columnas)
                var data = fila.data();

                // id_anio está en la primera columna (índice 0)
                var id_anio = data[0];
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
                            abrirModal('editmodal');
                        }
                    }
                });
            });

            // Eliminar
            $(document).on('click', '.delete-data', function(e) {
                e.preventDefault();
                var tabla = $('#myTable').DataTable();

                // obtenemos la fila DataTables desde el botón clicado
                var fila = tabla.row($(this).closest('tr'));

                // traemos los datos de esa fila (array con todas las columnas)
                var data = fila.data();

                // id_anio está en la primera columna (índice 0)
                var id_anio = data[0];
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
                var tabla = $('#myTable').DataTable();

                // obtenemos la fila DataTables desde el botón clicado
                var fila = tabla.row($(this).closest('tr'));

                // traemos los datos de esa fila (array con todas las columnas)
                var data = fila.data();

                // id_anio está en la primera columna (índice 0)
                var id_anio = data[0];
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

            // Funcionalidad para las pestañas y filtros del historial
            $('#historial-tab').on('click', function() {
                // Cargar historial cuando se hace clic en la pestaña
                if (!$(this).hasClass('loaded')) {
                    cargarHistorial();
                    $(this).addClass('loaded');
                }
            });

            // Aplicar filtros
            $('#aplicarFiltros').on('click', function() {
                cargarHistorial();
            });

            // Limpiar filtros
            $('#limpiarFiltros').on('click', function() {
                $('#filtro_usuario').val('');
                $('#filtro_anio').val('');
                $('#filtro_accion').val('');
                cargarHistorial();
            });

            // Función para cargar el historial con filtros
            function cargarHistorial() {
                var filtro_usuario = $('#filtro_usuario').val();
                var filtro_anio = $('#filtro_anio').val();
                var filtro_accion = $('#filtro_accion').val();

                $.ajax({
                    type: "GET",
                    url: "/liceo/controladores/anio_academico_controlador.php",
                    data: {
                        'action': 'historialLogs',
                        'ajax': '1',
                        'filtro_usuario': filtro_usuario,
                        'filtro_anio': filtro_anio,
                        'filtro_accion': filtro_accion
                    },
                    success: function(response) {
                        $('#historial-container').html(response);
                        
                        // Inicializar DataTable para la tabla del historial
                        if ($.fn.DataTable.isDataTable('#historialTable')) {
                            $('#historialTable').DataTable().destroy();
                        }
                        
                        $('#historialTable').DataTable({
                            language: {
                                search: 'Buscar',
                                info: 'Mostrando página _PAGE_ de _PAGES_',
                                infoEmpty: 'No se han encontrado resultados',
                                infoFiltered: '(se han encontrado _MAX_ resultados)',
                                lengthMenu: 'Mostrar _MENU_ por página',
                                zeroRecords: '0 resultados encontrados'
                            },
                            order: [[4, 'desc']], // Ordenar por fecha descendente
                            columnDefs: [{
                                targets: [0],
                                visible: false
                            }]
                        });
                    },
                    error: function() {
                        $('#historial-container').html('<p class="text-center text-danger">Error al cargar el historial.</p>');
                    }
                });
            }

            // Cerrar modales con botones
            $('[data-dismiss="modal"]').on('click', function() {
                var modal = $(this).closest('.modal');
                cerrarModal(modal.attr('id'));
            });
        });
    </script>

    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/footer.php') ?>
    </footer>

</body>

</html>