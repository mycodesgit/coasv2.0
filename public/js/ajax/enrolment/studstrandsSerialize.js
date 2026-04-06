$(document).ready(function() {
    var urlParams = new URLSearchParams(window.location.search);
    var schlyear = urlParams.get('schlyear') || ''; 
    var semester = urlParams.get('semester') || '';
    var campus = urlParams.get('campus') || ''; 

    var dataTable = $('#studstrandsTable').DataTable({
        "ajax": {
            "url": studStrandpersemRoute,
            "type": "GET",
            "data": { 
                "schlyear": schlyear,
                "semester": semester,
                "campus": campus,
            }
        },
        destroy: true,
        info: true,
        responsive: true,
        lengthChange: true,
        searching: true,
        paging: true,
        buttons: [
                'excel', 'pdf'
            ],
        "columns": [
            {data: 'studentID'},
            {data: 'strand'},
            {
                data: null,
                render: function(data, type, row) {
                    var firstname = data.fname.toUpperCase(); // Convert first name to uppercase
                    var middleInitial = data.mname ? data.mname.substr(0, 1).toUpperCase() + '.' : ''; // Get middle initial and add period
                    var lastName = data.lname.toUpperCase(); // Convert last name to uppercase
                    var extension = data.ext && data.ext !== 'N/A' ? data.ext : ''; // Check if extension is not null and not 'N/A'
                    return lastName + ', ' + firstname + ' ' + middleInitial + (extension ? ' ' + extension : ''); // Return formatted string
                }
            },
            {data: 'progName'},
            {data: 'progAcronym'},
            {data: 'studYear'},
            {data: 'studSec'},
            {data: 'schlyear'},
            {data: 'semester'},
            {data: 'address'},
            {data: 'brgy'},
            {data: 'city'},
            {data: 'province'},
            {data: 'region'},
            {data: 'zcode'},
            {
                data: null,
                render: function(data, type, row) {
                    // Display lstsch_attended, if empty display suc_lst_attended
                    return data.lstsch_attended && data.lstsch_attended.trim() !== ''
                        ? data.lstsch_attended
                        : (data.suc_lst_attended || '');
                }
            },
        ],
        "createdRow": function (row, data, index) {
            $(row).attr('id', 'tr-' + data.id); 
        },
        dom: 'Bfrtip'
    }).buttons().container().appendTo('#courseEn_wrapper .col-md-6:eq(0)');
    $(document).on('studenrlld', function() {
        dataTable.ajax.reload();
    });
});