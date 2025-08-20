//for Selecting subject manually in modal 
$(document).ready(function() {
    $('#subjectSelect').change(function() {
        var selectedOption = $(this).find(':selected');
        $('#subjecID').val(selectedOption.data('subp-sid'));
        $('#sub_code').val(selectedOption.data('sub-code'));
        $('#sub_title').val(selectedOption.data('sub-title'));
        $('#subUnit').val(selectedOption.data('sub-unit'));
        $('#lecFee').val(selectedOption.data('lec-fee'));
        $('#labFee').val(selectedOption.data('lab-fee'));
        $('#devFee').val(selectedOption.data('dev-fee'));
        $('#itfee').val(selectedOption.data('it-fee'));
    });
});