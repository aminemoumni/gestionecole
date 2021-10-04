const { default: Swal } = require('sweetalert2');
const app = require('./app');
global.$ = $;



$(document).ready(function() {
   
   
        $('#updatePassword').on('click',  function (e) {
            
            var oldPass = $('#oldPass').val(); 
            var newPass = $('#newPass').val(); 
            var idUser = $(this).attr('data-idUser'); 
           alert(oldPass);
             $.ajax({
                url: "/updatePassword",
                type: 'post',
                data:{oldPass:oldPass,newPass:newPass,idUser:idUser},
                success: function (result) {
                    $( ".btn-close" ).trigger( "click" );
               
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


   

});