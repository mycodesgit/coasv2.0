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
            {
                data: null,
                render: function(data, type, row) {
                    return row.progAcronym + ' - ' + row.classSection;
                }
            },
            {data: 'schlyear'},
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
                        return data;
                    }
                }
            },
            {
                data: null, // 'null' indicates no specific field from the data source
                render: function() {
                    return '<span class="badge bg-success">Encoded</span>';
                }
            }
        ],
        "createdRow": function (row, data, index) {
            $(row).attr('id', 'tr-' + data.id); 
        }
    });
});

