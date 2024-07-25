toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right"
};
$(document).ready(function() {

    var urlParams = new URLSearchParams(window.location.search);
    var schlyear = urlParams.get('schlyear') || ''; 
    var semester = urlParams.get('semester') || '';
    var progCod = urlParams.get('progCod') || '';

    var dataTable = $('#asd').DataTable({
        "ajax": {
            "url": classplottedReadRoute,
            "type": "GET",
            "data": { 
                "schlyear": schlyear,
                "semester": semester,
                "progCod": progCod,
            }
        },
        responsive: false,
        lengthChange: false,
        searching: false,
        paging: false,
        "columns": [
            {
                data: null,
                render: function (data, type, row) {
                    return `${row.sub_name} - ${row.subSec}`;
                },
            },
            {data: 'sub_title'},
            {
                data: null,
                render: function (data, type, row) {
                    return `${row.lname}, ${row.fname}`;
                },
            },
            {
                data: null,
                render: function (data, type, row) {
                    return `${row.schedday}, ${row.start_time}, ${row.end_time} `;
                },
            },
            {
            data: 'id',
                render: function(data, type, row) {
                    if (type === 'display') {
                        var delLink = '<button type="button" value="' + data + '" class="btn btn-outline-danger btn-sm btn-plotdelete">' +
                            '<i class="fas fa-trash"></i>' +
                            '</button>';
                        return delLink;
                    } else {
                        return data;
                    }
                },
            width: '2%',
            className: 'text-center'
            },
        ],
        "createdRow": function (row, data, index) {
            $(row).attr('id', 'tr-' + data.id); 
        }
    });
    $(document).on('subjOffAdded', function() {
        dataTable.ajax.reload();
    });
});

$(document).on('click', '.btn-plotdelete', function(e) {
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
            //alert('You are about to delete item with ID: ' + id);
            $.ajax({
                type: "GET",
                url: classplottedDeleteRoute.replace(':id', id),
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


