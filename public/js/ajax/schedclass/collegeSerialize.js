$(document).ready(function() {
    var dataTable = $('#collegeProg').DataTable({
        "ajax": {
            "url": collegeReadRoute,
            "type": "GET",
        },
        responsive: true,
        lengthChange: true,
        searching: true,
        paging: true,
        "columns": [
            {data: 'college_abbr'},
            {data: 'college_name'},
            {
                data: 'id',
                render: function(data, type, row) {
                    if (type === 'display') {
                        var dropdown = '<div class="d-inline-block">' +
                            '<a class="btn btn-primary btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown"></a>' +
                            '<div class="dropdown-menu">' +
                            '<a href="#" class="dropdown-item btn-editcol" data-id="' + row.id + '" data-colabbr="' + row.college_abbr + '" data-colname="' + row.college_name + '">' +
                            '<i class="fas fa-pen"></i> Edit' +
                            '</a>' +
                            '<button type="button" value="' + data + '" class="dropdown-item coa-delete">' +
                            '<i class="fas fa-trash"></i> Delete' +
                            '</button>' +
                            '</div>' +
                            '</div>';
                        return dropdown;
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
    $(document).on('collegeAdded', function() {
        dataTable.ajax.reload();
    });
});


$(document).on('click', '.btn-editcol', function() {
    var id = $(this).data('id');
    var colAbbr = $(this).data('colabbr');
    var colName = $(this).data('colname');
    
    $('#editCollegeId').val(id);
    $('#editCollegeAbbr').val(colAbbr);
    $('#editCollegeName').val(colName);
    $('#editCollegeModal').modal('show');
});

