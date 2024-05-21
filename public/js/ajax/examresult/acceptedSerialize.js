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

    function toggleActionColumn() {
        if (isCampus === requestedCampus) {
            $('#actionColumnHeader').show(); 
            $('#applistTable td.action-column').show();
        } else {
            $('#actionColumnHeader').hide(); 
            $('#applistTable td.action-column').hide(); 
        }
    }

    var dataTable = $('#acceptedTableapp').DataTable({
        "ajax": {
            "url": allAppAcceptedRoute,
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
            {data: 'interviewer'},
            {data: 'course'},
            {
                data: 'p_status',
                render: function(data) {
                    if (data == 6) {
                        return '<td><small><span class="badge badge-primary" style="font-size: 7pt">Pushed to Enrolment</span></small></td>';
                    } else {
                        return '<td><small><span class="badge badge-warning" style="font-size: 7pt">Not Push  to Enrolment</span></small></td>';
                    }
                }
            },
            {data: 'campus'},
            {data: 'appstrand'},
            {
                data: 'adid',
                className: "action-column",
                render: function(data, type, row) {
                    if (type === 'display' && isCampus === requestedCampus) {
                        var dropdown = '<div class="d-inline-block">' +
                            '<a class="btn btn-primary btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown"></a>' +
                            '<div class="dropdown-menu">';

                        if (isCampus) {
                            dropdown += '<a href="printPreEnrolment/srch/' + row.adid + '" class="dropdown-item btn-edit">' +
                                '<i class="fas fa-file-pdf"></i> Generate Pre-Enrollment' +
                                '</a>';

                                if (row.p_status == 5) {
                                    dropdown += '<a href="#" class="dropdown-item btn-pushtoenrollment" data-id="' + row.adid + '">' +
                                        '<i class="fas fa-check"></i> Push Enrollment' +
                                        '</a>';
                                } else {
                                    dropdown += '<span class="dropdown-item disabled"><i class="fas fa-check"></i> Done Pushed Enrollment</span>';
                                }
                        } else {
                            dropdown += '<span class="dropdown-item disabled"><i class="fas fa-eye"></i> View</span>' +
                                '<span class="dropdown-item disabled"><i class="fas fa-trash"></i> Delete</span>';
                        }
                        
                        dropdown += '</div>' +
                            '</div>';
                        return dropdown;
                    } else {
                        return '';
                    }
                },
            }
        ],
        "createdRow": function (row, data, index) {
            $(row).attr('id', 'tr-' + data.id); 
        }
    });
    toggleActionColumn();
    $(document).on('pushtoenrolltable', function() {
        dataTable.ajax.reload();
    });
});

$(document).on('click', '.btn-pushtocnfrm', function() {
    var id = $(this).data('id');
    $('#pushtocnfrmId').val(id);
    $('#pushtocnfrmModal').modal('show');
    
    $.ajax({
        url: appidEncryptRoute,
        type: "POST",
        data: { data: $('#pushtocnfrmId').val() },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            //alert(response); 
            $('#pushtocnfrmId').val(response)
        },
        error: function(xhr, status, error) {
            alert('Error: ' + error); 
        }
    });
});

$(document).on('click', '.btn-pushtoenrollment', function() {
    var id = $(this).data('id');
    $('#pushtoEnrollmentId').val(id);
    $('#pushtoEnrollmentModal').modal('show');
    
    $.ajax({
        url: appidEncryptRoute,
        type: "POST",
        data: { data: $('#pushtoEnrollmentId').val() },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            //alert(response); 
            $('#pushtoEnrollmentId').val(response)
        },
        error: function(xhr, status, error) {
            alert('Error: ' + error); 
        }
    });
});

$('#pushtoEnrollmentForm').submit(function(event) {
    event.preventDefault();
    var formData = $(this).serialize();

    $.ajax({
        url: pushtoEnrollmentRoute,
        type: "POST",
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if(response.success) {
                toastr.success(response.message);
                $('#pushtoEnrollmentModal').modal('hide');
                $(document).trigger('pushtoenrolltable');
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