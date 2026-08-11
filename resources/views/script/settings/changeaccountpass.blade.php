<script>
    $(document).ready(function () {
        $('#changePasswordForm').on('submit', function (e) {
            e.preventDefault();

            let form = $(this);
            let actionUrl = form.attr('action');
            let submitBtn = $('#btnSavePassword');
            let alertBox = $('#passwordAlert');

            // Reset error feedback states
            $('.form-control').removeClass('is-invalid');
            $('.invalid-feedback').text('');
            alertBox.addClass('d-none').removeClass('alert-danger alert-success').text('');

            // Disable button & show spinner state
            submitBtn.prop('disabled', true);
            $('#btnText').text('Saving...');

            $.ajax({
                url: actionUrl,
                type: 'POST',
                data: form.serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.status === 200) {
                        alertBox.removeClass('d-none').addClass('alert alert-success').text(response.message);
                        form[0].reset();

                        // Automatically close modal after 1.5 seconds
                        setTimeout(function () {
                            $('#modal-changepassword').modal('hide');
                            alertBox.addClass('d-none');
                        }, 1500);
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        
                        if (errors.password) {
                            $('#password').addClass('is-invalid');
                            $('#password_error').text(errors.password[0]);
                        }
                        if (errors.password_confirmation) {
                            $('#password_confirmation').addClass('is-invalid');
                            $('#password_confirmation_error').text(errors.password_confirmation[0]);
                        }
                    } else {
                        alertBox.removeClass('d-none').addClass('alert alert-danger').text('An unexpected error occurred. Please try again.');
                    }
                },
                complete: function () {
                    submitBtn.prop('disabled', false);
                    $('#btnText').text('Save');
                }
            });
        });

        // Reset errors when modal closes
        $('#modal-changepassword').on('hidden.bs.modal', function () {
            $('#changePasswordForm')[0].reset();
            $('.form-control').removeClass('is-invalid');
            $('.invalid-feedback').text('');
            $('#passwordAlert').addClass('d-none');
        });
    });
</script>