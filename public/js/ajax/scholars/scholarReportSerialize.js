$(document).ready(function() {
    var urlParams = new URLSearchParams(window.location.search);
    var schlyear = urlParams.get('schlyear') || ''; 
    var semester = urlParams.get('semester') || '';
    var campus = urlParams.get('campus') || '';

    var dataTable = $('#studreportscholar').DataTable({
        "ajax": {
            "url": studschreportReadRoute,
            "type": "GET",
            "data": { 
                "schlyear": schlyear,
                "semester": semester,
                "campus": campus
            }
        },
        responsive: true,
        lengthChange: true,
        searching: false,
        paging: true,
        buttons: [
                'excel', 'pdf'
            ],
        "columns": [
            { data: 'lname', render: function(data, type, row) {
                return row.lname + ', ' + row.fname + ' ' + row.mname;
            } },
            { data: 'ext' },
            { data: 'studentID' },
            { data: 'bday' },
            { data: 'address' },
            { data: 'brgy' },
            { data: 'city' },
            { data: 'province' },
            { data: 'region' },
            { data: 'zcode' },
            { data: 'pehcourse' },
            { data: 'studYear' },
            { data: 'scholar_name' },
            { data: 'stud_father' },
            { data: 'stud_mother' },
            { data: 'gender' },
            { data: 'contact' },
            { data: 'email' },
        ],
        dom: 'Bfrtip'
    }).buttons().container().appendTo('#studreportscholar_wrapper .col-md-6:eq(0)');
});