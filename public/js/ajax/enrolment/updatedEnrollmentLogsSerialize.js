$(document).ready(function() {
    var urlParams = new URLSearchParams(window.location.search);
    var schlyear = urlParams.get('schlyear') || ''; 
    var semester = urlParams.get('semester') || '';
    var campus = urlParams.get('campus') || ''; 

    var dataTable = $('#updatelogstable').DataTable({
        "ajax": {
            "url": studUpdateEnrollogsRoute,
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
        "columns": [
            { data: 'upcrt',
                render: function (data, type, row) {
                    if (type === 'display') {
                        return moment(data).format('MMMM D, YYYY h:mm A');
                    } else {
                        return data;
                    }
                }
            },
            {data: 'studentID'},
            {
                data: null,
                render: function(data, type, row) {
                    var firstname = data.fname;
                    var middleInitial = data.mname ? data.mname.substr(0, 1) + '.' : '';
                    var lastName = data.lname;
                    var ext = data.ext && data.ext !== 'N/A' ? ' ' + data.ext : ' ';
                    
                    return lastName + ', ' + firstname + ' ' + middleInitial + ext;
                }
            },
            {data: 'gender'},
            {data: 'semester'},
            {data: 'schlyear'},
            {data: 'postedBy'},
            {
                data: 'stud_id',
                render: function(data, type, row) {
                    if (type === 'display') {
                        // Log the data for debugging
                        //console.log("Rendering row with data:", data);

                        var schlyearValue = window.schlyear;
                        var semesterValue = window.semester;
                        var campusValue = window.campus;
                        var routeWithParams = decodeURIComponent(routeTemplate)
                            .replace(':stud_id', data)
                            .replace(':schlyear', schlyearValue)
                            .replace(':semester', semesterValue)
                            .replace(':campus', campusValue);

                        // Log the final route for debugging
                        //console.log("Generated route: ", routeWithParams);

                        var editLink = '<a href="' + routeWithParams + '" class="btn btn-primary btn-sm btn-studview" target="_blank">' +
                            '<i class="fas fa-eye"></i>' +
                            '</a>';
                        return editLink;
                    } else {
                        return data;
                    }
                },
            },
        ],
        "order": [[0, 'desc']],
        "createdRow": function (row, data, index) {
            $(row).attr('id', 'tr-' + data.id); 
        }
    });
    $(document).on('updteLog', function() {
        dataTable.ajax.reload();
    });
});