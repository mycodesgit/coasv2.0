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

    var dataTable = $('#applistTable').DataTable({
        "ajax": {
            "url": allApplicantRoute,
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
                className: "action-column",
                render: function(data, type, row) {
                    if (type === 'display' && isCampus === requestedCampus) {
                        var dropdown = '<div class="d-inline-block">' +
                            '<a class="btn btn-primary btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown"></a>' +
                            '<div class="dropdown-menu">';

                        if (isCampus) {
                            dropdown += '<a href="#" class="dropdown-item btn-viewappdata" data-id="' + row.adid + '" data-admissionid="' + row.admission_id + '" data-type="' + row.type + '" data-campus="' + row.campus + '" data-fname="' + row.fname + '" data-mname="' + row.mname + '" data-lname="' + row.lname + '" data-ext="' + row.ext + '" data-gender="' + row.gender + '" data-bday="' + row.bday + '" data-civilstat="' + row.civil_status + '" data-contact="' + row.contact + '" data-email="' + row.email + '" data-address="' + row.address + '" data-lsa="' + row.lstsch_attended + '" data-strand="' + row.strand + '" data-cula="' + row.suc_lst_attended + '" data-culac="' + row.course + '" data-cp1="' + row.preference_1 + '" data-cp2="' + row.preference_2 + '">' +
                                '<i class="fas fa-eye"></i> View Data' +
                                '</a>' +
                                '<a href="#" class="dropdown-item btn-reupload" data-id="' + row.adid + '">' +
                                '<i class="fas fa-upload"></i> Re-Upload' +
                                '</a>' +
                                '<a href="#" class="dropdown-item btn-image" data-id="' + row.adid + '" data-image="' + row.studiddoc_image + '">' +
                                '<i class="fas fa-id-card-clip"></i> School ID' +
                                '</a>' +
                                '<a href="#" class="dropdown-item btn-imagereportcardtor" data-id="' + row.adid + '" data-uploadreportcard="' + 
                                    (row.grade12File || row.shsFile || row.transfereeFile || row.alsFile || row.lifelongFile || '') + '">' +
                                '<i class="fas fa-file-lines"></i> Report Card/TOR' +
                                '</a>' +
                                '<a href="#" class="dropdown-item btn-imageproof" data-id="' + row.adid + '" data-imageproof="' + row.proofdoc_image + '" data-proof="' + row.typefileproofupload + '">' +
                                '<i class="fas fa-image"></i> Proof/Evidence' +
                                '</a>' +
                                '<a href="#" class="dropdown-item btn-assignsched" data-id="' + row.adid + '" data-dateid="' + row.dateID + '" data-dadmission="' + row.d_admission + '" data-time="' + row.time + '" data-venue="' + row.venue + '">' +
                                '<i class="fas fa-calendar"></i> Schedule' +
                                '</a>' +
                                '<a href="#" class="dropdown-item btn-pushtoexam" data-id="' + row.adid + '" data-email="' + row.email + '">' +
                                '<i class="fas fa-check"></i> Push Examinee' +
                                '</a>' +
                                '<button type="button" value="' + data + '" class="dropdown-item examinee-delete">' +
                                '<i class="fas fa-trash"></i> Delete' +
                                '</button>';
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
    $(document).on('schedExamUpdated', function() {
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

$('#editAppDataPersonalinfoForm').submit(function(event) {
    event.preventDefault();
    var formData = $(this).serialize();

    $.ajax({
        url: allAppUpdateRoute,
        type: "POST",
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if(response.success) {
                toastr.success(response.message);
                $('#viewdataresultexamModal').modal('hide');
                $(document).trigger('schedExamUpdated');
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

$(document).on('click', '.btn-reupload', function() {
    var id = $(this).data('id');
    
    $('#editReUploadId').val(id);

    $('input[name="buttons[]"]').prop('checked', false);

    $.ajax({
        url: appaccessRoute.replace(':id', id), // Replace :id with actual ID
        type: 'GET',
        success: function(response) {
            if (response.buttons) {
                response.buttons.forEach(function(button) {
                    $('input[name="reuploadallow[]"][value="' + button + '"]').prop('checked', true);
                });
            }
            $('#editReUploadModal').modal('show');
        },
        error: function(xhr) {
            console.error(xhr.responseText); 
        }
    });
});

$('#editReUploadAccessForm').submit(function(event) {
    event.preventDefault(); 
    
    var formData = $(this).serialize(); 

    var id = $('#editReUploadId').val(); 
    var selectedReuploadaccess = [];

    $('input[name="reuploadallow[]"]:checked').each(function() {
        selectedReuploadaccess.push($(this).val()); 
    });

    $.ajax({
        url: appSaveAccessRoute.replace(':id', id), 
        type: "POST",
        data: {
            reuploadallow: selectedReuploadaccess,
            _token: $('meta[name="csrf-token"]').attr('content') 
        },
        success: function(response) {
            if(response.success) {
                toastr.success(response.message);
                $('#editReUploadModal').modal('hide'); 
                $(document).trigger('schedExamUpdated'); 
            } else {
                toastr.error(response.message);
            }
        },
        error: function(xhr) {
            console.error(xhr.responseText); 
            toastr.error('An error occurred while saving the user access.');
        }
    });
});

$(document).on('click', '.btn-image', function() {
    var id = $(this).data('id');
    var image = $(this).data('image');
    
    $('#editUploadPhotoId').val(id);
    $('#editUploadPhotoDoc').val(image);

    if (image) {
        $('#uploadedPhoto').attr('src', photoStorage + "/" + image).show();
        $('#uploadedPhoto').removeAttr('alt');
        $('#noDocumentText').hide();
    } else {
        $('#uploadedPhoto').attr('src', '').hide();
        $('#noDocumentText').show();
        $('#noDocumentText').css('font-size', '58px');
    }

    $('#editUploadPhotoModal').modal('show');

    $.ajax({
        url: appidEncryptRoute,
        type: "POST",
        data: { data: $('#editUploadPhotoId').val() },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            //alert(response); 
            $('#editUploadPhotoId').val(response)
        },
        error: function(xhr, status, error) {
            alert('Error: ' + error); 
        }
    });
});

$(document).on('click', '.btn-imagereportcardtor', function() {
    var id = $(this).data('id');
    var imagereportcard = $(this).data('uploadreportcard');
    
    $('#editUploadReportCardId').val(id);
    $('#editUploadReportCardDoc').val(imagereportcard);

    if (imagereportcard) {
        $('#uploadedPhotoReportCard').attr('src', photoStorage + "/" + imagereportcard).show();
        $('#uploadedPhotoReportCard').removeAttr('alt');
        $('#noDocumentTextReportCard').hide();
    } else {
        $('#uploadedPhotoReportCard').attr('src', '').hide();
        $('#noDocumentTextReportCard').show();
        $('#noDocumentTextReportCard').css('font-size', '58px');
    }

    $('#editUploadReportCardModal').modal('show');

    $.ajax({
        url: appidEncryptRoute,
        type: "POST",
        data: { data: $('#editUploadReportCardId').val() },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            //alert(response); 
            $('#editUploadReportCardId').val(response)
        },
        error: function(xhr, status, error) {
            alert('Error: ' + error); 
        }
    });
});

$(document).on('click', '.btn-imageproof', function() {
    var id = $(this).data('id');
    var imageproof = $(this).data('imageproof');
    var typeproof = $(this).data('proof');
    
    $('#editUploadPhotoProofId').val(id);
    $('#editUploadPhotoProofDoc').val(imageproof);
    $('#uploadedTypeProof').val(typeproof);

    if (imageproof) {
        $('#uploadedPhotoProof').attr('src', photoStorage + "/" + imageproof).show();
        $('#uploadedPhotoProof').removeAttr('alt');
        $('#noDocumentTextProof').hide();
    } else {
        $('#uploadedPhotoProof').attr('src', '').hide();
        $('#noDocumentTextProof').show();
        $('#noDocumentTextProof').css('font-size', '58px');
    }

    $('#editUploadPhotoProofModal').modal('show');

    $.ajax({
        url: appidEncryptRoute,
        type: "POST",
        data: { data: $('#editUploadPhotoProofId').val() },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            //alert(response); 
            $('#editUploadPhotoProofId').val(response)
        },
        error: function(xhr, status, error) {
            alert('Error: ' + error); 
        }
    });
});

$(document).on('click', '.btn-assignsched', function() {
    var id = $(this).data('id');
    var dateSelected = $(this).data('dateid');
    var dadmissionSelected = $(this).data('dadmission');
    var dadmissionSched = $(this).data('dadmission');
    var dtimeSelected = $(this).data('time');
    var dtimeSchedule = $(this).data('time');
    var venueSelected = $(this).data('venue');
    var venueSched = $(this).data('venue');

    if (dadmissionSched) {
        var dateParts = dadmissionSched.split('-');
        var year = dateParts[0];
        var monthIndex = parseInt(dateParts[1]) - 1;
        var day = dateParts[2];
        var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                          "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        var monthName = monthNames[monthIndex];
        var formattedDate = monthName + " " + day + ", " + year;

        $('#schedDate').val(formattedDate);
    } else {
        $('#schedDate').val('No schedule');
    }

    if (dtimeSchedule) {
        $('#schedTime').val(dtimeSchedule);
    } else {
        $('#schedTime').val('No schedule time');
    }
    
    if (venueSched) {
        $('#schedVenue').val(venueSched);
    } else {
        $('#schedVenue').val('No venue');
    }

    if (dtimeSchedule) {
        $('#schedTime').val(dtimeSchedule);
        $('#formdatesched').hide();
    } else {
        $('#formdatesched').show();
    }

    $('#editAssignSchedId').val(id);
    $('#editAssignDateID').val(dateSelected);
    $('#selectedDateTimeID').val(dateSelected);
    $('#selectedDate').val(dadmissionSelected);
    $('#selectedTime').val(dtimeSelected);
    $('#selectedVenue').val(venueSelected);

    $('#editAssignSchedModal').modal('show');

    $.ajax({
        url: appidEncryptRoute,
        type: "POST",
        data: { data: $('#editAssignSchedId').val() },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            //alert(response); 
            $('#editAssignSchedId').val(response)
        },
        error: function(xhr, status, error) {
            alert('Error: ' + error); 
        }
    });
});

$('#editAssignSchedForm').submit(function(event) {
    event.preventDefault();
    var formData = $(this).serialize();

    $.ajax({
        url: allAppAssignSchedRoute,
        type: "POST",
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if(response.success) {
                toastr.success(response.message);
                $('#editAssignSchedModal').modal('hide');
                $(document).trigger('schedExamUpdated');
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

$(document).on('click', '.btn-pushtoexam', function() {
    var id = $(this).data('id');
    var email = $(this).data('email');

    $('#pushtoexamId').val(id);
    $('#pushtoexamEmail').val(email);
    $('#pushtoexamModal').modal('show');
    
    $.ajax({
        url: appidEncryptRoute,
        type: "POST",
        data: { data: $('#pushtoexamId').val() },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            //alert(response); 
            $('#pushtoexamId').val(response)
        },
        error: function(xhr, status, error) {
            alert('Error: ' + error); 
        }
    });
});


$('#pushtoexamForm').submit(function(event) {
    event.preventDefault();
    var formData = $(this).serialize();

    $.ajax({
        url: pushtoexamRoute,
        type: "POST",
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if(response.success) {
                toastr.success(response.message);
                $('#pushtoexamModal').modal('hide');
                $(document).trigger('schedExamUpdated');
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

$(document).on('click', '.examinee-delete', function(e) {
    var id = $(this).val();
    alert(id);
    
    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
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
                url: allAppDeleteRoute.replace(':id', id),
                success: function (response) {  
                    $("#tr-" + id).delay(1000).fadeOut();
                    Swal.fire({
                        title: 'Deleted!',
                        text: 'Successfully Deleted!',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 1000
                    });
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Something went wrong. Please try again later.',
                        icon: 'error'
                    });
                    console.error("Error:", error);
                }
            });
        }
    });
});

