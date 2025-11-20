<script>
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right"
    };
    $(document).ready(function() {
        var dataTable = $('#waitpreTable').DataTable({
            "ajax": {
                "url": preenrollistReadRoute,
                "type": "GET",
            },
            destroy: true,
            info: false,
            responsive: true,
            lengthChange: false,
            searching: false,
            paging: false,
            "columns": [
                { data: 'created_ats',
                    render: function (data, type, row) {
                        if (type === 'display') {
                            return moment(data).format('MMM D, YYYY');
                        } else {
                            return data;
                        }
                    }
                },
                {data: 'course'},
                {data: 'status',
                        render: function(data, type, row) {
                        switch(parseInt(data)) {
                            case 1:
                                return '<span class="badge badge-warning">Pending</span>';
                            case 2:
                                return '<span class="badge badge-info">Submitted</span>';
                            default:
                                return '<span class="badge badge-secondary">Unknown Status</span>';
                        }
                    },
                },
            ],
            "createdRow": function (row, data, index) {
                $(row).attr('id', 'tr-' + data.id); 
            }
        });
        setInterval(function() {
            dataTable.ajax.reload(null, false); // false = keeps current page
        }, 10000);
    });
</script>