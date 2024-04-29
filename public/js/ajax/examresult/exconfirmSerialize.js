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

    var dataTable = $('#confrmlistTable').DataTable({
        "ajax": {
            "url": allAppConfirmRoute,
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
            {
                data: 'remarks',
                render: function(data) {
                    if (data == 1) {
                        return '<td><small><span>Accepted for Enrolment</span></small></td>';
                    } else {
                        return '<td><small><span>Not accepted for enrolment</span></small></td>';
                    }
                }
            },
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
                                '<a href="#" class="dropdown-item btn-updateInterviewResult" data-id="' + row.adid + '" data-name="' + row.fname + ' ' + row.mname + ' ' + row.lname + '" data-strnd="' + row.strand + '" data-camp="' + row.campus + '" data-cpref1="' + row.preference_1 + '" data-cpref2="' + row.preference_2 + '">' +
                                '<i class="fas fa-file-lines"></i> Assign Interview Test Result' +
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

$(document).on('click', '.btn-updateInterviewResult', function() {
    var id = $(this).data('id');
    var fullname = $(this).data('name');
    var nameParts = fullname.split(' ');
    var middleInitial = nameParts[1] ? nameParts[1].charAt(0) + '.' : ''; 
    var updatedName = nameParts[0] + ' ' + middleInitial + ' ' + nameParts[2];
    var strnd = $(this).data('strnd');
    var camp = $(this).data('camp');
    var pref1 = $(this).data('cpref1');
    var pref2 = $(this).data('cpref2');

    $('#interviewExamId').val(id);
    $('#interviewresultName').val(updatedName);
    $('#interviewresultStrand').val(strnd);
    $('#campus').val(camp).trigger('change');
    $('#coursePref1').val(pref1);
    $('#coursePref2').val(pref2);

    $('#interviewresultexamModal').modal('show');
    
    $.ajax({
        url: appidEncryptRoute,
        type: "POST",
        data: { data: $('#interviewExamId').val() },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            //alert(response); 
            $('#interviewExamId').val(response)
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

 $(document).ready(function() {
    $('#campus').val('YourStaticCampusValue');
    $('#campus').trigger('change');
    
    $('#campus').change(function() {
        var campus = $(this).val();
        $.ajax({
            url: progCampRoute,
            method: 'GET',
            data: { campus: campus },
            success: function(response) {
                $('#course').empty();
                $('#course').append('<option disabled selected>Select Course Preference</option>');
                $.each(response, function(index, program) {
                    $('#course').append('<option value="' + program.code + '">' + program.program + '</option>');
                });
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });
});
