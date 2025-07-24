toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right"
};
$(document).ready(function() {
    var urlParams = new URLSearchParams(window.location.search);
    var schlyear = urlParams.get('schlyear') || ''; 
    var semester = urlParams.get('semester') || '';

    var dataTable = $('#subofferedlist').DataTable({
        "ajax": {
            "url": subOfferedReadRoute,
            "type": "GET",
            "data": { 
                "schlyear": schlyear,
                "semester": semester,
            }
        },
        responsive: true,
        lengthChange: true,
        searching: true,
        paging: true,
        buttons: [
                'excel', 'pdf'
            ],
        "columns": [
            {data: 'subCode'},
            {data: 'subSec'},
            {
                data: 'semester',
                render: function(data, type, row) {
                    if (data == 1) {
                        return '1st';
                    } else if (data == 2) {
                        return '2nd';
                    } else if (data == 3) {
                        return 'Summer';
                    } else {
                        return 'Unknown Semester';
                    }
                }
            },
            {data: 'sub_name'},
            {data: 'sub_title'},
            {data: 'lecUnit'},
            {data: 'labUnit'},
            {data: 'subUnit'},
            {data: 'isType'},

        ],
        "createdRow": function (row, data, index) {
            $(row).attr('id', 'tr-' + data.soid); 
        },
        dom: 'Bfrtip'
    }).buttons().container().appendTo('#subofferedlist .col-md-6:eq(0)');
    $(document).on('subjOffAdded', function() {
        dataTable.ajax.reload();
    });
});


