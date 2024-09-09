$(document).ready(function() {
    var urlParams = new URLSearchParams(window.location.search);
    var schlyear = urlParams.get('schlyear') || ''; 
    var semester = urlParams.get('semester') || '';
    var campus = urlParams.get('campus') || ''; 

    var dataTable = $('#attendanceTable').DataTable({
        "ajax": {
            "url": attendanceReadRoute,
            "type": "GET",
            "data": { 
                "schlyear": schlyear,
                "semester": semester,
                "campus": campus
            }
        },
        destroy: true,
        info: true,
        responsive: true,
        lengthChange: true,
        searching: true,
        paging: true,
        "columns": [
            {data: 'sub_name'},
            {data: 'sub_title'},
            {data: 'subSec'},
            {data: 'countstud'},
            {
                data: 'sid',
                render: function(data, type, row) {
                    if (type === 'display') {
                        // Log the data for debugging
                        //console.log("Rendering row with data:", data);

                        var schlyearValue = window.schlyear;
                        var semesterValue = window.semester;
                        var routeWithParams = decodeURIComponent(routeTemplate)
                            .replace(':id', data)
                            .replace(':schlyear', schlyearValue)
                            .replace(':semester', semesterValue);

                        // Log the final route for debugging
                        //console.log("Generated route: ", routeWithParams);

                        var editLink = '<a href="' + routeWithParams + '" class="btn btn-primary btn-sm btn-studview">' +
                            '<i class="fas fa-eye"></i>' +
                            '</a>';
                        return editLink;
                    } else {
                        return data;
                    }
                },
            },
        ],
        "createdRow": function (row, data, index) {
            $(row).attr('id', 'tr-' + data.id); 
        }
    });
    $(document).on('fundAdded', function() {
        dataTable.ajax.reload();
    });
});