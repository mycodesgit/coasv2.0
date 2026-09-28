<script>
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right"
    };
    $(document).ready(function() {
        $('#transacCategory').submit(function(event) {
            event.preventDefault();
            var formData = $(this).serialize();

            $.ajax({
                url: selectQueueCatRoute,
                type: "POST",
                data: formData,
                success: function(response) {
                    if(response.success) {
                        toastr.success(response.message);
                        console.log(response);
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
    });
    $(document).ready(function () {
        $('#counterStatusSelect').on('change', function () {
            let status = $(this).val();
            let queueUserId = $('#queueUserId').val();

            // 1. Immediately toggle the UI sections smoothly
            $('.status-container').hide();

            if (status == '1') {
                $('#status-closed').fadeIn();
            } else if (status == '2') {
                $('#status-open').fadeIn();
            } else if (status == '3') {
                $('#status-paused').fadeIn();
            }

            // 2. Send AJAX request to update database
            $.ajax({
                url: selectQueueStatRoute,
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: queueUserId,
                    counterstatus: status
                },
                success: function (response) {
                    if (response.success) {
                        console.log('Status updated successfully');
                    }
                },
                error: function (xhr) {
                    alert('Failed to update status. Please try again.');
                }
            });
        });
    });
</script>