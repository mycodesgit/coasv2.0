toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right"
};
$(document).ready(function() {
    $('#adFac').submit(function(event) {
        event.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: facultyCreateRoute,
            type: "POST",
            data: formData,
            success: function(response) {
                if(response.success) {
                    toastr.success(response.message);
                    console.log(response);
                    $(document).trigger('facAdded');
                    $('input[name="lname"]').val('');
                    $('input[name="fname"]').val('');
                    $('input[name="mname"]').val('');
                    $('input[name="ext"]').val('');
                    $('select[name="faccollege"]').val('');
                    $('select[name="facdept"]').val('');
                    $('select[name="adrID"]').val('');
                    $('input[name="email"]').val('');
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


    const campusMap = {
        'MC': 'Main',
        'VC': 'Victorias',
        'SCC': 'San Carlos',
        'HC': 'Hinigaran',
        'MP': 'Moise Padilla',
        'IC': 'Ilog',
        'CA': 'Candoni',
        'CC': 'Cauayan',
        'SC': 'Sipalay',
        'HinC': 'Hinobaan'
    };

    var dataTable = $('#facltyTable').DataTable({
        "ajax": {
            "url": facultyReadRoute,
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
                    const mname = row.mname ? row.mname : '';
                    return `${row.lname}, ${row.fname} ${mname}`;
                }
            },
            {data: 'adrDesc'},
            {data: 'college_abbr'},
            {
                data: null,
                render: function(data, type, row) {
                    if (type === 'display') {
                        var dept = row.deptCod || '';
                        var major = row.deptmajor || '';
                        
                        if (dept && major) {
                            return dept + ' - ' + major;
                        } else if (dept) {
                            return dept; // Only show department
                        } else {
                            return ''; // Empty if both are null
                        }
                    }
                    return data;
                },
                title: 'Department'
            },
            {data: 'rank'},
            { 
                data: 'fcamp', 
                title: 'Campus',
                render: function(data, type, row) {
                    if (!data) return '';
                    // Split comma-separated codes and map to names
                    const codes = data.split(',');
                    const names = codes.map(code => campusMap[code] || code);
                    return names.join(', ');
                }
            },
            {
                data: 'campactive',
                render: function(data, type, row) {
                    if (!data) return '';
                    // Directly map the code to name
                    return campusMap[data.trim()] || data;
                }
            },
            {
                data: 'fctyid',
                render: function(data, type, row) {
                    if (type === 'display') {
                        var dropdown = '<div class="d-inline-block">' +
                            '<a class="btn btn-success btn-sm dropdown-toggle dropdown-icon text-light" data-bs-toggle="dropdown"></a>' +
                            '<div class="dropdown-menu">' +
                            '<a href="#" class="dropdown-item btn-facultyedit" data-id="' + row.fctyid + '" data-flname="' + row.lname + '" data-ffname="' + row.fname + '" data-fmname="' + row.mname + '" data-fxname="' + row.ext + '" data-adrname="' + row.prefix + '" data-suffix="' + row.suffix + '" data-faccollege="' + row.faccollege + '" data-facdept="' + row.facdept + '" data-email="' + row.email + '" data-rank="' + row.rank + '" data-deptmajor="' + row.deptmajor + '">' +
                            '<i class="fas fa-pen"></i> Edit' +
                            '</a>' +
                            // '<button type="button" value="' + data + '" class="dropdown-item faclty-delete">' +
                            // '<i class="fas fa-trash"></i> Delete' +
                            // '</button>' +
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
            $(row).attr('id', 'tr-' + data.fctyid); 
        }
    });
    $(document).on('facAdded', function() {
        dataTable.ajax.reload();
    });
});

// Toggle function
function toggleSedDropdown() {
    var selectedDept = $('#editdept').val();
    
    if (selectedDept === 'SED') {
        $('#sedDropdownContainer').slideDown(300);
        $('#sedProgram').prop('required', true);
    } else {
        $('#sedDropdownContainer').slideUp(300);
        $('#sedProgram').prop('required', false);
        $('#sedProgram').val('');
    }
}

$(document).ready(function() { 
    // Bind change event
    $('#editdept').on('change', toggleSedDropdown);

    $(document).on('click', '.btn-facultyedit', function() {
        var id = $(this).data('id');
        var lName = $(this).data('flname');
        var fName = $(this).data('ffname');
        var mName = $(this).data('fmname');
        var exName = $(this).data('fxname');
        var prefixName = $(this).data('adrname');
        var suffixName = $(this).data('suffix');
        var collegeName = $(this).data('faccollege');
        var deptName = $(this).data('facdept');
        var email = $(this).data('email');
        var rank = $(this).data('rank');
        var deptMajor = $(this).data('deptmajor');

        $('#editFacultyId').val(id);
        $('#editLastname').val(lName);
        $('#editFirstname').val(fName);
        $('#editMiddlename').val(mName);
        $('#editExtname').val(exName);
        $('#editPrefix').val(prefixName);
        $('#editSuffix').val(suffixName);
        $('#editcollege').val(collegeName);
        $('#editdept').val(deptName);
        $('#editEmail').val(email);
        $('#eeditacadrank').val(rank);

        // Set program if exists
        if (deptName === 'SED' && deptMajor) {
            $('#sedProgram').val(deptMajor);
        } else {
            $('#sedProgram').val('');
        }
        
        // Trigger change to show/hide SED dropdown
        $('#editdept').trigger('change');

        $('#editFacultyModal').modal('show');
    });
});

$('#editFacultyForm').submit(function(event) {
    event.preventDefault();
    var formData = $(this).serialize();

    $.ajax({
        url: facultyUpdateRoute,
        type: "POST",
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if(response.success) {
                toastr.success(response.message);
                document.activeElement.blur();
                $('#editFacultyModal').modal('hide');
                $(document).trigger('facAdded');
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

$(document).on('click', '.faclty-delete', function(e) {
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
                type: "GET",
                url: facultyDeleteRoute.replace(':id', id),
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

$(document).ready(function(){
    $('#college').on('change', function(){
        var collegeCode = $(this).val();
        if(collegeCode) {
            var url = getdepartmentRoute.replace(':college', collegeCode);
            $.ajax({
                url: url,
                type: 'GET',
                success: function(data) {

                    $('#department').empty();
                    $('#department').append('<option disabled selected> ---Select---</option>');

                    $.each(data, function(key, value){
                        $('#department').append(
                            '<option value="'+ value.deptCod +'">'+ value.deptName +'</option>'
                        );
                    });
                }
            });
        } else {
            $('#department').empty();
        }
    });
});

$(document).ready(function(){
    function loadDepartments(collegeCode) {
        if(collegeCode) {
            var url = getdepartmentRoute.replace(':college', collegeCode);
            $.ajax({
                url: url,
                type: 'GET',
                success: function(data) {

                    $('#editdept').empty();
                    $('#editdept').append('<option disabled selected> ---Select---</option>');

                    $.each(data, function(key, value){
                        $('#editdept').append(
                            '<option value="'+ value.deptCod +'">'+ value.deptName +'</option>'
                        );
                    });
                }
            });

        } else {
            $('#editdept').empty();
        }
    }
    // When value changes
    $('#editcollege').on('change', function(){
        loadDepartments($(this).val());
    });
    // When user clicks the dropdown again
    $('#editcollege').on('focus', function(){
        loadDepartments($(this).val());
    });
});

$(document).ready(function() {
    $('#isNew').change(function() {
        let value = $(this).val();

        if (value === 'yes') {
            $('#newFacultyForm').show();
            $('#existingFaculty').hide();
        } else if (value === 'no') {
            $('#newFacultyForm').hide();
            $('#existingFaculty').show();
        }
    });
});

$(document).ready(function() {
    var $facultySelect = $('#facultySelect');

    var $searchInput = $('<input type="text" class="form-control form-control-sm mb-2" placeholder="Type to search...">');
    $facultySelect.before($searchInput);

    var $label = $('<label>Result:</label>');
    $facultySelect.before($label);

    $searchInput.on('input', function() {
        var searchTerm = $(this).val();
        if (searchTerm.length < 2) { 
            $facultySelect.find('option:not(:first)').remove();
            return;
        }
        $.ajax({
            url: searchFacultyRoute,
            type: "GET",
            dataType: "json",
            data: { search: searchTerm },
            success: function(data) {
                $facultySelect.find('option:not(:first)').remove();
                $.each(data, function(index, faculty) {
                    $facultySelect.append(
                        $('<option></option>')
                            .attr('value', faculty.id)
                            .text(faculty.text)
                    );
                });
            },
            error: function() {
                console.error("Failed to load faculty list.");
            }
        });
    });
});

const campusMap = {
    'Main': 'MC',
    'Victorias': 'VC',
    'San Carlos': 'SCC',
    'Hinigaran': 'HC',
    'Moise Padilla': 'MP',
    'Ilog': 'IC',
    'Candoni': 'CA',
    'Cauayan': 'CC',
    'Sipalay': 'SC',
    'Hinobaan': 'HinC'
};

const campusInput = document.getElementById('campusInput');
const campusHidden = document.getElementById('campusHidden');
const campactiveHidden = document.getElementById('campactiveHidden');
const facultySelect = document.getElementById('facultySelect');
const saveBtn = document.getElementById('saveCampusBtn');
const token = document.querySelector('input[name="_token"]').value;

campusInput.addEventListener('change', function() {
    const code = campusMap[this.value] || '';
    campusHidden.value = code;
    campactiveHidden.value = code; 
});

saveBtn.addEventListener('click', function(e) {
    e.preventDefault(); 

    const facultyId = facultySelect.value;
    const campusCode = campusHidden.value;
    const activeCampus = campactiveHidden.value;

    if (!facultyId || !campusCode) {
        toastr.warning('Please select a faculty and campus.');
        return;
    }

    const url = campusUpdateRoute.replace(':id', facultyId);

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token
        },
        body: JSON.stringify({ 
            campus: campusCode,
            campactive: activeCampus
        })
    })
    .then(response => response.json())
    .then(data => {
        toastr.success(data.message, 'Success');
        $(document).trigger('facAdded'); 
    })
    .catch(err => {
        toastr.error('Error updating campus.', 'Error');
        console.error(err);
    });
});
