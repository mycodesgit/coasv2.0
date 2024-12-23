$(document).ready(function() {
    var dataTable = $('#queueMonitor').DataTable({
        "ajax": {
            "url": monitorRoute,
            "type": "GET",
        },
        //destroy: false,
        info: false,
        responsive: false,
        lengthChange: false,
        searching: false,
        paging: false,
        "columns": [
            {data: 'window'},
            {data: 'number'},
        ],
        "createdRow": function (row, data, index) {
            $(row).attr('id', 'tr-' + data.id); 
        }
    });
    setInterval(function () {
        dataTable.ajax.reload(null, false);
    }, 5000);
});