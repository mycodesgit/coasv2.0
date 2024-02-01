document.addEventListener("DOMContentLoaded", function() {
    let year_start = 2018; // Set the starting year
    let year_end = 2030;
    let year_selected = (new Date).getFullYear();
    let option = '<option value="">Year</option>';

    for (let i = year_start; i <= year_end; i++) {
        let selected = (i === year_selected ? ' selected' : '');
        option += '<option value="' + i + '"' + selected + '>' + i + '</option>';
    }

    let yearDropdown = document.getElementById("year");
    if (yearDropdown) {
        yearDropdown.innerHTML = option;
    }
});
