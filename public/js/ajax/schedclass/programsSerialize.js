$(document).ready(function() {
    var dataTable = $('#classProg').DataTable({
        "ajax": {
            "url": progReadRoute,
            "type": "GET",
        },
        responsive: true,
        lengthChange: true,
        searching: true,
        paging: true,
        "columns": [
            {data: 'progCod'},
            {data: 'progAcronym'},
            {data: 'progName'},
            {data: 'campus'},
            {
                data: 'id',
                render: function(data, type, row) {
                    if (type === 'display') {
                        var dropdown = '<div class="d-inline-block">' +
                            '<a class="btn btn-primary btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown"></a>' +
                            '<div class="dropdown-menu">' +
                            '<a href="#" class="dropdown-item btn-programedit" data-id="' + row.id + '" data-college="' + row.progCollege + '" data-department="' + row.progDep + '" data-programcod="' + row.progCod + '" data-programaccnt="' + row.progAccount + '" data-programname="' + row.progName + '" data-programacro="' + row.progAcronym + '" data-programlv="' + row.progLev + '" data-campus="' + row.campus + '">' +
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
    $(document).on('progAdded', function() {
        dataTable.ajax.reload();
    });
});

$(document).on('click', '.btn-programedit', function() {
    var id = $(this).data('id');
    var college = $(this).data('college');
    var department = $(this).data('department');
    var progCode = $(this).data('programcod');
    var progaccount = $(this).data('programaccnt');
    var proganame = $(this).data('programname');
    var progAcro = $(this).data('programacro');
    var proglv = $(this).data('programlv');
    var camp = $(this).data('campus');
    var selectedCampus = camp ? camp.split(',') : [];

    $('#editProgramId').val(id);
    $('#college').val(college);
    $('#department').val(department);
    $('#editprogCod').val(progCode);
    $('#progaccount').val(progaccount);
    $('#progName').val(proganame);
    $('#progAcronym').val(progAcro);
    $('#editprogLev').val(proglv);
    $('#editCampAbbr').val(camp);
    $('.select2').val(selectedCampus);
    $('.select2').trigger('change');


    $('#editProgramModal').modal('show');

    $.ajax({
        url: idEncryptRoute,
        type: "POST",
        data: { data: $('#editProgramId').val() },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            //alert(response); 
            $('#editProgramId').val(response)
        },
        error: function(xhr, status, error) {
            alert('Error: ' + error); 
        }
    });
});

$(document).ready(function () {
    console.log('Document is ready');

    // When College changes: load departments
    $('#college').change(function () {
        var college = $(this).val();
        $('#subjcollege').val(college);
        $('#department').empty().append('<option disabled selected>Loading...</option>');

        $.ajax({
            url: progDeptRoute, // Add this route in web.php
            type: 'GET',
            data: { college_abbr: college },
            success: function (data) {
                $('#department').empty().append('<option disabled selected>--Select--</option>');
                $.each(data, function (key, dept) {
                    $('#department').append(`<option value="${dept.deptCod}">${dept.deptName}</option>`);
                });
            },
            error: function (xhr, status, error) {
                console.error('Error fetching departments:', status, error);
                alert('Failed to load departments.');
            }
        });
    });

    // When either College or Department changes: get next program number
    $('#college, #department').change(function () {
        var college = $('#college').val();
        var department = $('#department').val();

        $('#subjcollege').val(college);
        $('#subjdep').val(department);

        if (college && department) {
            $.ajax({
                url: progCodeRoute,
                type: 'GET',
                data: {
                    college_abbr: college,
                    deptCod: department
                },
                success: function (data) {
                    var combinedCode = college + '-' + department + '-' + data.nextNumber;
                    $('#editprogCod').val(combinedCode);
                    $('#progaccount').val('TUITION - ' + college);
                },
                error: function (xhr, status, error) {
                    console.error('AJAX error:', status, error);
                    alert('Error retrieving the next program number');
                }
            });
        }
    });
});

$('#editProgramForm').submit(function(event) {
    event.preventDefault();
    var formData = $(this).serialize();

    $.ajax({
        url: progUpdateRoute,
        type: "POST",
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if(response.success) {
                toastr.success(response.message);
                $('#editProgramModal').modal('hide');
                $('select[name="campus[]"]').val('');
                $(document).trigger('progAdded');
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

