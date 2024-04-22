toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right"
};
$(document).ready(function() {
    var urlParams = new URLSearchParams(window.location.search);
    var schlyear = urlParams.get('schlyear') || ''; 
    var semester = urlParams.get('semester') || '';
    var campus = urlParams.get('campus') || '';

    var dataTable = $('#schstud').DataTable({
        "ajax": {
            "url": studschReadRoute,
            "type": "GET",
            "data": { 
                "schlyear": schlyear,
                "semester": semester,
                "campus": campus
            }
        },
        responsive: true,
        lengthChange: true,
        searching: true,
        paging: true,
        "columns": [
            { data: 'lname', render: function(data, type, row) {
                return row.lname + ', ' + row.fname;
            } },
            { data: 'studentID' },
            { data: null, render: function(data, type, row) {
                return row.progAcronym + ' ' + row.studYear + '-' + row.studSec;
            } },
            { data: 'scholar_name' },
            { data: 'scholar_sponsor' },
            { data: 'chedsch_name' },
            { data: 'unisch_name' },
            { data: 'amount', render: $.fn.dataTable.render.number(',', '.', 2) },
            {
            data: 'id',
                render: function(data, type, row) {
                    if (type === 'display') {
                        var editLink = '<a href="#" class="btn btn-primary btn-sm btn-studSchEn" data-id="' + row.id + '" data-studentid="' + row.studentID + '" data-studentname="' + row.lname + ', ' + row.fname +'" data-sch="' + row.schid + '">' +
                            '<i class="fas fa-eye"></i>' +
                            '</a>';
                        return editLink;
                    } else {
                        return data;
                    }
                },
            },
        ],
        "columnDefs": [
            {
                "targets": [3, 4, 5, 6],
                "render": function(data, type, row) {
                    return '<div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 15ch;">' + data + '</div>';
                }
            }
        ]
    });
    $(document).on('schstudAdded', function() {
        dataTable.ajax.reload();
    });
});

$(document).on('click', '.btn-studSchEn', function() {
    var id = $(this).data('id');
    var studID = $(this).data('studentid');
    var studName = $(this).data('studentname');
    var studSCH = $(this).data('sch');
    
    $('#editstudSchEnId').val(id);
    $('#editstudSchEnStudID').val(studID);
    $('#editstudSchEnStudName').val(studName);
    $('#editstudSchEnSch').val(studSCH);
    $('#editstudSchEnModal').modal('show');

    $.ajax({
        url: idStudSchEncryptRoute,
        type: "POST",
        data: { data: $('#editstudSchEnId').val() },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            //alert(response); 
            $('#editstudSchEnId').val(response)
        },
        error: function(xhr, status, error) {
            alert('Error: ' + error); 
        }
    });
});

$('#editstudSchEnForm').submit(function(event) {
    event.preventDefault();
    var formData = $(this).serialize();

    $.ajax({
        url: studschUpdateRoute,
        type: "POST",
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if(response.success) {
                toastr.success(response.message);
                $('#editstudSchEnModal').modal('hide');
                $(document).trigger('schstudAdded');
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
