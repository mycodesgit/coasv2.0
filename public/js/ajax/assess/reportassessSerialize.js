toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right"
};
$(document).ready(function() {
    var urlParams = new URLSearchParams(window.location.search);
    var category = urlParams.get('category') || ''; 

    var dataTable = $('#reportAssessUndergrad').DataTable({
        "ajax": {
            "url": studundergradReadRoute,
            "type": "GET",
            "data": { 
                "category": category,
            }
        },
        responsive: true,
        lengthChange: true,
        searching: true,
        paging: true,
        "columns": [
            {data: 'stud_id'},
            { 
                data: null,
                render: function(data, type, row) {
                    var firstname = data.fname;
                    var middleInitial = data.mname ? data.mname.substr(0, 1) + '.' : '';
                    var lastNameWithExt = data.lname + (data.ext !== 'N/A' ? ' ' + data.ext : 'Null');
                    return firstname + ' ' + middleInitial + ' ' + lastNameWithExt;
                }
            },
            {
                data: 'totalamount',
                render: function(data, type, row) {
                    return parseFloat(data).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                }
            },
            {
                data: 'amountpaid',
                render: function(data, type, row) {
                    return parseFloat(data).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                }
            },
            {
                data: null,
                render: function(data, type, row) {
                    var totalamount = parseFloat(row.totalamount || 0);
                    var amountpaid = parseFloat(row.amountpaid || 0);
                    var balance = totalamount - amountpaid;
                    return '<strong>' + balance.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</strong>';
                }
            }
        ],
        "createdRow": function (row, data, index) {
            $(row).attr('id', 'tr-' + data.id); 
        }
    });
    $(document).on('ustudAssess', function() {
        dataTable.ajax.reload();
    });
});



