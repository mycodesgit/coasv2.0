toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right"
};
$(document).ready(function() {
    $('#numberad').submit(function(event) {
        event.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: numberCreateRoute,
            type: "POST",
            data: formData,
            success: function(response) {
                if(response.success) {
                    toastr.success(response.message);
                    console.log(response);
                    $(document).trigger('numberAdded');
                    $('input[name="start"]').val('');
                    $('input[name="end"]').val('');
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

    var dataTable = $('#numberTable').DataTable({
        "ajax": {
            "url": numberRoute,
            "type": "GET",
        },
        destroy: true,
        info: true,
        responsive: true,
        lengthChange: true,
        searching: true,
        paging: true,
        "columns": [
            {data: 'queue_number'},
            {data: 'catname'},
            {
                data: 'status', render: function(data, type, row) {
                    var badgeClass = data === 'serving' ? 'bg-success' : 'bg-warning';
                    return '<span class="badge ' + badgeClass + '">' + data + '</span>';
                }
            },
            {data: 'campus'},
        ],
        "createdRow": function (row, data, index) {
            $(row).attr('id', 'tr-' + data.id); 
        }
    });
    $(document).on('numberAdded', function() {
        dataTable.ajax.reload();
    });
});