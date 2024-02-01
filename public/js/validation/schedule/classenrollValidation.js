$(function () {
    $('#classEnroll').validate({
        rules: {
            schlyear: {
                required: true,
            },
            semester: {
                required: true,
            },
            campus: {
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
            campus: {
                required: "Select Preffered Campus",
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

$(function () {
    $('#classEnrollAdd').validate({
        rules: {
            schlyear: {
                required: true,
            },
            semester: {
                required: true,
            },
            campus: {
                required: true,
            },
            class: {
                required: true,
            },
            class_section: {
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
            campus: {
                required: "Select Preffered Campus",
            },
            class: {
                required: "Select Preffered Program",
            },
            class_section: {
                required: "Enter Year & Section",
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