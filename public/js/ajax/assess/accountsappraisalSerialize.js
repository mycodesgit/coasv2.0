toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right"
};
$(document).ready(function() {
    $('#adAccntApp').submit(function(event) {
        event.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: accntApprslCreateRoute,
            type: "POST",
            data: formData,
            success: function(response) {
                if(response.success) {
                    toastr.success(response.message);
                    console.log(response);
                    $(document).trigger('accntsAppAdded');
                    $('input[name="fund_id"]').val('');
                    $('input[name="account_name"]').val('');
                    $('input[name="coa_id"]').val('');
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

    var dataTable = $('#accntApp').DataTable({
        "ajax": {
            "url": accntApprslReadRoute,
            "type": "GET",
        },
        responsive: true,
        lengthChange: true,
        searching: true,
        paging: true,
        "columns": [
            {data: 'fund_id'},
            {data: 'accountcoa_name'},
            {data: 'account_name'},
            {
                data: 'acntid',
                render: function(data, type, row) {
                    if (type === 'display') {
                        var dropdown = '<div class="d-inline-block">' +
                            '<a class="btn btn-success btn-sm dropdown-toggle dropdown-icon text-light" data-bs-toggle="dropdown"></a>' +
                            '<div class="dropdown-menu">' +
                            '<a href="#" class="dropdown-item btn-AccntsApp" data-id="' + row.acntid + '" data-fnd="' + row.fund_id + '" data-accntname="' + row.account_name + '" data-accntcoaid="' + row.accountcoa_code + '">' +
                            '<i class="fas fa-pen"></i> Edit' +
                            '</a>' +
                            '<button type="button" value="' + data + '" class="dropdown-item accountsApp-delete">' +
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
            $(row).attr('id', 'tr-' + data.acntid); 
        }
    });
    $(document).on('accntsAppAdded', function() {
        dataTable.ajax.reload();
    });
});

$(document).on('click', '.btn-AccntsApp', function() {
    console.log('Button clicked');
    var id = $(this).data('id');
    var accntFundId = $(this).data('fnd');
    var accntName = $(this).data('accntname');
    var accntCoaId = $(this).data('accntcoaid');

    $('#editaccntFundId').attr('id', 'editaccntFundId');

    $('#editaccntAppId').val(id);
    $('#editaccntFundId').val(accntFundId);
    $('#editaccntCOAname').val(accntName);
    $('#editaccntCOAid').val(accntCoaId);
    $('#editAppraisalAccntModal').modal('show');
});

$('#editAppraisalAccnForm').submit(function(event) {
    event.preventDefault();
    var formData = $(this).serialize();

    $.ajax({
        url: accntApprslUpdateRoute,
        type: "POST",
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if(response.success) {
                toastr.success(response.message);
                $('#editAppraisalAccntModal').modal('hide');
                $(document).trigger('accntsAppAdded');
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

$(document).on('click', '.accountsApp-delete', function(e) {
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
                url: accntApprslDeleteRoute.replace(':acntid', id),
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

