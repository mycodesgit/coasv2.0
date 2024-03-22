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
            progCode: {
                required: true,
            },
            classSection: {
                required: true,
            },
            classno: {
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
            progCode: {
                required: "Select Course",
            },
            classSection: {
                required: "Please Enter Year&Section",
            },
            classno: {
                required: "Please Enter Est. No. of Students",
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