$(function () {
    $('#admissionAssignSchedule').validate({
        rules: {
            dateID: {
                required: true,
            },
            venue: {
                required: true,
            }
        },
        messages: {
            dateID: {
                required: "Select Date",
            },
            venue: {
                required: "Select Venue",
            }
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.col-md-6').append(error);        
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
    });

    $('#admissionAssignResult').validate({
        rules: {
            raw_score: {
                required: true,
            },
            percentile: {
                required: true,
            }
        },
        messages: {
            raw_score: {
                required: "Please Enter Raw Score",
            },
            percentile: {
                required: "Please Enter Percentile",
            }
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.col-md-6').append(error);        
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
    });
});