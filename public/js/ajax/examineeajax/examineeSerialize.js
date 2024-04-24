toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right"
};

$(document).ready(function() {
    var urlParams = new URLSearchParams(window.location.search);
    var year = urlParams.get('year') || ''; 
    var campus = urlParams.get('campus') || ''; 

    var dataTable = $('#examlistTable').DataTable({
        "ajax": {
            "url": allExamlicantRoute,
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
                data: 'id',
                render: function(data, type, row) {
                    if (type === 'display') {
                        var dropdown = '<div class="d-inline-block">' +
                            '<a class="btn btn-primary btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown"></a>' +
                            '<div class="dropdown-menu">';

                        if (isCampus) {
                            dropdown += '<a href="srchexamineeList/edit/srchexam/' + row.id + '" class="dropdown-item btn-edit" data-id="' + row.id + '" data-chedname="' + row.chedsch_name + '">' +
                                '<i class="fas fa-eye"></i> View' +
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
                        return data;
                    }
                },
            }
        ],
        "createdRow": function (row, data, index) {
            $(row).attr('id', 'tr-' + data.id); 
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

