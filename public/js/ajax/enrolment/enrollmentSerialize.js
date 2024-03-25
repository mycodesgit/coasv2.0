$(document).ready(function() {
    $('#programNameSelect').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var programName = selectedOption.data('program-name');
        var yearSec = selectedOption.data('year-section');
        
        $('#programNameInput').val(programName);
        $('#yearsectionInput').val(yearSec);
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
