<?php
if (!isset($asignaciones)) {
    die("Error: No se pudieron cargar los datos");
}

// Configuración de paginación
$por_pagina = $_GET['por_pagina'] ?? 10;
$pagina_actual = $_GET['pagina'] ?? 1;
$total_asignaciones = count($asignaciones);
$total_paginas = ceil($total_asignaciones / $por_pagina);

// Aplicar paginación
$asignaciones_paginadas = array_slice($asignaciones, ($pagina_actual - 1) * $por_pagina, $por_pagina);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <?php 
    $head_path = $_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/head.php';
    if (file_exists($head_path)) {
        include($head_path);
    } else {
        echo '<title>Asignar Materias a Profesores</title>';
        echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">';
        echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">';
    }
    ?>
    <title>Asignar Materias a Profesores</title>
    <style>
        .card-header {
            background-color: #ffffff;
            color: #ffffff;
            border-bottom: 2px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn-asignar {
            background-color: #28a745;
            border-color: #28a745;
            color: white;
        }
        .btn-asignar:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
        .filter-section {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .table thead th {
            background-color: #2c3e50;
            color: white;
        }
        .badge-activa {
            background-color: #28a745;
        }
        .badge-inactiva {
            background-color: #dc3545;
        }
        .modal-header {
            background-color: #ffffff;
            color: white;
        }
        .pagination {
            margin-bottom: 0;
        }
        .registros-por-pagina {
            width: 80px;
        }
        .search-box {
            max-width: 300px;
        }
    </style>
</head>
<body>
    <?php 
    $navbar_path = $_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/navbar.php';
    if (file_exists($navbar_path)) {
        include($navbar_path);
    }
    ?>

    <?php 
    $sidebar_path = $_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/sidebar.php';
    if (file_exists($sidebar_path)) {
        include($sidebar_path);
    }
    ?>

    <div class="container" style="margin-top: 30px;">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <?php if (isset($_SESSION['status']) && $_SESSION['status'] != '') : ?>
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <?php echo $_SESSION['status']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['status']); ?>
                <?php endif; ?>

                <div class="card">
                    <div class="card-header">
                        <div>
                            <h4 class="mb-0"><i class="bi bi-link-45deg"></i> Asignar Materias a Profesores</h4>
                            <p class="mb-0 text-muted">Sistema de gestión de asignaciones académicas</p>
                        </div>
                        <button type="button" class="btn btn-asignar" data-bs-toggle="modal" data-bs-target="#modalAsignacion">
                            <i class="bi bi-plus-circle"></i> Nueva Asignación
                        </button>
                    </div>
                    
                    <div class="card-body">
                        <!-- Filtros y Búsqueda -->
                        <div class="filter-section">
                            <div class="row g-3 align-items-center">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                                        <input type="text" class="form-control" id="buscarInput" placeholder="Buscar por profesor, materia o cédula...">
                                        <button class="btn btn-outline-secondary" type="button" id="btnBuscar">
                                            Buscar
                                        </button>
                                        <button class="btn btn-outline-secondary" type="button" id="btnLimpiar">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-text">Mostrar</span>
                                        <select class="form-select registros-por-pagina" id="porPagina">
                                            <option value="10" <?= $por_pagina == 10 ? 'selected' : '' ?>>10</option>
                                            <option value="20" <?= $por_pagina == 20 ? 'selected' : '' ?>>20</option>
                                            <option value="30" <?= $por_pagina == 30 ? 'selected' : '' ?>>30</option>
                                            <option value="50" <?= $por_pagina == 50 ? 'selected' : '' ?>>50</option>
                                            <option value="100" <?= $por_pagina == 100 ? 'selected' : '' ?>>100</option>
                                        </select>
                                        <span class="input-group-text">registros</span>
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="d-flex justify-content-end">
                                        <span class="badge bg-secondary align-self-center">
                                            Total: <?= $total_asignaciones ?> asignaciones
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tabla de asignaciones existentes -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="tablaAsignaciones">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Profesor</th>
                                        <th>Materia</th>
                                        <th>Fecha Asignación</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($asignaciones_paginadas) > 0): ?>
                                        <?php foreach ($asignaciones_paginadas as $asignacion): ?>
                                        <tr class="fila-asignacion">
                                            <td><?= $asignacion['id_asignacion'] ?></td>
                                            <td>
                                                <strong><?= htmlspecialchars($asignacion['apellido'] . ', ' . $asignacion['nombre']) ?></strong>
                                                <br><small class="text-muted">Cédula: <?= htmlspecialchars($asignacion['cedula']) ?></small>
                                            </td>
                                            <td>
                                                <strong><?= htmlspecialchars($asignacion['nombre_materia']) ?></strong>
                                                <br><small class="text-muted">ID: <?= $asignacion['id_materia'] ?></small>
                                            </td>
                                            <td><?= date('d/m/Y H:i', strtotime($asignacion['fecha_asignacion'])) ?></td>
                                           
                                            <td>
                                                <a href="asigna_materia_controlador.php?action=eliminar&id=<?= $asignacion['id_asignacion'] ?>" 
                                                   class="btn btn-danger btn-sm"
                                                   onclick="return confirm('¿Estás seguro de eliminar esta asignación?')">
                                                    <i class="bi bi-trash"></i> Eliminar
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">
                                                <i class="bi bi-inbox display-4"></i>
                                                <p class="mt-2">No hay asignaciones registradas</p>
                                                <small>Use el botón "Nueva Asignación" para crear una</small>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>

                            <!-- Paginación -->
                            <?php if ($total_paginas > 1): ?>
                            <nav aria-label="Paginación de asignaciones">
                                <ul class="pagination justify-content-center">
                                    <!-- Botón Anterior -->
                                    <li class="page-item <?= $pagina_actual == 1 ? 'disabled' : '' ?>">
                                        <a class="page-link" href="?pagina=<?= $pagina_actual - 1 ?>&por_pagina=<?= $por_pagina ?>">
                                            <i class="bi bi-chevron-left"></i>
                                        </a>
                                    </li>
                                    
                                    <!-- Números de página -->
                                    <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                                        <li class="page-item <?= $i == $pagina_actual ? 'active' : '' ?>">
                                            <a class="page-link" href="?pagina=<?= $i ?>&por_pagina=<?= $por_pagina ?>">
                                                <?= $i ?>
                                            </a>
                                        </li>
                                    <?php endfor; ?>
                                    
                                    <!-- Botón Siguiente -->
                                    <li class="page-item <?= $pagina_actual == $total_paginas ? 'disabled' : '' ?>">
                                        <a class="page-link" href="?pagina=<?= $pagina_actual + 1 ?>&por_pagina=<?= $por_pagina ?>">
                                            <i class="bi bi-chevron-right"></i>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                            <?php endif; ?>

                            <!-- Información de paginación -->
                            <div class="text-center text-muted mt-2">
                                Mostrando <?= count($asignaciones_paginadas) ?> de <?= $total_asignaciones ?> registros
                                <?php if ($total_asignaciones > 0): ?>
                                    (Página <?= $pagina_actual ?> de <?= $total_paginas ?>)
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para nueva asignación -->
    <div class="modal fade" id="modalAsignacion" tabindex="-1" aria-labelledby="modalAsignacionLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAsignacionLabel">
                        <i class="bi bi-plus-circle"></i> Nueva Asignación
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="asigna_materia_controlador.php">
                    <input type="hidden" name="action" value="crear">
                    
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Seleccionar Profesor</label>
                            <select class="form-select" name="id_profesor" required>
                                <option value="">Seleccionar profesor</option>
                                <?php foreach ($profesores as $profesor): ?>
                                <option value="<?= $profesor['id_profesor'] ?>">
                                    <?= htmlspecialchars($profesor['apellido'] . ', ' . $profesor['nombre'] . ' (' . $profesor['cedula'] . ')') ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Seleccionar Materia</label>
                            <select class="form-select" name="id_materia" required>
                                <option value="">Seleccionar materia</option>
                                <?php foreach ($materias as $materia): ?>
                                <option value="<?= $materia['id_materia'] ?>">
                                    <?= htmlspecialchars($materia['nombre']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-asignar">
                            <i class="bi bi-check-lg"></i> Asignar Materia
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php 
    $footer_path = $_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/footer.php';
    if (file_exists($footer_path)) {
        include($footer_path);
    }
    ?>

    <!-- Scripts necesarios -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    $(document).ready(function() {
        // Función para buscar en la tabla
        function buscarEnTabla() {
            var texto = $('#buscarInput').val().toLowerCase();
            
            $('.fila-asignacion').each(function() {
                var fila = $(this);
                var textoFila = fila.text().toLowerCase();
                
                if (textoFila.indexOf(texto) > -1) {
                    fila.show();
                } else {
                    fila.hide();
                }
            });
        }
        
        // Buscar al escribir
        $('#buscarInput').on('keyup', function() {
            buscarEnTabla();
        });
        
        // Botón buscar
        $('#btnBuscar').click(function() {
            buscarEnTabla();
        });
        
        // Botón limpiar
        $('#btnLimpiar').click(function() {
            $('#buscarInput').val('');
            $('.fila-asignacion').show();
        });
        
        // Cambiar número de registros por página
        $('#porPagina').change(function() {
            var por_pagina = $(this).val();
            window.location.href = '?por_pagina=' + por_pagina + '&pagina=1';
        });
        
        // Limpiar formulario al cerrar modal
        $('#modalAsignacion').on('hidden.bs.modal', function() {
            $('#modalAsignacion form')[0].reset();
        });
        
        // Mostrar modal si hay error en la asignación
        <?php if (isset($_SESSION['show_modal']) && $_SESSION['show_modal']): ?>
            $('#modalAsignacion').modal('show');
            <?php unset($_SESSION['show_modal']); ?>
        <?php endif; ?>
    });
    </script>
</body>
</html>