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

});