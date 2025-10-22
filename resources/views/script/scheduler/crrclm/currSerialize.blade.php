<script>
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right"
    };
    $(document).ready(function() {
        $('#adCurriculumForm').submit(function(event) {
            event.preventDefault();
            var formData = $(this).serialize();

            $.ajax({
                url: curriculumCreateRoute,
                type: "POST",
                data: formData,
                success: function(response) {
                    if(response.success) {
                        toastr.success(response.message);
                        console.log(response);
                        $(document).trigger('currAdded');
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
        var semester = urlParams.get('semester') || '';
        var progCod = urlParams.get('progCod') || ''; 

        var dataTable = $('#currTable').DataTable({
            "ajax": {
                "url": curriculumReadRoute,
                "type": "GET",
                "data": { 
                    "semester": semester,
                    "progCod": progCod
                }
            },
            responsive: true,
            lengthChange: true,
            searching: true,
            paging: true,
            "columns": [
                {data: 'subCode'},
                {data: 'sub_name'},
                {data: 'lecUnit'},
                {data: 'labUnit'},
                {data: 'subUnit'},
                {data: 'lecFee'},
                {data: 'labFee'},
                {data: 'devFee'},
                {
                    data: 'fundAccount',
                    render: function(data, type, row) {
                        return data ? data : 'No Account';
                    }
                },
                {data: 'itfee'},
                {
                    data: 'id',
                    render: function(data, type, row) {
                        if (type === 'display') {
                            var dropdown = '<div class="d-inline-block">' +
                                '<a class="btn btn-primary btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown"></a>' +
                                '<div class="dropdown-menu">' +
                                '<a href="#" class="dropdown-item btn-studSubOffer" data-id="' + row.id + '" data-subcode="' + row.subCode + '" data-subsec="' + row.subSec + '" data-lecunit="' + row.lecUnit + '" data-labunit="' + row.labUnit + '" data-subunit="' + row.subUnit + '" data-lecfee="' + row.lecFee + '" data-labfee="' + row.labFee + '" data-devfee="' + row.devFee + '" data-maxstud="' + row.maxstud + '" data-fund="' + row.fund + '" data-istemp="' + row.isTemp + '" data-isojt="' + row.isOJT + '" data-istype="' + row.isType + '" data-itfee="' + row.itfee + '" data-fundaccount="' + row.fundAccount + '">' +
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
        $(document).on('currAdded', function() {
            dataTable.ajax.reload();
        });
    });

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
        $('#fundSelect').on('change', function() {
            var selectedOption = $(this).find('option:selected');
            var fundId = selectedOption.val();
            var accountName = selectedOption.data('account-name');
            
            $('#fundIdInput').val(fundId);
            $('#accountNameInput').val(accountName);
        });
    });
</script>