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
                    // Only display ext if it's not null, not 'N/A', and not empty
                    var ext = (data.ext && data.ext !== 'N/A') ? ' ' + data.ext : '';
                    var lastNameWithExt = data.lname + ext;
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
                        return '<td><small><span class="badge bg-success" style="font-size: 7pt">Interview Completed</span></small></td>';
                    } else {
                        return '<td><small><span class="badge bg-danger" style="font-size: 7pt">Interview Pending</span></small></td>';
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
                            '<a class="btn btn-success btn-sm dropdown-toggle dropdown-icon text-light" data-bs-toggle="dropdown"></a>' +
                            '<div class="dropdown-menu">';

                        if (isCampus) {
                            dropdown += '<a href="#" class="dropdown-item btn-viewappdata" data-id="' + row.adid + '" data-admissionid="' + row.admission_id + '" data-type="' + row.type + '" data-campus="' + row.campus + '" data-fname="' + row.fname + '" data-mname="' + row.mname + '" data-lname="' + row.lname + '" data-ext="' + row.ext + '" data-gender="' + row.gender + '" data-bday="' + row.bday + '" data-civilstat="' + row.civil_status + '" data-contact="' + row.contact + '" data-email="' + row.email + '" data-address="' + row.address + '" data-lsa="' + row.lstsch_attended + '" data-strand="' + row.strand + '" data-cula="' + row.suc_lst_attended + '" data-culac="' + row.course + '" data-cp1="' + row.preference_1 + '" data-cp2="' + row.preference_2 + '">' +
                                '<i class="fas fa-eye"></i> View Data' +
                                '</a>' + 
                                '<a href="srchconfirmList/view/' + row.adid + '" class="dropdown-item btn-edit" target="_blank">' +
                                '<i class="fas fa-file-pdf"></i> Generate Pre-Enrollment' +
                                '</a>' +
                                '<a href="#" class="dropdown-item btn-updateInterviewResult" data-id="' + row.adid + '" data-name="' + row.fname + ' ' + row.mname + ' ' + row.lname + '" data-strnd="' + row.strand + '" data-camp="' + row.campus + '" data-crating="' + row.rating + '" data-remark="' + row.remarks + '" data-cpref1="' + row.preference_1 + '" data-cpref2="' + row.preference_2 + '" data-creason="' + row.reason + '">' +
                                '<i class="fas fa-file-lines"></i> Assign Interview Test Result' +
                                '</a>';

                                if (row.remarks == 1) {
                                    dropdown += '<a href="#" class="dropdown-item btn-pushtoaccept" data-id="' + row.adid + '">' +
                                        '<i class="fas fa-check"></i> Push to Accepted Applicant' +
                                        '</a>';
                                } else {
                                    dropdown += '<span class="dropdown-item disabled"><i class="fas fa-check"></i> Push Examinee</span>';
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
    $(document).on('interrslttable', function() {
        dataTable.ajax.reload();
    });
});

$(document).on('click', '.btn-viewappdata', function() {
    var id = $(this).data('id');
    var admissionid = $(this).data('admissionid');
    var type = $(this).data('type');
    var campus = $(this).data('campus');
    var fname = $(this).data('fname');
    var mname = $(this).data('mname');
    var lname = $(this).data('lname');
    var ext = $(this).data('ext');
    var gender = $(this).data('gender');
    var bday = $(this).data('bday');
    var civilstat = $(this).data('civilstat');
    var contact = $(this).data('contact');
    var email = $(this).data('email');
    var address = $(this).data('address');
    var lsa = $(this).data('lsa');
    var strand = $(this).data('strand');
    var cula = $(this).data('cula');
    var culac = $(this).data('culac');
    var cp1 = $(this).data('cp1');
    var cp2 = $(this).data('cp2');

    $('#viewdataresultexamId').val(id);

    var typeDisplay;
    if(type == 1) {
        typeDisplay = "New";
    } else if(type == 2) {
        typeDisplay = "Returnee";
    } else if(type == 3) {
        typeDisplay = "Transferee";
    } else {
        typeDisplay = "Unknown";
    }

    $('#viewdataresultexamType').val(typeDisplay);

    var campusDisplay;
    if(campus == 'MC') {
        campusDisplay = "Main";
    } else if(campus == 'VC') {
        campusDisplay = "Victorias";
    } else if(campus == 'SCC') {
        campusDisplay = "San Carlos";
    } else if(campus == 'HC') {
        campusDisplay = "Hinigaran";
    } else if(campus == 'MP') {
        campusDisplay = "Moises Padilla";
    } else if(campus == 'IC') {
        campusDisplay = "Ilog";
    } else if(campus == 'CA') {
        campusDisplay = "Candoni";
    } else if(campus == 'CC') {
        campusDisplay = "Cauayan";
    } else if(campus == 'SC') {
        campusDisplay = "Sipalay";
    } else if(campus == 'HinC') {
        campusDisplay = "Hinobaan";
    } else {
        campusDisplay = "Unknown";
    }

    $('#viewdataresultexamCampus').val(campusDisplay);
    $('#viewdataresultexamAdID').val(admissionid);
    $('#viewdataresultexamFname').val(fname);
    $('#viewdataresultexamMname').val(mname);
    $('#viewdataresultexamLname').val(lname);
    $('#viewdataresultexamExt').val(ext);
    $('#viewdataresultexamGender').val(gender);
    $('#viewdataresultexamBday').val(bday);
    $('#viewdataresultexamcvilstat').val(civilstat);
    $('#viewdataresultexamMobile').val(contact);
    $('#viewdataresultexamEmail').val(email);
    $('#viewdataresultexamAddress').val(address);
    $('#viewdataresultexamLSA').val(lsa);
    $('#viewdataresultexamStrand').val(strand);
    $('#viewdataresultexamCUla').val(cula);
    $('#viewdataresultexamCUlac').val(culac);
    $('#viewdataresultexamCP1').val(cp1);
    $('#viewdataresultexamCP2').val(cp2);

    $('#viewdataresultexamModal').modal('show');
    
    $.ajax({
        url: appidEncryptRoute,
        type: "POST",
        data: { data: $('#viewdataresultexamId').val() },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            //alert(response); 
            $('#viewdataresultexamId').val(response)
        },
        error: function(xhr, status, error) {
            alert('Error: ' + error); 
        }
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
    var rating = parseFloat($(this).data('crating'));
    var remark = $(this).data('remark');
    var pref1 = $(this).data('cpref1');
    var pref2 = $(this).data('cpref2');
    var reason = $(this).data('creason');

    $('#interviewExamId').val(id);
    $('#interviewresultName').val(updatedName);
    $('#interviewresultStrand').val(strnd);
    $('#campus').val(camp).trigger('change');
    $('#interviewresultRating').val(rating);
    $('#interviewRemarks').val(remark);
    $('#coursePref1').val(pref1);
    $('#coursePref2').val(pref2);
    $('#interReason').val(reason);

    $('#interviewresultexamModal').modal('show');
    
    // $.ajax({
    //     url: appidEncryptRoute,
    //     type: "POST",
    //     data: { data: $('#interviewExamId').val() },
    //     headers: {
    //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //     },
    //     success: function(response) {
    //         //alert(response); 
    //         $('#interviewExamId').val(response)
    //     },
    //     error: function(xhr, status, error) {
    //         alert('Error: ' + error); 
    //     }
    // });
});

$('#interviewResultForm').submit(function(event) {
    event.preventDefault();
    var formData = $(this).serialize();

    $.ajax({
        url: updateConfirmRoute,
        type: "POST",
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if(response.success) {
                toastr.success(response.message);
                document.activeElement.blur();
                $('#interviewresultexamModal').modal('hide');
                $(document).trigger('interrslttable');
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
    
    // $.ajax({
    //     url: appidEncryptRoute,
    //     type: "POST",
    //     data: { data: $('#pushtocnfrmId').val() },
    //     headers: {
    //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //     },
    //     success: function(response) {
    //         //alert(response); 
    //         $('#pushtocnfrmId').val(response)
    //     },
    //     error: function(xhr, status, error) {
    //         alert('Error: ' + error); 
    //     }
    // });
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
                document.activeElement.blur();
                $('#pushtocnfrmModal').modal('hide');
                $(document).trigger('interrslttable');
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

$(document).on('click', '.btn-pushtoaccept', function() {
    var id = $(this).data('id');
    $('#pushtoAcceptId').val(id);
    $('#pushtoAcceptModal').modal('show');
    
    // $.ajax({
    //     url: appidEncryptRoute,
    //     type: "POST",
    //     data: { data: $('#pushtoAcceptId').val() },
    //     headers: {
    //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //     },
    //     success: function(response) {
    //         //alert(response); 
    //         $('#pushtoAcceptId').val(response)
    //     },
    //     error: function(xhr, status, error) {
    //         alert('Error: ' + error); 
    //     }
    // });
});

$('#pushtoAcceptForm').submit(function(event) {
    event.preventDefault();
    var formData = $(this).serialize();

    $.ajax({
        url: pushtoAcceptRoute,
        type: "POST",
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if(response.success) {
                toastr.success(response.message);
                $('#pushtoAcceptModal').modal('hide');
                $(document).trigger('interrslttable');
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