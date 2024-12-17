$(document).ready(function() {
    var dataTable = $('#setconfUpEncodedtable').DataTable({
        "ajax": {
            "url": setconfencodedRoute,
            "type": "GET",
        },
        "order": [[1, 'desc']],
        destroy: false,
        info: false,
        responsive: true,
        lengthChange: false,
        searching: false,
        paging: true,
        "columns": [
            {data: 'schlyear'},
            {
                data: 'semester',
                render: function(data, type, row) {
                    if (data == 1) {
                        return '1st Semester';
                    } else if (data == 2) {
                        return '2nd Semester';
                    } else if (data == 3) {
                        return 'Summer';
                    } else {
                        return data;
                    }
                }
            },
            {
                data: null,
                render: function(data, type, row) {
                    return row.progAcronym + ' - ' + row.yrlevel;
                }
            },
            {data: 'schlyear'},
        ],
        "createdRow": function (row, data, index) {
            $(row).attr('id', 'tr-' + data.id); 
        }
    });
});

