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

    var dataTable = $('#exresultlistTable').DataTable({
        "ajax": {
            "url": allresultRoute,
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
            {data: 'raw_score'},
            {data: 'percentile'},
            {
                data: null,
                render: function(data, type, row) {
                    if (type === 'display') {
                        // Parse the date and time strings and format them using moment.js
                        var dateTimeString = row.d_admission + ' ' + row.time;
                        var formattedDateTime = moment(dateTimeString, 'YYYY-MM-DD HH:mm:ss').format('MMM DD, YYYY h:mm A');
                        return formattedDateTime;
                    } else {
                        return data; // Return the original data for other types
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
                                '</a>' +
                                '<a href="#" class="dropdown-item btn-updateresultexam" data-id="' + row.adid + '" data-rawscore="' + row.raw_score + '" data-percentile="' + row.percentile + '">' +
                                '<i class="fas fa-file-lines"></i> Update Test Result' +
                                '</a>' +
                                '<a href="#" class="dropdown-item btn-pushtocnfrm" data-id="' + row.adid + '">' +
                                '<i class="fas fa-check"></i> Push Examinee' +
                                '</a>';
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
    $(document).on('rslttable', function() {
        dataTable.ajax.reload();
    });
});

$(document).on('click', '.btn-updateresultexam', function() {
    var id = $(this).data('id');
    var uprawScore = $(this).data('rawscore');
    var uppercentile = $(this).data('percentile');

    $('#updateresultexamId').val(id);
    $('#updateresultexamRawScore').val(uprawScore);
    $('#updateresultexamPercent').val(uppercentile);

    $('#updateresultexamModal').modal('show');
    
    $.ajax({
        url: appidEncryptRoute,
        type: "POST",
        data: { data: $('#updateresultexamId').val() },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            //alert(response); 
            $('#updateresultexamId').val(response)
        },
        error: function(xhr, status, error) {
            alert('Error: ' + error); 
        }
    });
});

$('#updateTstResult').submit(function(event) {
    event.preventDefault();
    var formData = $(this).serialize();

    $.ajax({
        url: updateTestResultRoute,
        type: "POST",
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if(response.success) {
                toastr.success(response.message);
                $('#updateresultexamModal').modal('hide');
                $(document).trigger('rslttable');
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

$('#pushtocnfrmForm').submit(function(event) {
    event.preventDefault();
    var formData = $(this).serialize();

    $.ajax({
        url: pushtocnfrmRoute,
        type: "POST",
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if(response.success) {
                toastr.success(response.message);
                $('#pushtocnfrmModal').modal('hide');
                $(document).trigger('rslttable');
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
