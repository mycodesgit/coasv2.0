toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right"
};
$(document).ready(function() {
    $('#studFeeAssess').on('submit', function (e) {
        e.preventDefault();

        var rowsData = [];
        $('#studentFeesTable tbody tr').each(function () {
            var row = {
                fundname_code: $('input[name="fundname_code[]"]', this).val().trim(), 
                amountFee: $('input[name="amountFee[]"]', this).val().trim(), 
                accountName: $('input[name="accountName[]"]', this).val().trim(),
                prog_code: $('input[name="prog_Code[]"]').val(), 
                yrlevel: $('input[name="yrlevel[]"]').val(), 
                schlyear: $('input[name="schlyear[]"]').val(), 
                semester: $('input[name="semester[]"]').val(),
                campus: $('input[name="campus[]"]').val() 
            };
            rowsData.push(row);
        });

        
        $.ajax({
            url: studfeeCreateRoute, 
            method: "POST",
            data: {
                rows_data: rowsData 
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') 
            },
            success: function (response) {
                if (response.success) {
                    toastr.success(response.message);
                    console.log(response);
                    $('#studentFeesModal').modal('hide');
                    $(document).trigger('studFeeAdded');
                } else {
                    toastr.error(response.message);
                }
            },
            error: function (xhr) {
                if (xhr.status === 419) {
                    //alert("CSRF token mismatch or expired. Please reload the page and try again.");
                    var errorMessage = xhr.responseText ? JSON.parse(xhr.responseText).message : 'An error occurred';
                    toastr.error(errorMessage);
                } else {
                    //alert("Failed to add student fees.");
                    var errorMessage = xhr.responseText ? JSON.parse(xhr.responseText).message : 'An error occurred';
                    toastr.error(errorMessage);
                }
            }
        });
    });

    var urlParams = new URLSearchParams(window.location.search);
    var campus = urlParams.get('campus') || ''; 
    var progCode = urlParams.get('prog_Code') || ''; 
    var yrlevel = urlParams.get('yrlevel') || ''; 
    var schlyear = urlParams.get('schlyear') || ''; 
    var semester = urlParams.get('semester') || '';
    var dataTable = $('#studentFees').DataTable({
        "ajax": {
            "url": studfeeReadRoute,
            "type": "GET",
            "data": { 
                "campus": campus,
                "prog_Code": progCode,
                "yrlevel": yrlevel,
                "schlyear": schlyear,
                "semester": semester
            }
        },
        info: false,
        responsive: true,
        lengthChange: false,
        searching: false,
        paging: false,
        "columns": [
            {data: 'fundname_code'},
            {data: 'accountName'},
            {
                data: 'amountFee',
                render: function (data, type, row) {
                    return '<strong>' + parseFloat(data).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,') + '</strong>';
                }
            },
            {
                data: 'id',
                render: function(data, type, row) {
                    if (type === 'display') {
                        var dropdown = '<div class="d-inline-block">' +
                            '<a class="btn btn-success btn-sm dropdown-toggle dropdown-icon text-light" data-bs-toggle="dropdown"></a>' +
                            '<div class="dropdown-menu">' +
                            '<a href="#" class="dropdown-item btn-studfee" data-id="' + row.id + '" data-fundcode="' + row.fundname_code + '" data-fundstudname="' + row.accountName + '" data-fundstudamount="' + row.amountFee + '">' +
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
        "footerCallback": function (row, data, start, end, display) {
            var api = this.api();
            grandTotal = api.column(2, {page: 'current'}).data().reduce(function (a, b) {
                return parseFloat(a) + parseFloat(b);
            }, 0);
            $(api.column(2).footer()).html(parseFloat(grandTotal).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            $('#grandTotal').text(parseFloat(grandTotal).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        },
        "createdRow": function (row, data, index) {
            $(row).attr('id', 'tr-' + data.id); 
        }
    });
    $(document).on('studFeeAdded', function() {
        dataTable.ajax.reload();
    });
});

$(document).on('click', '.btn-studfee', function() {
    var id = $(this).data('id');
    var fundStudfee = $(this).data('fundcode');
    var acountStud = $(this).data('fundstudname');
    var amountStud = $(this).data('fundstudamount');
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
        url: studfeeUpdateRoute,
        type: "POST",
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if(response.success) {
                toastr.success(response.message);
                $('#editStudFeeModal').modal('hide');
                $(document).trigger('studFeeAdded');
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
                type: "GET",
                url: studfeeDeleteRoute.replace(':id', id),
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

