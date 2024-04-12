$(document).ready(function() {
    var urlParams = new URLSearchParams(window.location.search);
    var schlyear = urlParams.get('schlyear') || ''; 
    var semester = urlParams.get('semester') || '';
    var campus = urlParams.get('campus') || '';

    var dataTable = $('#schstud').DataTable({
        "ajax": {
            "url": studschReadRoute,
            "type": "GET",
            "data": { 
                "schlyear": schlyear,
                "semester": semester,
                "campus": campus
            }
        },
        responsive: true,
        lengthChange: true,
        searching: true,
        paging: true,
        "columns": [
            { data: 'lname', render: function(data, type, row) {
                return row.lname + ', ' + row.fname;
            } },
            { data: 'studentID' },
            { data: null, render: function(data, type, row) {
                return row.progAcronym + ' ' + row.studYear + '-' + row.studSec;
            } },
            { data: 'scholar_name' },
            { data: 'scholar_sponsor' },
            { data: 'chedsch_name' },
            { data: 'unisch_name' },
            { data: 'amount', render: $.fn.dataTable.render.number(',', '.', 2) },
        ],
        "columnDefs": [
            {
                "targets": [3, 4, 5, 6],
                "render": function(data, type, row) {
                    return '<div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 15ch;">' + data + '</div>';
                }
            }
        ]
    });
});
