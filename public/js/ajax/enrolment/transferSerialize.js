toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right"
};
$(document).ready(function() {
    $('#adStudTransfer').submit(function(event) {
        event.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: studtransferCreateRoute,
            type: "POST",
            data: formData,
            success: function(response) {
                if(response.success) {
                    toastr.success(response.message);
                    console.log(response);
                    $(document).trigger('transferAdded');
                    $('input[name="stud_id"]').val('');
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

    var campusMapping = {
            'VC': 'Victorias',
            'SCC': 'San Carlos',
            'HC': 'Hinigaran',
            'MP': 'Moises Padilla',
            'IC': 'Ilog',
            'CA': 'Candoni',
            'CC': 'Cauayan',
            'SC': 'Sipalay',
            'HinC': 'Hinobaan',
            'VE': 'Valladolid',
            'MC': 'Main'
        };

    var dataTable = $('#liststudtrans').DataTable({
        "ajax": {
            "url": studtransferReadRoute,
            "type": "GET",
        },
        destroy: true,
        info: true,
        responsive: true,
        lengthChange: true,
        searching: true,
        paging: true,
        "columns": [
            {
                data: null,
                render: function(data, type, row) {
                    var firstname = data.fname;
                    var middleInitial = data.mname ? data.mname.substr(0, 1) + '.' : '';
                    var lastName = data.lname;
                    var ext = data.ext && data.ext !== 'N/A' ? ' ' + data.ext : ' ';
                    
                    return lastName + ', ' + firstname + ' ' + middleInitial + ext;
                }
            },
            {data: 'stud_id'},
            {
                data: null, 
                render: function (data, type, row) {
                    var fromCampusName = campusMapping[data.fromcampus] || 'Unknown';
                    var toCampusName = campusMapping[data.tocampus] || 'Unknown';
                    
                    return `${fromCampusName} to ${toCampusName}`;
                },
            },
            { data: 'updated_at',
                render: function (data, type, row) {
                    if (type === 'display') {
                        return moment(data).format('MMMM D, YYYY');
                    } else {
                        return data;
                    }
                }
            },
            {
                data: 'id',
                render: function(data, type, row) {
                    if (type === 'display') {
                        var dropdown = '<div class="d-inline-block">' +
                            '<a class="btn btn-success btn-sm dropdown-toggle dropdown-icon text-light" data-bs-toggle="dropdown"></a>' +
                            '<div class="dropdown-menu">' +
                            '<a href="#" class="dropdown-item btn-studtransedit" data-id="' + row.id + '" data-studid="' + row.transcardidno + '" data-fromcamp="' + row.fromcampus + '">' +
                            '<i class="fas fa-pen"></i> Edit' +
                            '</a>' +
                            '<button type="button" value="' + data + '" class="dropdown-item kioskuser-delete">' +
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
        "createdRow": function (row, data, index) {
            $(row).attr('id', 'tr-' + data.studkiosid); 
        }
    });
    $(document).on('transferAdded', function() {
        dataTable.ajax.reload();
    });
});

$(document).on('click', '.btn-studtransedit', function() {
    var id = $(this).data('id');
    var studID = $(this).data('studid');
    var fromCamp = $(this).data('fromcamp');

    $('#editStudTransferId').val(id);
    $('#editStudTransferidcardno').val(studID);
    $('#editStudTransferfromcampus').val(fromCamp);

    $('#editStudTransferModal').modal('show');
});