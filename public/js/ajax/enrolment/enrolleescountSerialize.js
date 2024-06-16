$(document).ready(function() {
    $('#submitForm').on('click', function(e) {
        e.preventDefault();

        var form = $('#enrollStud');
        var formData = form.serialize();

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#studentCount').html('<h3>Total enrolled: ' + response.numstudproghisTotal + '</h3>');
                $('#studentCountFirst').html('<h4>First year: ' + response.numstudproghisfirst + '</h4>');
                $('#studentCountSecond').html('<h4>Second year: ' + response.numstudproghissecond + '</h4>');
                $('#studentCountThird').html('<h4>Third year: ' + response.numstudproghisthird + '</h4>');
                $('#studentCountFourth').html('<h4>Fourth year: ' + response.numstudproghistfourth + '</h4>');
                $('#resultModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.error(error);
                alert('An error occurred while processing your request.');
            }
        });
    });
});