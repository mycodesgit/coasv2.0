let currentCard = 1;
const totalCards = document.querySelectorAll('[id^="card-"]').length;

$(function () {
    const validator = $('#admissionApply').validate({
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
                required: "Select Proof/Evidence of Disadvantage Situation",
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
            element.closest('.col-md-2, .col-md-6, .col-md-12, .icheck-primary').append(error);        
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
        document.getElementById("next-btn").disabled = !isCurrentCardValid;
    }
    // $("input[name='email']").on("blur", function() {
    //     const email = $(this).val();
    //     const validDomains = ["gmail.com", "yahoo.com", "outlook.com"];
    //     const domain = email.split("@")[1];
        
    //     if (domain && !validDomains.includes(domain)) {
    //         alert("Please use a valid email domain like gmail.com, yahoo.com, or outlook.com");
    //     }
    // });
});

function updateProgressBar() {
    const progressPercentage = (currentCard / totalCards) * 100;
    document.getElementById("progress-bar").style.width = progressPercentage + "%";
    document.getElementById("progress-text").textContent = `Page ${currentCard} of ${totalCards}`;

    // Show or hide navigation buttons based on current card
    document.getElementById("back-btn").style.display = currentCard > 1 ? "inline-block" : "none";
    document.getElementById("next-btn").style.display = currentCard < totalCards ? "inline-block" : "none";
    
    // Show submit button only on the last card
    document.getElementById("submit-btn").style.display = currentCard === totalCards ? "inline-block" : "none";

    // Check field completion if on the last card
    if (currentCard === totalCards) {
        checkLastCardCompletion();
    }
}

// Check if all fields in the last card are filled out
function checkLastCardCompletion() {
    const lastCardFields = document.querySelectorAll(`#card-${totalCards} input, #card-${totalCards} select`);
    const allFilled = Array.from(lastCardFields).every(field => field.value.trim() !== "");
    
    // Enable submit button if all fields are filled
    document.getElementById("submit-btn").disabled = !allFilled;
}

// Move to the next card
function nextCard(cardNumber) {
    if (cardNumber > totalCards) return;

    if (!$(getCurrentCardSelector()).find("input, select").valid()) {
        validator.focusInvalid();
        return; // Stop moving to the next card if current card is invalid
    }

    // Hide current card and show the next one
    document.getElementById(`card-${currentCard}`).style.display = "none";
    document.getElementById(`card-${cardNumber}`).style.display = "block";
    
    currentCard = cardNumber;
    updateProgressBar();
}

// Move to the previous card
function prevCard(cardNumber) {
    if (cardNumber < 1) return;

    document.getElementById(`card-${currentCard}`).style.display = "none";
    document.getElementById(`card-${cardNumber}`).style.display = "block";
    
    currentCard = cardNumber;
    updateProgressBar();
}

// Helper to get the current card selector
function getCurrentCardSelector() {
    return `#card-${currentCard}`;
}

// Add event listeners to check field completion on input
document.querySelectorAll(`#card-${totalCards} input, #card-${totalCards} select`).forEach(field => {
    field.addEventListener('input', checkLastCardCompletion);
});

// Initialize the progress bar on page load
updateProgressBar();
