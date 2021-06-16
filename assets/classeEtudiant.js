const app = require('./app');
global.$ = $;



$(document).ready(function() {
    
    var table = $('#listOfEtudiant').DataTable({
        "lengthMenu": [[5, 25,50, 100,-1], [5 , 25, 50, 100, "All"]],
        "order": [[0, "desc"]],
        "ajax": '/admin/classe/classeEtudiant',
        "processing": true,
        "serverSide": true,
        "deferRender": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json"
        },
    });

    $('body').on('click', '.valideEtudiant', function (e) {
         
        var id = $(this).attr('data-id'); 
        //  alert(index);
         $.ajax({
            url: "/admin/classe/admin_classe_valider",
            type: 'post',
            data:{id:id},
            success: function (result) {
                Swal.fire(
                    'Affectation etudiant',
                    result,
                    'success'
                )
                table.ajax.reload()
            },
            error: function(error) {
                console.log(error)
            }
        });
     
    });
    $('body').on('click', '.annuleEtudiant', function (e) {
         
        var id = $(this).attr('data-id'); 
        //  alert(index);
         $.ajax({
            url: "/admin/classe/admin_classe_annuler",
            type: 'post',
            data:{id:id},
            success: function (result) {
                Swal.fire(
                    'Desaffectation etudiant',
                    result,
                    'success'
                )
                table.ajax.reload()
            },
            error: function(error) {
                console.log(error)
            }
        });

     
    });
});