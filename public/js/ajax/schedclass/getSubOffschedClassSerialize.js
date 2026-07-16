var subjectsData = [];

$(document).ready(function () {

    var urlParams = new URLSearchParams(window.location.search);
    var schlyear = urlParams.get('schlyear') || '';
    var semester = urlParams.get('semester') || '';
    var progCod = urlParams.get('progCod') || '';

    console.log('schlyear:', schlyear);
    console.log('semester:', semester);
    console.log('progCod:', progCod);

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

            // Subject dropdown
            var $subject = $('#subject_id');
            $subject.empty().append('<option></option>');

            $.each(subjectsData, function (i, subject) {
                $subject.append(
                    '<option value="' + subject.soschid + '">' +
                    subject.sub_name + ' - ' + subject.subSec +
                    '</option>'
                );
            });

        },
        error: function () {
            alert('Failed to load subjects');
        }
    });

});

$('#subject_id').on('change', function () {

    var selectedId = $(this).val();

    if (!selectedId) return;

    // Get selected subject
    var selectedSubject = subjectsData.find(function(item){
        return item.soschid == selectedId;
    });

    if (!selectedSubject) return;

    var $merge = $('#merge_sections');
    $merge.empty();

    // Show only same subject (same subCode)
    $.each(subjectsData, function(index, subject){

        if(subject.subCode == selectedSubject.subCode &&
           subject.soschid != selectedSubject.soschid){

            $merge.append(
                '<option value="' + subject.soschid + '">' +
                subject.sub_name + ' - ' + subject.subSec +
                '</option>'
            );
        }

    });

    $merge.trigger('change.select2');
});