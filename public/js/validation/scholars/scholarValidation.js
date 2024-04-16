$(function () {
    $('#addChedSch').validate({
        rules: {
            chedsch_name: {
                required: true,
            },
        },
        messages: {
            chedsch_name: {
                required: "Enter CHED Scholarship",
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

$(function () {
    $('#addUniSch').validate({
        rules: {
            unisch_name: {
                required: true,
            },
        },
        messages: {
            unisch_name: {
                required: "Enter CPSU Scholarship",
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


$(function () {
    $('#addScholar').validate({
        rules: {
            scholar_name: {
                required: true,
            },
            scholar_sponsor: {
                required: true,
            },
            chedcategory: {
                required: true,
            },
            unicategory: {
                required: true,
            },
            fund_source: {
                required: true,
            },
        },
        messages: {
            scholar_name: {
                required: "Enter Scholarship Name",
            },
            scholar_sponsor: {
                required: "Enter Scholarship Sponsor",
            },
            chedcategory: {
                required: "Select Scholarship Category",
            },
            unicategory: {
                required: "Select Scholarship Category",
            },
            fund_source: {
                required: "Select Scholarship Funding Source",
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
