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
                // "data": { 
                //     "schlyear": schlyear,
                //     "semester": semester,
                //     "campus": campus
                // }
            },
            destroy: true,
            info: true,
            responsive: true,
            lengthChange: true,
            searching: true,
            paging: true,
            "columns": [
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
                {data: 'progCod'},
                {data: 'campus'},
                {
                    data: 'id',
                    render: function (data, type, row) {
                        if (type === 'display') {

                            let url = preenrollistShowRoute
                                + '?stud_id=' + encodeURIComponent(row.studentID)
                                + '&schlyear=' + encodeURIComponent(row.schlyear)
                                + '&semester=' + encodeURIComponent(row.semester);

                            return `
                                <a href="${url}" class="btn btn-sm btn-primary">
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
        $(document).on('fundAdded', function() {
            dataTable.ajax.reload();
        });
    });
</script>