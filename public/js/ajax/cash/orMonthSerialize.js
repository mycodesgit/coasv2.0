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
        info: false,
        responsive: true,
        lengthChange: false,
        searching: false,
        paging: false,
        "columns": [
            {data: 'datepaid'},
            {data: 'orno'},
            {data: 'studID'},
            { 
                data: null,
                render: function(data, type, row) {
                    var firstname = data.fname;
                    var middleInitial = data.mname ? data.mname.substr(0, 1) + '.' : '';
                    var lastNameWithExt = data.lname + (data.ext !== 'N/A' ? ' ' + data.ext : '');
                    return firstname + ' ' + middleInitial + ' ' + lastNameWithExt;
                }
            },
            {data: 'total_amount'},
        ],
        "createdRow": function (row, data, index) {
            $(row).attr('id', 'tr-' + data.id); 
        }
    });
    $(document).on('permonthOR', function() {
        dataTable.ajax.reload();
    });
});