toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right"
};
$(document).ready(function() {
    $('#addScholar').submit(function(event) {
        event.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: allschcatCreateRoute,
            type: "POST",
            data: formData,
            success: function(response) {
                if(response.success) {
                    toastr.success(response.message);
                    console.log(response);
                    $('#modal-allsch').modal('hide');
                    $(document).trigger('allschcatAdded');
                    $('input[name="scholar_name"]').val('');
                    $('input[name="scholar_sponsor"]').val('');
                    $('select[name="chedcategory"]').val('');
                    $('select[name="unicategory"]').val('');
                    $('select[name="fund_source"]').val('');
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

    var dataTable = $('#allschTable').DataTable({
        "ajax": {
            "url": allschcatReadRoute,
            "type": "GET",
        },
        info: true,
        responsive: true,
        lengthChange: true,
        searching: true,
        paging: true,
        "columns": [
            {data: 'scholar_name'},
            {data: 'scholar_sponsor'},
            {data: 'chedsch_name'},
            {data: 'unisch_name'},
            {data: 'fndsource_name'},
            {
                data: 'id',
                render: function(data, type, row) {
                    if (type === 'display') {
                        var dropdown = '<div class="d-inline-block">' +
                            '<a class="btn btn-primary btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown"></a>' +
                            '<div class="dropdown-menu">';

                        if (isAdmin) {
                            dropdown += '<a href="#" class="dropdown-item btn-allschcat" data-id="' + row.id + '" data-allschname="' + row.scholar_name + '" data-allschsponname="' + row.scholar_sponsor + '" data-allschchedname="' + row.chedschid + '" data-allschuniname="' + row.unischid + '" data-allschfsname="' + row.fsschid + '">' +
                                '<i class="fas fa-pen"></i> Edit' +
                                '</a>' +
                                '<button type="button" value="' + data + '" class="dropdown-item unischcat-delete">' +
                                '<i class="fas fa-trash"></i> Delete' +
                                '</button>';
                        } else {
                            dropdown += '<span class="dropdown-item disabled"><i class="fas fa-pen"></i> Edit</span>' +
                                '<span class="dropdown-item disabled"><i class="fas fa-trash"></i> Delete</span>';
                        }
                        
                        dropdown += '</div>' +
                            '</div>';
                        return dropdown;
                    } else {
                        return data;
                    }
                },
            }
        ],
        "createdRow": function (row, data, index) {
            $(row).attr('id', 'tr-' + data.id); 
        }
    });
    $(document).on('allschcatAdded', function() {
        dataTable.ajax.reload();
    });
});

$(document).on('click', '.btn-allschcat', function() {
    var id = $(this).data('id');
    var allschName = $(this).data('allschname');
    var allsponName = $(this).data('allschsponname');
    var allschedName = $(this).data('allschchedname');
    var allsuniName = $(this).data('allschuniname');
    var allsfsName = $(this).data('allschfsname');
    
    $('#editSchChoiceId').val(id);
    $('#editSchChoiceName').val(allschName);
    $('#editSchSponChoiceName').val(allsponName);
    $('#edichedChoiceName').val(allschedName);
    $('#ediuniChoiceName').val(allsuniName);
    $('#edifsChoiceName').val(allsfsName);
    $('#editAllSchModal').modal('show');
});


$('#editUNISchForm').submit(function(event) {
    event.preventDefault();
    var formData = $(this).serialize();

    $.ajax({
        url: allschcatUpdateRoute,
        type: "POST",
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if(response.success) {
                toastr.success(response.message);
                $('#editAllSchModal').modal('hide');
                $(document).trigger('unischcatAdded');
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

$(document).on('click', '.unischcat-delete', function(e) {
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
                url: unischcatDeleteRoute.replace(':id', id),
                success: function(response) {
                    $("#tr-" + id).delay(1000).fadeOut();
                    Swal.fire({
                        title: 'Deleted!',
                        text: 'Successfully Deleted!',
                        icon: 'warning',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $(document).trigger('unischcatAdded');
                    if(response.success) {
                        toastr.success(response.message);
                        console.log(response);
                    }
                }
            });
        }
    })
});

