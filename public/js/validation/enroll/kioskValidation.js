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

$(function () {
    $('#bulkKiosk').validate({
        rules: {
            campus: {
                required: true,
            },
            schlyear: {
                required: true,
            },
            semester: {
                required: true,
            },
        },
        messages: {
            campus: {
                required: "Select Campus",
            },
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
            element.closest('.col-md-2, .col-md-3').append(error);        
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
    });
});