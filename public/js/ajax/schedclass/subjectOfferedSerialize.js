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
    var campus = '';
    if (isuserRole) {
        campus = urlParams.get('campus') || '';
    }

    var dataTable = $('#subofferedlist').DataTable({
        "ajax": {
            "url": subOfferedReadRoute,
            "type": "GET",
            "data": function(d) { 
                d.schlyear = schlyear;
                d.semester = semester;
                if (isuserRole) {
                    d.campus = campus;
                }
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
            {data: 'lecFee'},
            {data: 'labFee'},
            {data: 'devFee'},
            {data: 'isType'},
            {
                data: 'fundAccount',
                render: function(data, type, row) {
                    return data ? data : 'No Account';
                }
            },
            {data: 'itfee'},
            {
                data: 'soid',
                render: function(data, type, row) {
                    if (type === 'display') {
                        var dropdown = '<div class="d-inline-block">' +
                            '<a class="btn btn-success btn-sm dropdown-toggle dropdown-icon text-light" data-bs-toggle="dropdown"></a>' +
                            '<div class="dropdown-menu">' +
                            '<a href="#" class="dropdown-item btn-studSubOffer" data-id="' + row.soid + '" data-subcode="' + row.subCode + '" data-subsec="' + row.subSec + '" data-lecunit="' + row.lecUnit + '" data-labunit="' + row.labUnit + '" data-subunit="' + row.subUnit + '" data-lecfee="' + row.lecFee + '" data-labfee="' + row.labFee + '" data-devfee="' + row.devFee + '" data-maxstud="' + row.maxstud + '" data-fund="' + row.fund + '" data-istemp="' + row.isTemp + '" data-isojt="' + row.isOJT + '" data-istype="' + row.isType + '" data-itfee="' + row.itfee + '" data-fundaccount="' + row.fundAccount + '">' +
                            '<i class="fas fa-pen"></i> Edit' +
                            '</a>' +
                            '<button type="button" value="' + data + '" class="dropdown-item subsoff-delete">' +
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
            $(row).attr('id', 'tr-' + data.soid); 
        }
    });
    $(document).on('subjOffAdded', function() {
        dataTable.ajax.reload();
    });
});

// $(document).ready(function() {
//     $('#subCode').on('change', function() {
//         var selectedOption = $(this).find('option:selected');
//         var subcode = selectedOption.data('sub-code');
//         var lecUnit = selectedOption.data('lec-unit');
//         var labUnit = selectedOption.data('lab-unit');
        
//         $('#subcode').val(subcode);
//         $('#lecUnit').val(lecUnit);
//         $('#labUnit').val(labUnit);
//         $('#subUnit').val(lecUnit + labUnit);
//     });
// });

// $(document).ready(function() {
//     $('#subCode').on('change', function() {
//         var selectedOption = $(this).find('option:selected');
//         var subcode = selectedOption.data('sub-code');
//         var lecUnit = selectedOption.data('lec-unit');
//         var labUnit = selectedOption.data('lab-unit');
//         var subUnit = lecUnit + labUnit;

//         $('#subcode').val(subcode);
//         $('#lecUnit').val(lecUnit);
//         $('#labUnit').val(labUnit);
//         $('#subUnit').val(subUnit);

//         calculateFees(subUnit, labUnit);
//     });

//     function calculateFees(subUnit, labUnit) {
//         var lecFee = subUnit * 180;
//         var labFee = labUnit > 0 ? 500 : 0;

//         $('#lecFee').val(lecFee);
//         $('#labFee').val(labFee);
//     }
// });

$(document).ready(function() {
    $('#subCode').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var subcode = selectedOption.data('sub-code');
        var lecUnit = selectedOption.data('lec-unit');
        var labUnit = selectedOption.data('lab-unit');
        var subUnit = lecUnit + labUnit;

        $('#subcode').val(subcode);
        $('#lecUnit').val(lecUnit);
        $('#labUnit').val(labUnit);
        $('#subUnit').val(subUnit);

        if (subcode.startsWith('KAB-GSS')) {
            $('#lecFee').removeAttr('readonly');
        } else {
            $('#lecFee').attr('readonly', 'readonly');
        }

        calculateFees(subcode, subUnit, labUnit);
    });

    function calculateFees(subcode, subUnit, labUnit) {
        var specialCodes = ["KAB-SER-076", "KAB-SER-077", "KAB-SER-144", "KAB-SER-145", "KAB-SER-146", "KAB-SER-147", "KAB-SER-148", "KAB-SER-149"];
        // var lecFee = specialCodes.includes(subcode) ? 270 : subUnit * 180;
        // var labFee = labUnit > 0 ? 500 : 0;

        var lecFee = 0;

        // Set lecFee to 0 if subcode starts with "KAB-GSS"
        if (subcode.startsWith('KAB-GSS')) {
            lecFee = 0;
        } else if (specialCodes.includes(subcode)) {
            lecFee = 270;
        } else {
            lecFee = subUnit * 180;
        }

        var labFee = labUnit > 0 ? 500 : 0;

        $('#lecFee').val(lecFee);
        $('#labFee').val(labFee);

        if (labUnit > 0) {
        $('#fundSelect option:eq(1)').prop('selected', true);
            $('#fundSelect').trigger('change');
        }
    }
    
    $('#isOJT').on('change', function () {
        let isOJT = $(this).val();
        let subUnit = parseFloat($('#subUnit').val()) || 0;
        let urlParams = new URLSearchParams(window.location.search);
        let semester = urlParams.get('semester');

        if (semester === '1' || semester === '2' || semester === '3') {
            if (isOJT === 'Yes') {
                let labFee = 3000;
                $('#devFee').val(labFee);
            } else if (isOJT === 'YesThesis') {
                let labFee = 500;
                $('#labFee').val(labFee);
            } else if (isOJT === 'YesPrac') {
                let labFee = 500;
                $('#labFee').val(labFee);
            } else {
                $('#labFee').val(0);
                $('#devFee').val(0);
            }
        }
        // If semester is not 3, do nothing
    });

    $('#isOJTSelect').on('change', function () {
        let isOJT = $(this).val();
        let subUnit = parseFloat($('#subUnitEdit').val()) || 0;
        let urlParams = new URLSearchParams(window.location.search);
        let semester = urlParams.get('semester');

        if (semester === '1' || semester === '2' || semester === '3') {
            if (isOJT === 'Yes') {
                let labFee = 3000;
                $('#editdevfee').val(labFee);
            } else if (isOJT === 'YesThesis') {
                let labFee = 500;
                $('#editlabfee').val(labFee);
            } else if (isOJT === 'YesPrac') {
                let labFee = 500;
                $('#editlabfee').val(labFee);
            } else {
                $('#editlabfee').val(0);
                $('#editdevfee').val(0);
            }
        }
    });
});

$(document).ready(function() {
    $('#subCodeEdit').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var subcodeEdit = selectedOption.data('sub-codeedit');
        var lecUnitEdit = selectedOption.data('lec-unitedit');
        var labUnitEdit = selectedOption.data('lab-unitedit');
        
        $('#subcodeEdit').val(subcodeEdit);
        $('#lecUnitEdit').val(lecUnitEdit);
        $('#labUnitEdit').val(labUnitEdit);
        $('#subUnitEdit').val(lecUnitEdit + labUnitEdit);
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

$(document).ready(function() {
    $('#fundSelectEdit').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var fundIdEdit = selectedOption.val();
        var accountNameEdit = selectedOption.data('account-nameedit');
        
        $('#fundIdInputEdit').val(fundIdEdit);
        $('#accountNameInputEdit').val(accountNameEdit);
    });
});

$(document).on('click', '.btn-studSubOffer', function() {
    var id = $(this).data('id');
    var subofferCode = $(this).data('subcode');
    var subsec = $(this).data('subsec');
    var lecUnit = $(this).data('lecunit');
    var labUnit = $(this).data('labunit');
    var subUnit = $(this).data('subunit');
    var lecfee = $(this).data('lecfee');
    var labfee = $(this).data('labfee');
    var devfee = $(this).data('devfee');
    var maxstud = $(this).data('maxstud');
    var fund = $(this).data('fund');
    var isTemp = $(this).data('istemp');
    var isOJTSelect = $(this).data('isojt');
    var isTypeSelect = $(this).data('istype');
    var fundSelectEdit = $(this).data('fundaccount');
    var fundAccount = $(this).data('fundaccount');
    var itfee = $(this).data('itfee');

    $('#editSubOfferId').val(id);
    $('#subcodeEdit').val(subofferCode);
    $('#subsecEdit').val(subsec);
    $('#lecUnitEdit').val(lecUnit);
    $('#lecUnitEdit').val(lecUnit);
    $('#labUnitEdit').val(labUnit);
    $('#subUnitEdit').val(subUnit);
    $('#editlecfee').val(lecfee);
    $('#editlabfee').val(labfee);
    $('#editdevfee').val(devfee);
    $('#editmaxstud').val(maxstud);
    $('#fundEdit').val(fund);
    $('#isTempSelect').val(isTemp);
    $('#isOJTSelect').val(isOJTSelect);
    $('#isTypeSelect').val(isTypeSelect);
    $('#fundSelectEdit').val(fundAccount);
    $('#fundAccountEdit').val(fundAccount);
    $('#itFeeSelect').val(itfee);

    $.ajax({
        url: subOfferedNameReadRoute, 
        method: 'GET',
        data: { subCode: subofferCode },
        success: function(response) {
            $('#subnameEdit').val(response.sub_name);
            $('#subtitleEdit').val(response.sub_title);
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });

    $('#editStudSubOfferModal').modal('show');
});

document.getElementById('fundSelectEdit').addEventListener('change', function() {
    var selectedOption = this.options[this.selectedIndex];
    var fundEdit = document.getElementById('fundEdit');
    var fundAccountEdit = document.getElementById('fundAccountEdit');

    if (selectedOption.id === 'noAccountOption') {
        fundEdit.value = null;
        fundAccountEdit.value = null;
    } else {
        fundEdit.value = selectedOption.text.split(' - ')[0];
        fundAccountEdit.value = selectedOption.value;
    }
});

$('#editStudSubOfferForm').submit(function(event) {
    event.preventDefault();
    var formData = $(this).serialize();

    $.ajax({
        url: subOfferedUpdateRoute,
        type: "POST",
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if(response.success) {
                toastr.success(response.message);
                $('#editStudSubOfferModal').modal('hide');
                $(document).trigger('subjOffAdded');
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

$(document).on('click', '.subsoff-delete', function(e) {
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
                url: subOfferedDeleteRoute.replace(':id', id),
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


