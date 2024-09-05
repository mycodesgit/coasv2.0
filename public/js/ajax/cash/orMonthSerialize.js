$(document).ready(function() {
    var urlParams = new URLSearchParams(window.location.search);
    var datepaid = urlParams.get('datepaid') || ''; 
    var dataTable = $('#permonthORtable').DataTable({
        "ajax": {
            "url": permonthOrReadRoute,
            "type": "GET",
            "data": { 
                "datepaid": datepaid,
            }
        },
        info: true,
        responsive: true,
        lengthChange: true,
        searching: true,
        paging: true,
        buttons: [
                'excel'
            ],
        "columns": [
            {data: 'datepaid'},
            {data: 'orno'},
            {data: 'studID'},
            {
                data: null,
                render: function(data, type, row) {
                    var firstname = data.fname;
                    var middleInitial = data.mname ? data.mname.substr(0, 1) + '.' : '';
                    var lastName = data.lname;
                    var ext = data.ext && data.ext !== 'N/A' ? ' ' + data.ext : ' ';
                    
                    return lastName + ', ' + firstname + ' ' + middleInitial + ext;
                }
            },
            {data: 'total_amount'},
        ],
        "createdRow": function (row, data, index) {
            $(row).attr('id', 'tr-' + data.id); 
        },
        dom: 'Bfrtip'
    }).buttons().container().appendTo('#permonthORtable_wrapper .col-md-6:eq(0)');
    $(document).on('permonthOR', function() {
        dataTable.ajax.reload();
    });
});