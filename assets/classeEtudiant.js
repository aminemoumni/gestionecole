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
    var index=0;
    $('body').on('click', '.addFacture', function (e) {
        index=0;
        var id = $(this).attr('data-id');
        $('.overlayOuvrage, .popOuvrage, .loadingScreen').show()

        
        $.ajax({
            url: "/admin/classe/addFacture",
            type: 'post',
            data:{id:id},
            success: function (result) {
                $('.contentFacture').html(result)
                $(".loadingScreen").hide()
            },
            error: function(error) {
                console.log(error)
            }
        });
    });
  
    $('body').on('click', '.addEtudiantFrais', function (e) {
        var id = $('#selectFrais').val();
        index=index+1;
        
        $.ajax({
            url: "/admin/classe/addFraisEtudiant",
            type: 'post',
            data:{id:id,index:index},
            success: function (result) {
                $("#tbodyFacture").prepend(result)
                
            },
            error: function(error) {
                console.log(error)
            }
        });
    });

    
    $('body').on('click', '.deleteFraisEtudiant', function (e) {
       $(this).closest("tr").remove();
    });

    // $('body').on('click', '.printFacture', function (e) {
    //     var dataArr = [];
    //     $("fraisEtudiant").each(function(){
    //      dataArr.push($(this).html());
    //     });
    //     $.ajax({
    //         url: "/admin/classe/facture",
    //         type: 'post',
    //         data:{datafacture:dataArr}
    //     });

    //  });

    $( "#form_facture" ).submit(function(e) {
        var data = {};
        $.each($('#form_facture').serializeArray(), function(i, field) {
            data[field.name] = field.value;
        });
        console.log(data)
        $.ajax({
            url: "/admin/classe/facture",
            type: 'post',
            data:{data:data},
            success: function (result) {
                Swal.fire(
                    'imprimer facture',
                    result,
                    'success'
                )
                
            },
            error: function(error) {
                console.log(error)
            }
        });

        
      });





});