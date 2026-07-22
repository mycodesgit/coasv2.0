<script>
toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right"
};

$(document).ready(function() {

    const scanner = document.getElementById("rfidScanner");
    const display = document.getElementById("studentUniqueRFID");
    let lastScannedRFID = "";

    scanner.addEventListener("keypress", function(e) {
        if (e.key === "Enter") {
            e.preventDefault(); 

            let currentRFID = scanner.value.trim();

            // Prevent duplicate scan
            if(currentRFID !== "" && currentRFID !== lastScannedRFID) {
                display.value = currentRFID;
                lastScannedRFID = currentRFID;
            }

            scanner.value = ""; 
        }
    });

    $('#adRFIDstud').submit(function(event) {
        event.preventDefault();

        // if(display.value.trim() === "") {
        //     toastr.error("Please scan the RFID card before saving.");
        //     return;
        // }

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

                    //$('#adRFIDstud')[0].reset();
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