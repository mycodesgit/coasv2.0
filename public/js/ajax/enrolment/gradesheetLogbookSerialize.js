toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right"
};
$(document).ready(function() {
    var urlParams = new URLSearchParams(window.location.search);
    var schlyear = urlParams.get('schlyear') || ''; 
    var semester = urlParams.get('semester') || '';
    var collegeabbr = urlParams.get('collegeabbr') || '';

    var dataTable = $('#gdesheetloglist').DataTable({
        "ajax": {
            "url": gradesheetlogbookReadRoute,
            "type": "GET",
            "data": { 
                "schlyear": schlyear,
                "semester": semester,
                "collegeabbr": collegeabbr,
            }
        },
        responsive: true,
        lengthChange: true,
        searching: true,
        paging: true,
        buttons: [
                'excel', 'pdf'
            ],
        "columns": [
            {data: 'schlyear'},
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
            { 
                data: null,
                render: function(data, type, row) {
                    var firstname = data.fname  || '';
                    var lastName = data.lname  || '';
                    return firstname + ' ' + lastName;
                }
            },
            {data: 'sub_name'},
            {data: 'sub_title'},
            {data: 'subSec'},
            {data: 'dept'},
            { data: 'lastupdated',
                render: function (data, type, row) {
                    if (type === 'display') {
                        return moment(data).format('MMMM D, YYYY');
                    } else {
                        return data;
                    }
                }
            },

        ],
        "createdRow": function (row, data, index) {
            $(row).attr('id', 'tr-' + data.soid); 
        },
        dom: 'Bfrtip'
    }).buttons().container().appendTo('#gdesheetloglist_wrapper .col-md-6:eq(0)');
    $(document).on('subjOffAdded', function() {
        dataTable.ajax.reload();
    });
});


