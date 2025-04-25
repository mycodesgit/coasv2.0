toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right"
};
$(document).ready(function() {
    var dataTable = $('#regions').DataTable({
        "ajax": {
            "url": allRegionRoute,
            "type": "GET",
        },
        destroy: true,
        info: true,
        responsive: true,
        lengthChange: true,
        searching: true,
        paging: true,
        "columns": [
            {data: 'region_id'},
            {data: 'name'},
            {
                data: 'id',
                render: function(data, type, row) {
                    if (type === 'display') {
                        var dropdown = '<div class="d-inline-block">' +
                            '<a class="btn btn-primary btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown"></a>' +
                            '<div class="dropdown-menu">' +
                            '<a href="#" class="dropdown-item btn-regionedit" data-id="' + row.id + '" data-name="' + row.name + '">' +
                            '<i class="fas fa-pen"></i> Edit' +
                            '</a>' +
                            '<button type="button" value="' + data + '" class="dropdown-item fund-delete">' +
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
            $(row).attr('id', 'tr-' + data.id); 
        }
    });
    $(document).on('regionAdded', function() {
        dataTable.ajax.reload();
    });
});

$(document).on('click', '.btn-regionedit', function() {
    var id = $(this).data('id');
    var regionName = $(this).data('name');

    $('#editRegionId').val(id);
    $('#editRegionName').val(regionName);

    $('#editRegionModal').modal('show');
});

$(document).ready(function() {
    var dataTable = $('#provinces').DataTable({
        "ajax": {
            "url": allProvinceRoute,
            "type": "GET",
        },
        destroy: true,
        info: true,
        responsive: true,
        lengthChange: true,
        searching: true,
        paging: true,
        "columns": [
            {data: 'province_id'},
            {data: 'region_name'},
            {data: 'name'},
            {
                data: 'id',
                render: function(data, type, row) {
                    if (type === 'display') {
                        var dropdown = '<div class="d-inline-block">' +
                            '<a class="btn btn-primary btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown"></a>' +
                            '<div class="dropdown-menu">' +
                            '<a href="#" class="dropdown-item btn-provinceedit" data-id="' + row.id + '" data-name="' + row.name + '">' +
                            '<i class="fas fa-pen"></i> Edit' +
                            '</a>' +
                            '<button type="button" value="' + data + '" class="dropdown-item fund-delete">' +
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
            $(row).attr('id', 'tr-' + data.id); 
        }
    });
    $(document).on('provinceAdded', function() {
        dataTable.ajax.reload();
    });
});

$(document).on('click', '.btn-provinceedit', function() {
    var id = $(this).data('id');
    var provinceName = $(this).data('name');

    $('#editProvinceId').val(id);
    $('#editProvinceName').val(provinceName);

    $('#editProvinceModal').modal('show');
});

$(document).ready(function() {
    var dataTable = $('#city').DataTable({
        "ajax": {
            "url": allCityRoute,
            "type": "GET",
        },
        destroy: true,
        info: true,
        responsive: true,
        lengthChange: true,
        searching: true,
        paging: true,
        "columns": [
            {data: 'city_id'},
            {data: 'region_name'},
            {data: 'province_name'},
            {data: 'name'},
            {data: 'zip_code'},
            {
                data: 'id',
                render: function(data, type, row) {
                    if (type === 'display') {
                        var dropdown = '<div class="d-inline-block">' +
                            '<a class="btn btn-primary btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown"></a>' +
                            '<div class="dropdown-menu">' +
                            '<a href="#" class="dropdown-item btn-cityedit" data-id="' + row.id + '" data-name="' + row.name + '" data-zipcode="' + row.zip_code + '">' +
                            '<i class="fas fa-pen"></i> Edit' +
                            '</a>' +
                            '<button type="button" value="' + data + '" class="dropdown-item fund-delete">' +
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
            $(row).attr('id', 'tr-' + data.id); 
        }
    });
    $(document).on('cityAdded', function() {
        dataTable.ajax.reload();
    });
});

$(document).on('click', '.btn-cityedit', function() {
    var id = $(this).data('id');
    var cityName = $(this).data('name');
    var zipCode = $(this).data('zipcode');

    $('#editCityId').val(id);
    $('#editCityName').val(cityName);
    $('#editZipcode').val(zipCode);

    $('#editCityModal').modal('show');
});

$('#editCityForm').submit(function(event) {
    event.preventDefault();
    var formData = $(this).serialize();

    $.ajax({
        url: allcityUpdateRoute,
        type: "POST",
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if(response.success) {
                toastr.success(response.message);
                $('#editCityModal').modal('hide');
                $(document).trigger('cityAdded');
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