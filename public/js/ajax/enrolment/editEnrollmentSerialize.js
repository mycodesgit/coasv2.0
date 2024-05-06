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

//for Assess the fees of subjects
document.getElementById('assessButton').addEventListener('click', function() {   
    var schlyear = document.getElementById('schlyearInput').value;
    var semester = document.getElementById('semesterInput').value;
    var campus = document.getElementById('campusInput').value;
    var programCode = document.getElementById('editprogramCodeInput').value;
    var numericPart = document.getElementById('editnumericPart').value;
    var totalLecFee = 0; 
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

                var fundnameCodeInput = document.getElementById('fundnameCodeInput');
                var accountNameInput = document.getElementById('accountNameInput');
                var amountFeeInput = document.getElementById('amountFeeInput');

                fundnameCodeInput.value = '';
                accountNameInput.value = '';
                amountFeeInput.value = '';

                data.forEach(function(item) {
                    var row = tableBody.insertRow();
                    row.insertCell(0).textContent = item.fundname_code;
                    row.insertCell(1).textContent = item.accountName;
                    //row.insertCell(2).textContent = item.amountFee;
                    var amount = item.amountFee === '0' ? 
                        (item.accountName.startsWith('TUITION') ? totalLecFeeInput.value : 
                        (item.accountName === 'LAB FEE' ? totalLabFeeInput.value : '0')) : item.amountFee;
                    row.insertCell(2).textContent = amount;

                    if (data.indexOf(item) > 0) {
                        fundnameCodeInput.value += ', ';
                        accountNameInput.value += ', ';
                        amountFeeInput.value += ', ';
                    }

                    if (item.amountFee !== '0') {
                        fundnameCodeInput.value += (fundnameCodeInput.value.trim().length > 0 ? ' ' : '') + item.fundname_code;
                        accountNameInput.value += (accountNameInput.value.trim().length > 0 ? ' ' : '') + item.accountName;
                        amountFeeInput.value += (amountFeeInput.value.trim().length > 0 ? ' ' : '') + item.amountFee;
                    } else {
                        if (item.accountName === 'LAB FEE') {
                            accountNameInput.value += (accountNameInput.value.trim().length > 0 ? ' ' : '') + 'LAB FEE';
                            amountFeeInput.value += totalLabFeeInput.value;
                            fundnameCodeInput.value += (fundnameCodeInput.value.trim().length > 0 ? ' ' : '') + item.fundname_code;
                        } else if (item.accountName.startsWith('TUITION')) {
                            var tuitionText = item.accountName.split('-')[1].trim();
                            accountNameInput.value += (accountNameInput.value.trim().length > 0 ? ' ' : '') + 'TUITION - ' + tuitionText;
                            amountFeeInput.value += totalLecFeeInput.value;
                            fundnameCodeInput.value += (fundnameCodeInput.value.trim().length > 0 ? ' ' : '') + item.fundname_code;
                        }
                    }


                });
            } else {
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

$(document).on('click', '.delete-row', function(e) {
    var deleteButton = $(this);
    var row = deleteButton.closest('tr');
    var subjIDToDelete = row.find('td:eq(0)').text(); 
    
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
            row.remove();
            updateTotalsAndIDs(subjIDToDelete); 
            Swal.fire(
                'Deleted!',
                'The subject has been removed.',
                'success'
            );
        }
    })
});

function updateTotalsAndIDs(subjIDToDelete) {
    var totalUnits = 0;
    var totalLecFee = 0;
    var totalLabFee = 0;
    var subjIDs = [];

    $('#subjectTable tbody tr').each(function() {
        var cells = $(this).find('td');
        totalUnits += parseInt(cells.eq(4).text());
        totalLecFee += parseFloat(cells.eq(5).text());
        totalLabFee += parseFloat(cells.eq(6).text());
        var subjID = cells.eq(0).text(); 
        if (subjID !== subjIDToDelete) { 
            subjIDs.push(subjID);
        }
    });

    $('#totalunitInput').val(totalUnits);
    $('#totalLecFeeInput').val(totalLecFee.toFixed());
    $('#totalLabFeeInput').val(totalLabFee.toFixed());
    var subjIDString = subjIDs.join(',');
    $('#subjIDsInput').val(subjIDString); 
}

