toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right"
};
$(document).ready(function() {
    var urlParams = new URLSearchParams(window.location.search);
    var campus = urlParams.get('campus') || ''; 
    var schlyear = window.schlyear || ''; 
    var semester = window.semester || '';
    var routeTemplate = window.routeTemplate;

    // Log the variables to ensure they are being set correctly
    //console.log("schlyear: ", schlyear);
    //console.log("semester: ", semester);
    //console.log("routeTemplate: ", routeTemplate);

    var dataTable = $('#madapak').DataTable({
        "ajax": {
            "url": window.studsubgradeoffered,
            "type": "GET",
            "data": { 
                "schlyear": schlyear,
                "semester": semester,
                "campus": campus
            }
        },
        info: true,
        responsive: true,
        lengthChange: true,
        searching: true,
        paging: true,
        "columns": [
            {data: 'sid'},
            {data: 'sub_name'},
            {data: 'sub_title'},
            {data: 'subSec'},
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

    $(document).on('madapak', function() {
        dataTable.ajax.reload();
    });
});

