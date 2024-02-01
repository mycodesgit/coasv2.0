$(function () {
    $('#adVenueCon').validate({
        rules: {
            venue: {
                required: true,
            },
        },
        messages: {
            venue: {
                required: "Please Enter Venue",
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