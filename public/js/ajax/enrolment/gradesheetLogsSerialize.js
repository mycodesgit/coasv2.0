$(document).ready(function() {
    var urlParams = new URLSearchParams(window.location.search);
    var schlyear = urlParams.get('schlyear') || ''; 
    var semester = urlParams.get('semester') || '';
    var campus = urlParams.get('campus') || '';

    var dataTable = $('#encodegrdeLogs').DataTable({
        "ajax": {
            "url": studGradeEncodedpersemRoute,
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
        "columns": [
            {data: 'studsID'},
            { 
                data: null,
                render: function(data, type, row) {
                    var firstname = data.fname;
                    var middleInitial = data.mname ? data.mname.substr(0, 1) + '.' : '';
                    var lastNameWithExt = data.lname + (data.ext !== 'N/A' ? ' ' + data.ext : '');
                    return firstname + ' ' + middleInitial + ' ' + lastNameWithExt;
                }
            },
            {data: 'gender'},
            { data: 'datefgrade',
                render: function (data, type, row) {
                    if (type === 'display') {
                        return moment(data).format('MMMM D, YYYY h:mm A');
                    } else {
                        return data;
                    }
                }
            },
            { data: 'datecgrade',
                render: function (data, type, row) {
                    if (type === 'display') {
                        return moment(data).format('MMMM D, YYYY h:mm A');
                    } else {
                        return data;
                    }
                }
            },
            {
                data: null,
                render: function(data, type, row) {
                    return row.sub_name + ' - ' + row.subSec;
                }
            },
            {data: 'fgrade'},
            {data: 'cgrade'},
            {data: 'encodedBy'},
        ],
        "order": [[0, 'desc']],
        "createdRow": function (row, data, index) {
            $(row).attr('id', 'tr-' + data.id); 
        }
    });
    $(document).on('encodedgradeLog', function() {
        dataTable.ajax.reload();
    });
});