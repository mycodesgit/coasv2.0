<script>
toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right"
};

$(document).ready(function() {

    // RFID scanner listener
    const scanner = document.getElementById("rfidScanner");
    const display = document.getElementById("studentUniqueRFID");
    let lastScannedRFID = "";

    scanner.addEventListener("keypress", function(e) {
        if (e.key === "Enter") {
            e.preventDefault(); // <-- prevents autosubmit

            let currentRFID = scanner.value.trim();

            // Prevent duplicate scan
            if(currentRFID !== "" && currentRFID !== lastScannedRFID) {
                display.value = currentRFID;
                lastScannedRFID = currentRFID;
            }

            scanner.value = ""; // clear for next scan
        }
    });

    // Form submission via AJAX
    $('#adRFIDstud').submit(function(event) {
        event.preventDefault();

        // Prevent saving if no RFID scanned
        if(display.value.trim() === "") {
            toastr.error("Please scan the RFID card before saving.");
            return;
        }

        // Serialize as string to preserve full Student ID format
        var formData = new FormData(this); 

        $.ajax({
            url: rfidstudentCreateRoute,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if(response.success) {
                    toastr.success(response.message);
                    console.log(response);

                    // Reset form and prepare for next student
                    $('#adRFIDstud')[0].reset();
                    lastScannedRFID = "";
                    $('#stdntID').focus();
                } else {
                    toastr.error(response.message);
                    console.log(response);
                }
            },
            error: function(xhr) {
                var errorMessage = xhr.responseText ? JSON.parse(xhr.responseText).message : 'An error occurred';
                toastr.error(errorMessage);
            }
        });
    });

});
</script>