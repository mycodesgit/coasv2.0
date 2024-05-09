toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right"
};
$(document).ready(function() {
    $('#adSetConf').submit(function(event) {
        event.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: setconfCreateRoute,
            type: "POST",
            data: formData,
            success: function(response) {
                if(response.success) {
                    toastr.success(response.message);
                    console.log(response);
                    $('#modal-setconf').modal('hide');
                    $(document).trigger('setconfAdded');
                    // $('input[name="fund_name"]').val('');
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

    var dataTable = $('#setconftable').DataTable({
        "ajax": {
            "url": setconfRoute,
            "type": "GET",
        },
        "order": [[1, 'desc']],
        destroy: true,
        info: true,
        responsive: true,
        lengthChange: true,
        searching: true,
        paging: true,
        "columns": [
            {data: 'id'},
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
                render: function (data, type, row) {
                    var statusBadge = '';
                    if (data.set_status == 1) {
                        statusBadge = '<span class="badge badge-danger">Inactive</span>';
                    } else if (data.set_status == 2) {
                        statusBadge = '<span class="badge badge-success">Active</span>';
                    } else {
                        statusBadge = data.set_status;
                    }
                    return statusBadge;
                }
            },
            {
                data: 'id',
                render: function(data, type, row) {
                    if (type === 'display') {
                        var dropdown = '<div class="d-inline-block">' +
                            '<a class="btn btn-primary btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown"></a>' +
                            '<div class="dropdown-menu">' +
                            '<a href="#" class="dropdown-item btn-setconfedit" data-id="' + row.id + '" data-schlyear="' + row.schlyear + '" data-semester="' + row.semester + '" data-setstatus="' + row.set_status + '">' +
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
    $(document).on('setconfAdded', function() {
        dataTable.ajax.reload();
    });
});

$(document).on('click', '.btn-setconfedit', function() {
    var id = $(this).data('id');
    var schlyear = $(this).data('schlyear');
    var semester = $(this).data('semester');
    var setstatus = $(this).data('setstatus');

    $('#editSetConfId').val(id);
    $('#editSetConfschlyear').val(schlyear);
    $('#editSetConfsemester').val(semester);
    $('#editSetConfstatus').val(setstatus);
    $('#editSetConfModal').modal('show');
});

$('#editSetConfForm').submit(function(event) {
    event.preventDefault();
    var formData = $(this).serialize();

    $.ajax({
        url: setconfUpdateRoute,
        type: "POST",
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if(response.success) {
                toastr.success(response.message);
                $('#editSetConfModal').modal('hide');
                $(document).trigger('setconfAdded');
            } else {
                toastr.error(response.message);
            }
        },
        error: function(xhr, status, error, message) {
            var errorMessage = xhr.responseText ? JSON.parse(xhr.responseText).message : 'An error occurred';
            toastr.error(errorMessage);
        }
    });
});

// $(document).on('click', '.fund-delete', function(e) {
//     var id = $(this).val();
//     $.ajaxSetup({
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         },
//     });
//     Swal.fire({
//         title: 'Are you sure?',
//         text: "You won't be able to recover this!",
//         icon: 'warning',
//         showCancelButton: true,
//         confirmButtonColor: '#3085d6',
//         cancelButtonColor: '#d33',
//         confirmButtonText: 'Yes, delete it!'
//     }).then((result) => {
//         if (result.isConfirmed) {
//             $.ajax({
//                 type: "GET",
//                 url: fundDeleteRoute.replace(':id', id),
//                 success: function(response) {
//                     $("#tr-" + id).delay(1000).fadeOut();
//                     Swal.fire({
//                         title: 'Deleted!',
//                         text: 'Successfully Deleted!',
//                         icon: 'warning',
//                         showConfirmButton: false,
//                         timer: 1500
//                     });
//                     if(response.success) {
//                         toastr.success(response.message);
//                         console.log(response);
//                     }
//                 }
//             });
//         }
//     })
// });

