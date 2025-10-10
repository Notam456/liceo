<!DOCTYPE html>
<html lang="es">
<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/head.php'); ?>
    <title>Constructor de Horarios</title>
</head>
<body>
    <nav><?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/navbar.php'); ?></nav>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/sidebar.php'); ?>

    <div class="container" style="margin-top: 30px;">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card mb-5">
                    <div class="card-header">
                        <h4>Constructor de Horario</h4>
                    </div>
                    <div class="card-body">
                      
                        <form id="form-horario" class="row g-3 align-items-end mb-4 border p-3 rounded">
                            <div class="col-md-5">
                                <label for="asignacion" class="form-label">Materia y Profesor</label>
                                <select name="asignacion" id="asignacion" class="form-select" required>
                                    <option value="">Seleccione...</option>
                                    <?php foreach ($asignaciones as $asig): ?>
                                        <option value="<?= htmlspecialchars($asig['id_asignacion']) ?>">
                                            <?= htmlspecialchars($asig['nombre_materia']) ?> - <?= htmlspecialchars($asig['nombre_profesor'] . ' ' . $asig['apellido_profesor']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="dia" class="form-label">Día</label>
                                <select name="dia" id="dia" class="form-select" required>
                                    <option value="">Seleccione...</option>
                                    <?php foreach ($dias as $dia): ?>
                                        <option value="<?= htmlspecialchars($dia) ?>"><?= htmlspecialchars($dia) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="button" id="btn-agregar" class="btn btn-success w-100">Agregar</button>
                            </div>
                        </form>

                       
                        <div class="row">
                            <?php foreach ($dias as $dia): ?>
                                <div class="col-md-4 mb-3">
                                    <div class="card">
                                        <div class="card-header bg-secondary text-white">
                                            <strong><?= htmlspecialchars($dia) ?></strong>
                                        </div>
                                        <ul class="list-group list-group-flush" id="lista-<?= strtolower(str_replace('é', 'e', $dia)) ?>">
                                            
                                        </ul>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button onclick="window.location.href='/liceo/controladores/seccion_controlador.php'" class="btn btn-primary mt-3">Regresar a Secciones</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    const horarioCargado = <?= json_encode($horario_existente) ?>;
    const seccionId = <?= json_encode($seccion_id) ?>;

    function renderizarItemHorario(item) {
        return `
            <li class="list-group-item d-flex justify-content-between align-items-center" data-id-horario="${item.id_horario}">
                <span>
                    <strong class="text-primary">${item.nombre_materia}</strong><br>
                    <small class="text-muted">${item.nombre_profesor} ${item.apellido_profesor}</small>
                </span>
                <button class="btn btn-sm btn-danger btn-eliminar" title="Eliminar">
                    <i class="bi bi-trash3"></i>
                </button>
            </li>`;
    }

    document.addEventListener('DOMContentLoaded', function () {
        horarioCargado.forEach(item => {
            const diaId = `lista-${item.dia.toLowerCase().replace('é', 'e')}`;
            const lista = document.getElementById(diaId);
            if (lista) {
                lista.innerHTML += renderizarItemHorario(item);
            }
        });
    });

    document.getElementById('btn-agregar').addEventListener('click', function () {
        const asignacionSelect = document.getElementById('asignacion');
        const diaSelect = document.getElementById('dia');
        const id_asignacion = asignacionSelect.value;
        const dia = diaSelect.value;

        if (!id_asignacion || !dia) {
            Swal.fire('Error', 'Por favor complete todos los campos.', 'error');
            return;
        }

        $.ajax({
            type: "POST",
            url: "/liceo/controladores/horario_controlador.php",
            data: {
                'action': 'guardar',
                'id_asignacion': id_asignacion,
                'seccion': seccionId,
                'dia': dia,
            },
            dataType: 'json',
            success: function (response) {
                if(response.success) {
                    const newItem = {
                        id_horario: response.id_horario,
                        nombre_materia: asignacionSelect.options[asignacionSelect.selectedIndex].text.split(' - ')[0],
                        nombre_profesor: asignacionSelect.options[asignacionSelect.selectedIndex].text.split(' - ')[1].split(' ')[0],
                        apellido_profesor: asignacionSelect.options[asignacionSelect.selectedIndex].text.split(' - ')[1].split(' ')[1] || '',
                        dia: dia
                    };
                    const diaId = `lista-${dia.toLowerCase().replace('é', 'e')}`;
                    const lista = document.getElementById(diaId);
                    if (lista) {
                        lista.innerHTML += renderizarItemHorario(newItem);
                    }
                    asignacionSelect.value = '';
                    diaSelect.value = '';
                    Swal.fire('¡Guardado!', 'La materia ha sido agregada al horario.', 'success');
                } else {
                    Swal.fire('Error', response.message || 'No se pudo guardar el registro.', 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Ocurrió un problema de comunicación.', 'error');
            }
        });
    });

    document.addEventListener('click', function (e) {
        const botonEliminar = e.target.closest('.btn-eliminar');
        if (botonEliminar) {
            const listItem = botonEliminar.closest('li');
            const id_horario = listItem.dataset.idHorario;

            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, ¡eliminar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: '/liceo/controladores/horario_controlador.php',
                        data: {
                            action: 'eliminar',
                            id_horario: id_horario
                        },
                        dataType: 'json',
                        success: function (response) {
                            if(response.success) {
                                listItem.remove();
                                Swal.fire('¡Eliminado!', 'La materia ha sido eliminada del horario.', 'success');
                            } else {
                                Swal.fire('Error', response.message || 'No se pudo eliminar el registro.', 'error');
                            }
                        },
                        error: function () {
                            Swal.fire('Error', 'Ocurrió un problema de comunicación.', 'error');
                        }
                    });
                }
            });
        }
    });
    </script>
    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/footer.php'); ?>
    </footer>
</body>
</html>