<table class="table table-striped" id="historialTable">
    <thead>
        <tr class="table-secondary">
            <th scope="col">#</th>
            <th scope="col">Usuario</th>
            <th scope="col">Año Académico</th>
            <th scope="col">Acción</th>
            <th scope="col">Fecha y Hora</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($historial_logs && mysqli_num_rows($historial_logs) > 0) {
            $contador = 1;
            while ($row = mysqli_fetch_array($historial_logs)) {
        ?>
                <tr>
                    <td><?php echo $contador++; ?></td>
                    <td><?php echo htmlspecialchars($row['nombre_usuario']); ?></td>
                    <td><?php echo htmlspecialchars($row['periodo_anio']); ?></td>
                    <td>
                        <span class="badge <?php echo ($row['accion'] == 'activar') ? 'bg-success' : 'bg-warning'; ?>">
                            <?php echo ucfirst($row['accion']); ?>
                        </span>
                    </td>
                    <td><?php echo date('d/m/Y H:i:s', strtotime($row['fecha'])); ?></td>
                </tr>
            <?php
            }
        } else {
            ?>
            <tr>
                <td></td>
                <td>No se encontraron registros en el historial.</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>
