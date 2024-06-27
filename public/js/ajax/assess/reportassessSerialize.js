toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right"
};
$(document).ready(function() {
    var urlParams = new URLSearchParams(window.location.search);
    var schlyear = urlParams.get('schlyear') || ''; 
    var semester = urlParams.get('semester') || ''; 
    var category = urlParams.get('category') || ''; 

    var dataTable = $('#reportAssessUndergrad').DataTable({
        "ajax": {
            "url": studundergradReadRoute,
            "type": "GET",
            "data": { 
                "schlyear": schlyear,
                "semester": semester,
                "category": category,
            }
        },
        responsive: true,
        lengthChange: true,
        searching: true,
        paging: true,
        "columns": [
            {data: 'studentID'},
            { 
                data: null,
                render: function(data, type, row) {
                    var firstname = data.fname;
                    var middleInitial = data.mname ? data.mname.substr(0, 1) + '.' : '';
                    var lastNameWithExt = data.lname + (data.ext !== 'N/A' ? ' ' + data.ext : 'Null');
                    return firstname + ' ' + middleInitial + ' ' + lastNameWithExt;
                }
            },
            {data: 'schlyear'},
            {data: 'semester'},
            {
                data: 'totalamount',
                render: function(data, type, row) {
                    return parseFloat(data).toFixed(2);
                }
            },
            {
                data: 'amountpaid',
                render: function(data, type, row) {
                    return parseFloat(data).toFixed(2);
                }
            },
            {
                data: null,
                render: function(data, type, row) {
                    var totalamount = parseFloat(row.totalamount);
                    var amountpaid = parseFloat(row.amountpaid);
                    var balance = totalamount - amountpaid;
                    return balance.toFixed(2);
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



