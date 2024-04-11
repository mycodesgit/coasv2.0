$(function () {
    $('#studscholar').validate({
        rules: {
            schlyear: {
                required: true,
            },
            semester: {
                required: true,
            },
        },
        messages: {
            schlyear: {
                required: "Select Academic Year",
            },
            semester: {
                required: "Select Semester",
            },
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.col-md-2, .col-md-4').append(error);        
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
    });
});
