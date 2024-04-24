$(document).ready(function() {
    var urlParams = new URLSearchParams(window.location.search);
    var query = urlParams.get('query') || ''; 

    var dataTable = $('#studhisTable').DataTable({
        "ajax": {
            "url": studhistoryReadRoute,
            "type": "GET",
            "data": { 
                "query": query
            }
        },
        info: true,
        responsive: true,
        lengthChange: false,
        searching: false,
        paging: true,
        "columns": [
            {data: 'stud_id'},
            {data: 'lname'},
            {data: 'fname'},
            {data: 'mname'},
            {data: 'ext'},
            {
            data: 'id',
                render: function(data, type, row) {
                    if (type === 'display') {
                        var editLink = '<a href="#" class="btn btn-primary btn-sm btn-studhisview" data-id="' + row.id + '" data-studhislname="' + row.lname + ', ' +row.fname + '">' +
                            '<i class="fas fa-eye"></i>' +
                            '</a>';
                        return editLink;
                    } else {
                        return data;
                    }
                },
            },
        ],
        "createdRow": function (row, data, index) {
            $(row).attr('id', 'tr-' + data.id); 
        }
    });

    if (dataTable) {
        console.log("DataTable initialized successfully.");
    } else {
        console.error("DataTable initialization failed. The table element with ID '#studhisTable' was not found.");
    }
});


$(document).on('click', '.btn-studhisview', function() {
    var id = $(this).data('id');
    var studhislname = $(this).data('studhislname');
    
    $('#viewStudHisId').val(id);
    $('#viewStudHisName').val(studhislname);
    $('#viewStudHisModal').modal('show');
});