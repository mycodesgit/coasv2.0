toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right"
};

$(document).ready(function() {
    var urlParams = new URLSearchParams(window.location.search);
    var category = urlParams.get('category') || '';
    var semester = urlParams.get('semester') || '';
    var schlyear = urlParams.get('schlyear') || '';
    var lastId = 0; // track last loaded student

    var dataTable = $('#reportAssessUndergrad').DataTable({
        responsive: true,
        lengthChange: true,
        searching: true,
        paging: true,
        buttons: [
                'excel', 'pdf'
            ],
        columns: [
            {data: 'studID'},
            { 
                data: null,
                render: function(data) {
                    var firstname = data.fname;
                    var middleInitial = data.mname ? data.mname.substr(0,1) + '.' : '';
                    var ext = (data.ext && data.ext !== 'N/A') ? ' ' + data.ext : '';
                    return firstname + ' ' + middleInitial + ' ' + data.lname + ext;
                }
            },
            {
                data: 'totalamount',
                render: function(data) {
                    return parseFloat(data).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                }
            },
            {
                data: 'amountpaid',
                render: function(data) {
                    return parseFloat(data).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                }
            },
            {
                data: null,
                render: function(data) {
                    var total = parseFloat(data.totalamount || 0);
                    var paid = parseFloat(data.amountpaid || 0);
                    return '<strong>' + (total - paid).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</strong>';
                }
            },
        ],
        dom: 'Bfrtip'
    });

    function loadNextBatch() {
        $.ajax({
            url: studundergradReadRoute,
            type: 'GET',
            data: {
                category: category,
                semester: semester,
                schlyear: schlyear,
                last_id: lastId
            },
            success: function(response) {
                if(response.length === 0) return; 

                response.forEach(function(student) {
                    dataTable.row.add(student).draw(false); 
                    lastId = student.studID; 
                });
            }
        });
    }

    loadNextBatch();

    // Load next batch every 30 seconds
    setInterval(loadNextBatch,  30 * 1000);
});