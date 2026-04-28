<script>
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right"
    };
    $(document).ready(function() {
        var dataTable = $('#extendedstudTable').DataTable({
            "ajax": {
                "url": showRoute,
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
            buttons: [
                'excel', 'pdf'
            ],
            "columns": [
                {data: 'stud_id'},
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
                {data: 'history_count'},
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
                {
                    data: 'id',
                    render: function(data, type, row) {
                        if (type === 'display') {
                            var editLink = '<a href="#" class="btn btn-success btn-sm btn-studhisview text-light"  data-id="' + row.stud_id + '">' +
                                '<i class="fas fa-eye"></i>' +
                                '</a>';
                            return editLink;
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
        });
        $(document).on('extendedAdded', function() {
            dataTable.ajax.reload();
        });
    });

    $(document).ready(function() {
        $('#extendedstudTable').on('click', '.btn-studhisview', function() {
            var studentId = $(this).data('id');
            //var studentName = $(this).data('fname') + ' ' + $(this).data('lname');

            $('#viewStudHisId').val(studentId);
            //$('#studentName').text(studentName);

            $.ajax({
                url: studenhistoryClickReadRoute,
                method: 'GET',
                data: { stud_id: studentId },
                success: function(response) {
                    var historyTable = $('#enrollmentHistoryTable');
                    historyTable.empty();

                    if (response.data.length > 0) {
                        response.data.forEach(function(history) {
                            var semesterText;
                            switch(history.semester) {
                                case 1:
                                    semesterText = '<span class="badge bg-info">1st Sem</span>';
                                    break;
                                case 2:
                                    semesterText = '<span class="badge bg-info">2nd Sem</span>';
                                    break;
                                case 3:
                                    semesterText = '<span class="badge bg-secondary">Summer</span>';
                                    break;
                                default:
                                    semesterText = 'Unknown Semester';
                                    break;
                            }
                            var row = '<tr>' +
                                '<td>' + history.studentID + '</td>' +
                                '<td>' + history.schlyear + '</td>' +
                                '<td>' + semesterText + '</td>' +
                                '<td>' + history.progAcronym + '</td>' +
                                '<td>' + history.studYear + '</td>' +
                                '<td>' + history.studSec + '</td>' +
                                '</tr>';
                            historyTable.append(row);
                        });
                    } else {
                        historyTable.append('<tr><td colspan="5" class="text-center">No enrollment history found.</td></tr>');
                    }

                    $('#viewStudHisModal').modal('show');
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    alert('An error occurred while fetching the enrollment history.');
                }
            });
        });
    });
</script>