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
                {
                    data: 'semester',
                    render: function(data) {
                        if (data == 1) {
                            return '1st Sem';
                        } else if (data == 2) {
                            return '2nd Sem';
                        } else if (data == 3) {
                            return 'Summer';
                        } else {
                            return '';
                        }
                    }
                },
                {
                    data: 'issued_at',
                    render: function(data) {
                        if (data) {
                            var releaseDate = new Date(data).toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' });
                            return '<span class="badge bg-success"><i class="fas fa-check-circle"></i> Released (' + releaseDate + ')</span>';
                        }
                        return '<span class="badge bg-warning text-dark"><i class="fas fa-clock"></i> Pending</span>';
                    }
                },
                {
                    data: null,
                    render: function(data) {
                        var fullName = data.fname + ' ' + data.lname;
                        return '<button type="button" class="btn btn-sm btn-success btn-release-yearbook" ' +
                            'data-studid="' + data.studID + '" ' +
                            'data-name="' + fullName + '">' +
                            '<i class="ti ti-hand-grab"></i> Release' +
                            '</button>';
                    }
                }
            ],
            "createdRow": function (row, data, index) {
                $(row).attr('id', 'tr-' + data.id); 
            },
            // dom: 'Bfrtip'
        });
        $(document).on('studpayed', function() {
            dataTable.ajax.reload();
        });
    });

    $(document).on('click', '.btn-release-yearbook', function() {
        var studID = $(this).data('studid');
        var studName = $(this).data('name');

        $('#modalStudID').val(studID);
        $('#modalStudentName').val(studID + ' - ' + studName);
        $('#releaseYearbookModal').modal('show');
    });

    $('#releaseYearbookForm').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: issueYearbookRoute,
            type: "POST",
            data: formData,
            success: function(response) {
                if(response.success) {
                    toastr.success(response.message);
                    $('#releaseYearbookModal').modal('hide');
                    $(document).trigger('studpayed');
                    $('#releaseYearbookForm')[0].reset();
                    
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                var msg = xhr.responseJSON ? xhr.responseJSON.message : 'Error releasing yearbook.';
                toastr.error(msg);
            }
        });
    });
</script>