// const { default: Swal } = require('sweetalert2');
// const app = require('./app');
global.$ = $;

$(document).ready(function() {
    
    
    var tableEpreuve = $('#listOfEpreuve').DataTable({
        "lengthMenu": [[5, 25,50, 100,-1], [5 , 25, 50, 100, "All"]],
        "order": [[0, "desc"]],
        "ajax": '/admin/classe/listEpreuve',
        "processing": true,
        "serverSide": true,
        "deferRender": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json"
        },
    });

    $('#selectClasseEpreuve').on('change', function (e) {
        
    
        var index = $(this).attr('data-column');  // getting column index
        var value = $(this).val();
        tableEpreuve.columns(4).search("").draw();
      alert(index);
    // getting search input value
        // alert(index+'-'+value);
        tableEpreuve.columns(index).search(value).draw();    
        e.preventDefault();
    });

    $('body').on('click', '.valideEpreuve', function (e) {
        var id = $(this).attr('data-id');
        
        $.ajax({
            url: "/admin/classe/validEpreuve",
            type: 'post',
            data:{id:id},
            success: function (result) {
                Swal.fire(
                    'Validation depreuve affectué',
                    result,
                    'success'
                )
                tableEpreuve.ajax.reload()
            },
            error: function(error) {
                console.log(error)
            }
        });
    });

    $('body').on('click', '.valideNote', function (e) {
        var id = $(this).attr('data-id');
        $('.overlayOuvrage, .popOuvrage, .loadingScreen').show()
        
        
        $.ajax({
            url: "/admin/classe/validNote",
            type: 'post',
            data:{id:id},
            success: function (result) {
                console.log(result);
                $( ".btn-close" ).trigger( "click" ); 
                $('.noteEpreuve').html(result);
                $(".loadingScreen").hide();
                
            },
            error: function(error) {
                console.log(error)
            }
        });
    });

});