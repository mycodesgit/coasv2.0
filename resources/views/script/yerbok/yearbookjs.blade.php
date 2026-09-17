<script>
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right"
    };

    $(document).ready(function() {

        $('#adYearbook').submit(function(event) {
            event.preventDefault();
            var formData = $(this).serialize();

            $.ajax({
                url: yearBookCreateRoute,
                type: "POST",
                data: formData,
                success: function(response) {
                    if(response.success) {
                        toastr.success(response.message);
                        console.log(response);
                        $(document).trigger('yrbookAdded');
                        $('#adYearbook')[0].reset();
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

        var dataTable = $('#yearbooklistTable').DataTable({
            "ajax": {
                "url": yearBookReadRoute,
                "type": "GET",
            },
            destroy: true,
            info: true,
            responsive: true,
            lengthChange: true,
            searching: true,
            paging: true,
            "columns": [
                {data: 'school_year'},
                {data: 'edition_title'},
                {data: 'total_ordered'},
                {data: 'total_received'},
                {data: 'unit_cost'},
                {
                    data: 'id',
                    render: function(data, type, row) {
                        if (type === 'display') {
                            var buttons = '<button type="button" class="btn btn-sm btn-success btn-yrbokedit mr-1 text-light" data-id="' + row.id + '" data-schlyear="' + row.school_year + '" data-editiontitle="' + row.edition_title + '" data-totalordered="' + row.total_ordered + '" data-totalreceived="' + row.total_received + '" data-unitcost="' + row.unit_cost + '" data-campus="' + row.campus + '" data-toggle="tooltip" data-placement="top" title="Edit Category.">';
                            buttons += '<i class="ti ti-pencil"></i> </button>' +'&nbsp;';
                            buttons += '<button type="button" value="' + data + '" class="btn btn-sm btn-danger category-delete" data-toggle="tooltip" data-placement="top" title="Delete Category."><i class="ti ti-trash"></i> </button>';
                            return buttons;
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
        $(document).on('yrbookAdded', function() {
            dataTable.ajax.reload();
        });
    });

    $(document).on('click', '.btn-yrbokedit', function() {
        var id = $(this).data('id');
        var schlyear = $(this).data('schlyear');
        var editiontitle = $(this).data('editiontitle');
        var totalordered = $(this).data('totalordered');
        var totalreceived = $(this).data('totalreceived');
        var unitcost = $(this).data('unitcost');
        var campus = $(this).data('campus');

        $('#editYearbookModalId').val(id);
        $('#editYearbook').val(schlyear);
        $('#editEditableTitle').val(editiontitle);
        $('#editTotalOrdered').val(totalordered);
        $('#editTotalReceived').val(totalreceived);
        $('#editCost').val(unitcost);
        $('#editIsCampus').val(campus);

        $('#editYearbookModal').modal('show');
    });

    $('#editYearbookForm').submit(function(event) {
        event.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: yearBookUpdateRoute,
            type: "POST",
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if(response.success) {
                    toastr.success(response.message);
                    $('#editYearbookModal').modal('hide');
                    $(document).trigger('yrbookAdded');
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
</script>
