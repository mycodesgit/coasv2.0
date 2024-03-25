toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right"
};
$(document).ready(function() {
    $('#subjOffer').submit(function(event) {
        event.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: subOfferedCreateRoute,
            type: "POST",
            data: formData,
            success: function(response) {
                if(response.success) {
                    toastr.success(response.message);
                    console.log(response);
                    $(document).trigger('subjOffAdded');
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

    var urlParams = new URLSearchParams(window.location.search);
    var schlyear = urlParams.get('schlyear') || ''; 
    var semester = urlParams.get('semester') || '';

    var dataTable = $('#subofferedlist').DataTable({
        "ajax": {
            "url": subOfferedReadRoute,
            "type": "GET",
            "data": { 
                "schlyear": schlyear,
                "semester": semester,
            }
        },
        responsive: true,
        lengthChange: true,
        searching: true,
        paging: true,
        "columns": [
            {data: 'subCode'},
            {data: 'subSec'},
            {
                data: 'semester',
                render: function(data, type, row) {
                    if (data == 1) {
                        return '1st';
                    } else if (data == 2) {
                        return '2nd';
                    } else if (data == 3) {
                        return 'Summer';
                    } else {
                        return 'Unknown Semester';
                    }
                }
            },
            {data: 'sub_name'},
            {data: 'lecUnit'},
            {data: 'labUnit'},
            {data: 'subUnit'},
            {data: 'maxstud'},
            {
                data: 'fundAccount',
                render: function(data, type, row) {
                    return data ? data : 'No Account';
                }
            }

        ],
        "createdRow": function (row, data, index) {
            $(row).attr('id', 'tr-' + data.id); 
        }
    });
    $(document).on('subjOffAdded', function() {
        dataTable.ajax.reload();
    });
});

$(document).ready(function() {
    $('#subCode').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var subcode = selectedOption.data('sub-code');
        var lecUnit = selectedOption.data('lec-unit');
        var labUnit = selectedOption.data('lab-unit');
        
        $('#subcode').val(subcode);
        $('#lecUnit').val(lecUnit);
        $('#labUnit').val(labUnit);
        $('#subUnit').val(lecUnit + labUnit);
    });
});

$(document).ready(function() {
    $('#fundSelect').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var fundId = selectedOption.val();
        var accountName = selectedOption.data('account-name');
        
        $('#fundIdInput').val(fundId);
        $('#accountNameInput').val(accountName);
    });
});



