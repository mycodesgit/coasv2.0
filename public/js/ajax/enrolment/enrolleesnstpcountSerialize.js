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
                //$('#studentCount').html('<h3>Total: ' + response.numstudproghisTotal + '</h3>');
                $('#studentCountFirst').html('<h4>NSTP: ' + response.numstudnstpTotal + '</h4>');
                $('#studentCountSecond').html('<h4>CWTS: ' + response.numstudnstpcwtsTotal + '</h4>');
                $('#studentCountThird').html('<h4>LTS: ' + response.numstudnstpltsTotal + '</h4>');
                $('#studentCountFourth').html('<h4>ROTC: ' + response.numstudnstprotcTotal + '</h4>');
                $('#resultModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.error(error);
                alert('An error occurred while processing your request.');
            }
        });
    });
});