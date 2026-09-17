<script>
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right"
    };

    $(document).ready(function() {

        $('#addShipmentForm').submit(function(event) {
            event.preventDefault();
            var formData = $(this).serialize();

            $.ajax({
                url: shipmentCreateRoute,
                type: "POST",
                data: formData,
                success: function(response) {
                    if(response.success) {
                        toastr.success(response.message);
                        console.log(response);
                        $(document).trigger('shipmentUpdated');
                        $('#addShipmentForm')[0].reset();
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

        var dataTable = $('#shipmentListTable').DataTable({
            "ajax": {
                "url": shipmentReadRoute,
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
                    data: 'yearbook',
                    render: function(data, type, row) {
                        if (data) {
                            return '<b>' + data.edition_title + '</b><br><small class="text-muted">SY ' + data.school_year + '</small>';
                        }
                        return '<span class="text-muted">N/A</span>';
                    }
                },
                { data: 'supplier_name' },
                { 
                    data: 'tracking_number',
                    render: function(data) {
                        return data ? '<code>' + data + '</code>' : '<span class="text-muted">—</span>';
                    }
                },
                { data: 'quantity_sent' },
                { data: 'quantity_received' },
                {
                    data: 'status',
                    render: function(data) {
                        if (data === 'pending') {
                            return '<span class="badge bg-secondary">Pending</span>';
                        } else if (data === 'released_by_supplier') {
                            return '<span class="badge bg-info text-dark">In Transit</span>';
                        } else if (data === 'received_by_office') {
                            return '<span class="badge bg-success">Received</span>';
                        } else if (data === 'disputed') {
                            return '<span class="badge bg-danger">Disputed</span>';
                        }
                        return '<span class="badge bg-light text-dark">' + data + '</span>';
                    }
                },
                {
                    data: 'id',
                    render: function(data, type, row) {
                        if (type === 'display') {
                            if (row.status === 'released_by_supplier') {
                                return '<button type="button" class="btn btn-sm btn-success btn-receive-shipment" ' +
                                    'data-id="' + row.id + '" ' +
                                    'data-sent="' + row.quantity_sent + '" ' +
                                    'data-tracking="' + (row.tracking_number || 'N/A') + '" ' +
                                    'data-toggle="tooltip" title="Receive Stock">' +
                                    '<i class="fas fa-box-open"></i> Receive</button>';
                            } else {
                                return '<button class="btn btn-sm btn-light text-muted border" disabled>' +
                                    '<i class="fas fa-check"></i> Processed</button>';
                            }
                        }
                        return data;
                    }
                }
            ],
            "createdRow": function (row, data, index) {
                $(row).attr('id', 'tr-' + data.id);
            }
        });
        $(document).on('shipmentUpdated', function() {
            dataTable.ajax.reload();
        });
    });

    $(document).on('click', '.btn-receive-shipment', function() {
        var id = $(this).data('id');
        var sentQty = $(this).data('sent');
        var tracking = $(this).data('tracking');

        $('#receiveShipmentId').val(id);
        $('#receiveQuantity').val(sentQty).attr('max', sentQty);
        $('#receiveNotes').val('');
        $('#modalShipmentInfo').html('<strong>Tracking:</strong> ' + tracking + ' | <strong>Dispatched:</strong> ' + sentQty + ' copies');

        $('#receiveShipmentModal').modal('show');
    });

    $('#receiveShipmentForm').submit(function(event) {
        event.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: shipmentReceiveRoute,
            type: "POST",
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if(response.success) {
                    toastr.success(response.message);
                    $('#receiveShipmentModal').modal('hide');
                    $(document).trigger('shipmentUpdated');
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                var errorMessage = xhr.responseText ? JSON.parse(xhr.responseText).message : 'An error occurred';
                toastr.error(errorMessage);
            }
        });
    });
</script>
