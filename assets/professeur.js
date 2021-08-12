// const app = require('./app');
// global.$ = $;



$(document).ready(function() {
    
    var table = $('#professeur_listOfClassEtudiant').DataTable({
        "lengthMenu": [[5, 25,50, 100,-1], [5 , 25, 50, 100, "All"]],
        "order": [[0, "desc"]],
        "ajax": '/admin/professeur/listEtudiant',
        "processing": true,
        "serverSide": true,
        "deferRender": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json"
        },
    });
    var tableCours = $('#professeur_listOfCours').DataTable({
        "lengthMenu": [[5, 25,50, 100,-1], [5 , 25, 50, 100, "All"]],
        "order": [[0, "desc"]],
        "ajax": '/admin/professeur/listCours',
        "processing": true,
        "serverSide": true,
        "deferRender": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json"
        },
    });
    var tableEpreuve = $('#professeur_listOfEpreuve').DataTable({
        "lengthMenu": [[5, 25,50, 100,-1], [5 , 25, 50, 100, "All"]],
        "order": [[0, "desc"]],
        "ajax": '/admin/professeur/listEpreuve',
        "processing": true,
        "serverSide": true,
        "deferRender": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json"
        },
    });


    $( "#addCourseForm" ).submit(function(e) {

        var data = {};
        $.each($('#addCourseForm').serializeArray(), function(i, field) {
            data[field.name] = field.value;
        });
        
        $.ajax({
            url: "/admin/professeur/addCourse",
            type: 'post',
            data:{data:data},
            success: function (result) {
                tableCours.ajax.reload()
                $( ".btn-close" ).trigger( "click" );
                        Swal.fire(
                    
                            'Insertion success',
                            result,
                            'success'
                            )
                    
                
                    
            },
            error: function(error) {
                console.log(error)
            }
        });

        e.preventDefault();
      });

      
    $( "#addEpreuveForm" ).submit(function(e) {

        var data = {};
        $.each($('#addEpreuveForm').serializeArray(), function(i, field) {
            data[field.name] = field.value;
        });
        
        $.ajax({
            url: "/admin/professeur/addEpreuve",
            type: 'post',
            data:{data:data},
            success: function (result) {
                $( ".btn-close" ).trigger( "click" );
               
                        Swal.fire(
                            'Insertion success',
                            result,
                            'success'
                            )
                        tableEpreuve.ajax.reload()
                    
                
                    
            },
            error: function(error) {
                console.log(error)
            }
        });

        e.preventDefault();
      });

      var idEtudiant=0;
    $('body').on('click', '.addNote', function (e) {
        idEtudiant = $(this).attr('data-id');
        //alert(idEtudiant);
        
      });
    $('body').on('click', '.seeNote', function (e) {
        idEtudiant = $(this).attr('data-id');
        //alert(idEtudiant);
        $('.overlayOuvrage, .popOuvrage, .loadingScreen').show()
        $.ajax({
            url: "/admin/professeur/seeNote",
            type: 'post',
            data:{id:idEtudiant},
            success: function (result) {
                //$('.seeNote').html(result)
                $( ".btn-close" ).trigger( "click" ); 
                //$('#seeNoteForm').show();
                $('.noteEtudiant').html(result);
                $(".loadingScreen").hide();
                
            },
            error: function(error) {
                console.log(error)
            }
        });
        
    });

    $( "#addNoteForm" ).submit(function(e) {
          
        var data = {};
        $.each($('#addNoteForm').serializeArray(), function(i, field) {
            data[field.name] = field.value;
        });
        $.ajax({
            url: "/admin/professeur/addNote",
            type: 'post',
            data:{data:data,id:idEtudiant},
            success: function (result) {
                $( ".btn-close" ).trigger( "click" );
               
                        Swal.fire(
                            'Insertion success',
                            result,
                            'success'
                            )
                        
                    
            },
            error: function(error) {
                console.log(error)
            }
        });

        e.preventDefault();
      });
});