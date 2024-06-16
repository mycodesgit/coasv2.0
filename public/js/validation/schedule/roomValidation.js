$(function () {
    $('#adRoom').validate({
        rules: {
            college_room: {
                required: true,
            },
            room_name: {
                required: true,
            },
            room_capacity: {
                required: true,
            },
        },
        messages: {
            college_room: {
                required: "Select which college belongs to the room",
            },
            room_name: {
                required: "Enter Room Name",
            },
            room_capacity: {
                required: "Enter Room Capacity",
            },
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.col-md-12').append(error);        
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
    });
});