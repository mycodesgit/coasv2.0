<script>
    $(document).ready(function() {
        var urlParams = new URLSearchParams(window.location.search);
        var schlyear = urlParams.get('schlyear') || ''; 
        var semester = urlParams.get('semester') || '';
        var campus = urlParams.get('campus') || ''; 

        var dataTable = $('#studpaidlistTable').DataTable({
            "ajax": {
                "url": ornostudReadRoute,
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
            // buttons: [
            //         'excel', 'pdf'
            //     ],
            "columns": [
                {data: 'orno'},
                {data: 'account'},
                {data: 'amountpaid'},
                {
                    data: 'datepaid',
                    render: function(data, type, row) {
                        if (!data) return '';
                        var date = new Date(data);
                        var options = { year: 'numeric', month: 'short', day: '2-digit' };
                        return date.toLocaleDateString('en-US', options);
                    }
                },
                {data: 'studID'},
                { 
                    data: null,
                    render: function(data, type, row) {
                        var firstname = data.fname;
                        var middleInitial = data.mname ? data.mname.substr(0, 1) + '.' : '';
                        // Only display ext if it's not null, not 'N/A', and not empty
                        var ext = (data.ext && data.ext !== 'N/A') ? ' ' + data.ext : '';
                        var lastNameWithExt = data.lname + ext;
                        return firstname + ' ' + middleInitial + ' ' + lastNameWithExt;
                    }
                },
                {data: 'schlyear'},
                {data: 'semester'},
            ],
            "createdRow": function (row, data, index) {
                $(row).attr('id', 'tr-' + data.id); 
            },
            dom: 'Bfrtip'
        }).buttons().container().appendTo('#courseEn_wrapper .col-md-6:eq(0)');
        $(document).on('studenrlld', function() {
            dataTable.ajax.reload();
        });
    });
</script>