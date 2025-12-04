$(document).ready(function() {
    $('#addStudentGradApply').submit(function(event) {
        event.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: studentAddNewRoute,
            type: "POST",
            data: formData,
            success: function(response) {
                if(response.success) {
                    toastr.success(response.message);
                    Swal.fire({
                        title: 'Success!',
                        html: 'Student ID: <strong>' + response.student_id + '</strong>',
                        icon: 'success',
                        confirmButtonText: 'Ok'
                    });
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

    $('#addunderStudentApply').submit(function(event) {
        event.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: studentAddNewUnderGradeRoute,
            type: "POST",
            data: formData,
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    Swal.fire({
                        title: 'Success!',
                        html: 'Student ID: <strong>' + response.student_id + '</strong>',
                        icon: 'success',
                        confirmButtonText: 'Ok'
                    });
                    console.log(response);
                } else {
                    toastr.error(response.message);
                    Swal.fire({
                        title: 'Error!',
                        html: response.message,
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                    console.log(response);
                }
            },
            error: function(xhr, status, error) {
                var errorMessage = 'An error occurred';
                if (xhr.responseText) {
                    try {
                        var response = JSON.parse(xhr.responseText);
                        errorMessage = response.message || errorMessage;
                    } catch (e) {
                        console.error('Failed to parse error response:', e);
                    }
                }
                toastr.error(errorMessage);

                Swal.fire({
                    title: 'Error!',
                    html: errorMessage,
                    icon: 'error',
                    confirmButtonText: 'Ok'
                });

                console.error('AJAX Error:', errorMessage);
            }
        });
    });


});