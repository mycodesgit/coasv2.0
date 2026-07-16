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

    // When course is selected, load subjects
    $('#progCod').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var progCod = selectedOption.val();
        var classSection = selectedOption.data('section');
        
        // Set the hidden input value
        $('#classSection').val(classSection);

        // Clear previous subjects
        var $subjectSelect = $('#subject_id');
        $subjectSelect.empty();
        $subjectSelect.append('<option disabled selected>Loading subjects...</option>');

        // Load subjects based on selected course
        if (progCod && classSection) {
            // Combine progCod and section with a space (or + depending on your URL format)
            var fullProgCod = progCod + ' ' + classSection; // or progCod + '+' + classSection
            
            $.ajax({
                url: classSubOfferSchedReadRoute,
                type: 'GET',
                data: {
                    schlyear: schlyear,
                    semester: semester,
                    progCod: fullProgCod // Pass the combined value
                },
                success: function(data) {
                    $subjectSelect.empty();
                    $subjectSelect.append('<option></option>');
                    
                    if (data.length === 0) {
                        $subjectSelect.append('<option disabled>No subjects available</option>');
                    } else {
                        $.each(data, function(i, subject) {
                            $subjectSelect.append(
                                '<option value="' + subject.soschid + '">' +
                                subject.sub_name + ' - ' + subject.subSec +
                                '</option>'
                            );
                        });
                    }
                },
                error: function() {
                    $subjectSelect.empty();
                    $subjectSelect.append('<option disabled>Failed to load subjects</option>');
                    alert('Failed to load subjects');
                }
            });
        } else {
            $subjectSelect.empty();
            $subjectSelect.append('<option disabled>Please select a course first</option>');
        }
    });
});

