<script>
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right"
    };
    $(document).ready(function() {
        var dataTable = $('#holdTable').DataTable({
            "ajax": {
                "url": preenrollistReadRoute,
                "type": "GET",
            },
            destroy: true,
            info: true,
            responsive: true,
            lengthChange: true,
            searching: true,
            paging: true,
            "columns": [
                { data: 'created_ats',
                    render: function (data, type, row) {
                        if (type === 'display') {
                            return moment(data).format('MMM D, YYYY hh:mm A');
                        } else {
                            return data;
                        }
                    }
                },
                {data: 'studentID'},
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
                {data: 'course'},
                { 
                    data: 'campus',
                    render: function(value) {
                        const map = {
                            "MC": "Main",
                            "VC": "Victorias",
                            "SCC": "San Carlos",
                            "HC": "Hinigaran",
                            "MP": "Moises Padilla",
                            "IC": "Ilog",
                            "CA": "Candoni",
                            "CC": "Cauayan",
                            "SC": "Sipalay",
                            "HinC": "Hinobaan",
                            "VE": "Valladolid"
                        };

                        return map[value] ?? value; // fallback if unknown
                    }
                },
                {data: 'status',
                        render: function(data, type, row) {
                        switch(parseInt(data)) {
                            case 1:
                                return '<span class="badge bg-warning">Pending</span>';
                            case 2:
                                return '<span class="badge bg-info">Submitted</span>';
                            default:
                                return '<span class="badge bg-secondary">Unknown Status</span>';
                        }
                    },
                },
                {
                    data: 'id',
                    render: function (data, type, row) {
                        if (type === 'display') {

                            let url = preenrollistShowRoute
                                + '?stud_id=' + encodeURIComponent(row.studentID)
                                + '&schlyear=' + encodeURIComponent(row.schlyear)
                                + '&semester=' + encodeURIComponent(row.semester);

                            return `
                                <a href="${url}" class="btn btn-sm btn-success text-light">
                                    <i class="fas fa-eye"></i>
                                </a>
                            `;
                        }
                        return data;
                    }
                }
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