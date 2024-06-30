toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right"
};
$(document).ready(function() {
    $('#adKioskuser').submit(function(event) {
        event.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: kioskuserCreateRoute,
            type: "POST",
            data: formData,
            success: function(response) {
                if(response.success) {
                    toastr.success(response.message);
                    console.log(response);
                    $(document).trigger('kioskuserAdded');
                    $('input[name="studid"]').val('');
                    $('input[name="password"]').val('');
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

    var dataTable = $('#kioskuser').DataTable({
        "ajax": {
            "url": kioskuserReadRoute,
            "type": "GET",
        },
        destroy: true,
        info: true,
        responsive: true,
        lengthChange: true,
        searching: true,
        paging: true,
        "columns": [
            {data: 'studid'},
            {data: 'lname'},
            {data: 'fname'},
            {data: 'mname'},
            {
                data: 'studkiosid',
                render: function(data, type, row) {
                    if (type === 'display') {
                        var dropdown = '<div class="d-inline-block">' +
                            '<a class="btn btn-primary btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown"></a>' +
                            '<div class="dropdown-menu">' +
                            '<a href="#" class="dropdown-item btn-kioskuseredit" data-id="' + row.studkiosid + '" data-studid="' + row.studid + '">' +
                            '<i class="fas fa-pen"></i> Edit' +
                            '</a>' +
                            '<button type="button" value="' + data + '" class="dropdown-item kioskuser-delete">' +
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
            $(row).attr('id', 'tr-' + data.studkiosid); 
        }
    });
    $(document).on('kioskuserAdded', function() {
        dataTable.ajax.reload();
    });
});

$(document).on('click', '.btn-kioskuseredit', function() {
    var id = $(this).data('id');
    var studID = $(this).data('studid');

    $('#editKioskUserId').val(id);
    $('#editKioskStudID').val(studID);

    $('#editKioskUserModal').modal('show');
});

$('#editKioskUserForm').submit(function(event) {
    event.preventDefault();
    var formData = $(this).serialize();

    $.ajax({
        url: kioskuserUpdateRoute,
        type: "POST",
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if(response.success) {
                toastr.success(response.message);
                $('#editKioskUserModal').modal('hide');
                $(document).trigger('kioskuserAdded');
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

$(document).on('click', '.kioskuser-delete', function(e) {
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
                url: kioskuserDeleteRoute.replace(':id', id),
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

$(document).ready(function() {
    function generatePassword() {
        var digits = String(Math.floor(Math.random() * 10000)).padStart(4, '0'); 
        var suffix = ['K', 'U', 'G']; 
        var char = suffix[Math.floor(Math.random() * suffix.length)]; 
        return digits + char;
    }

    $('#generatePassword').click(function() {
        var generatedPassword = generatePassword();
        $('#passwordInput').val(generatedPassword); 
    });
});

$(document).ready(function() {
    function editgeneratePassword() {
        var digits = String(Math.floor(Math.random() * 10000)).padStart(4, '0'); 
        var suffix = ['K', 'U', 'G']; 
        var char = suffix[Math.floor(Math.random() * suffix.length)]; 
        return digits + char;
    }

    $('#editgeneratePassword').click(function() {
        var editgeneratedPassword = editgeneratePassword();
        $('#editpasswordInput').val(editgeneratedPassword); 
    });
});

