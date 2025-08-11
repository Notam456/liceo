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
})

// Mostrar script
$(document).ready(function() {
    $('#myTable').on('click', '.view-data', function(e) {
        e.preventDefault()

        var id = $(this).closest('tr').find('.id_profesores').text()

        $.ajax({
            type: "POST",
            url: "../modulos/profesores/index.php",
            data: {
                'click-view-btn': true,
                'id_profesores': id,
            },
            success: function(response) {
                $('.view_profesores_data').html(response)
                $('#viewmodal').modal('show')
            }
        })
    })
})

// Editar script
$(document).ready(function() {
    $('#myTable').on('click', '.edit-data', function(e) {
        e.preventDefault()

        var id = $(this).closest('tr').find('.id_profesores').text()

        $.ajax({
            type: "POST",
            url: "../modulos/profesores/index.php",
            data: {
                'click-edit-btn': true,
                'id_profesores': id,
            },
            success: function(response) {
                $.each(response, function(Key, value) {
                    $('#edit_id_profesores').val(value['id_profesores'])
                    $('#edit_nombre_profesores').val(value['nombre_profesores'])
                    $('#edit_apellido_profesores').val(value['apellido_profesores'])
                    $('#edit_cedula_profesores').val(value['cedula_profesores'])
                    $('#edit_contacto_profesores').val(value['contacto_profesores'])
                    $('#edit_materia_impartida').val(value['materia_impartida'])
                    $('#edit_seccion_profesores').val(value['seccion_profesores'])
                })

                $('#editmodal').modal('show')
            }
        })
    })
})

//eliminar script
$(document).ready(function() {
    $('#myTable').on('click', '.delete-data', function(e) {
        e.preventDefault()

        var id = $(this).closest('tr').find('.id_profesores').text()

        Swal.fire({
            title: "¿Estas seguro?",
            text: "Cuando elimines este profesores lo borraras permanentemente de la base de datos!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    type: "POST",
                    url: "../modulos/profesores/index.php",
                    data: {
                        "click-delete-btn": true,
                        "id_profesores": id,
                    },
                    success: function(response) {
                        swal("profesores Eliminado Correctamente.!", {
                            icon: "success",
                        }).then((result) => {
                            location.reload()
                        })
                    }
                })
            }
            
        })
    })
})