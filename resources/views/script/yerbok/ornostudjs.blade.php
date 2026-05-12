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
                        var firstname = data.fname.toUpperCase(); // Convert first name to uppercase
                        var middleInitial = data.mname ? data.mname.substr(0, 1).toUpperCase() + '.' : ''; // Get middle initial and add period
                        var lastName = data.lname.toUpperCase(); // Convert last name to uppercase
                        var extension = data.ext && data.ext !== 'N/A' ? data.ext : ''; // Check if extension is not null and not 'N/A'
                        return lastName + ', ' + firstname + ' ' + middleInitial + (extension ? ' ' + extension : ''); // Return formatted string
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