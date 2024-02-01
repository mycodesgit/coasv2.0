$(function () {
    $('#adAppAd').validate({
        rules: {
            year: {
                required: true,
            },
            campus: {
                required: true,
            },
            strand: {
                required: true,
            }
        },
        messages: {
            year: {
                required: "Select Year",
            },
            campus: {
                required: "Select Campus",
            },
            strand: {
                required: "Select Strand",
            }
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.col-md-2, .col-md-3').append(error);        
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
    });
});