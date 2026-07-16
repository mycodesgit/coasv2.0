var subjectsData = [];
var allSubjectsForMerge = [];

$(document).ready(function () {

    var urlParams = new URLSearchParams(window.location.search);
    var schlyear = urlParams.get('schlyear') || '';
    var semester = urlParams.get('semester') || '';
    var progCod = urlParams.get('progCod') || '';

    // Load subjects for the selected course
    $.ajax({
        url: classSubOfferSchedReadRoute,
        type: 'GET',
        data: {
            schlyear: schlyear,
            semester: semester,
            progCod: progCod
        },
        success: function (data) {
            subjectsData = data;

            var $subject = $('#subject_id');
            $subject.empty().append('<option></option>');

            $.each(subjectsData, function (i, subject) {
                $subject.append(
                    '<option value="' + subject.soschid + '">' +
                    subject.sub_name + ' - ' + subject.subSec +
                    '</option>'
                );
            });

            $subject.trigger('change.select2');
        },
        error: function () {
            //alert('Failed to load subjects');
            console.log('Failed to load all subjects for merge');
        }
    });

    // Load ALL subjects for merge (all sections)
    $.ajax({
        url: getAllSubjectsMergeRoute, // Use the new route
        type: 'GET',
        data: {
            schlyear: schlyear,
            semester: semester
        },
        success: function (data) {
            allSubjectsForMerge = data;
            //console.log('All subjects loaded for merge:', allSubjectsForMerge.length);
        },
        error: function () {
            //console.log('Failed to load all subjects for merge');
        }
    });

    // When subject is selected, load merge options
    $('#subject_id').on('change', function () {
        var selectedId = $(this).val();

        if (!selectedId) {
            $('#merge_sections').empty().append('<option></option>');
            $('#merge_sections').trigger('change.select2');
            return;
        }

        var selectedSubject = subjectsData.find(function(item){
            return item.soschid == selectedId;
        });

        if (!selectedSubject) return;

        var $merge = $('#merge_sections');
        $merge.empty();
        $merge.append('<option></option>');

        // Use allSubjectsForMerge to find other sections with same subCode
        var found = false;
        $.each(allSubjectsForMerge, function(index, subject){
            if(subject.subCode == selectedSubject.subCode && 
               subject.soschid != selectedSubject.soschid){
                $merge.append(
                    '<option value="' + subject.soschid + '">' +
                    subject.sub_name + ' - ' + subject.subSec +
                    '</option>'
                );
                found = true;
            }
        });

        if (!found) {
            $merge.append('<option value="" disabled>No other sections available</option>');
        }

        $merge.trigger('change.select2');
    });
});