<script>
    $(document).ready(function() {
        var dataTable = $('#countstuddash').DataTable({
            "ajax": {
                "url": studCountRoute,
                "type": "GET",
            },
            responsive: false,
            lengthChange: false,
            searching: false,
            paging: false,
            info: false,
            "columns": [
                {
                    data: null,
                    render: function (data) {
                        return data.subSec + ' - ' + data.sub_name;
                    }
                },
                {data: 'countsub'},
            ],
            "createdRow": function (row, data, index) {
                $(row).attr('id', 'tr-' + data.id); 
            }
        });
    });
</script>