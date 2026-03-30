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
        }
    });
    function loadNextBatch() {
        var urlParams = new URLSearchParams(window.location.search);
        var category = urlParams.get('category') || '';

        $.ajax({
            url: studundergradReadRoute,
            type: 'GET',
            data: { 
                category: category,
                last_id: lastId
            },
            success: function(response) {
                if (response.length === 0) return; // no more rows

                response.forEach(function(student) {
                    dataTable.row.add(student).draw(false);
                    lastId = student.studID; // update lastId
                });
            }
        });
    }

    // Load first batch immediately
    loadNextBatch();

    // Load next batch every 3 minutes
    setInterval(loadNextBatch, 3 * 60 * 1000);
});



