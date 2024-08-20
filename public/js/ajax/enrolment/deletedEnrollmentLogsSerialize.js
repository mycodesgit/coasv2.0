$(document).ready(function() {
    var urlParams = new URLSearchParams(window.location.search);
    var schlyear = urlParams.get('schlyear') || ''; 
    var semester = urlParams.get('semester') || '';
    var campus = urlParams.get('campus') || ''; 

    var dataTable = $('#deletedlogstable').DataTable({
        "ajax": {
            "url": studDeleteEnrollogsRoute,
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
            {data: 'delstudentID'},
            {data: 'lname'},
            {data: 'fname'},
            {data: 'mname'},
            {data: 'ext'},
            {data: 'gender'},
            {data: 'delemployeename'},
        ],
        "createdRow": function (row, data, index) {
            $(row).attr('id', 'tr-' + data.id); 
        }
    });
    $(document).on('delLog', function() {
        dataTable.ajax.reload();
    });
});