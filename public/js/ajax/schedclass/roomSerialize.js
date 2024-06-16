toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right"
};
$(document).ready(function() {
    $('#adRoom').submit(function(event) {
        event.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: roomsCreateRoute,
            type: "POST",
            data: formData,
            success: function(response) {
                if(response.success) {
                    toastr.success(response.message);
                    console.log(response);
                    $(document).trigger('roomAdded');
                    $('select[name="college_room"]').val('');
                    $('input[name="room_name"]').val('');
                    $('input[name="room_capacity"]').val('');
                } else {
                    toastr.error(response.message);
                    console.log(response);
                }
            },
            error: function(xhr, status, error, message) {
                var errorMessage = xhr.responseText ? JSON.parse(xhr.responseText).message : 'An error occurred';
                toastr.error(errorMessage);
            }
        });
    });

    var dataTable = $('#classRooms').DataTable({
        "ajax": {
            "url": roomsReadRoute,
            "type": "GET",
        },
        responsive: true,
        lengthChange: true,
        searching: true,
        paging: true,
        "columns": [
            {data: 'college_abbr'},
            {data: 'room_name'},
            {data: 'room_capacity'},
            {data: 'campus'},
            {
                data: 'rmid',
                render: function(data, type, row) {
                    if (type === 'display') {
                        var dropdown = '<div class="d-inline-block">' +
                            '<a class="btn btn-primary btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown"></a>' +
                            '<div class="dropdown-menu">' +
                            '<a href="#" class="dropdown-item btn-roomedit" data-id="' + row.rmid + '" data-college="' + row.college_room + '" data-room="' + row.room_name + '" data-capacity="' + row.room_capacity + '">' +
                            '<i class="fas fa-pen"></i> Edit' +
                            '</a>' +
                            '<button type="button" value="' + data + '" class="dropdown-item room-delete">' +
                            '<i class="fas fa-trash"></i> Delete' +
                            '</button>' +
                            '</div>' +
                            '</div>';
                        return dropdown;
                    } else {
                        return data;
                    }
                },
            },
        ],
        "createdRow": function (row, data, index) {
            $(row).attr('id', 'tr-' + data.rmid); 
        }
    });
    $(document).on('roomAdded', function() {
        dataTable.ajax.reload();
    });
});


$(document).on('click', '.btn-roomedit', function() {
    var id = $(this).data('id');
    var collegeId = $(this).data('college');
    var roomName = $(this).data('room');
    var roomCapacity = $(this).data('capacity');

    $('#editRoomId').val(id);
    $('#editRoomCollege').val(collegeId);
    $('#editRoomName').val(roomName);
    $('#editRoomCapacity').val(roomCapacity);

    // Set the selected option in the dropdown
    $('#college_room').val(collegeId);

    // Add highlight class to the form group
    $('#college_room').closest('.form-group').addClass('highlight');

    $('#editRoomModal').modal('show');
});

// Remove the highlight class when the modal is hidden
$('#editRoomModal').on('hidden.bs.modal', function () {
    $('#college_room').closest('.form-group').removeClass('highlight');
});

$('#editRoomForm').submit(function(event) {
    event.preventDefault();
    var formData = $(this).serialize();

    $.ajax({
        url: roomsUpdateRoute,
        type: "POST",
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if(response.success) {
                toastr.success(response.message);
                $('#editRoomModal').modal('hide');
                $(document).trigger('roomAdded');
            } else {
                toastr.error(response.message);
            }
        },
        error: function(xhr, status, error, message) {
            var errorMessage = xhr.responseText ? JSON.parse(xhr.responseText).message : 'An error occurred';
            toastr.error(errorMessage);
        }
    });
});

$(document).on('click', '.room-delete', function(e) {
    var id = $(this).val();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
    });
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to recover this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: "GET",
                url: roomsDeleteRoute.replace(':rmid', id),
                success: function(response) {
                    $("#tr-" + id).delay(1000).fadeOut();
                    Swal.fire({
                        title: 'Deleted!',
                        text: 'Successfully Deleted!',
                        icon: 'warning',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    if(response.success) {
                        toastr.success(response.message);
                        console.log(response);
                    }
                }
            });
        }
    })
});


