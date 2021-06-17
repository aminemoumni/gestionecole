const app = require('./app');
global.$ = $;



$(document).ready(function() {
    
    var table = $('#listOfProfesseur').DataTable({
        "lengthMenu": [[5, 25,50, 100,-1], [5 , 25, 50, 100, "All"]],
        "order": [[0, "desc"]],
        "ajax": '/admin/classe/classeProfesseur',
        "processing": true,
        "serverSide": true,
        "deferRender": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json"
        },
    });

    $('body').on('click', '.seeClass', function (e) {
        var id = $(this).attr('data-id');
        // $('.overlayOuvrage, .popOuvrage').show()
        $.ajax({
            url: "/admin/classe/admin_get_professeur",
            type: 'post',
            data:{id:id},
            success: function (result) {
                console.log(result)
            },
            error: function(error) {
                console.log(error)
            }
        });
        
     
    });

});