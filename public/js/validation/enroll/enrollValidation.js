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
            element.closest('.col-md-2, .col-md-5, .col-md-3, .col-md-12').append(error);        
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
    $('#submitButton').click(function() {
        $('#AddenrollStud').validate({
            rules: {
                programNameSelect: {
                    required: true,
                },
                studLevel: {
                    required: true,
                },
                studSch: {
                    required: true,
                },
                studMajor: {
                    required: true,
                },
                studMinor: {
                    required: true,
                },
                studType: {
                    required: true,
                },
                transferee: {
                    required: true,
                },
                fourPs: {
                    required: true,
                },
            },
            messages: {
                programNameSelect: {
                    required: "Select Course Year&Section",
                },
                studLevel: {
                    required: "Select Student Level",
                },
                studSch: {
                    required: "Select Scholarship",
                },
                studMajor: {
                    required: "Select Major",
                },
                studMinor: {
                    required: "Select Minor",
                },
                studType: {
                    required: "Select Student Type",
                },
                transferee: {
                    required: "Select Transferee or Not",
                },
                fourPs: {
                    required: "Select Student 4P's Beneficiaries",
                },
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.col-md-1, .col-md-2, .col-md-3, .col-md-6, .col-md-9').append(error);        
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            },
        }).form();
    });
});
