// $(document).ready(function () {
//     $('#submitBtn').click(function () {
//         $('#confirmationForm').submit();
//     });
// });

$(document).ready(function () {
    $('#submitBtn').click(function (e) {
        e.preventDefault(); // Stop page reload

        var form = $('#confirmationForm');

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            success: function (response) {
                // 1. Close the confirmation modal
                $('#submitgrades').modal('hide');

                // 2. Reload ONLY the table body element from the current page URL
                $('#tableBodyContent').load(window.location.href + ' #tableBodyContent > *', function() {
                    // Optional: Re-initialize any plugins or tooltips attached to new table rows
                });

                // 3. Disable the main submit button until new edits occur
                $('#submitgradeid').prop('disabled', true);
            },
            error: function () {
                alert('Failed to submit grades. Please try again.');
            }
        });
    });
});

// Store grade choices for dynamic dropdown generation
const gradeCodes = gradecode;

$(document).ready(function () {

    // -------------------------------------------------------------
    // 1. Password Verification (Exact selector matching)
    // -------------------------------------------------------------

    // Edit Grades Password Verification
    $(document).on('input', 'input[id^="gradeauthpass"]:not([id*="Completion"])', function() {
        var id = $(this).attr('id').replace('gradeauthpass', '');
        var password = $(this).val();
        var editBtn = $('#editBtn' + id);

        if (password.length > 0) {
            $.ajax({
                url: passgradeRoute,
                type: "POST",
                data: {
                    _token: passgradeTokenRoute,
                    password: password
                },
                success: function(response) {
                    editBtn.prop('disabled', response.status !== 'success');
                },
                error: function() {
                    editBtn.prop('disabled', true);
                }
            });
        } else {
            editBtn.prop('disabled', true);
        }
    });

    // Edit Completion Password Verification
    $(document).on('input', 'input[id^="gradeauthpassCompletion"]', function() {
        var id = $(this).attr('id').replace('gradeauthpassCompletion', '');
        var password = $(this).val();
        var editCompletionBtn = $('#editCompletionBtn' + id);

        if (password.length > 0) {
            $.ajax({
                url: passgradeRoute,
                type: "POST",
                data: {
                    _token: passgradeTokenRoute,
                    password: password
                },
                success: function(response) {
                    editCompletionBtn.prop('disabled', response.status !== 'success');
                },
                error: function() {
                    editCompletionBtn.prop('disabled', true);
                }
            });
        } else {
            editCompletionBtn.prop('disabled', true);
        }
    });

    // -------------------------------------------------------------
    // 2. Helper function to generate dropdown without reloading page
    // -------------------------------------------------------------
    function generateGradeSelect(id, name, actionFunc, currentVal = '') {
        let options = '<option></option>';
        gradeCodes.forEach(function(item) {
            let selected = (item.grade == currentVal) ? 'selected' : '';
            options += `<option value="${item.grade}" ${selected}>${item.grade}</option>`;
        });

        return `<select class="form-control form-control-sm" name="${name}" id="${id}" onchange="${actionFunc}(this.id, this.value)">
                    ${options}
                </select>`;
    }

    // -------------------------------------------------------------
    // 3. AJAX Submission for Modals (No Page Reload)
    // -------------------------------------------------------------

    // Handle Edit Grades Confirmation Form
    $(document).on('submit', '[id^=editConfirmForm]', function(e) {
        e.preventDefault();
        var form = $(this);
        var id = form.find('input[name="id"]').val();

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            success: function(response) {
                // Hide modal using jQuery
                $('#editgrades' + id).modal('hide');

                // Reset password field & button
                $('#gradeauthpass' + id).val('');
                $('#editBtn' + id).prop('disabled', true);

                // Dynamically replace table cell content
                let newSelectHtml = generateGradeSelect(id, 'subjFgrade', 'updateGrade');
                $('#gradeCell' + id).html(newSelectHtml);
            },
            error: function() {
                alert('An error occurred while authorizing grade edits.');
            }
        });
    });

    // Handle Edit Completion Grades Confirmation Form
    $(document).on('submit', '[id^=editCompletionForm]', function(e) {
        e.preventDefault();
        var form = $(this);
        var id = form.find('input[name="id"]').val();

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            success: function(response) {
                // Hide modal using jQuery
                $('#editCompletiongrades' + id).modal('hide');

                // Reset password field & button
                $('#gradeauthpassCompletion' + id).val('');
                $('#editCompletionBtn' + id).prop('disabled', true);

                // Dynamically replace table cell content
                let newSelectHtml = generateGradeSelect(id, 'subjComp', 'updateGradeComp');
                $('#compCell' + id).html(newSelectHtml);
            },
            error: function() {
                alert('An error occurred while authorizing completion grade edits.');
            }
        });
    });

});

// $(document).ready(function() {
//     $('[id^=gradeauthpass]').on('input', function() {
//         var id = $(this).attr('id').replace('gradeauthpass', '');
//         var password = $(this).val();
//         var editBtn = $('#editBtn' + id);

//         if (password) {
//             $.ajax({
//                 url: passgradeRoute,
//                 type: "POST",
//                 data: {
//                     _token: passgradeTokenRoute,
//                     password: password
//                 },
//                 success: function(response) {
//                     if (response.status === 'success') {
//                         editBtn.prop('disabled', false);
//                     } else {
//                         editBtn.prop('disabled', true);
//                     }
//                 },
//                 error: function() {
//                     editBtn.prop('disabled', true);
//                 }
//             });
//         } else {
//             editBtn.prop('disabled', true);
//         }
//     });

//     $('[id^=gradeauthpassCompletion]').on('input', function() {
//         var id = $(this).attr('id').replace('gradeauthpassCompletion', '');
//         var password = $(this).val();
//         var editCompletionBtn = $('#editCompletionBtn' + id);

//         if (password) {
//             $.ajax({
//                 url: passgradeRoute,
//                 type: "POST",
//                 data: {
//                     _token: passgradeTokenRoute,
//                     password: password
//                 },
//                 success: function(response) {
//                     if (response.status === 'success') {
//                         editCompletionBtn.prop('disabled', false);
//                     } else {
//                         editCompletionBtn.prop('disabled', true);
//                     }
//                 },
//                 error: function() {
//                     editCompletionBtn.prop('disabled', true);
//                 }
//             });
//         } else {
//             editCompletionBtn.prop('disabled', true);
//         }
//     });

//     $('[id^=editBtn]').click(function () {
//         var id = $(this).attr('id').replace('editBtn', '');
//         $('#editConfirmForm' + id).submit();
//     });

//     $('[id^=editCompletionBtn]').click(function () {
//         var id = $(this).attr('id').replace('editCompletionBtn', '');
//         $('#editCompletionForm' + id).submit();
//     });
// });


