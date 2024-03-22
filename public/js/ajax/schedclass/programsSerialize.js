$(document).ready(function() {
    var dataTable = $('#classProg').DataTable({
        "ajax": {
            "url": progReadRoute,
            "type": "GET",
        },
        responsive: true,
        lengthChange: true,
        searching: true,
        paging: true,
        "columns": [
            {data: 'progCod'},
            {data: 'progAcronym'},
            {data: 'progName'},
        ],
        "createdRow": function (row, data, index) {
            $(row).attr('id', 'tr-' + data.id); 
        }
    });
    $(document).on('coaAdded', function() {
        dataTable.ajax.reload();
    });
});

