// tabla
new DataTable('#myTable', {
    language: {
        //url: '//cdn.datatables.net/plug-ins/2.1.2/i18n/es-ES.json',
        search: 'Buscar',
        info: 'Mostrando pagina _PAGE_ de _PAGES_',
        infoEmpty: 'No se han encontrado resultados',
        infoFiltered: '(se han encontrado _MAX_ resultados)',
        lengthMenu: 'Mostrar _MENU_ por pagina',
        zeroRecords: '0 resultados encontrados',

    },
    columnDefs: [{
        width: '93px',
        targets: [2, 3, 4]
    }],
    order: [[2, 'asc']]
});
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(function(tooltipTriggerEl) {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });
});


// Mostrar script
$(document).ready(function() {
    $('#myTable').on('click', '.view-data', function(e) {
        e.preventDefault();

        var id_seccion = $(this).closest('tr').find('.id_seccion').text();

        $.ajax({
            type: "POST",
            url: "index.php",
            data: {
                'click-view-btn': true,
                'id_seccion': id_seccion,
            },
            success: function(response) {
                $('.view_seccion_data').html(response);
                $('#viewmodal').modal('show');
            }
        });
    });
});

// Editar script
$(document).ready(function() {
    $('#myTable').on('click', '.edit-data', function(e) {
        e.preventDefault();

        var id_seccion = $(this).closest('tr').find('.id_seccion').text();

        $.ajax({
            type: "POST",
            url: "index.php",
            data: {
                'click-edit-btn': true,
                'id_seccion': id_seccion,
            },
            success: function(response) {
                $.each(response, function(Key, value) {
                    $('#idEdit').val(value['id_seccion']);
                    $('#nombreEdit').val(value['nombre'].slice(-1));
                    $('#añoEdit').val(value['año']);
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

        var id_seccion = $(this).closest('tr').find('.id_seccion').text();

        Swal.fire({
            title: '¿Estás seguro?',
            text: '¡Esta acción eliminará la sección permanentemente!',
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
                        "id_seccion": id_seccion,
                    },
                    success: function(response) {
                        Swal.fire(
                            '¡Eliminado!',
                            'La sección ha sido eliminada correctamente.',
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
