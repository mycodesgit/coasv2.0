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
            contact: {
                required: true,
                minlength: 11,
                maxlength: 11,
                digits: true, 
            },
            email: {
                required: true,
                email: true,
            },
            lstsch_attended: {
                required: {
                    depends: function(element) {
                        return $("#suc_lst_attended").val() === "" && $("#course").val() === ""; // Make "Last School Attended" required only if "College/University last attended" and "Course" are not filled
                    }
                },
            },
            strand: {
                required: {
                    depends: function(element) {
                        return $("#suc_lst_attended").val() === "" && $("#course").val() === ""; // Make "Strand" required only if "College/University last attended" and "Course" are not filled
                    }
                },
            },
            suc_lst_attended: {
                required: {
                    depends: function(element) {
                        return $("#lstsch_attended").val() === "" && $("#strand").val() === ""; // Make "College/University last attended" required only if "Last School Attended" and "Strand" are not filled
                    }
                },
            },
            course: {
                required: {
                    depends: function(element) {
                        return $("#lstsch_attended").val() === "" && $("#strand").val() === ""; // Make "Course" required only if "Last School Attended" and "Strand" are not filled
                    }
                },
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
            contact: {
                required: "Please enter your contact #.",
                minlength: "Contact number must be exactly 11 digits.",
                maxlength: "Contact number must be exactly 11 digits.",
                digits: "Please enter only digits.",
            },
            email: {
                required: "Please enter a email address",
                email: "Please enter a valid email address"
            },
            lstsch_attended: {
                required: "Enter Last School Attended",
            },
            strand: {
                required: "Select Strand",
            },
            suc_lst_attended: {
                required: "Enter College/University last attended",
            },
            course: {
                required: "Select Course",
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