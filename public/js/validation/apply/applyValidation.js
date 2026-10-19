let currentCard = 1;
let validator;
const totalCards = document.querySelectorAll('[id^="card-"]').length;

// ---------- Global helper functions moved from master_apply.blade.php ----------
function updateAddress() {
    if (!window.jQuery) return;
    let hnum = $('#viewdatastudHnum').val(); // house number input
    let barangay = $('#barangay').find(':selected').data('name');
    let city = $('#city').find(':selected').data('name');
    let province = $('#province').find(':selected').data('name');
    let region = $('#region').find(':selected').data('name');
    let zipcode = $('#zipcode').val(); // hidden or text input for zip

    // Filter out undefined or empty values, then join with comma
    let fullAddress = [hnum, barangay, city, province, region, zipcode].filter(Boolean).join(', ');

    $('#viewdatastudAddress').val(fullAddress);
}

function calculateAge() {
    var birthday = document.getElementById('bday').value;
    var today = new Date();
    var birthDate = new Date(birthday);
    var age = today.getFullYear() - birthDate.getFullYear();

    if (today.getMonth() < birthDate.getMonth() || (today.getMonth() === birthDate.getMonth() && today.getDate() < birthDate.getDate())) {
        age--;
    }

    document.getElementById('age').value = age;
}

function handleReligionChange() {
    const select = document.getElementById('religionSelect');
    if (!select) return;
    const otherInput = document.getElementById('otherReligionInput');

    if (select.value === 'OTHER') {
        otherInput.style.display = 'block';
        otherInput.setAttribute('name', 'religion'); // include in form submission
        otherInput.focus();
    } else {
        otherInput.style.display = 'none';
        otherInput.removeAttribute('name'); // prevent duplicate field in submission
    }
}

function updateCoursePreferences(campus) {
    $.ajax({
        url: programsRoute + '?campus=' + campus,
        type: 'GET',
        success: function (data) {
            updateOptions('preference_1', data.programs);
            updateOptions('preference_2', data.programs);
        },
        error: function () {
            console.error('Error fetching programs');
        }
    });
}

function updateExamSchedule(campus) {
    $.ajax({
        url: examschedRoute + '?campus=' + campus,
        type: 'GET',
        success: function (data) {
            updateSchedOptions('d_admissionselect', data.schedtest);
        },
        error: function () {
            console.error('Error fetching Schedule Test');
        }
    });
}

function updateOptions(selectName, options) {
    const select = $('select[name=' + selectName + ']');
    select.empty();
    select.append('<option value="">Select Course Preference</option>');
    $.each(options, function (key, value) {
        select.append('<option value="' + value.code + '">' + value.program + '</option>');
    });
}

function updateSchedOptions(selectName, options) {
    const select = $('select[name=' + selectName + ']');
    select.empty();
    select.append('<option disabled selected> --Select Testing Schedule-- </option>');
    $.each(options, function (key, value) {
        const formattedDate = (typeof moment !== 'undefined') ? moment(value.date).format('MMMM DD, YYYY') : value.date;
        const formattedTime = (typeof moment !== 'undefined') ? moment(value.time, 'HH:mm').format('hh:mm A') : value.time;
        const slots = value.slots;
        const primDateTimeID = value.id;

        if (slots === 0) {
            select.append('<option disabled>' + formattedDate + ' ' + formattedTime + ' (Slots is Full)</option>');
        } else {
            select.append('<option value="' + value.date + '" data-primdatetimeid="' + primDateTimeID + '">' + formattedDate + ' ' + formattedTime + ' (Available Slots: ' + slots + ')</option>');
        }
    });
}

function clearForm() {
    const form = document.getElementById('admissionApply');
    if (form) form.reset();
}

// ---------- Main jQuery logic ----------
$(function () {
    validator = $('#admissionApply').validate({
        rules: {
            // Card 1 rules
            studagree: {
                required: true,
            },
            email: {
                required: true,
                email: true,
                pattern: /^[a-zA-Z0-9._%+-]+@(gmail\.com|yahoo\.com|outlook\.com)$/,
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
            civil_status: {
                required: true,
            },
            religion: {
                required: true,
            },
            brgy: {
                required: true,
            },
            city: {
                required: true,
            },
            address: {
                required: true,
            },
            qstion1: {
                required: true,
            },
            qstion2: {
                required: true,
            },

            // Card 2 rules
            type: {
                required: true
            },
            campus: {
                required: true
            },
            d_admissionselect: {
                required: true
            },
            lstsch_attended: {
                required: true
            },
            strand: {
                required: true
            },
            suc_lst_attended: {
                required: true
            },
            course: {
                required: true
            },
            preference_1: {
                required: true
            },
            preference_2: {
                required: true
            },


            // Card 3 rules
            monthly_income: {
                required: true,
            },
            studiddoc_image: {
                required: true,
            },
            typefileproofupload: {
                required: true,
            },
            proofdoc_image: {
                required: true,
            },
        },
        messages: {
            // Card 1 messages
            studagree: {
                required: "Check the checkbox for agreement",
            },
            email: {
                required: "Please enter a email address",
                email: "Please enter a valid email address",
                pattern: "Please use only a popular email domain gmail.com",
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
            civil_status: {
                required: "Select Status",
            },
            religion: {
                required: "Enter Religion",
            },
            brgy: {
                required: "Enter Barangay",
            },
            city: {
                required: "Select City or Municipality",
            },
            address: {
                required: "Enter Present Address",
            },
            qstion1: {
                required: "Select Option",
            },
            qstion2: {
                required: "Select Option",
            },

            // Card 2 messages
            type: {
                required: "Select Admission Type"
            },
            campus: {
                required: "Select Preferred Campus"
            },
            d_admissionselect: {
                required: "Select Schedule of Examination"
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
            preference_1: {
                required: "Select Preferred Course"
            },
            preference_2: {
                required: "Select Preferred Course"
            },

            // Card 3 messages
            monthly_income: {
                required: "Enter Parent's Monthly Income",
            },
            studiddoc_image: {
                required: "Upload one image from the requirements",
            },
            typefileproofupload: {
                required: "Select Proof of Equity Target Documents from Parents/Legal Guardians",
            },
            proofdoc_image: {
                required: "Upload one image from the requirements",
            },
        },
        onfocusout: false,
        onkeyup: false,
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.col-md-2, .col-md-6, .col-md-12').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
    });

    $('#admissionApply').on('input change', 'input, select', function () {
        toggleNextButton();
    });

    function toggleNextButton() {
        // Check if current card's fields are valid
        const isCurrentCardValid = $(getCurrentCardSelector()).find("input, select").valid();
        const btn = document.getElementById("next-btn");
        if (btn) btn.disabled = !isCurrentCardValid;
    }

    // Select2 init
    $('.select2').select2();
    $('.select2bs4').select2({
        theme: 'bootstrap4',
        height: '100'
    });

    // Region / province / city / barangay cascade
    $('#region').on('change', function () {
        var regionId = $(this).val();
        var regionName = $(this).find(':selected').data('name');
        $('#region_name').val(regionName);
        updateAddress();

        $('#province').empty().append('<option disabled selected>Loading...</option>');
        $.get(provincesRoute + '/' + regionId, function (data) {
            const rows = Array.isArray(data) ? data : (data && Array.isArray(data.data) ? data.data : []);
            $('#province').html('<option disabled selected>Select Province</option>');
            rows.forEach(p => $('#province').append(`<option value="${p.province_id}" data-name="${p.name}">${p.name}</option>`));
        });
    });

    $('#province').on('change', function () {
        var provinceId = $(this).val();
        var provinceName = $(this).find(':selected').data('name');
        $('#province_name').val(provinceName);
        updateAddress();

        $('#city').empty().append('<option disabled selected>Loading...</option>');
        $.get(citiesRoute + '/' + provinceId, function (data) {
            const rows = Array.isArray(data) ? data : (data && Array.isArray(data.data) ? data.data : []);
            $('#city').html('<option disabled selected>Select City</option>');
            rows.forEach(c => $('#city').append(`<option value="${c.city_id}" data-name="${c.name}" data-zip="${c.zip_code}">${c.name}</option>`));
        });
    });

    $('#city').on('change', function () {
        var cityName = $(this).find(':selected').data('name');
        var zip = $(this).find(':selected').data('zip');

        $('#city_name').val(cityName);
        $('#zipcode').val(zip || '');
        updateAddress();

        var cityId = $(this).val();
        $('#barangay').empty().append('<option disabled selected>Loading...</option>');
        $.get(barangaysRoute + '/' + cityId, function (data) {
            const rows = Array.isArray(data) ? data : (data && Array.isArray(data.data) ? data.data : []);
            $('#barangay').html('<option disabled selected>Select Barangay</option>');
            rows.forEach(b => $('#barangay').append(`<option value="${b.id}" data-name="${b.name}">${b.name}</option>`));
        });
    });

    $('#barangay').on('change', function () {
        var brgyName = $(this).find(':selected').data('name');
        $('#brgy_name').val(brgyName);
        updateAddress();
    });

    $('#viewdatastudHnum').on('input', updateAddress);

    // Email blur handling
    $('#email').on('blur', function () {
        let email = $(this).val();

        if (email.trim() !== '') {
            const btn = document.getElementById('next-btn');
            if (btn) btn.style.display = 'inline-block';
        } else {
            const btn = document.getElementById('next-btn');
            if (btn) btn.style.display = 'none';
        }
    });

    // Campus / course prefs / exam schedule
    let scheduleRefreshInterval;
    function startScheduleRefresh() {
        if (scheduleRefreshInterval) clearInterval(scheduleRefreshInterval);
        scheduleRefreshInterval = setInterval(() => {
            const selectedCampus = $('#campus').val();
            if (selectedCampus) {
                updateExamSchedule(selectedCampus);
            }
        }, 5000);
    }
    function stopScheduleRefresh() {
        if (scheduleRefreshInterval) clearInterval(scheduleRefreshInterval);
    }

    $('#campus').change(function () {
        const selectedCampus = $(this).val();
        updateCoursePreferences(selectedCampus);
        updateExamSchedule(selectedCampus);
        stopScheduleRefresh();
        if (selectedCampus) {
            startScheduleRefresh();
        }
    });

    $(window).on('blur', stopScheduleRefresh);
    $(window).on('focus', function () {
        const selectedCampus = $('#campus').val();
        if (selectedCampus) {
            updateExamSchedule(selectedCampus);
            startScheduleRefresh();
        }
    });

    $(document).on('scheduleUpdated', function () {
        const selectedCampus = $('#campus').val();
        updateExamSchedule(selectedCampus);
    });

    $('select[name="d_admissionselect"]').change(function () {
        const selectedOption = $(this).find('option:selected');
        const selectedText = selectedOption.text();

        const dateTime = (typeof moment !== 'undefined')
            ? moment(selectedText, 'MMMM D, YYYY hh:mm A')
            : null;

        if (dateTime) {
            const formattedDate = dateTime.format('YYYY-MM-DD');
            const formattedTime = dateTime.format('HH:mm:ss');
            const dateTimeID = selectedOption.data('primdatetimeid');

            $('#selectedDate').val(formattedDate);
            $('#selectedTime').val(formattedTime);
            $('#selectedDateTimeID').val(dateTimeID);
        }
    });

    // Gender question handling
    const genderSelectEl = document.getElementById('genderSelect');
    if (genderSelectEl) {
        genderSelectEl.addEventListener('change', function () {
            const selectedGender = this.value;
            const genderPlaceholder = document.getElementById('genderPlaceholder');
            const genderQuestion = document.getElementById('genderQuestion');

            if (selectedGender === 'Female' || selectedGender === 'Male') {
                if (genderPlaceholder) genderPlaceholder.textContent = selectedGender.toUpperCase();
                if (genderQuestion) genderQuestion.style.display = 'block';
            } else {
                if (genderQuestion) genderQuestion.style.display = 'none';
            }
        });
    }

    // Admission type toggle
    var admissionType = document.getElementById('admissionType');
    var newReturneeForm = document.getElementById('newReturneeForm');
    var transfereeForm = document.getElementById('transfereeForm');

    function toggleFormSections(value) {
        if (value == 1 || value == 2) {
            if (newReturneeForm) newReturneeForm.style.display = 'block';
            if (transfereeForm) transfereeForm.style.display = 'none';
        } else if (value == 3) {
            if (newReturneeForm) newReturneeForm.style.display = 'none';
            if (transfereeForm) transfereeForm.style.display = 'block';
        } else {
            if (newReturneeForm) newReturneeForm.style.display = 'none';
            if (transfereeForm) transfereeForm.style.display = 'none';
        }
    }

    if (admissionType) {
        toggleFormSections(admissionType.value);
        admissionType.addEventListener('change', function () {
            toggleFormSections(this.value);
        });
    }

    // Course "other" handling
    const courseSelect = document.getElementById('courseSelect');
    const otherCourseInput = document.getElementById('otherCourseInput');
    if (courseSelect && otherCourseInput) {
        courseSelect.addEventListener('change', function () {
            if (this.value === 'OTHER') {
                otherCourseInput.style.display = 'block';
                otherCourseInput.required = true;
            } else {
                otherCourseInput.style.display = 'none';
                otherCourseInput.required = false;
                otherCourseInput.value = '';
            }
        });
    }

    // Type of proof upload change (guarded)
    document.querySelectorAll('input[name="typefileproofupload"]').forEach(radio => {
        radio.addEventListener('change', function () {
            try {
                const selectedText = this.closest('.custom-option-card') ? this.closest('.custom-option-card').querySelector('span.fw-semibold')?.innerText.trim() : '';
                const label = document.getElementById('uploadLabel');
                if (label && selectedText) {
                    label.innerHTML = `Upload Proof of Equity Target Documents from Parents/Legal Guardians – <b style="color:#007AFF">${selectedText}</b> <i style="color: red">*</i>`;
                }
            } catch (e) { /* ignore */ }
        });
    });

    // Religion other input (initial state)
    // (handleReligionChange is bound via onchange in markup)
});

// applicantType / upload sections handling (kept from original external file)
// const applicantType = document.getElementById('applicantType');
// const sections = document.querySelectorAll('.upload-section');

// if (applicantType) {
//     applicantType.addEventListener('change', function () {
//         sections.forEach(section => {
//             const input = section.querySelector('.file-input');

//             // Always clear the file input when switching
//             if (input) input.value = "";

//             if (section.id === this.value) {
//                 section.style.display = 'block';
//                 if (input) input.setAttribute('required', true);
//             } else {
//                 section.style.display = 'none';
//                 if (input) input.removeAttribute('required');
//             }
//         });

//         // After switching, check again so submit button updates
//         checkLastCardCompletion();
//     });
// }

// Also check again when user uploads a new file
document.querySelectorAll('.file-input').forEach(input => {
    input.addEventListener('change', checkLastCardCompletion);
});

// ---------- Wizard functions ----------
function updateProgressBar() {
    const progressPercentage = (currentCard / totalCards) * 100;
    const bar = document.getElementById("progress-bar");
    if (bar) bar.style.width = progressPercentage + "%";
    const text = document.getElementById("progress-text");
    if (text) text.textContent = `Page ${currentCard} of ${totalCards}`;

    const backBtn = document.getElementById("back-btn");
    if (backBtn) backBtn.style.display = currentCard > 1 ? "inline-block" : "none";
    const nextBtn = document.getElementById("next-btn");
    if (nextBtn) nextBtn.style.display = currentCard < totalCards ? "inline-block" : "none";

    const submitBtn = document.getElementById("submit-btn");
    if (submitBtn) submitBtn.style.display = currentCard === totalCards ? "inline-block" : "none";

    if (currentCard === totalCards) {
        checkLastCardCompletion();
    }
}

function checkLastCardCompletion() {
    const lastCardFields = document.querySelectorAll(`#card-${totalCards} input, #card-${totalCards} select`);

    const allFilled = Array.from(lastCardFields)
        .filter(field => field.offsetParent !== null) // only visible fields
        .every(field => field.value.trim() !== "");

    const submitBtn = document.getElementById("submit-btn");
    if (submitBtn) submitBtn.disabled = !allFilled;
}

function nextCard(cardNumber) {
    if (cardNumber > totalCards) return;

    if (!$(getCurrentCardSelector()).find("input, select").valid()) {
        validator.focusInvalid();
        return; // Stop moving to the next card if current card is invalid
    }

    document.getElementById(`card-${currentCard}`).style.display = "none";
    document.getElementById(`card-${cardNumber}`).style.display = "block";

    currentCard = cardNumber;
    updateProgressBar();
}

function prevCard(cardNumber) {
    if (cardNumber < 1) return;

    document.getElementById(`card-${currentCard}`).style.display = "none";
    document.getElementById(`card-${cardNumber}`).style.display = "block";

    currentCard = cardNumber;
    updateProgressBar();
}

function getCurrentCardSelector() {
    return `#card-${currentCard}`;
}

document.querySelectorAll(`#card-${totalCards} input, #card-${totalCards} select`).forEach(field => {
    field.addEventListener('input', checkLastCardCompletion);
});

// Initialize progress bar on page load
updateProgressBar();
