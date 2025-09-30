<script>
    $(document).ready(function() {
        var urlParams = new URLSearchParams(window.location.search);
        var year = urlParams.get('year') || ''; 
        var campus = urlParams.get('campus') || ''; 
        var date = urlParams.get('date') || ''; 

        var dataTable = $('#appsschedlistTable').DataTable({
            "ajax": {
                "url": allApplicantSchedRoute,
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
                {data: 'contact'},
                { 
                    data: null,
                    render: function(data, type, row) {
                        if (data.date && data.time) {
                            // Format date and time using JavaScript to match 'F j, Y g:i A'
                            var dateObj = new Date(data.date + 'T' + data.time);
                            var options = { 
                                year: 'numeric', 
                                month: 'long', 
                                day: 'numeric', 
                                hour: 'numeric', 
                                minute: '2-digit', 
                                hour12: true 
                            };
                            return dateObj.toLocaleString('en-US', options);
                        }
                        return '';
                    }
                },
                {data: 'venue'},
                {data: 'campus'},
            ],
            "createdRow": function (row, data, index) {
                $(row).attr('id', 'tr-' + data.id); 
            }
        });
    });
</script>