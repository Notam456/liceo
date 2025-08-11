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
    , columnDefs: [{ width: '93px', targets: [4,5,6] }]
})

// Mostrar script
$(document).ready(function() {
    $('#myTable').on('click', '.view-data', function(e) {
        e.preventDefault()

        var id = $(this).closest('tr').find('.id_coordinadores').text()

        $.ajax({
            type: "POST",
            url: "../modulos/coordinadores/index.php",
            data: {
                'click-view-btn': true,
                'id_coordinadores': id,
            },
            success: function(response) {
                $('.view_coordinadores_data').html(response)
                $('#viewmodal').modal('show')
            }
        })
    })
})

// Editar script
$(document).ready(function() {
    $('#myTable').on('click', '.edit-data', function(e) {
        e.preventDefault()

        var id = $(this).closest('tr').find('.id_coordinadores').text()

        $.ajax({
            type: "POST",
            url: "../modulos/coordinadores/index.php",
            data: {
                'click-edit-btn': true,
                'id_coordinadores': id,
            },
            success: function(response) {
                $.each(response, function(Key, value) {
                    $('#edit_id_coordinadores').val(value['id_coordinadores'])
                    $('#edit_nombre_coordinadores').val(value['nombre_coordinadores'])
                    $('#edit_apellido_coordinadores').val(value['apellido_coordinadores'])
                    $('#edit_cedula_coordinadores').val(value['cedula_coordinadores'])
                    $('#edit_contacto_coordinadores').val(value['contacto_coordinadores'])
                    $('#edit_area_coordinacion').val(value['area_coordinacion'])
                    $('#edit_seccion_coordinadores').val(value['seccion_coordinadores'])
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

        var id = $(this).closest('tr').find('.id_coordinadores').text()

        if(confirm("¿Estás seguro de que quieres eliminar a este coordinador?")) {
            $.ajax({
                type: "POST",
                url: "../modulos/coordinadores/index.php",
                data: {
                    "click-delete-btn": true,
                    "id_coordinadores": id,
                },
                success: function(response) {
                    alert("Coordinador eliminado correctamente");
                    location.reload();
                }
            })
        }
    })
})
