toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right"
};
$(document).ready(function() {
    $('#addGuidanceForm').submit(function(event) {
        event.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: sigpnatoriesCreateRoute,
            type: "POST",
            data: formData,
            success: function(response) {
                if(response.success) {
                    toastr.success(response.message);
                    console.log(response);
                    $(document).trigger('presvicesigAdded');
                    $('#modal-guidanceSignatory').modal('hide');
                    $('input[name="fulname"]').val('');
                    $('input[name="titledeg"]').val('');
                } else {
                    toastr.error(response.message);
                    console.log(response);
                }
            },
            error: function(xhr, status, error, message) {
                var errorMessage = xhr.responseText ? JSON.parse(xhr.responseText).message : 'An error occurred';
                toastr.error(errorMessage);
            }
        });
    });

    var urlParams = new URLSearchParams(window.location.search);
    var schlyear = urlParams.get('schlyear') || ''; 
    var semester = urlParams.get('semester') || '';

    var dataTable = $('#signatoryguidanceTable').DataTable({
        "ajax": {
            "url": sigpnatoriesReadRoute,
            "type": "GET",
            "data": function(d) { 
                d.schlyear = schlyear;
                d.semester = semester;
            }
        },
        destroy: true,
        info: true,
        responsive: true,
        lengthChange: true,
        searching: true,
        paging: true,
        "columns": [
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
                        return 'Unknown Semester';
                    }
                }
            },
            {data: 'fulname'},
            {data: 'position'},
            {
                data: null,
                render: function (data, type, row) {
                    let status = '';

                    if (data.status == 1) {
                        status = '<span class="badge bg-success">Active</span>';
                    } else {
                        status = '<span class="badge bg-danger">Unactive</span>';
                    } 
                    return status;
                }
            },
            {
                data: 'id',
                render: function(data, type, row) {
                    if (type === 'display') {
                        var dropdown = '<div class="d-inline-block">' +
                            '<a class="btn btn-success btn-sm dropdown-toggle dropdown-icon text-light" data-bs-toggle="dropdown"></a>' +
                            '<div class="dropdown-menu">' +
                            '<a href="#" class="dropdown-item btn-fundedit" data-id="' + row.id + '" data-fundname="' + row.fund_name + '">' +
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
    $(document).on('presvicesigAdded', function() {
        dataTable.ajax.reload();
    });
});