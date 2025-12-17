$(document).ready(function () {
    $('#submitBtn').click(function () {
        $('#confirmationForm').submit();
    });
});

$(document).ready(function() {
    $('[id^=gradeauthpass]').on('input', function() {
        var id = $(this).attr('id').replace('gradeauthpass', '');
        var password = $(this).val();
        var editBtn = $('#editBtn' + id);
        
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
                        editBtn.prop('disabled', false);
                    } else {
                        editBtn.prop('disabled', true);
                    }
                },
                error: function() {
                    editBtn.prop('disabled', true);
                }
            });
        } else {
            editBtn.prop('disabled', true);
        }
    });

    $('[id^=gradeauthpassCompletion]').on('input', function() {
        var id = $(this).attr('id').replace('gradeauthpassCompletion', '');
        var password = $(this).val();
        var editCompletionBtn = $('#editCompletionBtn' + id);

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
                        editCompletionBtn.prop('disabled', false);
                    } else {
                        editCompletionBtn.prop('disabled', true);
                    }
                },
                error: function() {
                    editCompletionBtn.prop('disabled', true);
                }
            });
        } else {
            editCompletionBtn.prop('disabled', true);
        }
    });
    
    $('[id^=editBtn]').click(function () {
        var id = $(this).attr('id').replace('editBtn', '');
        $('#editConfirmForm' + id).submit();
    });

    $('[id^=editCompletionBtn]').click(function () {
        var id = $(this).attr('id').replace('editCompletionBtn', '');
        $('#editCompletionForm' + id).submit();
    });
});


