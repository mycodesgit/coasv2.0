$(function () {
    $('#facdegAdd').validate({
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
            facdept: {
                required: true,
            },
            fac_id: {
                required: true,
            },
            rankcomma: {
                required: true,
            },
            designation: {
                required: true,
            },
            dunit: {
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
            facdept: {
                required: "Select Department",
            },
            fac_id: {
                required: "Select Faculty",
            },
            rankcomma: {
                required: "Enter Addresse",
            },
            designation: {
                required: "Enter Designation",
            },
            dunit: {
                required: "Enter Designation unit",
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