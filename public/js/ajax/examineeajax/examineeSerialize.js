toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right"
};
$(document).ready(function() {
    $('#admissionAssignSchedule').submit(function(event) {
        event.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: assignSchedRoute,
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

    $('#admissionAssignResult').submit(function(event) {
        event.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: assignResultRoute,
            type: "PUT",
            data: formData,
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    console.log(response);
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const rawScoreInput = document.querySelector('input[name="raw_score"]');
    
    const remarksInput = document.querySelector('input[name="percentile"]');
    
    rawScoreInput.addEventListener('input', function() {
        const rawScoreValue = parseInt(this.value);
        
        if (rawScoreValue < 100) {
            remarksInput.value = 'Failed';
        } else {
            remarksInput.value = 'Qualified';
        }
        
    });
});

