$(document).ready(function() {

    $.ajax({
        url: classFacultySchedReadRoute,
        type: 'GET',
        success: function(data) {
            var $select = $('#faculty_id');
            $select.empty();
            $select.append('<option></option>');
            $.each(data, function(index, faculty) {
                $select.append('<option value="' + faculty.id + '">' + faculty.lname + ' ' + faculty.fname + ' ' + faculty.mname + '</option>');
            });
        },
        error: function() {
            alert('Failed to load faculty');
        }
    });
});