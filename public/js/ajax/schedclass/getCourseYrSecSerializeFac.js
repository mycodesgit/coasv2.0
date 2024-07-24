$(document).ready(function() {
    var urlParams = new URLSearchParams(window.location.search);
    var schlyear = urlParams.get('schlyear') || ''; 
    var semester = urlParams.get('semester') || '';
    var campus = urlParams.get('campus') || ''; 

    $.ajax({
        url: classenrollyrsecReadRoute,
        type: 'GET',
        data: {
            schlyear: schlyear,
            semester: semester,
            campus: campus
        },
        success: function(data) {
            var $select = $('#progCod');
            $select.empty();
            $select.append('<option></option>');
            $.each(data, function(index, prog) {
                $select.append('<option value="' + prog.progCode + '" data-section="' + prog.classSection + '">' + prog.progAcronym + ' ' + prog.classSection + '</option>');
            });
        },
        error: function() {
            alert('Failed to load rooms');
        }
    });

    $('#progCod').on('change', function() {
        var selectedSection = $(this).find('option:selected').data('section');
        $('#classSection').val(selectedSection);
    });
});
