const { default: Swal } = require('sweetalert2');
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
        $('.overlayOuvrage, .popOuvrage, .loadingScreen').show()

        
        $.ajax({
            url: "/admin/classe/admin_get_professeur",
            type: 'post',
            data:{id:id},
            success: function (result) {
                $('.contentProfesseur').html(result)
                $(".loadingScreen").hide()
            },
            error: function(error) {
                console.log(error)
            }
        });
        $('body').on('click', '.addProfesseurClasse', function (e) {
            
            var idClasse = $('#selectEtablissement').val(); 
            var idProfesseur = $(this).attr('data-idProfesseur'); 
            //  alert(idProfesseur);
            // alert(idClasse);
             $.ajax({
                url: "/admin/classe/admin_set_class",
                type: 'post',
                data:{idClasse:idClasse,idProfesseur:idProfesseur},
                success: function (result) {
                    $('.contentProfesseur').html(result)
                },
                error: function(error) {
                    console.log(error)
                }
            });
         
        });  


        $('body').on('click', '.desafecterProfesseur', function (e) {
            
            var idClasse = $(this).attr('data-idClasse');
            var idProfesseur = $(this).attr('data-idprofesseur');
            // alert(idProfesseur);
            // alert(idClasse);
             $.ajax({
                url: "/admin/classe/admin_classProfesseur_annuler",
                type: 'post',
                data:{idClasse:idClasse,idProfesseur:idProfesseur},
                success: function (result) {
                    $('.contentProfesseur').html(result)
                },
                error: function(error) {
                    console.log(error)
                }
            });
         
        }); 
     
    });
    $('body').on('click', '.deleteProfesseur', function (e) {
        var id = $(this).attr('data-id');
       
        
        $.ajax({
            url: "/admin/classe/admin_delete_professeur",
            type: 'post',
            data:{id:id},
            success: function (result) {
                Swal.fire(
                    'Suppression du professeur',
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

    $( "#addProfesseurForm" ).submit(function(e) {

        var data = {};
        $.each($('#addProfesseurForm').serializeArray(), function(i, field) {
            data[field.name] = field.value;
        });
        console.log(data)
        $.ajax({
            url: "/admin/classe/adminAddProfesseur",
            type: 'post',
            data:{data:data},
            success: function (result) {
                $( ".btn-close" ).trigger( "click" );
                if(result=="success")
                    {
                        Swal.fire(
                    
                            'Insertion success',
                            result,
                            'success'
                            )
                        table.ajax.reload()
                    }
                else {
                         Swal.fire(
                             'Insertion fail',
                             result,
                             'success'
                         )
                     }
                    
            },
            error: function(error) {
                console.log(error)
            }
        });

        e.preventDefault();
      });
});