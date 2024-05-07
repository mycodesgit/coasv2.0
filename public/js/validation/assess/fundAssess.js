$(function () {
    $('#adFund').validate({
        rules: {
            fund_name: {
                required: true,
            },
        },
        messages: {
            fund_name: {
                required: "Please Enter Fund",
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
    $('#adCOA').validate({
        rules: {
            accountcoa_code: {
                required: true,
            },
            accountcoa_name: {
                required: true,
            },
        },
        messages: {
            accountcoa_code: {
                required: "Please Enter COA Account Code",
            },
            accountcoa_name: {
                required: "Please Enter COA Account Name",
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
    $('#adAccntApp').validate({
        rules: {
            fund_id: {
                required: true,
            },
            account_name: {
                required: true,
            },
            coa_id: {
                required: true,
            },
        },
        messages: {
            fund_id: {
                required: "Select Fund Account",
            },
            account_name: {
                required: "Please Enter Account",
            },
            coa_id: {
                required: "Select COA Account",
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
    $('#studFeeAssess').validate({
        rules: {
            fundname_code: {
                required: true,
            },
            accountName: {
                required: true,
            },
            amountFee: {
                required: true,
            },
        },
        messages: {
            fundname_code: {
                required: "Select Fund Account",
            },
            accountName: {
                required: "Please Enter Account",
            },
            amountFee: {
                required: "Enter Amount",
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
    $('#studFee').validate({
        rules: {
            prog_Code: {
                required: true,
            },
            yrlevel: {
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
            prog_Code: {
                required: "Select Course",
            },
            yrlevel: {
                required: "Select Year Level",
            },
            schlyear: {
                required: "Select School Year",
            },
            semester: {
                semester: "Select Semester",
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
    $('#studstatesum').validate({
        rules: {
            schlyear: {
                required: true,
            },
            semester: {
                required: true,
            },
            category: {
                required: true,
            },
        },
        messages: {
            schlyear: {
                required: "Select School Year",
            },
            semester: {
                semester: "Select Semester",
            },
            category: {
                semester: "Select Category",
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