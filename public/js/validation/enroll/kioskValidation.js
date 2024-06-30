$(function () {
    $('#adKioskuser').validate({
        rules: {
            studid: {
                required: true,
            },
            password: {
                required: true,
            },
        },
        messages: {
            studid: {
                required: "Please Enter Student ID Number",
            },
            password: {
                required: "Please Generate Password",
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