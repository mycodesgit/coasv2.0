$(document).ready(function() {
    var dataTable = $('#classRooms').DataTable({
        "ajax": {
            "url": roomsReadRoute,
            "type": "GET",
        },
        responsive: true,
        lengthChange: true,
        searching: true,
        paging: true,
        "columns": [
            {data: 'college_abbr'},
            {data: 'room_name'},
            {data: 'room_capacity'},
            {data: 'campus'},
        ],
        "createdRow": function (row, data, index) {
            $(row).attr('id', 'tr-' + data.id); 
        }
    });
    $(document).on('roomAdded', function() {
        dataTable.ajax.reload();
    });
});

