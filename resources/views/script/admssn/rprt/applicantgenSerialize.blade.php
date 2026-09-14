<script>
    $(document).ready(function() {
        var urlParams = new URLSearchParams(window.location.search);
        var year = urlParams.get('year') || ''; 
        var campus = urlParams.get('campus') || ''; 
        var strand = urlParams.get('strand') || ''; 

        // Build route URL with current URL query parameters
        var bulkDownloadUrl = "{{ route('applicants.reports.bulkDownloadPdf') }}" + 
            "?year=" + encodeURIComponent(year) + 
            "&campus=" + encodeURIComponent(campus) + 
            "&strand=" + encodeURIComponent(strand);

        var dataTable = $('#appsreplistTable').DataTable({
            "ajax": {
                "url": allApplicantRoute,
                "type": "GET",
                "data": { 
                    "year": year,
                    "campus": campus,
                    "strand": strand
                }
            },
            responsive: true,
            lengthChange: true,
            searching: true,
            paging: true,
            buttons: [
                'excel',
                {
                    text: '<i class="fas fa-file-archive mr-1"></i> Bulk PDF Download',
                    className: 'btn btn-primary',
                    action: function (e, dt, node, config) {
                        // Get total filtered records dynamically from DataTables
                        var totalRecords = dt.rows({ search: 'applied' }).count();
                        var chunkSize = 100;
                        var container = $('#batchButtonsContainer').empty();

                        if (totalRecords === 0) {
                            alert('No records found to download.');
                            return;
                        }

                        // Generate batch range buttons
                        for (var offset = 0; offset < totalRecords; offset += chunkSize) {
                            var start = offset + 1;
                            var end = Math.min(offset + chunkSize, totalRecords);
                            
                            var downloadUrl = "{{ route('applicants.reports.bulkDownloadPdf') }}" +
                                "?year=" + encodeURIComponent(year) +
                                "&campus=" + encodeURIComponent(campus) +
                                "&strand=" + encodeURIComponent(strand) +
                                "&offset=" + offset +
                                "&limit=" + chunkSize;

                            var btnHtml = '<a href="' + downloadUrl + '" class="btn btn-outline-primary m-1">' +
                                'Records ' + start + ' - ' + end + '</a>';
                            
                            container.append(btnHtml);
                        }

                        $('#batchDownloadModal').modal('show');
                    }
                }
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
                {data: 'campus'},
                { 
                    data: null,
                    render: function(data, type, row) {
                        return data.lstsch_attended ? data.lstsch_attended : data.suc_lst_attended;
                    }
                },
                {
                    data: 'id',
                    render: function(data, type, row) {
                        if (type === 'display') {
                            var buttons = '<button type="button" class="btn btn-sm btn-warning btn-formsview mr-1" data-id="' + row.id + '" data-toggle="tooltip" data-placement="top" title="View Forms"><i class="fas fa-file-pdf"></i></button>&nbsp;';
                            return buttons;
                        } else {
                            return data;
                        }
                    },
                },
            ],
            "createdRow": function (row, data, index) {
                $(row).attr('id', 'tr-' + data.id); 
            },
            dom: 'Bfrtip'
        }).buttons().container().appendTo('#appsreplistTable_wrapper .col-md-6:eq(0)');
    });

    $(document).on('click', '.btn-formsview', function () {
        var id = $(this).data('id');

        $('#viewAppAdmssionSlipModal').modal('show');
        $('#modalContent').html('<div class="text-center">Loading...</div>');

        $.ajax({
            url: applicantViewAdSlipRoute + '/' + id,
            type: 'GET',
            success: function (response) {
                $('#modalContent').html(response);
            },
            error: function () {
                $('#modalContent').html('<div class="alert alert-danger">Failed to load data.</div>');
            }
        });
    });
</script>