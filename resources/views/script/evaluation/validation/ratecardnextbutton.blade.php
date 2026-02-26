<script>
    let currentCard = 1;
    const totalCards = document.querySelectorAll('[id^="card-"]').length;

    $(document).ready(function () {
        function checkInputs(cardId, buttonId) {
            let allFilled = true;

            if ($(cardId + ' input[type="radio"]').length > 0) {
                $(cardId + ' .radio-group').each(function () {
                    let radioGroupName = $(this).find('input[type="radio"]').attr('name');
                    if ($(`input[name="${radioGroupName}"]:checked`).length === 0) {
                        allFilled = false;
                        return false;
                    }
                });
            } else {
                $(cardId + ' .required-input').each(function () {
                    if ($(this).val().trim() === '') {
                        allFilled = false;
                        return false;
                    }
                });
            }

            $(buttonId).prop('disabled', !allFilled);
        }

        $('#card-1 .required-input').on('input change', function () {
            checkInputs('#card-1', '#next-btn');
        });

        $('#card-2 input[type="radio"]').on('change', function () {
            checkInputs('#card-2', '#submit-btn');
        });

        checkInputs('#card-1', '#next-btn');
        checkInputs('#card-2', '#submit-btn');

        updateProgressBar();
    });

    function updateProgressBar() {
        const progressPercentage = (currentCard / totalCards) * 100;
        document.getElementById("progress-bar").style.width = progressPercentage + "%";
        document.getElementById("progress-text").textContent = `Page ${currentCard} of ${totalCards}`;

        document.getElementById("back-btn").style.display = currentCard > 1 ? "inline-block" : "none";
        document.getElementById("next-btn").style.display = currentCard < totalCards ? "inline-block" : "none";
        document.getElementById("submit-btn").style.display = currentCard === totalCards ? "inline-block" : "none";
    }

    // ✅ **Fix: Move to the next card properly**
    function nextCard() {
        if (currentCard >= totalCards) return;

        // Validate the current card before moving forward
        if (!$(getCurrentCardSelector()).find("input, select").valid()) {
            return; // Stop if invalid inputs exist
        }

        document.getElementById(`card-${currentCard}`).style.display = "none";
        currentCard++; // Increment card count
        document.getElementById(`card-${currentCard}`).style.display = "block";

        updateProgressBar();
    }

    // ✅ **Fix: Move to the previous card properly**
    function prevCard() {
        if (currentCard <= 1) return;

        document.getElementById(`card-${currentCard}`).style.display = "none";
        currentCard--; // Decrement card count
        document.getElementById(`card-${currentCard}`).style.display = "block";

        updateProgressBar();
    }

    // ✅ **Fix: Get current card selector**
    function getCurrentCardSelector() {
        return `#card-${currentCard}`;
    }

</script>