$(document).ready(function() {
    var urlParams = new URLSearchParams(window.location.search);
    var schlyear = urlParams.get('schlyear') || ''; 
    var semester = urlParams.get('semester') || '';
    var campus = urlParams.get('campus') || ''; 

    var dataTable = $('#courseEn').DataTable({
        "ajax": {
            "url": courseEnrollReadRoute,
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
            { data: 'progCod' },
            { data: 'progName' },
            { data: 'progAcronym' },
            { 
                data: 'studYear',
                render: function(data, type, full, meta) {
                    return full.studYear + '-' + full.studSec;
                }
            },
            { 
                data: 'studentCount',
                render: function(data, type, full, meta) {
                    if (type === 'display') {
                        return '<strong>' + data + '</strong>';
                    }
                    return data;
                }
            },
            { data: 'maleCount' },
            { data: 'femaleCount' }

        ],
        "createdRow": function (row, data, index) {
            $(row).attr('id', 'tr-' + data.id); 
        }
    });
});