$(function () {
    $('#adFac').validate({
        rules: {
            dept: {
                required: true,
            },
            lname: {
                required: true,
            },
            fname: {
                required: true,
            },
            mname: {
                required: true,
            },
            adrID: {
                required: true,
            },
            email: {
                required: true,
                email: true,
                pattern: /^[a-zA-Z0-9._%+-]+@cpsu\.edu\.ph$/,
            }
        },
        messages: {
            dept: {
                required: "Select Department",
            },
            lname: {
                required: "Enter Last Name",
            },
            fname: {
                required: "Enter First Name",
            },
            mname: {
                required: "Enter Middle Initial",
            },
            adrID: {
                required: "Select Salutation",
            },
            email: {
                required: "Enter Institutional Email",
            }
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