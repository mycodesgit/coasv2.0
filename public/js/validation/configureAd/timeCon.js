$(function () {
    $('#adTimeCon').validate({
        rules: {
            date: {
                required: true,
            },
            time: {
                required: true,
            },
            slots: {
                required: true,
            },
        },
        messages: {
            date: {
                required: "Please Enter Date",
            },
            time: {
                required: "Please Enter Time",
            },
            slots: {
                required: "Please Enter Slots",
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