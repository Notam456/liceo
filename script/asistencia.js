$(document).ready(function() {
    // Configuración de DataTables
    $('#tablaAsistencia').DataTable({
        columnDefs: [
            { targets: 0, visible: false },
            { targets: -1, orderable: false }
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        }
    });

    // Mostrar/ocultar nota de justificación
    $('body').on('change', '.estado-radio', function() {
        if ($(this).val() === 'J') {
            $(this).closest('.form-group').find('.justificado-note').show();
            $(this).closest('.form-group').find('.justificado-note textarea').prop('required', true);
        } else {
            $(this).closest('.form-group').find('.justificado-note').hide();
            $(this).closest('.form-group').find('.justificado-note textarea').prop('required', false);
        }
    });

    $('body').on('change', '.justificado-radio', function() {
        if($(this).is(":checked")) {
            $(this).closest("tr").find(".justificado-note").show();
        }
    });
    $('body').on('change', 'input[name^="asistencia["][value="P"], input[name^="asistencia["][value="A"]', function() {
        $(this).closest("tr").find(".justificado-note").hide();
    });

    // Cargar estudiantes al seleccionar sección
    $('#seccionAsistencia').change(function() {
        var seccion = $(this).val();

        if(seccion) {
            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: {
                    'obtener_estudiantes': true,
                    'seccion': seccion
                },
                success: function(response) {
                    $('#listaEstudiantes').html(response);
                }
            });
        } else {
            $('#listaEstudiantes').html('<p class="text-muted">Seleccione una sección para ver los estudiantes</p>');
        }
    });

    // Aplicar filtros
    $('#aplicarFiltro').click(function() {
        var seccion = $('#filtroSeccion').val();
        var fecha = $('#filtroFecha').val();

        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: {
                'filtrar_asistencia': true,
                'seccion': seccion,
                'fecha': fecha
            },
            success: function(response) {
                $('#tablaAsistencia tbody').html(response);
            }
        });
    });

    // Editar asistencia
    $('#tablaAsistencia').on('click', '.edit-asistencia', function(e) {
        e.preventDefault();

        var id = $(this).closest('tr').find('td:first').text();

        $.ajax({
            url: 'index.php',
            type: 'POST',
            dataType: 'json',
            data: {
                'obtener_asistencia': true,
                'id_asistencia': id
            },
            success: function(response) {
                $('#id_asistencia_edit').val(response.id_asistencia);
                $('#fecha_edit').val(response.fecha);
                $('#estudiante_edit').val(response.nombre_estudiante + ' ' + response.apellido_estudiante);

                // Establecer el estado correcto
                $('input[name="estado"][value="' + response.estado + '"]').prop('checked', true);

                // Mostrar/ocultar campo de justificación
                if(response.estado === 'J') {
                    $('#editarAsistenciaModal .justificado-note').show();
                    $('#justificacion_edit').val(response.justificacion);
                } else {
                    $('#editarAsistenciaModal .justificado-note').hide();
                }

                $('#editarAsistenciaModal').modal('show');
            }
        });
    });

    // Eliminar asistencia
    $('#tablaAsistencia').on('click', '.delete-asistencia', function(e) {
        e.preventDefault();

        var id = $(this).siblings('.delete_id_asistencia').val();

        Swal.fire({
            title: '¿Estás seguro?',
            text: '¡Esta acción eliminará el registro de asistencia permanentemente!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'index.php',
                    type: 'POST',
                    data: {
                        'eliminar_asistencia': true,
                        'id_asistencia': id
                    },
                    success: function(response) {
                        Swal.fire(
                            '¡Eliminado!',
                            'El registro de asistencia ha sido eliminado correctamente.',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    }
                });
            }
        });
    });
});
