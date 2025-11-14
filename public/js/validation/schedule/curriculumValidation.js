$(function () {
    $('#curriculumSearch').validate({
        rules: {
            semester: {
                required: true,
            },
            progCod: {
                required: true,
            },
        },
        messages: {
            semester: {
                required: "Select Semester",
            },
            progCod: {
                required: "Select Preffered Program",
            },
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.col-md-3, .col-md-4').append(error);        
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
    $('#adCurriculumForm').validate({
        rules: {
            semester: {
                required: true,
            },
            progCode: {
                required: true,
            },
            yrlvl: {
                required: true,
            },
            subCode: {
                required: true,
            }
        },
        messages: {
            semester: {
                required: "Select Academic Year",
            },
            progCode: {
                required: "Select Course",
            },
            yrlvl: {
                required: "Please Enter Est. No. of Students",
            },
            subCode: {
                required: "Select Subject Code",
            }
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.col-md-3, .col-md-4, .col-md-12').append(error);        
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
    });
});