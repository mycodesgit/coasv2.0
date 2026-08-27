<script>
    $(document).ready(function() {
        var dataTable = $('#rcntschd').DataTable({
            "ajax": {
                "url": "{{ route('recentschedfetch')}}",
                "type": "GET",
            },
            responsive: false,
            lengthChange: false,
            searching: false,
            paging: true,
            "columns": [
                {
                    data: null,
                    render: function (data, type, row) {
                        return `${row.sub_name} - ${row.subSec}`;
                    },
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        return `${row.lname}, ${row.fname}`;
                    },
                },
                {data: 'room_name'},
                {
                    data: null,
                    render: function (data, type, row) {
                        return `${row.schedday}, ${row.start_time}, ${row.end_time} `;
                    },
                },
                {data: 'postedBy'},
            ],
            "createdRow": function (row, data, index) {
                $(row).attr('id', 'tr-' + data.id); 
            }
        });
    });
</script>