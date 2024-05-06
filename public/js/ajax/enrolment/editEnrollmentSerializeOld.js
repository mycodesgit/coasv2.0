$(document).ready(function() {
    function updateInputFields(selectedOption) {
        var programCode = selectedOption.data('program-code');
        var programID = selectedOption.data('program-classid');
        var programName = selectedOption.data('program-name');
        var yearSec = selectedOption.data('year-section');
        var classSection = selectedOption.data('section');
        var parts = classSection.split('-');

        $('#editprogramCodeInput').val(programCode);
        $('#editprogramIDInput').val(programID);
        $('#editprogramNameInput').val(programName);
        $('#edityearsectionInput').val(yearSec);

        if (parts.length === 2) {
            var numericPart = parts[0];
            var alphabeticalPart = parts[1];
            $('#editnumericPart').val(numericPart);
            $('#editalphabeticalPart').val(alphabeticalPart);
        }
    }

    var selectedOption = $('#programNameEditSelect').find('option:selected');
    
    updateInputFields(selectedOption);

     $('#programNameEditSelect').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        updateInputFields(selectedOption);
    });

    // Add this block inside the $(document).ready() function
    $('#programNameEditSelect').on('change', function() {
        var selectedCourse = $(this).val();
        var schlyear = $('#schlyearInput').val();
        var semester = $('#semesterInput').val();
        
        if (selectedCourse === '--Select--') {
            return;
        }

        $.ajax({
            url: fetchensubRoute,
            method: 'GET',
            dataType: 'json',
            data: {
                course: selectedCourse,
                schlyear: schlyear,
                semester: semester
            },
            success: function(subjects) {
                var tableBody = $('#subjectTable').find('tbody');
                tableBody.empty();

                subjects.forEach(function(subject) {
                    var row = $('<tr>');
                    row.append($('<td>').text(subject.subjID));
                    row.append($('<td>').text(subject.subCode));
                    row.append($('<td>').text(subject.sub_name + ' - ' + subject.subSec));
                    row.append($('<td>').text(subject.sub_title));
                    row.append($('<td>').text(subject.subUnit));
                    row.append($('<td>').text(subject.lecFee));
                    row.append($('<td>').text(subject.labFee));

                    var removeCell = $('<td>');
                    var removeButton = $('<button>').addClass('btn btn-outline-danger btn-sm');
                    var icon = $('<i>').addClass('fas fa-trash');
                    removeButton.append(icon);
                    removeButton.on('click', function() {
                        // Your existing code for removing the subject
                    });
                    removeCell.append(removeButton);
                    row.append(removeCell);

                    tableBody.append(row);
                });

                updateTotalsAndIDs(); // Call this function to update totals and IDs
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert('Failed to fetch subjects.');
            }
        });
    });
});


//for update units, lecfee, labfee and subjectID
function updateTotalsAndIDs() {
    var totalUnits = 0;
    var totalLecFee = 0;
    var totalLabFee = 0;
    var subjIDs = [];

    var tableBody = document.getElementById('subjectTable').getElementsByTagName('tbody')[0];
    var rows = tableBody.getElementsByTagName('tr');

    for (var i = 0; i < rows.length; i++) {
        var cells = rows[i].cells;
        if (cells.length >= 7) {
            totalUnits += parseInt(cells[4].textContent);
            totalLecFee += parseFloat(cells[5].textContent);
            totalLabFee += parseFloat(cells[6].textContent);
            subjIDs.push(cells[0].textContent);
        }
    }

    document.getElementById('totalunitInput').value = totalUnits;
    document.getElementById('totalLecFeeInput').value = totalLecFee.toFixed();
    document.getElementById('totalLabFeeInput').value = totalLabFee.toFixed();
    var subjIDString = subjIDs.join(',');
    document.getElementById('subjIDsInput').value = subjIDString;
}

//for Selecting Course from option to generate subject offer using template
document.getElementById('programNameEditSelect').addEventListener('change', function() {
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
                    row.insertCell(0).textContent = subject.subjID;
                    row.insertCell(1).textContent = subject.subCode;
                    row.insertCell(2).textContent = subject.sub_name + ' - ' + subject.subSec;
                    row.insertCell(3).textContent = subject.sub_title;
                    row.insertCell(4).textContent = subject.subUnit;
                    row.insertCell(5).textContent = subject.lecFee;
                    row.insertCell(6).textContent = subject.labFee;

                    var removeCell = row.insertCell(7);
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
                                for (var i = 0; i < 7; i++) {
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
    var tableBody = document.getElementById('studFeeTable').getElementsByTagName('tbody')[0];
    tableBody.innerHTML = '';
    xhr.send();
});
