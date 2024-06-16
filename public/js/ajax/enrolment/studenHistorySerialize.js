$(document).ready(function() {
    var urlParams = new URLSearchParams(window.location.search);
    var query = urlParams.get('query') || ''; 

    var dataTable = $('#studhisTable').DataTable({
        "ajax": {
            "url": studenhistoryReadRoute,
            "type": "GET",
            "data": { 
                "query": query
            }
        },
        info: true,
        responsive: true,
        lengthChange: false,
        searching: false,
        paging: true,
        "columns": [
            {data: 'stud_id'},
            {data: 'lname'},
            {data: 'fname'},
            {data: 'mname'},
            {data: 'ext'},
            {
            data: 'id',
                render: function(data, type, row) {
                    if (type === 'display') {
                        var editLink = '<a href="#" class="btn btn-primary btn-sm btn-studhisview"  data-id="' + row.stud_id + '" data-fname="' + row.fname + '" data-lname="' + row.lname + '">' +
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
        }
    });

    if (dataTable) {
        console.log("DataTable initialized successfully.");
    } else {
        console.error("DataTable initialization failed. The table element with ID '#studhisTable' was not found.");
    }
});


$(document).ready(function() {
    $('#studhisTable').on('click', '.btn-studhisview', function() {
        var studentId = $(this).data('id');
        var studentName = $(this).data('fname') + ' ' + $(this).data('lname');

        $('#viewStudHisId').val(studentId);
        $('#studentName').text(studentName);

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
                                semesterText = '<span class="badge badge-info">1st Sem</span>';
                                break;
                            case 2:
                                semesterText = '<span class="badge badge-info">2nd Sem</span>';
                                break;
                            case 3:
                                semesterText = '<span class="badge badge-secondary">Summer</span>';
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

