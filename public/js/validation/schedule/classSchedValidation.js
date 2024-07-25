$(function () {
    $('#classsched').validate({
        rules: {
            schlyear: {
                required: true,
            },
            semester: {
                required: true,
            },
            progCod: {
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
            progCod: {
                required: "Select Course",
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
    $('#facultysched').validate({
        rules: {
            schlyear: {
                required: true,
            },
            semester: {
                required: true,
            },
            faculty_id: {
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
            faculty_id: {
                required: "Select Faculty",
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
    $('#roomsched').validate({
        rules: {
            schlyear: {
                required: true,
            },
            semester: {
                required: true,
            },
            room_id: {
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
            room_id: {
                required: "Select Room",
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
