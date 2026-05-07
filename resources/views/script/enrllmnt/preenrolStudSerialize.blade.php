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
            responsive: false,
            lengthChange: false,
            searching: false,
            paging: false,
            "columns": [
                {data: 'status',
                        render: function(data, type, row) {
                        switch(parseInt(data)) {
                            case 1:
                                return '<span class="badge bg-warning">Pending in College/Department</span>';
                            case 2:
                                return '<span class="badge bg-info">Submitted</span>';
                            default:
                                return '<span class="badge bg-secondary">Unknown Status</span>';
                        }
                    },
                },
                {data: 'course'},
                { data: 'created_ats',
                    render: function (data, type, row) {
                        if (type === 'display') {
                            return moment(data).format('MMM D, YYYY');
                        } else {
                            return data;
                        }
                    }
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