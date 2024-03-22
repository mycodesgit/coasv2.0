$(document).ready(function() {
    var dataTable = $('#listsub').DataTable({
        "ajax": {
            "url": subjectReadRoute,
            "type": "GET",
        },
        responsive: true,
        lengthChange: true,
        searching: true,
        paging: true,
        "columns": [
            {data: 'sub_code'},
            {data: 'sub_name'},
            {data: 'sub_desc'},
            {data: 'sub_unit'},
        ],
        "createdRow": function (row, data, index) {
            $(row).attr('id', 'tr-' + data.id); 
        }
    });
    $(document).on('coaAdded', function() {
        dataTable.ajax.reload();
    });
});

