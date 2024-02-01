$(function () {
    $('#adProg').validate({
        rules: {
            code: {
                required: true,
            },
            program: {
                required: true,
            },
        },
        messages: {
            code: {
                required: "Please Enter Program Code",
            },
            program: {
                required: "Please Enter Program Name",
            },
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.col-md-12').append(error);        
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
    });
});