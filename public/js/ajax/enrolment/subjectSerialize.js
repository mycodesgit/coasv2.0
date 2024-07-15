$(document).ready(function() {
    $('#addSubject').submit(function(event) {
        event.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: subjectCreateRoute,
            type: "POST",
            data: formData,
            success: function(response) {
                if(response.success) {
                    toastr.success(response.message);
                    console.log(response);
                    $('#modal-subjects').modal('hide');
                    $(document).trigger('listsub');
                    $('input[name="sub_code"]').val('');
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

    var dataTable = $('#listsub').DataTable({
        "ajax": {
            "url": subjectReadRoute,
            "type": "GET",
        },
        responsive: true,
        lengthChange: true,
        searching: true,
        paging: true,
        "columns": [
            {data: 'sub_code'},
            {data: 'sub_name'},
            {data: 'sub_title'},
            {data: 'sub_unit'},
        ],
        "createdRow": function (row, data, index) {
            $(row).attr('id', 'tr-' + data.id); 
        }
    });
    $(document).on('listsub', function() {
        dataTable.ajax.reload();
    });
});


$(document).ready(function() {
    console.log('Document is ready');

    $('#college, #department').change(function() {
        console.log('Change event triggered');

        var college = $('#college').val();
        var department = $('#department').val();

        console.log('College:', college, 'Department:', department);

        // Set the values of subjcollege and subjdep with the selected college_abbr and deptCod
        $('#subjcollege').val(college);
        $('#subjdep').val(department);

        if (college && department) {
            $.ajax({
                url: subjectCodeRoute,
                type: 'GET',
                data: {
                    college_abbr: college,
                    deptCod: department
                },
                success: function(data) {
                    console.log('Received response:', data);
                    var combinedCode = college + '-' + department + '-' + data.nextNumber;
                    $('#sub_code').val(combinedCode);
                    $('#subjcostcenter').val(college + '-' + department);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', status, error);
                    alert('Error retrieving the next subject number');
                }
            });
        }
    });
});


