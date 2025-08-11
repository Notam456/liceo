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

    },
    columnDefs: [{
        width: '93px',
        targets: [3, 4, 5]
    }]
});

// Mostrar script
$(document).ready(function() {
    $('#myTable').on('click', '.view-data', function(e) {
        e.preventDefault();

        var id = $(this).closest('tr').find('.id').text();

        $.ajax({
            type: "POST",
            url: "index.php",
            data: {
                'click-view-btn': true,
                'id': id,
            },
            success: function(response) {
                $('.view_user_data').html(response);
                $('#viewmodal').modal('show');
            }
        });
    });
});

// Editar script
$(document).ready(function() {
    $('#myTable').on('click', '.edit-data', function(e) {
        e.preventDefault();

        var id = $(this).closest('tr').find('.id').text();

        $.ajax({
            type: "POST",
            url: "index.php",
            data: {
                'click-edit-btn': true,
                'id': id,
            },
            success: function(response) {
                $.each(response, function(Key, value) {
                    $('#idEdit').val(value['id_materia']);
                    $('#nombre_materia_edit').val(value['nombre_materia']);
                    $('#info_materia_edit').val(value['info_materia']);
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

        var id = $(this).closest('tr').find('.id').text();

        Swal.fire({
            title: '¿Estás seguro?',
            text: '¡Esta acción eliminará la materia permanentemente!',
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
                        "id": id,
                    },
                    success: function(response) {
                        Swal.fire(
                            '¡Eliminado!',
                            'La materia ha sido eliminada correctamente.',
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
