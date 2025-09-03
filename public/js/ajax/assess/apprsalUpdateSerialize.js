toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right"
};
$(document).ready(function() {
    $('#adstudfeesmissing').submit(function(event) {
        event.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: studFeesUpdtCreateRoute,
            type: "POST",
            data: formData,
            success: function(response) {
                if(response.success) {
                    toastr.success(response.message);
                    console.log(response);
                    $(document).trigger('feesstudAdded');
                    $('input[name="amount"]').val('');
                } else {
                    toastr.error(response.message);
                    console.log(response);
                }
            },
            error: function(xhr, status, error, message) {
                var errorMessage = xhr.responseText ? JSON.parse(xhr.responseText).message : 'An error occurred';
                toastr.error(errorMessage);
            }
        });
    });

    var urlParams = new URLSearchParams(window.location.search);
    var stud_id = urlParams.get('stud_id') || ''; 
    var schlyear = urlParams.get('schlyear') || '';
    var semester = urlParams.get('semester') || ''; 
    var category = urlParams.get('category') || ''; 
    var dataTable = $('#curapprsledit').DataTable({
        "ajax": {
            "url": studFeesUpdtReadRoute,
            "type": "GET",
            "data": { 
                "stud_id": stud_id,
                "schlyear": schlyear,
                "semester": semester,
                "category": category,
            }
        },
        destroy: true,
        info: false,
        responsive: true,
        lengthChange: false,
        searching: false,
        paging: false,
        "columns": [
            {data: 'fundID'},
            {data: 'account'},
            {data: 'amount'},
        ],
        "createdRow": function (row, data, index) {
            $(row).attr('id', 'tr-' + data.id); 
        }
    });
    $(document).on('feesstudAdded', function() {
        dataTable.ajax.reload();
    });
});