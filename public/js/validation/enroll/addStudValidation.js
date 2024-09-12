$(function () {
    $('#addStudentApply').validate({
        rules: {
            type: {
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
            gender: {
                required: true,
            },
            civil_status: {
                required: true,
            },
            bday: {
                required: true,
            },
            pbirth: {
                required: true,
            },
            email: {
                required: true,
            },
            contact: {
                required: true,
            },
            address: {
                required: true,
            },
            hnum: {
                required: true,
            },
            brgy: {
                required: true,
            },
            city: {
                required: true,
            },
            province: {
                required: true,
            },
            region: {
                required: true,
            },
            zcode: {
                required: true,
            },
        },
        messages: {
            type: {
                required: "Please Select Student Type",
            },
            lname: {
                required: "Please Enter Lastname",
            },
            fname: {
                required: "Please Enter Firstname",
            },
            mname: {
                required: "Please Enter Middlename",
            },
            gender: {
                required: "Please Select Student Gender",
            },
            civil_status: {
                required: "Please Select Student Status",
            },
            bday: {
                required: "Please Enter Student Birthday",
            },
            pbirht: {
                required: "Please Enter Student Birthplace",
            },
            contact: {
                required: "Please Enter Student Contact number",
            },
            address: {
                required: "Please Enter Student Address",
            },
            hnum: {
                required: "Please Enter Student House no.",
            },
            brgy: {
                required: "Please Enter Student Brgy.",
            },
            city: {
                required: "Please Enter Student City",
            },
            province: {
                required: "Please Enter Student Province",
            },
            region: {
                required: "Please Enter Student Region",
            },
            zcode: {
                required: "Please Enter Student Zipcode",
            },
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.col-md-2, .col-md-4, .col-md-6, .col-md-12').append(error);        
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
    });
});