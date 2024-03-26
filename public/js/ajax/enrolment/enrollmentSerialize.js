$(document).ready(function() {
    $('#programNameSelect').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var programName = selectedOption.data('program-name');
        var yearSec = selectedOption.data('year-section');
        
        $('#programNameInput').val(programName);
        $('#yearsectionInput').val(yearSec);
    });
});

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
                subjects.forEach(function(subject) {
                    var row = tableBody.insertRow();
                    row.insertCell(0).textContent = subject.subCode;
                    row.insertCell(1).textContent = subject.sub_name + ' - ' + subject.subSec;
                    row.insertCell(2).textContent = subject.sub_title;
                    row.insertCell(3).textContent = subject.subUnit;
                    row.insertCell(4).textContent = subject.lecFee;
                    row.insertCell(5).textContent = subject.labFee;
                });
            } else {
                alert('Failed to fetch subjects.');
            }
        }
    };
    xhr.send();
});

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

                // Add the selected subject to the table
                var tableBody = document.getElementById('subjectTable').getElementsByTagName('tbody')[0];
                var row = tableBody.insertRow();
                row.insertCell(0).textContent = subCode || selectedSubjectCodeText; //
                row.insertCell(1).textContent = sub_name;
                row.insertCell(2).textContent = sub_title || selectedSubjectTitleText;
                row.insertCell(3).textContent = subUnit || selectedSubjectUnitText;
                row.insertCell(4).textContent = lecFee || selectedSubjectlecFeeText;
                row.insertCell(5).textContent = labFee || selectedSubjectlabFeeText;

                $('#modal-addSub').modal('hide');
            } else {
                alert('Failed to fetch subject details.');
            }
        }
    };
    xhr.send();
});








