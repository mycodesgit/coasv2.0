$(document).ready(function() {
    var urlParams = new URLSearchParams(window.location.search);
    var lname = urlParams.get('lname') || ''; 
    var studentID = urlParams.get('studentID') || '';
    var query = urlParams.get('query') || '';

    var dataTable = $('#studhisTable').DataTable({
        "ajax": {
            "url": historyReadRoute,
            "type": "GET",
        },
        info: true,
        responsive: true,
        lengthChange: true,
        searching: true,
        paging: true,
        "columns": [
            {data: 'studentID'},
            {data: 'lname'},
        ],
        "createdRow": function (row, data, index) {
            $(row).attr('id', 'tr-' + data.id); 
        }
    });
    $(document).on('unischcatAdded', function() {
        dataTable.ajax.reload();
    });
});