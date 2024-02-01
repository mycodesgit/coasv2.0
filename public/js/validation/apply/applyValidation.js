$(function () {
    $('#admissionApply').validate({
        rules: {
            // admissionid: {
            //     required: true,
            // },
            type: {
                required: true,
            },
            campus: {
                required: true,
            },
            lastname: {
                required: true,
            },
            firstname: {
                required: true,
            },
            gender: {
                required: true,
            },
            bday: {
                required: true,
            },
            age: {
                required: true,
            },
            email: {
                required: true,
                email: true,
            },
            civil_status: {
                required: true,
            },
            religion: {
                required: true,
            },
            monthly_income: {
                required: true,
            },
            address: {
                required: true,
            },
            preference_1: {
                required: true,
            },
            preference_2: {
                required: true,
            },
            doc_image: {
                required: true,
            },
            remember: {
                required: true,
            },
        },
        messages: {
            // admissionid: {
            //     required: "Please Enter Admission ID",
            // },
            type: {
                required: "Select Admission Type",
            },
            campus: {
                required: "Select Preffered Campus",
            },
            lastname: {
                required: "Enter Lastname",
            },
            firstname: {
                required: "Enter Firstname",
            },
            gender: {
                required: "Select Gender",
            },
            bday: {
                required: "Enter Birthdate",
            },
            age: {
                required: "Enter Age",
            },
            email: {
                required: "Please enter a email address",
                email: "Please enter a valid email address"
            },
            civil_status: {
                required: "Select Civil Status",
            },
            religion: {
                required: "Select Religion",
            },
            monthly_income: {
                required: "Enter Parent's Monthly Income",
            },
            address: {
                required: "Enter Present Address",
            },
            preference_1: {
                required: "Select Preffered Course",
            },
            preference_2: {
                required: "Select Preffered Course",
            },
            doc_image: {
                required: "Upload one image from the requirements",
            },
            remember: {
                required: "Please check to agree to the following terms and conditions",
            },
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.col-md-2, .col-md-6, .col-md-12').append(error);        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
    });
});