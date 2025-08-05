toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right"
};
$(document).ready(function() {
    var urlParams = new URLSearchParams(window.location.search);
    var schlyear = urlParams.get('schlyear') || ''; 
    var semester = urlParams.get('semester') || '';
    var campus = urlParams.get('campus') || ''; 

    var dataTable = $('#rotctab').DataTable({
        "ajax": {
            "url": rotcnstpReadRoute,
            "type": "GET",
            "data": { 
                "schlyear": schlyear,
                "semester": semester,
                "campus": campus,
            }
        },
        destroy: true,
        info: true,
        responsive: false,
        lengthChange: true,
        searching: true,
        paging: true,
        buttons: [
                'excel', 'pdf'
            ],
        "columns": [
            {
                data: null,
                render: function (data, type, row, meta) {
                    return meta.row + 1;
                }
            },
            {data: 'studID'},
            {data: 'schlyear'},
            {data: 'sub_name'},
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
            {data: 'progAcronym'},
            {data: 'email'},
            {data: 'contact'},
        ],
        "createdRow": function (row, data, index) {
            $(row).attr('id', 'tr-' + data.id); 
        },
        dom: 'Bfrtip'
    }).buttons().container().appendTo('#rotctab_wrapper .col-md-6:eq(0)');
    $(document).on('nstpAdded', function() {
        dataTable.ajax.reload();
    });
});