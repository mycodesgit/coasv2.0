$(document).ready(function() {
    // Assuming you have variables for schlyear, semester, and campus
    var urlParams = new URLSearchParams(window.location.search);
    var schlyear = urlParams.get('schlyear') || ''; 
    var semester = urlParams.get('semester') || '';

    $.ajax({
        url: classSubOfferSchedReadRoute,
        type: 'GET',
        data: {
            schlyear: schlyear,
            semester: semester,
        },
        success: function(data) {
            var $select = $('#subject_id');
            $select.empty();
            $select.append('<option></option>');
            $.each(data, function(index, subject) {
                $select.append('<option value="' + subject.soschid + '">' + subject.sub_name + ' - ' + subject.subSec + '</option>');
            });
        },
        error: function() {
            alert('Failed to load subjects');
        }
    });
});