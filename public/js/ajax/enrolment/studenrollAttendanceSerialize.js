var currentDataTable = null;
var urlParams = new URLSearchParams(window.location.search);
var schlyear = urlParams.get('schlyear') || '';
var semester = urlParams.get('semester') || '';
var campus = urlParams.get('campus') || '';

$(document).ready(function() {

    var dataTable = $('#attendanceTable').DataTable({
        "ajax": {
            "url": attendanceReadRoute,
            "type": "GET",
            "data": {
                "schlyear": schlyear,
                "semester": semester,
                "campus": campus
            }
        },
        destroy: true,
        info: true,
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
                    currentDataTable = dt;
                    generateBatchButtons();

                    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                        var myModal = new bootstrap.Modal(document.getElementById('batchDownloadModal'));
                        myModal.show();
                    } else {
                        $('#batchDownloadModal').modal('show');
                    }
                }
            }
        ],
        "columns": [
            {
                data: null,
                render: function(data, type, row) {
                    if (row.isType === 'No') {
                        return row.sub_name; // Only show sub_name if isType is 'No'
                    }
                    return row.sub_name + ' - ' + row.isType; // Show both if isType is not 'No'
                }
            },
            {data: 'sub_title'},
            {data: 'subSec'},
            // {data: 'countstud'},
            {
                data: 'sid',
                render: function(data, type, row) {
                    if (type === 'display') {
                        // Log the data for debugging
                        //console.log("Rendering row with data:", data);

                        var schlyearValue = window.schlyear;
                        var semesterValue = window.semester;
                        var routeWithParams = decodeURIComponent(routeTemplate)
                            .replace(':id', data)
                            .replace(':schlyear', schlyearValue)
                            .replace(':semester', semesterValue);

                        // Log the final route for debugging
                        //console.log("Generated route: ", routeWithParams);

                        var editLink = '<a href="' + routeWithParams + '" class="btn btn-success btn-sm btn-studview text-light" target="_blank">' +
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
    $(document).on('studAttendanceAdded', function() {
        dataTable.ajax.reload();
    });
});

// Function to dynamically render batch buttons based on selected chunk size
function generateBatchButtons() {
    if (!currentDataTable) return;

    var totalRecords = currentDataTable.rows({ search: 'applied' }).count();
    var chunkSize = parseInt($('#chunkSizeSelect').val()) || 10;
    var container = $('#batchButtonsContainer').empty();

    if (totalRecords === 0) {
        container.html('<span class="text-danger">No records found to download.</span>');
        return;
    }

    for (var offset = 0; offset < totalRecords; offset += chunkSize) {
        var start = offset + 1;
        var end = Math.min(offset + chunkSize, totalRecords);

        var downloadUrl = bulkAttendancePdfRoute +
            "?schlyear=" + encodeURIComponent(schlyear) +
            "&semester=" + encodeURIComponent(semester) +
            "&campus=" + encodeURIComponent(campus) +
            "&offset=" + offset +
            "&limit=" + chunkSize;

        var btnHtml = '<a href="' + downloadUrl + '" class="btn btn-outline-primary m-1" target="_blank">' +
            '<i class="fas fa-download mr-1"></i> Records ' + start + ' - ' + end + '</a>';

        container.append(btnHtml);
    }
}

// Re-generate buttons whenever the user changes the batch size dropdown
$(document).on('change', '#chunkSizeSelect', function() {
    generateBatchButtons();
});
