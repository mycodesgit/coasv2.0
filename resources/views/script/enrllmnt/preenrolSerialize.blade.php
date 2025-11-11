<script>
    //for update units, lecfee, labfee and subjectID
    function updateTotalsAndIDs() {
        var totalUnits = 0;
        var totalLecFee = 0;
        var totalLabFee = 0;
        var totalDevFee = 0;
        var itsubjFee = [];
        var subjIDs = [];

        var tableBody = document.getElementById('subjectTable').getElementsByTagName('tbody')[0];
        var rows = tableBody.getElementsByTagName('tr');

        for (var i = 0; i < rows.length; i++) {
            var cells = rows[i].cells;
            if (cells.length >= 8) {
                totalUnits += parseInt(cells[4].textContent);
                totalLecFee += parseFloat(cells[5].textContent);
                totalLabFee += parseFloat(cells[6].textContent);
                totalDevFee += parseFloat(cells[7].textContent);
                itsubjFee.push(cells[8].textContent.trim());
                subjIDs.push(cells[0].textContent);
            }
        }

        document.getElementById('totalunitInput').value = totalUnits;
        document.getElementById('totalLecFeeInput').value = totalLecFee.toFixed();
        document.getElementById('totalLabFeeInput').value = totalLabFee.toFixed();
        document.getElementById('totalDevFeeInput').value = totalDevFee.toFixed();
        document.getElementById('itsubjInput').value = itsubjFee;
        var subjIDString = subjIDs.join(',');
        document.getElementById('subjIDsInput').value = subjIDString;
    }
    
    document.getElementById('programNameSelect').addEventListener('change', function() {
        var selectedOption = this.options[this.selectedIndex];
        var programCode = selectedOption.getAttribute('data-program-code');
        var classSection = selectedOption.getAttribute('data-section');
        var programID = selectedOption.getAttribute('data-program-classid');
        var programName = selectedOption.getAttribute('data-program-name');
        var yearSec = selectedOption.getAttribute('data-year-section');
        var classNo = selectedOption.getAttribute('data-classno');

        var studentID = document.getElementById('studentID').value;
        var schlyear = document.getElementById('schlyearInput').value;
        var semester = document.getElementById('semesterInput').value;
        var campus = document.getElementById('campusInput').value;

        var selectedCourse = this.value;
        var schlyear = document.getElementById('schlyearInput').value; 
        var semester = document.getElementById('semesterInput').value; 

        var parts = classSection.split('-');

        document.getElementById('programCodeInput').value = programCode;
        document.getElementById('programIDInput').value = programID;
        document.getElementById('programNameInput').value = programName;
        document.getElementById('yearsectionInput').value = yearSec;

        if (parts.length === 2) {
            var numericPart = parts[0];
            var alphabeticalPart = parts[1];
            document.getElementById('numericPart').value = numericPart;
            document.getElementById('alphabeticalPart').value = alphabeticalPart;
        }

        // Construct URL
        var checkEnrollmentUrl = checkEnrollmentRoute + '?stud_id=' + encodeURIComponent(studentID) + 
                                '&schlyear=' + encodeURIComponent(schlyear) + 
                                '&semester=' + encodeURIComponent(semester) + 
                                '&campus=' + encodeURIComponent(campus) + 
                                '&programCode=' + encodeURIComponent(programCode) + 
                                '&classSection=' + encodeURIComponent(classSection);
                                console.log(checkEnrollmentUrl);

        // Perform AJAX request to check student enrollment
        var xhrEnrollment = new XMLHttpRequest();
        xhrEnrollment.open('GET', checkEnrollmentUrl, true);
        xhrEnrollment.onreadystatechange = function() {
            if (xhrEnrollment.readyState === XMLHttpRequest.DONE) {
                if (xhrEnrollment.status === 200) {
                    var response = JSON.parse(xhrEnrollment.responseText);
                    if (response.error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.error,
                        });
                    } else {
                        var enrolledStudents = response.enrolledStudents;
                        var classNo = response.classNo;
                        if (response.isFull) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Full Class',
                                text: 'The selected course is already full of' + ' ' + classNo,
                            });
                        } else {
                            // Proceed with fetching and generating subjects
                            fetchAndGenerateSubjects(selectedCourse, schlyear, semester);
                        }
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Request Error',
                        text: 'Failed to check enrollment status.',
                    });
                }
            }
        };
        xhrEnrollment.send();
    });

    function fetchAndGenerateSubjects(selectedCourse, schlyear, semester) {
        if (selectedCourse === '--Select--') {
            return;
        }

        var xhr = new XMLHttpRequest();
        xhr.open('GET', fetchTemplateRoute + '?course=' + encodeURIComponent(selectedCourse) + '&schlyear=' + encodeURIComponent(schlyear) + '&semester=' + encodeURIComponent(semester), true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var subjects = JSON.parse(xhr.responseText);
                    var tableBody = document.getElementById('subjectTable').getElementsByTagName('tbody')[0];
                    tableBody.innerHTML = '';

                    subjects.forEach(function(subject) {
                        var row = tableBody.insertRow();
                        row.insertCell(0).textContent = subject.subjID;
                        row.insertCell(1).textContent = subject.subCode;
                        row.insertCell(2).textContent = subject.sub_name + ' - ' + subject.subSec + ' - ' + subject.isType;
                        row.insertCell(3).textContent = subject.sub_title;
                        row.insertCell(4).textContent = subject.subUnit;
                        row.insertCell(5).textContent = subject.lecFee;
                        row.insertCell(6).textContent = subject.labFee;
                        row.insertCell(7).textContent = subject.devFee;
                        row.insertCell(8).textContent = subject.itfee;

                        var removeCell = row.insertCell(9);
                        var removeButton = document.createElement('button');
                        removeButton.textContent = '';
                        removeButton.classList.add('btn', 'btn-outline-danger', 'btn-sm');
                        var icon = document.createElement('i');
                        icon.classList.add('fas', 'fa-trash');
                        removeButton.appendChild(icon);
                        removeButton.addEventListener('click', function() {
                            Swal.fire({
                                title: 'Are you sure?',
                                text: 'You are about to remove this subject. This action cannot be undone.',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Yes, remove it!'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    for (var i = 0; i < 9; i++) {
                                        row.cells[i].textContent = '';
                                    }
                                    row.parentNode.removeChild(row);
                                    updateTotalsAndIDs();
                                    Swal.fire(
                                        'Deleted!',
                                        'The subject has been removed.',
                                        'success'
                                    );
                                }
                            });
                        });
                        removeCell.appendChild(removeButton);
                    });
                    updateTotalsAndIDs();
                } else {
                    alert('Failed to fetch subjects.');
                }
            }
        };
        xhr.send();
    }
</script>