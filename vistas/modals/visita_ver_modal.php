<?php
// Since this is a modal, it's expected to be included by a controller action
// where $row is defined.
if (!isset($row)) {
    echo "Error: Los datos de la visita no están disponibles.";
    return;
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <h4>Datos del Estudiante</h4>
            <table class="table table-borderless">
                <tbody>
                    <tr>
                        <th>Nombre Completo</th>
                        <td><?php echo htmlspecialchars($row['nombre'] . ' ' . $row['apellido']); ?></td>
                    </tr>
                    <tr>
                        <th>Cédula</th>
                        <td><?php echo htmlspecialchars($row['cedula']); ?></td>
                    </tr>
                    <tr>
                        <th>Fecha de Nacimiento</th>
                        <td><?php echo date("d/m/Y", strtotime($row['fecha_nacimiento'])); ?></td>
                    </tr>
                    <tr>
                        <th>Contacto</th>
                        <td><?php echo htmlspecialchars($row['contacto']); ?></td>
                    </tr>
                    <tr>
                        <th>Parroquia</th>
                        <td><?php echo htmlspecialchars($row['parroquia']); ?></td>
                    </tr>
                    <tr>
                        <th>Grado y Sección</th>
                        <td><?php echo htmlspecialchars($row['numero_anio'] . '° ' . $row['letra_seccion']); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-6">
            <h4>Datos de la Visita</h4>
            <table class="table table-borderless">
                <tbody>

                    <tr>
                        <th>Fecha de la Visita</th>
                        <td><?php echo date("d/m/Y", strtotime($row['fecha_visita'])); ?></td>
                    </tr>
                    <tr>
                        <th>Estado</th>
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
                    </tr>
                    <?php if (!empty($row['observaciones'])): ?>
                    <tr>
                        <th>Observaciones</th>
                        <td><?php echo htmlspecialchars($row['observaciones']); ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($row['fecha_realizada'])): ?>
                    <tr>
                        <th>Fecha de Realización</th>
                        <td><?php echo date("d/m/Y", strtotime($row['fecha_realizada'])); ?></td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
