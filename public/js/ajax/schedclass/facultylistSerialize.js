toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right"
};
$(document).ready(function() {
    $('#adFac').submit(function(event) {
        event.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: facultyCreateRoute,
            type: "POST",
            data: formData,
            success: function(response) {
                if(response.success) {
                    toastr.success(response.message);
                    console.log(response);
                    $(document).trigger('facAdded');
                    $('input[name="lname"]').val('');
                    $('input[name="fname"]').val('');
                    $('input[name="mname"]').val('');
                    $('input[name="ext"]').val('');
                    $('select[name="dept"]').val('');
                    $('select[name="adrID"]').val('');
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

    var dataTable = $('#facltyTable').DataTable({
        "ajax": {
            "url": facultyReadRoute,
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
                data: null, // This column doesn't map to a single field in your data source
                render: function(data, type, row) {
                    return `${row.lname}, ${row.fname} ${row.mname}`;
                }
            },
            {data: 'adrDesc'},
            {data: 'college_abbr'},
            {data: 'campus'},
            {
                data: 'id',
                render: function(data, type, row) {
                    if (type === 'display') {
                        var dropdown = '<div class="d-inline-block">' +
                            '<a class="btn btn-primary btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown"></a>' +
                            '<div class="dropdown-menu">' +
                            '<a href="#" class="dropdown-item btn-facultyedit" data-id="' + row.id + '" data-flname="' + row.lname + '" data-ffname="' + row.fname + '" data-fmname="' + row.mname + '" data-fxname="' + row.ext + '" data-adrname="' + row.adrID + '">' +
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
    $(document).on('facAdded', function() {
        dataTable.ajax.reload();
    });
});

$(document).on('click', '.btn-facultyedit', function() {
    var id = $(this).data('id');
    var lName = $(this).data('flname');
    var fName = $(this).data('ffname');
    var mName = $(this).data('fmname');
    var exName = $(this).data('fxname');
    var salName = $(this).data('adrname');

    $('#editFacultyId').val(id);
    $('#editLastname').val(lName);
    $('#editFirstname').val(fName);
    $('#editMiddlename').val(mName);
    $('#editExtname').val(exName);
    $('#editSalutation').val(salName);

    $('#editFacultyModal').modal('show');
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

