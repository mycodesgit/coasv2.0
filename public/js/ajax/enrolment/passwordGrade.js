$(document).ready(function () {
    $('#submitBtn').click(function () {
        $('#confirmationForm').submit();
    });
});

$(document).ready(function () {
    $('#editBtn').click(function () {
        $('#editConfirmForm').submit();
    });
});

$(document).ready(function () {
    $('#editCompletionBtn').click(function () {
        $('#editCompletionForm').submit();
    });
});
        
$(document).ready(function() {
    $('#gradeauthpass').on('input', function() {
        var password = $(this).val();
        if (password) {
            $.ajax({
                url: passgradeRoute,
                type: "POST",
                data: {
                    _token: passgradeTokenRoute,
                    password: password
                },
                success: function(response) {
                    if (response.status === 'success') {
                        $('#editBtn').prop('disabled', false);
                    } else {
                        $('#editBtn').prop('disabled', true);
                    }
                }
            });
        } else {
            $('#editBtn').prop('disabled', true);
        }
    });
});

$(document).ready(function() {
    $('#gradeauthpassCompletion').on('input', function() {
        var password = $(this).val();
        if (password) {
            $.ajax({
                url: passgradeRoute,
                type: "POST",
                data: {
                    _token: passgradeTokenRoute,
                    password: password
                },
                success: function(response) {
                    if (response.status === 'success') {
                        $('#editCompletionBtn').prop('disabled', false);
                    } else {
                        $('#editCompletionBtn').prop('disabled', true);
                    }
                }
            });
        } else {
            $('#editCompletionBtn').prop('disabled', true);
        }
    });
});