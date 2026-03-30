toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right"
};
$(document).ready(function() {
    var urlParams = new URLSearchParams(window.location.search);
    var category = urlParams.get('category') || ''; 
    var schlyear = urlParams.get('schlyear') || '';
    var semester = urlParams.get('semester') || '';
    var lastId = 0;

    var dataTable = $('#reportAssessUndergrad').DataTable({
        "ajax": {
            "url": studundergradReadRoute,
            "type": "GET",
            data: function(d) {
                d.category = category;
                d.schlyear = schlyear;
                d.semester = semester;
                d.last_id = lastId; 
            },
            dataSrc: function(json) {
                if(json.length > 0) {
                    lastId = json[json.length - 1].studID; // update for next batch
                }
                return json;
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
            {data: 'studID'},
            { 
                data: null,
                render: function(data, type, row) {
                    var firstname = data.fname;
                    var middleInitial = data.mname ? data.mname.substr(0, 1) + '.' : '';
                    // Only display ext if it's not null, not 'N/A', and not empty
                    var ext = (data.ext && data.ext !== 'N/A') ? ' ' + data.ext : '';
                    var lastNameWithExt = data.lname + ext;
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
        },
        dom: 'Bfrtip'
    });
    setInterval(function() {
        dataTable.ajax.reload(null, false); // reload only new data
    }, 3 * 60 * 1000);
});



