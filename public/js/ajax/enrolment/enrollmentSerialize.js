toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right"
};

//for inserting data to DB  using ajax serialize
$(document).ready(function() {
    $('#submitButton').click(function(event) {
        event.preventDefault();
        var formData = $('#AddenrollStud').serialize();

        $.ajax({
            url: saveEnrollmentRoute,
            type: "POST",
            data: formData,
            success: function(response) {
                if(response.success) {
                    toastr.success(response.message);
                    console.log(response);
                    $(document).trigger('coaAdded');
                    $('input[name="accountcoa_code"]').val('');
                    $('input[name="accountcoa_name"]').val('');
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
});

//for auto generate data needed in forms by selecting course
$(document).ready(function() {
    $('#programNameSelect').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var programCode = selectedOption.data('program-code');
        var programID = selectedOption.data('program-classid');
        var programName = selectedOption.data('program-name');
        var yearSec = selectedOption.data('year-section');
        var classSection = selectedOption.data('section');

        var parts = classSection.split('-');

        $('#programCodeInput').val(programCode);
        $('#programIDInput').val(programID);
        $('#programNameInput').val(programName);
        $('#yearsectionInput').val(yearSec);

        if (parts.length === 2) {
            var numericPart = parts[0];
            var alphabeticalPart = parts[1];
            $('#numericPart').val(numericPart);
            $('#alphabeticalPart').val(alphabeticalPart);
        }

    });
});

//for Selecting subject manually in modal 
$(document).ready(function() {
    $('#subjectSelect').change(function() {
        var selectedOption = $(this).find(':selected');
         $('#sub_code').val(selectedOption.data('sub-code'));
        $('#sub_title').val(selectedOption.data('sub-title'));
        $('#subUnit').val(selectedOption.data('sub-unit'));
        $('#lecFee').val(selectedOption.data('lec-fee'));
        $('#labFee').val(selectedOption.data('lab-fee'));
    });
});

//for Selecting Course from option to generate subject offer using template
document.getElementById('programNameSelect').addEventListener('change', function() {
    var selectedCourse = this.value;
    var schlyear = document.getElementById('schlyearInput').value; 
    var semester = document.getElementById('semesterInput').value; 

    if (selectedCourse === '--Select--') {
        return;
    }

    var xhr = new XMLHttpRequest();
    xhr.open('GET', fetchTemplateRoute +'?course=' + encodeURIComponent(selectedCourse) + '&schlyear=' + encodeURIComponent(schlyear) + '&semester=' + encodeURIComponent(semester), true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                var subjects = JSON.parse(xhr.responseText);
                var tableBody = document.getElementById('subjectTable').getElementsByTagName('tbody')[0];
                tableBody.innerHTML = '';
                var totalUnits = 0;
                var totalLecFee = 0;
                var totalLabFee = 0;
                subjects.forEach(function(subject) {
                    var row = tableBody.insertRow();
                    row.insertCell(0).textContent = subject.subCode;
                    row.insertCell(1).textContent = subject.sub_name + ' - ' + subject.subSec;
                    row.insertCell(2).textContent = subject.sub_title;
                    row.insertCell(3).textContent = subject.subUnit;
                    row.insertCell(4).textContent = subject.lecFee;
                    row.insertCell(5).textContent = subject.labFee;

                    totalUnits += parseInt(subject.subUnit);
                    totalLecFee += parseFloat(subject.lecFee);
                    totalLabFee += parseFloat(subject.labFee);
                });

                document.getElementById('totalunitInput').value = totalUnits;

                var totalRow = tableBody.insertRow();
                totalRow.insertCell(0);
                totalRow.insertCell(1);
                totalRow.insertCell(2); 
                totalRow.insertCell(3); 
                totalRow.insertCell(4).textContent = totalLecFee.toFixed(2);
                totalRow.insertCell(5).textContent = totalLabFee.toFixed(2);
            } else {
                alert('Failed to fetch subjects.');
            }
        }
    };
    var tableBody = document.getElementById('studFeeTable').getElementsByTagName('tbody')[0];
    tableBody.innerHTML = '';
    xhr.send();
});

//for Manual Adding of Subject using Modal
document.getElementById('addSubjectBtn').addEventListener('click', function() {
    var selectedSubjectOption = document.querySelector('#subjectSelect option:checked');
    if (!selectedSubjectOption) {
        alert('Please select a subject.');
        return;
    }

    var selectedSubjectText = selectedSubjectOption.textContent;
    var sub_name = selectedSubjectText; 

    var selectedSubjectCodeText = document.getElementById('sub_code').value;
    var selectedSubjectTitleText = document.getElementById('sub_title').value;
    var selectedSubjectUnitText = document.getElementById('subUnit').value;
    var selectedSubjectlecFeeText = document.getElementById('lecFee').value;
    var selectedSubjectlabFeeText = document.getElementById('labFee').value;

    var xhr = new XMLHttpRequest();
    xhr.open('GET', getfetchSubjectRoute + '?dd=' + encodeURIComponent(selectedSubjectText), true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                var subjectDetails = JSON.parse(xhr.responseText);
                var subCode = subjectDetails.subCode; 
                var sub_title = subjectDetails.sub_title;
                var subUnit = subjectDetails.subUnit;
                var lecFee = subjectDetails.lecFee;
                var labFee = subjectDetails.labFee;

                var tableBody = document.getElementById('subjectTable').getElementsByTagName('tbody')[0];
                var row = tableBody.insertRow();
                row.insertCell(0).textContent = subCode || selectedSubjectCodeText; 
                row.insertCell(1).textContent = sub_name;
                row.insertCell(2).textContent = sub_title || selectedSubjectTitleText;
                row.insertCell(3).textContent = subUnit || selectedSubjectUnitText;
                row.insertCell(4).textContent = lecFee || selectedSubjectlecFeeText;
                row.insertCell(5).textContent = labFee || selectedSubjectlabFeeText;

                var totalUnits = parseInt(selectedSubjectUnitText) || 0;
                var rows = tableBody.getElementsByTagName('tr');
                for (var i = 0; i < rows.length - 1; i++) { 
                    var cell = rows[i].getElementsByTagName('td')[3];
                    if (cell) {
                        totalUnits += parseInt(cell.textContent) || 0;
                    }
                }
                document.getElementById('totalunitInput').value = totalUnits;
                $('#modal-addSub').modal('hide');
            } else {
                alert('Failed to fetch subject details.');
            }
        }
    };
    xhr.send();
});

//for Assess the fees of subjects
document.getElementById('assessButton').addEventListener('click', function() {   
    var schlyear = document.getElementById('schlyearInput').value;
    var semester = document.getElementById('semesterInput').value;
    var campus = document.getElementById('campusInput').value;
    var programCode = document.getElementById('programCodeInput').value;
    var numericPart = document.getElementById('numericPart').value;
    var totalLecFee = 0; // Initialize totalLecFee here
    var totalLabFee = 0;

    if (!programCode || !numericPart || !schlyear || !semester || !campus) {
        alert('Please fill in all fields.');
        return;
    }

    var xhr = new XMLHttpRequest();
    xhr.open('GET', fetchFeeDataRoute + '?programCode=' + encodeURIComponent(programCode) + '&numericPart=' + encodeURIComponent(numericPart) + '&schlyear=' + encodeURIComponent(schlyear) + '&semester=' + encodeURIComponent(semester) + '&campus=' + encodeURIComponent(campus), true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                var data = JSON.parse(xhr.responseText);
                if (data.length === 0) {
                    //alert('No fee data found for the given inputs.');
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Student Fee!',
                        text: 'Please contact the Assesment Office.',
                        showClass: {
                            popup: 'my-custom-show-animation'
                        },
                        hideClass: {
                            popup: ''
                        }
                    });
                    return;
                }

                var tableBody = document.getElementById('studFeeTable').getElementsByTagName('tbody')[0];
                tableBody.innerHTML = '';

                data.forEach(function(item) {
                    var row = tableBody.insertRow();
                    row.insertCell(0).textContent = item.fundname_code;
                    row.insertCell(1).textContent = item.accountName;
                    if (item.accountName === 'TUITION' && item.amountFee === 0) {
                        row.insertCell(2).textContent = totalLecFee.toFixed(2);
                    } else if (item.accountName === 'LAB FEE' && item.amountFee === 0) {
                        row.insertCell(2).textContent = totalLabFee.toFixed(2);
                    } else {
                        row.insertCell(2).textContent = item.amountFee;
                    }
                    if (item.accountName === 'TUITION') {
                        totalLecFee += parseFloat(item.amountFee); // Update totalLecFee
                    } else if (item.accountName === 'LAB FEE') {
                        totalLabFee += parseFloat(item.amountFee); // Update totalLabFee
                    }
                });
            } else {
                // alert('Failed to fetch fee data. Error: ' + xhr.statusText);
                Swal.fire({
                    icon: 'warning',
                    title: 'Failed to fetch Data',
                    text: 'Error: ' + xhr.statusText,
                    showClass: {
                        popup: 'my-custom-show-animation'
                    },
                    hideClass: {
                        popup: ''
                    }
                });
            }
        }
    };
    xhr.send();
});










