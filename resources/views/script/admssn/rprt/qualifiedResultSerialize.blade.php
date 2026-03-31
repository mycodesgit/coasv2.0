<script>
    $(document).ready(function() {
        var urlParams = new URLSearchParams(window.location.search);
        var year = urlParams.get('year') || ''; 
        var campus = urlParams.get('campus') || ''; 
        var date = urlParams.get('date') || ''; 

        var dataTable = $('#allQualifiedResult').DataTable({
            "ajax": {
                "url": allApplicantQualifiedResultRoute,
                "type": "GET",
                "data": { 
                    "year": year,
                    "campus": campus,
                    "date": date
                }
            },
            responsive: true,
            lengthChange: true,
            searching: true,
            paging: true,
            buttons: [
                'excel'
            ],
            "columns": [
                { 
                    data: null,
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    },
                    //title: '#'
                },
                {data: 'admission_id'},
                { 
                    data: null,
                    render: function(data, type, row) {
                        var firstname = data.fname;
                        var middleInitial = data.mname ? data.mname.substr(0, 1) + '.' : '';
                        var ext = (data.ext && data.ext !== 'N/A') ? ' ' + data.ext : '';
                        var lastNameWithExt = data.lname + ext;
                        return firstname + ' ' + middleInitial + ' ' + lastNameWithExt;
                    }
                },
                { 
                    data: null,
                    render: function(data, type, row) {
                        if (data.type == 1) {
                            return 'New';
                        } else if (data.type == 2) {
                            return 'Returnee';
                        } else if (data.type == 3) {
                            return 'Transferee';
                        } else {
                            return '';
                        }
                    }
                },
                {data: 'contact'},
                {data: 'percentile'},
                {data: 'strand'},
            ],
            "createdRow": function (row, data, index) {
                $(row).attr('id', 'tr-' + data.id); 
            },
            dom: 'Bfrtip'
        }).buttons().container().appendTo('#appsreplistTable_wrapper .col-md-6:eq(0)');
    });
</script>