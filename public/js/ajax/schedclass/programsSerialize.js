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
            {data: 'campus'},
            {
                data: 'id',
                render: function(data, type, row) {
                    if (type === 'display') {
                        var dropdown = '<div class="d-inline-block">' +
                            '<a class="btn btn-primary btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown"></a>' +
                            '<div class="dropdown-menu">' +
                            '<a href="#" class="dropdown-item btn-programedit" data-id="' + row.id + '" data-college="' + row.progCollege + '">' +
                            '<i class="fas fa-pen"></i> Edit' +
                            '</a>' +
                            '<button type="button" value="' + data + '" class="dropdown-item fund-delete">' +
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
    $(document).on('coaAdded', function() {
        dataTable.ajax.reload();
    });
});

$(document).on('click', '.btn-programedit', function() {
    var id = $(this).data('id');
    var college = $(this).data('college');
    var acountStud = $(this).data('fundstudname');
    var amountStud = $(this).data('fundstudamount');

    $('#editProgramId').val(id);
    $('#college').val(college);
    $('#editstudfeeaccountName').val(acountStud);
    $('#editstudfeeamountFee').val(amountStud);

    $('#editProgramModal').modal('show');
});
