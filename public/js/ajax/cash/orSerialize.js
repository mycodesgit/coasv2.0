toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right"
};
$(document).ready(function() {
    $('#studFeeAssess').submit(function(event) {
        event.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: studfeeCreateRoute,
            type: "POST",
            data: formData,
            success: function(response) {
                if(response.success) {
                    toastr.success(response.message);
                    console.log(response);
                    $(document).trigger('studFeeAdded');
                    $('input[name="amountFee"]').val('');
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
    var campus = urlParams.get('campus') || ''; 
    var progCode = urlParams.get('prog_Code') || ''; 
    var yrlevel = urlParams.get('yrlevel') || ''; 
    var schlyear = urlParams.get('schlyear') || ''; 
    var semester = urlParams.get('semester') || '';
    var dataTable = $('#studentFees').DataTable({
        "ajax": {
            "url": studfeeReadRoute,
            "type": "GET",
            "data": { 
                "campus": campus,
                "prog_Code": progCode,
                "yrlevel": yrlevel,
                "schlyear": schlyear,
                "semester": semester
            }
        },
        info: false,
        responsive: true,
        lengthChange: false,
        searching: false,
        paging: false,
        "columns": [
            {data: 'fundname_code'},
            {data: 'accountName'},
            {
                data: 'amountFee',
                render: function (data, type, row) {
                    return '<strong>' + parseFloat(data).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,') + '</strong>';
                }
            },
            {
                data: 'id',
                render: function(data, type, row) {
                    if (type === 'display') {
                        var dropdown = '<div class="d-inline-block">' +
                            '<a class="btn btn-primary btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown"></a>' +
                            '<div class="dropdown-menu">' +
                            '<a href="#" class="dropdown-item btn-studfee" data-id="' + row.id + '" data-fundcode="' + row.fundname_code + '" data-fundstudname="' + row.accountName + '" data-fundstudamount="' + row.amountFee + '">' +
                            '<i class="fas fa-pen"></i> Edit' +
                            '</a>' +
                            '<button type="button" value="' + data + '" class="dropdown-item studfees-delete">' +
                            '<i class="fas fa-trash"></i> Delete' +
                            '</button>' +
                            '</div>' +
                            '</div>';
                        return dropdown;
                    } else {
                        return data;
                    }
                },
            },
        ],
        "footerCallback": function (row, data, start, end, display) {
            var api = this.api();
            grandTotal = api.column(2, {page: 'current'}).data().reduce(function (a, b) {
                return parseFloat(a) + parseFloat(b);
            }, 0);
            $(api.column(2).footer()).html(parseFloat(grandTotal).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            $('#grandTotal').text(parseFloat(grandTotal).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        },
        "createdRow": function (row, data, index) {
            $(row).attr('id', 'tr-' + data.id); 
        }
    });
    $(document).on('studFeeAdded', function() {
        dataTable.ajax.reload();
    });
});