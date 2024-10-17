$(document).ready(function() {
    var urlParams = new URLSearchParams(window.location.search);
    var schlyear = urlParams.get('schlyear') || ''; 
    var semester = urlParams.get('semester') || '';
    var campus = urlParams.get('campus') || ''; 

    var dataTable = $('#studenrolltable').DataTable({
        "ajax": {
            "url": studEnrolledpersemRoute,
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
            {data: 'progAcronym'},
            {data: 'studYear'},
            {data: 'schlyear'},
            {data: 'semester'},
            {
                data: null,
                render: function(data, type, row) {
                    if (data.address && data.address.trim() !== '') {
                        return data.address; // Return address if it's not null or empty
                    } else {
                        // Concatenate brgy, city, province, region, and zcode if address is empty or null
                        var brgy = data.brgy ? data.brgy : '';
                        var city = data.city ? data.city : '';
                        var province = data.province ? data.province : '';
                        var region = data.region ? data.region : '';
                        var zcode = data.zcode ? data.zcode : '';
                        // Return formatted string
                        return brgy + (brgy ? ', ' : '') +
                               city + (city ? ', ' : '') + 
                               province + (province ? ', ' : '') + 
                               region + (region ? ' ' : '') + 
                               zcode;
                    }
                }
            },
            {data: 'region'},
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