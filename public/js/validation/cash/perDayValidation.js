$(function () {
    $('#perdayorno').validate({
        rules: {
            datepaid: {
                required: true,
            },
        },
        messages: {
            datepaid: {
                required: "Please Enter Date",
            },
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.col-md-4').append(error);        
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
    });
});