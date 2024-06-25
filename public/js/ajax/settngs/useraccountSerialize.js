toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right"
};
$(document).ready(function() {
    $('#adFund').submit(function(event) {
        event.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: fundCreateRoute,
            type: "POST",
            data: formData,
            success: function(response) {
                if(response.success) {
                    toastr.success(response.message);
                    console.log(response);
                    $(document).trigger('fundAdded');
                    $('input[name="fund_name"]').val('');
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

    var buttons = {
        'admission-url': 'Admission',
        'enrollment-url': 'Enrollment',
        'scheduler-url': 'Scheduling',
        'assessment-url': 'Assessment',
        'cashiering-url': 'Cashiering',
        'scholarship-url': 'Scholarship',
        'grading-url': 'Grading',
        'request-url': 'Request',
        'setting-url': 'Settings'
    };

    var dataTable = $('#userlist').DataTable({
        "ajax": {
            "url": useraccountRoute,
            "type": "GET",
        },
        destroy: true,
        info: true,
        responsive: true,
        lengthChange: true,
        searching: true,
        paging: true,
        "columns": [
            {
                data: null,
                render: function (data, type, row) {
                    var isAdminBadge = '';
                    if (data.role == 0) {
                        roleBadge = '<span class="badge badge-secondary">Administrator</span>';
                    } else if (data.role == 1) {
                        roleBadge = '<span class="badge badge-primary">Guidance Officer</span>';
                    } else if (data.role == 2) {
                        roleBadge = '<span class="badge badge-success">Guidance Staff</span>';
                    } else if (data.role == 3) {
                        roleBadge = '<span class="badge badge-danger">Registrar</span>';
                    } else if (data.role == 4) {
                        roleBadge = '<span class="badge badge-warning">Registrar Staff</span>';
                    } else if (data.role == 5) {
                        roleBadge = '<span class="badge badge-info">College Dean</span>';
                    } else if (data.role == 6) {
                        roleBadge = '<span class="badge badge-info">Program Head</span>';
                    } else if (data.role == 7) {
                        roleBadge = '<span class="badge badge-info">College Staff</span>';
                    } else if (data.role == 8) {
                        roleBadge = '<span class="badge badge-success">Scholarship Head</span>';
                    } else if (data.role == 9) {
                        roleBadge = '<span class="badge badge-success">Scholarship Staff</span>';
                    } else {
                        roleBadge = data.role;
                    }
                    return roleBadge;
                }
            },
            { 
                data: null,
                render: function(data, type, row) {
                    var firstname = data.fname;
                    var middleInitial = data.mname ? data.mname.substr(0, 1) + '.' : '';
                    var lastNameWithExt = data.lname + (data.ext !== 'N/A' ? ' ' + data.ext : '');
                    return firstname + ' ' + middleInitial + ' ' + lastNameWithExt;
                }
            },
            { data: 'email' },
            { data: 'campus' },
            {
                data: 'id',
                render: function(data, type, row) {
                    if (type === 'display') {
                        var dropdown = '<div class="d-inline-block">' +
                            '<a class="btn btn-primary btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown"></a>' +
                            '<div class="dropdown-menu">' +
                            '<a href="users/edit/view/' + row.adid + '" class="dropdown-item btn-useraccntedit">' +
                            '<i class="fas fa-pen"></i> Edit' +
                            '</a>' +
                            '<button type="button" class="dropdown-item btn-edit" data-toggle="modal" data-target="#buttonFilterModal' + data + '" data-event-id="' + data + '"><i class="fas fa-exclamation-circle"></i> Filter Buttons</button>' +
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
    $(document).on('click', '.btn-edit', function () {
         var userId = $(this).data('event-id');

        // Fetch user buttons data from the server
        getUserButtons(userId).then(function(userButtons) {
            // Generate modal dynamically
            var modalContent = '<div class="modal fade" id="buttonFilterModal' + userId + '" tabindex="-1" aria-labelledby="buttonFilterModalLabel" aria-hidden="true">' +
                                    '<div class="modal-dialog">' +
                                        '<div class="modal-content">' +
                                            '<form action="" method="POST">' +
                                                '<input type="hidden" name="id" value="' + userId + '">' +
                                                '<div class="modal-header">' +
                                                    '<h5 class="modal-title" id="buttonFilterModalLabel">Filter User Buttons</h5>' +
                                                    '<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
                                                        '<span aria-hidden="true">&times;</span>' +
                                                    '</button>' +
                                                '</div>' +
                                                '<div class="modal-body">';
            
            // Iterate over buttons and add checkboxes
            $.each(buttons, function(key, label) {
                modalContent += '<div class="icheck-success">' +
                                    '<input type="checkbox" id="' + key + '-' + userId + '" name="buttons[]" value="' + key + '" ' +
                                    (userButtons.includes(key) ? 'checked' : '') + '>' +
                                    '<label for="' + key + '-' + userId + '">' + label + '</label>' +
                                '</div>';
            });
            
            modalContent +=             '</div>' +
                                                '<div class="modal-footer">' +
                                                    '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>' +
                                                    '<button type="submit" class="btn btn-primary">Save changes</button>' +
                                                '</div>' +
                                            '</form>' +
                                        '</div>' +
                                    '</div>' +
                                '</div>';
            
            // Append modal to body and show it
            $('body').append(modalContent);
            $('#buttonFilterModal' + userId).modal('show');
        }).catch(function(error) {
            console.error('Error fetching user buttons:', error);
        });
    });

    // Function to fetch user buttons data from the server
    function getUserButtons(userId) {
        return $.ajax({
            url: '/user/' + userId + '/buttons',
            type: 'GET',
            dataType: 'json'
        }).then(function(response) {
            return response.buttons;
        }).catch(function(error) {
            console.error('Error fetching user buttons:', error);
            return [];
        });
    }
    $(document).on('userAdded', function() {
        dataTable.ajax.reload();
    });
});

$(document).on('click', '.btn-fundedit', function() {
    var id = $(this).data('id');
    var fundName = $(this).data('fundname');
    $('#editFundId').val(id);
    $('#editFundName').val(fundName);
    $('#editFundModal').modal('show');
});

$('#editFundForm').submit(function(event) {
    event.preventDefault();
    var formData = $(this).serialize();

    $.ajax({
        url: fundUpdateRoute,
        type: "POST",
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if(response.success) {
                toastr.success(response.message);
                $('#editFundModal').modal('hide');
                $(document).trigger('fundAdded');
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

$(document).on('click', '.fund-delete', function(e) {
    var id = $(this).val();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
    });
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to recover this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: "GET",
                url: fundDeleteRoute.replace(':id', id),
                success: function(response) {
                    $("#tr-" + id).delay(1000).fadeOut();
                    Swal.fire({
                        title: 'Deleted!',
                        text: 'Successfully Deleted!',
                        icon: 'warning',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    if(response.success) {
                        toastr.success(response.message);
                        console.log(response);
                    }
                }
            });
        }
    })
});

