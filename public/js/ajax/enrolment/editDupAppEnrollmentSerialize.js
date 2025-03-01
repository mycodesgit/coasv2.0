toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right"
};
$(document).ready(function() {
    var urlParams = new URLSearchParams(window.location.search);
    var stud_id = urlParams.get('stud_id') || ''; 
    var schlyear = urlParams.get('schlyear') || ''; 
    var semester = urlParams.get('semester') || ''; 

    var dataTable = $('#tablestudFee').DataTable({
        "ajax": {
            "url": fetchstudFeeRoute,
            "type": "GET",
            "data": { 
                "stud_id": stud_id,
                "schlyear": schlyear,
                "semester": semester,
            }
        },
        destroy: true,
        info: true,
        responsive: true,
        lengthChange: false,
        searching: false,
        paging: false,
        "columns": [
            {data: 'fundID'},
            {data: 'account'},
            {data: 'amount'},
            {
                data: 'id',
                render: function(data, type, row) {
                    if (type === 'display') {
                        return '<button type="button" value="' + data + '" class="btn btn-outline-danger btn-sm dupapp-delete">' +
                               '<i class="fas fa-trash"></i>' +
                               '</button>';
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
    $(document).on('fundAdded', function() {
        dataTable.ajax.reload();
    });
});

$(document).on('click', '.dupapp-delete', function(e) {
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
                type: "POST",
                url: dupappDeleteRoute.replace(':id', id),
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

