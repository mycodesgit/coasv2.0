toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right"
};
$(document).ready(function() {

    var urlParams = new URLSearchParams(window.location.search);
    var campus = urlParams.get('campus') || ''; 
    var schlyear = urlParams.get('schlyear') || ''; 
    var semester = urlParams.get('semester') || '';
    var progCod = urlParams.get('progCod') || '';

    var dataTable = $('#elpltable').DataTable({
        "ajax": {
            "url": elplReadRoute,
            "type": "GET",
            "data": { 
                "schlyear": schlyear,
                "semester": semester,
                "campus": campus,
                "progCod": progCod
            }
        },
        info: true,
        responsive: true,
        lengthChange: true,
        searching: true,
        paging: true,
        buttons: [
                'excel', 'pdf', 'print'
            ],
        "columns": [
            { "data": "studentID" },
            { "data": "lname" },
            { "data": "fname" },
            { "data": "mname" },
            { "data": "ext" },
            { "data": "gender" },
            { "data": "address" },
            { "data": "studYear" },
            { "data": "sub_name" },
            { "data": "subjFgrade" },
            { "data": "subUnit" }
        ],
        order: [[1, 'asc'], [6, 'asc']],
        "createdRow": function (row, data, index) {
            $(row).attr('id', 'tr-' + data.id); 
        },
        dom: 'Bfrtip'
    }).buttons().container().appendTo('#elpltable_wrapper .col-md-6:eq(0)');
    $(document).on('elpltable', function() {
        dataTable.ajax.reload();
    });
});