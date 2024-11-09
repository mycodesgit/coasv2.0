toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right"
};

$(document).ready(function() {
    var dataTable = $('#collegeProg').DataTable({
        "ajax": {
            "url": collegeReadRoute,
            "type": "GET",
        },
        responsive: true,
        lengthChange: true,
        searching: true,
        paging: true,
        "columns": [
            {data: 'college_abbr'},
            {data: 'college_name'},
            {data: 'campus'},
            {
            data: 'id',
                render: function(data, type, row) {
                    if (type === 'display') {
                        var editLink = '<a href="#" class="btn btn-primary btn-sm btn-editcol" data-id="' + row.id + '" data-colabbr="' + row.college_abbr + '" data-colname="' + row.college_name + '" data-colcamp="' + row.campus + '">' +
                            '<i class="fas fa-eye"></i>' +
                            '</a>';
                        return editLink;
                    } else {
                        return data;
                    }
                },
            },
        ],
        "createdRow": function (row, data, index) {
            $(row).attr('id', 'tr-' + data.id); 
        }
    });
    $(document).on('collegeAdded', function() {
        dataTable.ajax.reload();
    });
});


$(document).on('click', '.btn-editcol', function() {
    var id = $(this).data('id');
    var colAbbr = $(this).data('colabbr');
    var colName = $(this).data('colname');
    var colCamp = $(this).data('colcamp');
    var selectedCampus = colCamp.split(',');
    
    $('#editCollegeId').val(id);
    $('#editCollegeAbbr').val(colAbbr);
    $('#editCollegeName').val(colName);
    $('#editCampAbbr').val(colCamp);
    $('.select2').val(selectedCampus);
    $('.select2').trigger('change');

    $('#editCollegeModal').modal('show');

    $.ajax({
        url: idEncryptRoute,
        type: "POST",
        data: { data: $('#editCollegeId').val() },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            //alert(response); 
            $('#editCollegeId').val(response)
        },
        error: function(xhr, status, error) {
            alert('Error: ' + error); 
        }
    });

});

$('#editCollegeForm').submit(function(event) {
    event.preventDefault();
    var formData = $(this).serialize();

    $.ajax({
        url: collegeUpdateRoute,
        type: "POST",
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if(response.success) {
                toastr.success(response.message);
                $('#editCollegeModal').modal('hide');
                $(document).trigger('collegeAdded');
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

