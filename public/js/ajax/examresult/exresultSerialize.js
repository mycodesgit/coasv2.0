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
                            dropdown += '<a href="#" class="dropdown-item btn-viewappdata" data-id="' + row.adid + '" data-admissionid="' + row.admission_id + '" data-type="' + row.type + '" data-campus="' + row.campus + '" data-fname="' + row.fname + '" data-mname="' + row.mname + '" data-lname="' + row.lname + '" data-ext="' + row.ext + '" data-gender="' + row.gender + '" data-bday="' + row.bday + '" data-age="' + row.age + '" data-contact="' + row.contact + '" data-email="' + row.email + '" data-address="' + row.address + '" data-lsa="' + row.lstsch_attended + '" data-strand="' + row.strand + '" data-cula="' + row.suc_lst_attended + '" data-culac="' + row.course + '" data-cp1="' + row.preference_1 + '" data-cp2="' + row.preference_2 + '">' +
                                '<i class="fas fa-eye"></i> View Data' +
                                '</a>' +
                                '<a href="printPreEnrolment/srch/' + row.adid + '" class="dropdown-item btn-edit">' +
                                '<i class="fas fa-file-pdf"></i> Generate Pre-Enrollment' +
                                '</a>' +
                                '<a href="#" class="dropdown-item btn-updateresultexam" data-id="' + row.adid + '" data-rawscore="' + row.raw_score + '" data-percentile="' + row.percentile + '">' +
                                '<i class="fas fa-file-lines"></i> Update Test Result' +
                                '</a>';

                                if (row.percentile == 'Qualified') {
                                    dropdown += '<a href="#" class="dropdown-item btn-pushtocnfrm" data-id="' + row.adid + '">' +
                                        '<i class="fas fa-check"></i> Push Examinee' +
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
    $(document).on('rslttable', function() {
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
    var age = $(this).data('age');
    var contact = $(this).data('gender');
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
    $('#viewdataresultexamAge').val(age);
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

document.addEventListener('DOMContentLoaded', function() {
    const rawScoreInput = document.querySelector('input[name="raw_score"]');
    
    const remarksInput = document.querySelector('input[name="percentile"]');
    
    rawScoreInput.addEventListener('input', function() {
        const rawScoreValue = parseInt(this.value);
        
        if (rawScoreValue < 100) {
            remarksInput.value = 'Failed';
        } else {
            remarksInput.value = 'Qualified';
        }
        
    });
});
