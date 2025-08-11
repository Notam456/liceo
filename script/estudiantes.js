// tabla
new DataTable('#myTable', {
    language: {
        //url: '//cdn.datatables.net/plug-ins/2.1.2/i18n/es-ES.json',
        search: 'Buscar',
        info: 'Mostrando pagina _PAGE_ de _PAGES_',
        infoEmpty: 'No se han encontrado resultados',
        infoFiltered: '(se han encontrado _MAX_ resultados)',
        lengthMenu: 'Mostrar _MENU_ por pagina',
        zeroRecords: '0 resultados encontrados'
    }
    , columnDefs: [{ width: '93px', targets: [5,6,7] }]
});

// Mostrar script
$(document).ready(function() {
    $('#myTable').on('click', '.view-data', function(e) {
        e.preventDefault();

        var id = $(this).closest('tr').find('.id_estudiante').text();

        $.ajax({
            type: "POST",
            url: "index.php",
            data: {
                'click-view-btn': true,
                'id_estudiante': id,
            },
            success: function(response) {
                $('.view_estudiante_data').html(response);
                $('#viewmodal').modal('show');
            }
        });
    });
});

// Editar script
$(document).ready(function() {
    $('#myTable').on('click', '.edit-data', function(e) {
        e.preventDefault();

        var id = $(this).closest('tr').find('.id_estudiante').text();

        $.ajax({
            type: "POST",
            url: "index.php",
            data: {
                'click-edit-btn': true,
                'id_estudiante': id,
            },
            success: function(response) {
                $.each(response, function(Key, value) {
                    $('#edit_id_estudiante').val(value['id_estudiante']);
                    $('#edit_nombre_estudiante').val(value['nombre_estudiante']);
                    $('#edit_apellido_estudiante').val(value['apellido_estudiante']);
                    $('#edit_cedula_estudiante').val(value['cedula_estudiante']);
                    $('#edit_contacto_estudiante').val(value['contacto_estudiante']);
                    $('#edit_municipio').val(value['Municipio']);
                    $('#edit_parroquia').val(value['Parroquia']);
                    $('#edit_año_academico').val(value['año_academico']);
                    $('#edit_seccion_estudiante').val(value['seccion_estudiante']);
                });

                $('#editmodal').modal('show');
            }
        });
    });
});

//eliminar script
$(document).ready(function() {
    $('#myTable').on('click', '.delete-data', function(e) {
        e.preventDefault();

        var id = $(this).closest('tr').find('.id_estudiante').text();

        Swal.fire({
            title: '¿Estás seguro?',
            text: '¡Esta acción eliminará al estudiante permanentemente!',
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
                    url: "index.php",
                    data: {
                        "click-delete-btn": true,
                        "id_estudiante": id,
                    },
                    success: function(response) {
                        Swal.fire(
                            '¡Eliminado!',
                            'El estudiante ha sido eliminado correctamente.',
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
