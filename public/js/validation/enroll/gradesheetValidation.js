$(function () {
    $('#gradeSht').validate({
        rules: {
            syear: {
                required: true,
            },
            semester: {
                required: true,
            },
        },
        messages: {
            syear: {
                required: "Select School Year",
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

$(function () {
    $('#viewstudgrd').validate({
        rules: {
            stud_id: {
                required: true,
            },
        },
        messages: {
            stud_id: {
                required: "Please Enter Student ID Number",
            },
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.col-md-3').append(error);        
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
    });
});