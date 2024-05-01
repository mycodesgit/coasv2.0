$(document).ready(function() {
    function updateInputFields(selectedOption) {
        var programCode = selectedOption.data('program-code');
        var programID = selectedOption.data('program-classid');
        var programName = selectedOption.data('program-name');
        var yearSec = selectedOption.data('year-section');
        var classSection = selectedOption.data('section');
        var parts = classSection.split('-');

        $('#editprogramCodeInput').val(programCode);
        $('#editprogramIDInput').val(programID);
        $('#editprogramNameInput').val(programName);
        $('#edityearsectionInput').val(yearSec);

        if (parts.length === 2) {
            var numericPart = parts[0];
            var alphabeticalPart = parts[1];
            $('#editnumericPart').val(numericPart);
            $('#editalphabeticalPart').val(alphabeticalPart);
        }
    }

    var selectedOption = $('#programNameEditSelect').find('option:selected');
    
    updateInputFields(selectedOption);

    $('#programNameEditSelect').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        updateInputFields(selectedOption);
    });
});
