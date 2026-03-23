$(function () {
    $('#addUser').validate({
        rules: {
            fname: {
                required: true
            },
            lname: {
                required: true
            },
            email: {
                required: true,
                email: true,
                pattern: /^[a-zA-Z0-9._%+-]+@cpsu\.edu\.ph$/
            },
            password: {
                required: true,
                minlength: 5
            },
            campus: {
                required: true
            },
            dept: {
                required: true
            },
            role: {
                required: true
            },
        },
        messages: {
            fname: {
                required: "Please enter a First Name"
            },
            lname: {
                required: "Please enter a Last Name"
            },
            email: {
                required: "Please use institutional email",
                email: "Please enter a valid email address",
                pattern: "Only Email (@cpsu.edu.ph) is allowed"
            },
            password: {
                required: "Please provide a password",
                minlength: "Your password must be at least 5 characters long"
            },
            campus: {
                required: "Please select a Campus"
            },
            dept: {
                required: "Please select a Department"
            },
            role: {
                required: "Please select a User Type"
            },
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.col-md-6, .col-md-4').append(error);        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
    });
});

$(function () {
    $('#edituserPassForm').validate({
        rules: {
            password: {
                required: true,
                minlength: 5
            },
        },
        messages: {
            password: {
                required: "Please provide a password",
                minlength: "Your password must be at least 5 characters long"
            },
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.col-md-6, .col-md-12').append(error);        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
    });
});