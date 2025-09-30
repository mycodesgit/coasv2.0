<script>
    $(document).ready(function() {
        var urlParams = new URLSearchParams(window.location.search);
        var year = urlParams.get('year') || ''; 
        var campus = urlParams.get('campus') || ''; 

        var dataTable = $('#appsnoschedlistTable').DataTable({
            "ajax": {
                "url": allApplicantnoschedRoute,
                "type": "GET",
                "data": { 
                    "year": year,
                    "campus": campus,
                }
            },
            responsive: true,
            lengthChange: true,
            searching: true,
            paging: true,
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
                        var lastNameWithExt = data.lname + (data.ext !== 'N/A' ? ' ' + data.ext : '');
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
                {data: 'strand'},
                {data: 'email'},
                {data: 'contact'},
                { 
                    data: null,
                    render: function(data, type, row) {
                        return '<span class="badge badge-warning">No Schedule</span>';
                    }
                },
                {data: 'campus'},
            ],
            "createdRow": function (row, data, index) {
                $(row).attr('id', 'tr-' + data.id); 
            }
        });
    });
</script>