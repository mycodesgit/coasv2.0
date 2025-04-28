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
            { data: 'id' },
            { 
                data: null,
                render: function(data, type, row) {
                    var firstname = data.fname;
                    var middleInitial = data.mname ? data.mname.substr(0, 1) + '.' : '';
                    var lastNameWithExt = data.lname + (data.ext && data.ext !== 'null' ? ' ' + data.ext : '');
                    return firstname + ' ' + middleInitial + ' ' + lastNameWithExt;
                }
            },
            { data: 'email' },
            {
                data: null,
                render: function (data, type, row) {
                    let roleBadge = '';

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
                        roleBadge = '<span class="badge badge-warning">Scholarship Head</span>';
                    } else if (data.role == 9) {
                        roleBadge = '<span class="badge badge-warning">Scholarship Staff</span>';
                    } else if (data.role == 10) {
                        roleBadge = '<span class="badge badge-warning">Assessment Head</span>';
                    } else if (data.role == 11) {
                        roleBadge = '<span class="badge badge-warning">Assessment Staff</span>';
                    } else if (data.role == 12) {
                        roleBadge = '<span class="badge badge-secondary">MIS Staff</span>';
                    } else if (data.role == 13) {
                        roleBadge = '<span class="badge badge-secondary">MIS Director</span>';
                    } else if (data.role == 14) {
                        roleBadge = '<span class="badge badge-secondary">MIS Officer</span>';
                    } else if (data.role == 15) {
                        roleBadge = '<span class="badge badge-warning">Grad School Staff</span>';
                    } else if (data.role == 16) {
                        roleBadge = '<span class="badge" style="background-color: #e83e8c; color: #fff">OSSA Staff</span>';
                    } else if (data.role == 17) {
                        roleBadge = '<span class="badge badge-info">Cashier</span>';
                    } else if (data.role == 18) {
                        roleBadge = '<span class="badge badge-info">Cashier Staff</span>';
                    } else {
                        roleBadge = '<span class="badge badge-light">Unknown Role</span>';
                    }
                    return roleBadge;
                }
            },
            { data: 'campus' },
            {
                data: null,
                render: function (data, type, row) {
                    let statususer = '';

                    if (data.statuser == 1) {
                        statususer = '<span class="badge badge-success">Enabled</span>';
                    } else {
                        statususer = '<span class="badge badge-danger">Disabled</span>';
                    } 
                    return statususer;
                }
            },
            {
                data: 'id',
                render: function(data, type, row) {
                    if (type === 'display') {
                        var dropdown = '<div class="d-inline-block">' +
                            '<a class="btn btn-primary btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown"></a>' +
                            '<div class="dropdown-menu">' +
                            '<a href="#" class="dropdown-item btn-useredit" data-id="' + row.id + '" data-fname="' + row.fname + '" data-mname="' + row.mname + '" data-lname="' + row.lname + '" data-ext="' + row.ext + '" data-email="' + row.email + '" data-campus="' + row.campus + '" data-dept="' + row.dept + '" data-role="' + row.role + '">' +
                            '<i class="fas fa-pen"></i> Edit Info' +
                            '</a>' +
                            '<a href="#" class="dropdown-item btn-userpass" data-id="' + row.id + '" data-password="' + row.password + '">' +
                            '<i class="fas fa-lock"></i> Edit Pass' +
                            '</a>' +
                            '<a href="#" class="dropdown-item btn-useraccess" ' +
                                'data-id="' + row.id + '" ' +
                                'data-fullname="' + row.fname + ' ' + row.mname + ' ' + row.lname + (row.ext && row.ext !== 'null' ? ' ' + row.ext : '') + '" ' +
                                'data-role="' + row.role + '">' +
                                '<i class="fas fa-keyboard" style="color: green"></i> User Access' +
                            '</a>'+
                            '<a href="#" class="dropdown-item btn-userdeact" ' +
                                'data-id="' + row.id + '" ' +
                                'data-fullname="' + row.fname + ' ' + row.mname + ' ' + row.lname + (row.ext && row.ext !== 'null' ? ' ' + row.ext : '') + '" ' +
                                'data-statuser="' + row.statuser + '">' +
                                '<i class="fas fa-toggle-off" style="color: red"></i> Disabled Account' +
                            '</a>' +
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

    $(document).on('userAdded', function() {
        dataTable.ajax.reload();
    });
});

$(document).on('click', '.btn-useredit', function() {
    var id = $(this).data('id');
    var fname = $(this).data('fname');
    var mname = $(this).data('mname');
    var lname = $(this).data('lname');
    var ext = $(this).data('ext');
    var email = $(this).data('email');
    var campus = $(this).data('campus');
    var dept = $(this).data('dept');
    var role = $(this).data('role');

    $('#edituserId').val(id);
    $('#edituserfname').val(fname);
    $('#editusermname').val(mname);
    $('#edituserlname').val(lname);
    $('#edituserext').val(ext);
    $('#edituseremail').val(email);
    $('#editusercampus').val(campus);
    $('#edituserdept').val(dept);
    $('#edituserrole').val(role);

    $('#edituserModal').modal('show');
});

$('#edituserForm').submit(function(event) {
    event.preventDefault();
    var formData = $(this).serialize();

    $.ajax({
        url: useraccountUpdateRoute,
        type: "POST",
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if(response.success) {
                toastr.success(response.message);
                $('#edituserModal').modal('hide');
                $(document).trigger('userAdded');
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

$(document).on('click', '.btn-userpass', function() {
    var id = $(this).data('id');

    $('#edituserPassId').val(id);
    $('#edituserpass').val('');

    $('#edituserPassModal').modal('show');
});

$('#edituserPassForm').submit(function(event) {
    event.preventDefault();
    var formData = $(this).serialize();

    $.ajax({
        url: userpassUpdateRoute,
        type: "POST",
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if(response.success) {
                toastr.success(response.message);
                $('#edituserPassModal').modal('hide');
                $(document).trigger('userAdded');
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

$(document).on('click', '.btn-useraccess', function() {
    var id = $(this).data('id');
    var fullname = $(this).data('fullname');
    var roleaccess = $(this).data('role');

    $('#edituserAccessId').val(id);
    $('#editusername').val(fullname);
    $('#edituserroleaccess').val(roleaccess);

    $('input[name="buttons[]"]').prop('checked', false);

    $.ajax({
        url: useraccessRoute.replace(':id', id), // Replace :id with actual ID
        type: 'GET',
        success: function(response) {
            if (response.buttons) {
                response.buttons.forEach(function(button) {
                    $('input[name="buttons[]"][value="' + button + '"]').prop('checked', true);
                });
            }
            $('#edituserAccessModal').modal('show');
        },
        error: function(xhr) {
            console.error(xhr.responseText); 
        }
    });
});

$('#edituserAccessForm').submit(function(event) {
    event.preventDefault(); 
    
    var formData = $(this).serialize(); 

    var id = $('#edituserAccessId').val(); 
    var selectedButtons = [];

    $('input[name="buttons[]"]:checked').each(function() {
        selectedButtons.push($(this).val()); 
    });

    $.ajax({
        url: userSaveAccessRoute.replace(':id', id), 
        type: "POST",
        data: {
            buttons: selectedButtons,
            _token: $('meta[name="csrf-token"]').attr('content') 
        },
        success: function(response) {
            if(response.success) {
                toastr.success(response.message);
                $('#edituserAccessModal').modal('hide'); 
                $(document).trigger('userAdded'); 
            } else {
                toastr.error(response.message);
            }
        },
        error: function(xhr) {
            console.error(xhr.responseText); 
            toastr.error('An error occurred while saving the user access.');
        }
    });
});

$(document).on('click', '.btn-userdeact', function() {
    var id = $(this).data('id');
    var fullname = $(this).data('fullname');
    var statuser = $(this).data('statuser');

    $('#edituserDeactId').val(id);
    $('#edituserDeactfullname').val(fullname);
    $('#edituserDeactStat').val(statuser);

    $('#edituserDeactModal').modal('show');
});

$('#edituserDeactForm').submit(function(event) {
    event.preventDefault();
    var formData = $(this).serialize();

    $.ajax({
        url: userDeactRoute,
        type: "POST",
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if(response.success) {
                toastr.success(response.message);
                $('#edituserDeactModal').modal('hide');
                $(document).trigger('userAdded');
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

