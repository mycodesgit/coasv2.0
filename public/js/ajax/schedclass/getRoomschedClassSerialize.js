$(document).ready(function() {

    $.ajax({
        url: classRoomSchedReadRoute,
        type: 'GET',
        success: function(data) {
            var $select = $('#room_id');
            $select.empty();
            $select.append('<option></option>');
            $.each(data, function(index, room) {
                $select.append('<option value="' + room.id + '">' + room.room_name + '</option>');
            });
        },
        error: function() {
            alert('Failed to load rooms');
        }
    });
});