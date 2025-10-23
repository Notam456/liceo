<?php
// Obtener información de la sección para el modal
$id_seccion = $_POST['id_seccion'] ?? '';
$seccion_info = $seccionModelo->obtenerSeccionPorId($id_seccion);
$seccion_data = mysqli_fetch_assoc($seccion_info);
?>

<div class="modal-header">
    <h1 class="modal-title fs-5" id="viewmodalLabel">
        Información de la Sección - <?php echo $seccion_data['numero_anio'] ?? ''; ?>°<?php echo $seccion_data['letra'] ?? ''; ?>
    </h1>
</div>
<div class="modal-body">
    <div class="row mb-3">
        <div class="col-md-6">
            <strong>Grado:</strong> <?php echo $seccion_data['numero_anio'] ?? ''; ?>° Año
        </div>
        <div class="col-md-6">
            <strong>Sección:</strong> <?php echo $seccion_data['letra'] ?? ''; ?>
        </div>
    </div>
    
    <?php if (!empty($seccion_data['nombre_tutor'])): ?>
    <div class="row mb-3">
        <div class="col-12">
            <strong>Tutor:</strong> <?php echo $seccion_data['nombre_tutor'] . ' ' . $seccion_data['apellido_tutor']; ?>
        </div>
    </div>
    <?php endif; ?>
    
    <div class="row mb-4">
        <div class="col-12">
            <a href="/liceo/controladores/seccion_controlador.php?action=generar_matricula_completa&id_seccion=<?php echo $id_seccion; ?>" 
               target="_blank" 
               class="btn btn-secondary">
                <i class="fas fa-file-pdf"></i> Generar Matrícula Completa
            </a>
        </div>
    </div>
    
    <h5>Estudiantes en esta Sección</h5>
    
    <?php if ($estudiantes && mysqli_num_rows($estudiantes) > 0): ?>
        <div class="table-responsive">
            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Apellidos</th>
                        <th>Nombres</th>
                        <th>Cédula</th>
                        <th>Contacto</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $contador = 1;
                    while ($estudiante = mysqli_fetch_array($estudiantes)): 
                    ?>
                    <tr>
                        <td><?php echo $contador++; ?></td>
                        <td><?php echo $estudiante['apellido']; ?></td>
                        <td><?php echo $estudiante['nombre']; ?></td>
                        <td><?php echo $estudiante['cedula']; ?></td>
                        <td><?php echo $estudiante['contacto']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            <strong>Total de estudiantes:</strong> <?php echo mysqli_num_rows($estudiantes); ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            No hay estudiantes asignados a esta sección.
        </div>
    <?php endif; ?>
</div>