$(document).ready(function() {
    var urlParams = new URLSearchParams(window.location.search);
    var schlyear = urlParams.get('schlyear') || ''; 
    var semester = urlParams.get('semester') || '';

    var dataTable = $('#subofferedlist').DataTable({
        "ajax": {
            "url": subOfferedReadRoute,
            "type": "GET",
            "data": { 
                "schlyear": schlyear,
                "semester": semester,
            }
        },
        responsive: true,
        lengthChange: true,
        searching: true,
        paging: true,
        "columns": [
            {data: 'subCode'},
            {data: 'subSec'},
            {
                data: 'semester',
                render: function(data, type, row) {
                    if (data == 1) {
                        return '1st';
                    } else if (data == 2) {
                        return '2nd';
                    } else if (data == 3) {
                        return 'Summer';
                    } else {
                        return 'Unknown Semester';
                    }
                }
            },
            {data: 'sub_name'},
            {data: 'lecUnit'},
            {data: 'labUnit'},
            {data: 'subUnit'},
            {data: 'maxstud'},
            {
                data: 'fundAccount',
                render: function(data, type, row) {
                    return data ? data : 'No Account';
                }
            }

        ],
        "createdRow": function (row, data, index) {
            $(row).attr('id', 'tr-' + data.id); 
        }
    });
    $(document).on('coaAdded', function() {
        dataTable.ajax.reload();
    });
});

$(document).ready(function() {
    $('#subCode').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var subcode = selectedOption.data('sub-code');
        var lecUnit = selectedOption.data('lec-unit');
        var labUnit = selectedOption.data('lab-unit');
        
        $('#subcode').val(subcode);
        $('#lecUnit').val(lecUnit);
        $('#labUnit').val(labUnit);
        $('#subUnit').val(lecUnit + labUnit);
    });
});


