toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right"
};
$(document).ready(function() {
    var dataTable = $('#nstptable').DataTable({
        "ajax": {
            "url": nstpReadRoute,
            "type": "GET",
        },
        destroy: true,
        info: true,
        responsive: true,
        lengthChange: true,
        searching: true,
        paging: true,
        "columns": [
            {
                data: null,
                render: function (data, type, row, meta) {
                    return meta.row + 1;
                }
            },
            {data: 'schlyear'},
            {data: 'semester'},
            {data: 'region'},
            {                            
                data: null,
                render: function () {
                    return '';
                }
            },
            {data: 'lname'},
            {data: 'fname'},
            {data: 'ext'},
            {data: 'mname'},
            {data: 'bday'},
            {data: 'gender'},
            {data: 'brgy'},
            {data: 'city'},
            {data: 'province'},
            {
                data: null,
                render: function () {
                    return 'CPSU';
                }
            },
            {
                data: null,
                render: function () {
                    return '6058';
                }
            },
            {
                data: null,
                render: function () {
                    return 'SUC';
                }
            },
            {                            
                data: null,
                render: function () {
                    return '';
                }
            },
            {
                data: null,
                render: function () {
                    return 'course';
                }
            },
            {data: 'email'},
            {data: 'contact'},
        ],
        "createdRow": function (row, data, index) {
            $(row).attr('id', 'tr-' + data.id); 
        }
    });
    $(document).on('nstpAdded', function() {
        dataTable.ajax.reload();
    });
});