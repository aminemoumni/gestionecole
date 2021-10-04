$(document).ready(function() {
    
    $( "#addFraisForm" ).submit(function(e) {
        var data = {};
        $.each($('#addFraisForm').serializeArray(), function(i, field) {
            data[field.name] = field.value;
        });
        
        $.ajax({
            url: "/admin/classe/addFrais",
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

      $('#updateFrais').on('change',  function (e) {
        //alert("changed");
        var idFrais = $(this).attr('data-id');
        var prix = $('#updateFrais').val();
        alert(idFrais,prix);
        $.ajax({
            url: "/admin/classe/updateFrais",
            type: 'post',
            data:{id:idFrais,prix:prix},
            success: function (result) {
           
                Swal.fire(
                    'Modification success',
                    result,
                    'success'
                    )
                
            },
            error: function(error) {
                console.log(error)
            }
        }); 
    });

    //   

});