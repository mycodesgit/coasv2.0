$(function () {
    $('#adStrand').validate({
        rules: {
            code: {
                required: true,
            },
            strand: {
                required: true,
            },
        },
        messages: {
            code: {
                required: "Please Enter Strand Code",
            },
            strand: {
                required: "Please Enter Strand Name",
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