const app = require('./app');
global.$ = $;

$(document).ready(function() {
    // $('#listOfInscription').DataTable({
    //     processing: true,
    //     serverSide: true,
    //     ajax: {
    //         url: '{{path("listOfInscirption")}}',
    //         dataSrc: ''
    //     },
    //     columns: [
    //         { data: 'nom' },
    //         { data: 'prenom' },
    //         { data: 'region' },
    //         { data: 'age' },
    //         { data: 'classe' },
    //         { data: 'action' }
    //     ],
    //     language: {
    //         "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json"
    //     }
    // });
    var table = $('#listOfInscription').DataTable({
        "lengthMenu": [[5, 25,50, 100, -1], [5 , 25, 50, 100, "All"]],
        "order": [[0, "desc"]],
        "ajax": '/admin/etudiant/list',
        "processing": true,
        "serverSide": true,
        "deferRender": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json"
        },
    });


    $('#selectClasseInscription').on('change', function (e) {
        // alert('xxx');
    
        var index = $(this).attr('data-column');  // getting column index
        var value = $(this).val();
        table.columns(1).search("").draw();
     
    // getting search input value
        // alert(index+'-'+value);
        table.columns(index).search(value).draw();    
        e.preventDefault();
    });

    var tableNote = $('#child_listOfNoteEpreuve').DataTable({
        "lengthMenu": [[5, 25,50, 100,-1], [5 , 25, 50, 100, "All"]],
        "order": [[0, "desc"]],
        "ajax": '/etudiant/listNote',
        "processing": true,
        "serverSide": true,
        "deferRender": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json"
        },
    });
} );