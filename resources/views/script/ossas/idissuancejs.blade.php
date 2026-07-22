<script>
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right"
    };
    $(document).ready(function() {
        var urlParams = new URLSearchParams(window.location.search);
        var schlyear = urlParams.get('schlyear') || ''; 
        var semester = urlParams.get('semester') || ''; 

        var dataTable = $('#idIssuanceLogTable').DataTable({
            "ajax": {
                "url": "{{ route('id-issuance-log.show') }}",
                "type": "GET",
                "data": { 
                    "schlyear": schlyear,
                    "semester": semester,
                }
            },
            destroy: true,
            info: true,
            responsive: true,
            lengthChange: true,
            searching: true,
            paging: true,
            "columns": [
                {data: 'stdntid'},
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
                {data: 'contactperson'},
                {data: 'contactpersonno'},
                { data: 'issuance_date',
                    render: function (data, type, row) {
                        if (type === 'display') {
                            return moment(data).format('MMM D, YYYY hh:mm A');
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