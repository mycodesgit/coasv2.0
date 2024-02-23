$(function () {
    $('#enrollStud').validate({
        rules: {
            stud_id: {
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
            stud_id: {
                required: "Please Enter Student ID",
            },
            schlyear: {
                required: "Select School Year",
            },
            semester: {
                required: "Select Semester",
            },
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.col-md-2, .col-md-5, .col-md-12').append(error);        
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
    });
});