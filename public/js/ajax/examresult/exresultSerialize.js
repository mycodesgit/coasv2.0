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

    var dataTable = $('#exresultlistTable').DataTable({
        "ajax": {
            "url": allresultRoute,
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
            {
                data: 'adid',
                className: "action-column",
                render: function(data, type, row) {
                    if (type === 'display' && isCampus === requestedCampus) {
                        var dropdown = '<div class="d-inline-block">' +
                            '<a class="btn btn-primary btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown"></a>' +
                            '<div class="dropdown-menu">';

                        if (isCampus) {
                            dropdown += '<a href="srchexamineeList/edit/srchexam/' + row.adid + '" class="dropdown-item btn-edit" data-id="' + row.adid + '" data-chedname="' + row.chedsch_name + '">' +
                                '<i class="fas fa-eye"></i> View' +
                                '</a>' +
                                '<a href="#" class="dropdown-item btn-assignresultexam" data-id="' + row.adid + '" data-rawscore="' + row.raw_score + '" data-percentile="' + row.percentile + '">' +
                                '<i class="fas fa-file-lines"></i> Assign Result' +
                                '</a>' +
                                '<a href="#" class="dropdown-item btn-pushtoresult" data-id="' + row.adid + '">' +
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
    $(document).on('examineeUpdate', function() {
        dataTable.ajax.reload();
    });
});

// $(document).on('click', '.btn-assignresultexam', function() {
//     var id = $(this).data('id');
//     var rawScore = $(this).data('rawscore');
//     var percentile = $(this).data('percentile');

//     $('#assignresultexamId').val(id);
//     $('#assignresultexamRawScore').val(rawScore);
//     $('#assignresultexamPercent').val(percentile);

//     $('#assignresultexamModal').modal('show');
    
//     $.ajax({
//         url: appidEncryptRoute,
//         type: "POST",
//         data: { data: $('#assignresultexamId').val() },
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         },
//         success: function(response) {
//             //alert(response); 
//             $('#assignresultexamId').val(response)
//         },
//         error: function(xhr, status, error) {
//             alert('Error: ' + error); 
//         }
//     });
// });

// $('#admissionAssignResult').submit(function(event) {
//     event.preventDefault();
//     var formData = $(this).serialize();

//     $.ajax({
//         url: allExamAssignResultRoute,
//         type: "POST",
//         data: formData,
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         },
//         success: function(response) {
//             if(response.success) {
//                 toastr.success(response.message);
//                 $('#assignresultexamModal').modal('hide');
//                 $(document).trigger('examineeUpdate');
//             } else {
//                 toastr.error(response.message);
//             }
//         },
//         error: function(xhr, status, error, message) {
//             var errorMessage = xhr.responseText ? JSON.parse(xhr.responseText).message : 'An error occurred';
//             toastr.error(errorMessage);
//         }
//     });
// });

// $(document).on('click', '.btn-pushtoresult', function() {
//     var id = $(this).data('id');
//     $('#pushtoresultId').val(id);
//     $('#pushtoresultModal').modal('show');
    
//     $.ajax({
//         url: appidEncryptRoute,
//         type: "POST",
//         data: { data: $('#pushtoresultId').val() },
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         },
//         success: function(response) {
//             //alert(response); 
//             $('#pushtoresultId').val(response)
//         },
//         error: function(xhr, status, error) {
//             alert('Error: ' + error); 
//         }
//     });
// });

// $('#pushtoresultForm').submit(function(event) {
//     event.preventDefault();
//     var formData = $(this).serialize();

//     $.ajax({
//         url: pushtoresultRoute,
//         type: "POST",
//         data: formData,
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         },
//         success: function(response) {
//             if(response.success) {
//                 toastr.success(response.message);
//                 $('#pushtoresultModal').modal('hide');
//                 $(document).trigger('examineeUpdate');
//             } else {
//                 toastr.error(response.message);
//             }
//         },
//         error: function(xhr, status, error, message) {
//             var errorMessage = xhr.responseText ? JSON.parse(xhr.responseText).message : 'An error occurred';
//             toastr.error(errorMessage);
//         }
//     });
// });

// $(document).on('click', '.examinee-delete', function(e){
//     var id = $(this).val();
//     $.ajaxSetup({
//         headers: {
//           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         },
//     });
//     Swal.fire({
//         title: 'Are you sure?',
//         text: "You won't be able to recover this!",
//         icon: 'warning',
//         showCancelButton: true,
//         confirmButtonColor: '#3085d6',
//         cancelButtonColor: '#d33',
//         confirmButtonText: 'Yes, delete it!'
//         }).then((result) => {
//         if (result.isConfirmed) {
//             $.ajax({
//                 type: "GET",
//                 url: allAppDeleteRoute,
//                     success: function (response) {  
//                     $("#tr-"+id).delay(1000).fadeOut();
//                     Swal.fire({
//                         title:'Deleted!',
//                         text:'Successfully Deleted!',
//                         icon: 'success',
//                         showConfirmButton: false,
//                         timer: 1000
//                     })
//                 }
//             });
//         }
//     })
// });

// document.addEventListener('DOMContentLoaded', function() {
//     const rawScoreInput = document.querySelector('input[name="raw_score"]');
    
//     const remarksInput = document.querySelector('input[name="percentile"]');
    
//     rawScoreInput.addEventListener('input', function() {
//         const rawScoreValue = parseInt(this.value);
        
//         if (rawScoreValue < 100) {
//             remarksInput.value = 'Failed';
//         } else {
//             remarksInput.value = 'Qualified';
//         }
        
//     });
// });
