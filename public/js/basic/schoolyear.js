document.addEventListener("DOMContentLoaded", function() {
    let academicYearStart = 2018;
    let academicYearEnd = 2030;
    let selectedYear = (new Date).getFullYear();
    let option = '<option disabled selected>Year</option>';

    for (let i = academicYearStart; i <= academicYearEnd; i++) {
        let nextYear = i + 1;
        let academicYear = i + '-' + nextYear;
        let selected = (i === selectedYear ? ' selected' : '');
        option += `<option value="${academicYear}"${selected}>${academicYear}</option>`;
    }

    let yearDropdown = document.getElementById("schlyear");
    if (yearDropdown) {
        yearDropdown.innerHTML = option;
        let inputField = document.querySelector('input[name="schlyear"]');
        if (inputField) {
            inputField.setAttribute('readonly', true);
        }
    }
});
