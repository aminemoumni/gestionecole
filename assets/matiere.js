const app = require('./app');
global.$ = $;

$(document).ready(function() {
    $( "#addMatiereForm" ).submit(function(e) {

        var data = {};
        $.each($('#addMatiereForm').serializeArray(), function(i, field) {
            data[field.name] = field.value;
        });
        
        $.ajax({
            url: "/admin/matiere/addMatiere",
            type: 'post',
            data:{data:data},
            success: function (result) {
                $('.addMatiere').html(result)
                $( ".btn-close" ).trigger( "click" );       
            },
            error: function(error) {
                console.log(error)
            }
        });

        e.preventDefault();
      });
});