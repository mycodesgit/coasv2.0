toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right"
};
$(document).ready(function() {
    $('#adstudfeesmissing').submit(function(event) {
        event.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: studFeesUpdtCreateRoute,
            type: "POST",
            data: formData,
            success: function(response) {
                if(response.success) {
                    toastr.success(response.message);
                    console.log(response);
                    $(document).trigger('feesstudAdded');
                    $('input[name="amount"]').val('');
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
    var stud_id = urlParams.get('stud_id') || ''; 
    var schlyear = urlParams.get('schlyear') || '';
    var semester = urlParams.get('semester') || ''; 
    var category = urlParams.get('category') || ''; 
    var dataTable = $('#curapprsledit').DataTable({
        "ajax": {
            "url": studFeesUpdtReadRoute,
            "type": "GET",
            "data": { 
                "stud_id": stud_id,
                "schlyear": schlyear,
                "semester": semester,
                "category": category,
            }
        },
        destroy: true,
        info: false,
        responsive: true,
        lengthChange: false,
        searching: false,
        paging: false,
        "columns": [
            {data: 'fundID'},
            {data: 'account'},
            {data: 'amount'},
            {
                data: 'id',
                render: function(data, type, row) {
                    if (type === 'display') {
                        var dropdown = '<div class="d-inline-block">' +
                            '<a class="btn btn-primary btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown"></a>' +
                            '<div class="dropdown-menu">' +
                            '<a href="#" class="dropdown-item btn-studfee" data-id="' + row.id + '" data-fundid="' + row.fundID + '" data-account="' + row.account + '" data-amount="' + row.amount + '">' +
                            '<i class="fas fa-pen"></i> Edit' +
                            '</a>' +
                            '<button type="button" value="' + data + '" class="dropdown-item studfees-delete">' +
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
    $(document).on('feesstudAdded', function() {
        dataTable.ajax.reload();
    });
});

$(document).on('click', '.btn-studfee', function() {
    var id = $(this).data('id');
    var fundStudfee = $(this).data('fundid');
    var acountStud = $(this).data('account');
    var amountStud = $(this).data('amount');
    $('#editStudFeeId').val(id);
    $('#editstudfeeFund').val(fundStudfee);
    $('#editstudfeeaccountName').val(acountStud);
    $('#editstudfeeamountFee').val(amountStud);
    $('#editStudFeeModal').modal('show');
});

$('#editStudFeeForm').submit(function(event) {
    event.preventDefault();
    var formData = $(this).serialize();

    $.ajax({
        url: studFeesUpdtUpdateRoute,
        type: "POST",
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if(response.success) {
                toastr.success(response.message);
                $('#editStudFeeModal').modal('hide');
                $(document).trigger('feesstudAdded');
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

$(document).on('click', '.studfees-delete', function(e) {
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
                type: "POST",
                url: studFeesUpdtDeleteRoute.replace(':id', id),
                success: function(response) {
                    $("#tr-" + id).delay(1000).fadeOut();
                    Swal.fire({
                        title: 'Deleted!',
                        text: 'Successfully Deleted!',
                        icon: 'warning',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $(document).trigger('studFeeAdded');
                    if(response.success) {
                        toastr.success(response.message);
                        console.log(response);
                    }
                }
            });
        }
    })
});