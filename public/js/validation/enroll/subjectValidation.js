$(function () {
    $('#addSubject').validate({
        rules: {
            sub_code: {
                required: true,
            },
            subjcostcenter: {
                required: true,
            },
            sub_name: {
                required: true,
            },
            sub_title: {
                required: true,
            },
            sublecredit: {
                required: true,
            },
            sublabcredit: {
                required: true,
            },
            sub_unit: {
                required: true,
            },
            subjweeks: {
                required: true,
            },
            subjconthrs: {
                required: true,
            },
            subjlev: {
                required: true,
            },
            subdelmod: {
                required: true,
            },            
            subacadtype: {
                required: true,
            },
        },
        messages: {
            sub_code: {
                required: "Please Enter Student ID",
            },
            subjcostcenter: {
                required: "Please Enter Cost Center",
            },
            sub_name: {
                required: "Please Enter Subject Name",
            },
            sub_title: {
                required: "Please Enter Subject Description",
            },
            sublecredit: {
                required: "Lecture Unit",
            },
            sublabcredit: {
                required: "Laboratory Unit",
            },
            sub_unit: {
                required: "Total Unit",
            },
            subjweeks: {
                required: "Please Enter Number of Weeks",
            },
            subjconthrs: {
                required: "Please Enter Number of Hours",
            },
            subjlev: {
                required: "Please Select Level",
            },
            subdelmod: {
                required: "Please Select Delivery Mode",
            },
            subacadtype: {
                required: "Please Select Academic Type",
            },
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.col-md-1, .col-md-2, .col-md-4, .col-md-6, .col-md-12').append(error);        
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
