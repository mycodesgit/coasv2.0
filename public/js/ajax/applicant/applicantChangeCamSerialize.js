toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right"
};

$(document).ready(function() {
    var urlParams = new URLSearchParams(window.location.search);
    var year = urlParams.get('year') || ''; 
    var campus = urlParams.get('campus') || ''; 
    var strand = urlParams.get('strand') || ''; 

    var dataTable = $('#applistChangeTable').DataTable({
        "ajax": {
            "url": allApplicantchngecampRoute,
            "type": "GET",
            "data": { 
                "year": year,
                "campus": campus,
                "strand": strand
            }
        },
        responsive: true,
        lengthChange: true,
        searching: true,
        paging: true,
        "columns": [
            {data: 'admission_id'},
            { 
                data: null,
                render: function(data, type, row) {
                    var firstname = data.fname;
                    var middleInitial = data.mname ? data.mname.substr(0, 1) + '.' : '';
                    var lastNameWithExt = data.lname + (data.ext !== 'N/A' ? ' ' + data.ext : '');
                    return firstname + ' ' + middleInitial + ' ' + lastNameWithExt;
                }
            },
            { 
                data: null,
                render: function(data, type, row) {
                    if (data.type == 1) {
                        return 'New';
                    } else if (data.type == 2) {
                        return 'Returnee';
                    } else if (data.type == 3) {
                        return 'Transferee';
                    } else {
                        return '';
                    }
                }
            },
            {data: 'contact'},
            { data: 'created_at',
                render: function (data, type, row) {
                    if (type === 'display') {
                        return moment(data).format('MMMM D, YYYY');
                    } else {
                        return data;
                    }
                }
            },
            {data: 'campus'},
            {data: 'appstrand'},
            {
                data: 'adid',
                render: function(data, type, row) {
                    if (type === 'display') {
                        var dropdown = '<div class="d-inline-block">' +
                            '<a class="btn btn-primary btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown"></a>' +
                            '<div class="dropdown-menu">' +
                            '<a href="#" class="dropdown-item btn-changecamp" data-id="' + row.adid + '" data-name="' + row.fname + ' ' + row.mname + ' ' + row.lname + '" data-admissionid="' + row.admission_id + '" data-strand="' + row.strand + '" data-campus="' + row.campus + '">' +
                            '<i class="fas fa-pen"></i> Edit' +
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
    $(document).on('changeCamp', function() {
        dataTable.ajax.reload();
    });
});

$(document).on('click', '.btn-changecamp', function() {
    var id = $(this).data('id');
    var fullname = $(this).data('name');
    var nameParts = fullname.split(' ');
    var middleInitial = nameParts[1] ? nameParts[1].charAt(0) + '.' : ''; 
    var updatedName = nameParts[0] + ' ' + middleInitial + ' ' + nameParts[2];
    var campus = $(this).data('campus');
    var strand = $(this).data('strand');
    var admissionid = $(this).data('admissionid');

    $('#changecampId').val(id);
    $('#changecampName').val(updatedName);
    $('#changecampCamp').val(campus);
    $('#changecampAdID').val(admissionid);
    $('#changecampStrand').val(strand);

    $('#changecampModal').modal('show');
    
    $.ajax({
        url: appidEncryptRoute,
        type: "POST",
        data: { data: $('#changecampId').val() },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            //alert(response); 
            $('#changecampId').val(response)
        },
        error: function(xhr, status, error) {
            alert('Error: ' + error); 
        }
    });
});


$('#changecampForm').submit(function(event) {
    event.preventDefault();
    var formData = $(this).serialize();

    $.ajax({
        url: updateallApplicantchngecampRoute,
        type: "POST",
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if(response.success) {
                toastr.success(response.message);
                $('#changecampModal').modal('hide');
                $(document).trigger('changeCamp');
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
