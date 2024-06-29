<script>
    var currentInput = "studID_no";
    function updateSelect(value) {
        var inputElement = document.getElementById(currentInput);
        var currentValue = inputElement.value;
        currentValue = currentValue.replace(/[^a-zA-Z0-9-]/g, '');
        var newValue = currentValue + value;

        if (newValue.length > 12) {
            newValue = newValue.substring(0, 12);
        }
        var parts = newValue.split('-');
        if (parts.length >= 2) {
            parts[0] = parts[0].substring(0, 4);
            parts[1] = parts[1].substring(0, 4);
        }
        if (parts.length >= 3) {
            var lastPart = parts[2];
        if (lastPart.length > 1) {
            lastPart = lastPart.charAt(0);
        }
            lastPart = lastPart.replace(/[^A-Z]/g, '');
            parts[2] = lastPart;
        }
        newValue = parts.join('-');
        inputElement.value = newValue;
    }

    document.getElementById("btn-1").addEventListener("click", function () {
        updateSelect("1");
    });

    document.getElementById("btn-2").addEventListener("click", function () {
        updateSelect("2");
    });

    document.getElementById("btn-3").addEventListener("click", function () {
        updateSelect("3");
    });

    document.getElementById("btn-4").addEventListener("click", function () {
        updateSelect("4");
    });

    document.getElementById("btn-5").addEventListener("click", function () {
        updateSelect("5");
    });

    document.getElementById("btn-6").addEventListener("click", function () {
        updateSelect("6");
    });

    document.getElementById("btn-7").addEventListener("click", function () {
        updateSelect("7");
    });

    document.getElementById("btn-8").addEventListener("click", function () {
        updateSelect("8");
    });

    document.getElementById("btn-9").addEventListener("click", function () {
        updateSelect("9");
    });

    document.getElementById("btn-0").addEventListener("click", function () {
        updateSelect("0");
    });

    document.getElementById("btn-minus").addEventListener("click", function () {
        updateSelect("-");
    });

    document.getElementById("btn-k").addEventListener("click", function () {
        updateSelect("K");
    });

    document.getElementById("btn-g").addEventListener("click", function () {
        updateSelect("G");
    });

    document.getElementById("btn-u").addEventListener("click", function () {
        updateSelect("U");
    });

    document.getElementById("btn-reset").addEventListener("click", function () {
        var inputElement = document.getElementById("studID_no");
        var currentValue = inputElement.value;
        var newValue = currentValue.substring(0, currentValue.length - 1);
        inputElement.value = newValue;
    });

    document.getElementById("btn-reset").addEventListener("click", function () {
        var inputElement = document.getElementById("password");
        var currentValue = inputElement.value;
        var newValue = currentValue.substring(0, currentValue.length - 1);
        inputElement.value = newValue;
    });


    document.getElementById("studID_no").addEventListener("focus", function () {
        currentInput = "studID_no";
    });

    
    var buttons = document.querySelectorAll(".btn-app");

    buttons.forEach(function (button) {
        button.addEventListener("click", function () {
            if (button.classList.contains("clicked")) {
                button.classList.remove("clicked");
            } else {
                button.classList.add("clicked");
            }
            buttons.forEach(function (otherButton) {
                if (otherButton !== button) {
                    otherButton.classList.remove("clicked");
                }
            });
        });
    });

</script>