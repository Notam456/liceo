//eliminar script
$(document).ready(function() {
    $('#myTable').on('click', '.delete-data', function(e) {
        e.preventDefault()
        var id = $(this).closest('tr').find('.id_coordinadores').text()
        
        Swal.fire({
            title: "Estas seguro?",
            text: "Este coordinador se borrará de la base de datos!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, Borrar!"
            }).then((result) => {
                $.ajax({
                    type: "POST",
                    url: "conn_coordinadores.php",
                    data: {
                        "click-delete-btn": true,
                        "id_coordinadores": id,
                    },
                    success: function(response) {
                        location.reload()
                        
                        // swal("Cordinador Eliminado Correctamente.!", {
                        //     icon: "success",
                        // }).then((result) => {
                        //     location.reload()
                        // })
                    }
                })
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "El coordinador fue eliminado de la base de datos!",
                        text: "Hecho.",
                        icon: "success"
                    })
                }
                
        })

        // Swal.fire({
        //     title: "¿Estas seguro?",
        //     text: "Cuando elimines este coordinador lo borraras permanentemente de la base de datos!",
        //     icon: "warning",
        //     buttons: true,
        //     dangerMode: true,
        // }).then((willDelete) => {
        //     if (willDelete) {
        //         $.ajax({
        //             type: "POST",
        //             url: "conn_coordinadores.php",
        //             data: {
        //                 "click-delete-btn": true,
        //                 "id_coordinadores": id,
        //             },
        //             success: function(response) {
        //                 console.log("hola")
                       
        //                 // swal("Cordinador Eliminado Correctamente.!", {
        //                 //     icon: "success",
        //                 // }).then((result) => {
        //                 //     location.reload()
        //                 // })
        //             }
        //         })
        //     }
            
        // })



    })
})




//  $(document).on('click', '.delete-data', function (e) {
//     e.preventDefault() // Evita el comportamiento predeterminado del enlace
//     var id_coordinador = $(this).closest('tr').find('.delete_id_coordinador').val()
//     $('#confirm_id_coordinador').val(id_coordinador)
//     $('#deletemodal').modal('show')
// })