$(document).ready(function() {
    $('#schlyear1, #semester').change(function() {
        var schlyear = $('#schlyear1').val();
        var semester = $('#semester').val();
        if (schlyear && semester) {
            $.ajax({
                url: classenrollyrsecReadRoute,
                type: 'GET',
                data: {
                    schlyear: schlyear,
                    semester: semester
                },
                success: function(data) {
                    $('#progCod').empty();
                    $('#progCod').append('<option disabled selected>Select a course</option>');
                    $.each(data, function(key, value) {
                        $('#progCod').append('<option value="' + value.progCode + ' ' + value.classSection + '">' + value.progAcronym + ' ' + value.classSection + '</option>');
                    });
                }
            });
        }
    });
});