toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right"
};

$(document).ready(function() {
    var urlParams = new URLSearchParams(window.location.search);
    var year = urlParams.get('year') || ''; 
    var campus = urlParams.get('campus') || ''; 

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
                "campus": campus
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
            {
                data: 'adid',
                className: "action-column",
                render: function(data, type, row) {
                    if (type === 'display' && isCampus === requestedCampus) {
                        var dropdown = '<div class="d-inline-block">' +
                            '<a class="btn btn-primary btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown"></a>' +
                            '<div class="dropdown-menu">';

                        if (isCampus) {
                            dropdown += '<a href="edit/srch/' + row.adid + '" class="dropdown-item btn-edit">' +
                                '<i class="fas fa-eye"></i> View' +
                                '</a>' +
                                '<a href="#" class="dropdown-item btn-image" data-id="' + row.adid + '" data-image="' + row.doc_image + '">' +
                                '<i class="fas fa-image"></i> Uploaded Photo' +
                                '</a>' +
                                '<a href="#" class="dropdown-item btn-assignsched" data-id="' + row.adid + '" data-dateid="' + row.dateID + '" data-dadmission="' + row.d_admission + '" data-time="' + row.time + '" data-venue="' + row.venue + '">' +
                                '<i class="fas fa-calendar"></i> Schedule' +
                                '</a>' +
                                '<a href="#" class="dropdown-item btn-pushtoexam" data-id="' + row.adid + '">' +
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

    $('#editAssignSchedId').val(id);
    $('#editAssignDateID').val(dateSelected);
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
    $('#pushtoexamId').val(id);
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

$(document).on('click', '.examinee-delete', function(e){
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
                url: allAppDeleteRoute,
                    success: function (response) {  
                    $("#tr-"+id).delay(1000).fadeOut();
                    Swal.fire({
                        title:'Deleted!',
                        text:'Successfully Deleted!',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 1000
                    })
                }
            });
        }
    })
});

