<?php
if (!isset($reporte)) {
    header("Location: /liceo/error.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/head.php'); ?>
    <title>Reporte de Ausencias</title>
    <style>
        .card-alumno {
            margin-bottom: 15px;
            transition: all 0.3s;
        }
        .card-alumno.alert {
            border-left: 5px solid #dc3545;
            background-color: #fff8f8;
        }
        .badge-ausencias {
            font-size: 1rem;
        }
        .filtros {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .tabla-ausencias {
            margin-top: 20px;
        }
        .tabla-ausencias th {
            white-space: nowrap;
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
            <div class="col-md-12">
                <?php if (isset($_SESSION['status']) && $_SESSION['status'] != '') : ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Hey!</strong> <?php echo $_SESSION['status']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['status']); ?>
                <?php endif; ?>

                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4><i class="bi bi-clipboard2-pulse"></i> Reporte de Ausencias</h4>
                    </div>
                    <div class="card-body">
                        <div class="filtros">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="filtroCedula" class="form-label">Buscar por cédula:</label>
                                    <input type="text" class="form-control" id="filtroCedula" placeholder="Ej: 30426270">
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-danger" id="alert-ausencias" style="display: none;">
                            <h5><i class="bi bi-exclamation-triangle-fill"></i> Alerta: Estudiantes con 3 o más ausencias</h5>
                            <div id="lista-alertas" class="mt-2"></div>
                        </div>

                        <div class="table-responsive tabla-ausencias">
                            <table class="table table-striped table-hover" id="tablaReportes">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Estudiante</th>
                                        <th>Sección</th>
                                        <th>Contacto</th>
                                        <th>Cédula</th>
                                        <th>Ausencias</th>
                                        <th>Justificadas</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($reporte as $item): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($item['nombre']) ?></td>
                                        <td><?= htmlspecialchars($item['seccion']) ?></td>
                                        <td><?= htmlspecialchars($item['contacto']) ?></td>
                                        <td><?= htmlspecialchars($item['cedula']) ?></td>
                                        <td><span class="badge bg-danger"><?= $item['ausencias'] ?></span></td>
                                        <td><span class="badge bg-warning text-dark"><?= $item['justificadas'] ?></span></td>
                                        <td>
                                            <span class="badge <?= $item['total'] >= 3 ? 'bg-danger' : 'bg-secondary' ?>">
                                                <?= $item['total'] ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/footer.php') ?>
    </footer>

    <script>
    $(document).ready(function() {
        // Configuración de DataTables
        var table = $('#tablaReportes').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            },
            "order": [[6, "desc"]],
            "dom": '<"top"f>rt<"bottom"lip><"clear">'
        });

        // Filtro por cédula
        $('#filtroCedula').keyup(function() {
            table.column(3).search(this.value).draw();
        });

        // Mostrar alertas para estudiantes con 3+ ausencias
        var alertas = <?= json_encode(array_filter($reporte, function($item) { return $item['total'] >= 3; })) ?>;
        if (alertas.length > 0) {
            $('#alert-ausencias').show();
            $('#lista-alertas').html(
                alertas.map(item => 
                    `<div class="card-alumno alert">
                        ${item.nombre} (${item.cedula}) - 
                        <span class="badge bg-danger">${item.total} ausencias</span>
                    </div>`
                ).join('')
            );
        }
    });
    </script>
</body>
</html>