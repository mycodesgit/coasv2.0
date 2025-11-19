<script>
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right"
    };

    $(document).ready(function() {
        $('#submitEvalButton').click(function(event) {
            event.preventDefault();
            var formData = $('#AddenrollStud').serialize();

            var studentID = $('#studentID').val(); 

            var subjIDs = [];
            $('input[name="subjIDs"]').each(function() {
                subjIDs.push($(this).val());
            });

            formData += '&studentID=' + studentID;

            var subjIDsString = $('#subjIDsInput').val();
            var subjIDsArray = subjIDsString.split(',');

            subjIDsArray.forEach(function(subjID) {
                formData += '&subjIDs[]=' + subjID.trim(); 
            });

            $.ajax({
                url: saveEvalEnrollmentRoute,
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: formData, 
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        console.log(response);
                    } else {
                        // if (response.fullSubjects && response.fullSubjects.length > 0) {
                        //     var fullSubjectsList = response.fullSubjects;
                            
                        //     Swal.fire({
                        //         icon: 'error',
                        //         title: 'Subjects Full',
                        //         html: 'The following subjects are full: <br>' + fullSubjectsList.replace(/,/g, '<br>'),
                        //     });
                        // } 
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                    var response = JSON.parse(xhr.responseText);
                    if (response.error && response.fullSubjects && response.fullSubjects.length > 0) {
                        var fullSubjectsList = response.fullSubjects.map(function(subject) {
                            // return 'Subject ID: ' + subject.id + ', Name: ' + subject.name + ', Max Students: ' + subject.maxstud;
                            return ' ' + subject.name + ' - ' + subject.section + ', Max Students: ' + subject.maxstud;
                        }).join('<br>');
                        Swal.fire({
                            icon: 'error',
                            title: 'Subjects Full',
                            html: 'The following subjects are full:<br>' + fullSubjectsList,
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message,
                        });
                    }
                }
            });
        });
    });

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

    //for Selecting subject manually in modal 
    $(document).ready(function() {
        $('#subjectSelect').change(function() {
            var selectedOption = $(this).find(':selected');
            $('#subjecID').val(selectedOption.data('subp-sid'));
            $('#sub_code').val(selectedOption.data('sub-code'));
            $('#sub_title').val(selectedOption.data('sub-title'));
            $('#subUnit').val(selectedOption.data('sub-unit'));
            $('#lecFee').val(selectedOption.data('lec-fee'));
            $('#labFee').val(selectedOption.data('lab-fee'));
            $('#devFee').val(selectedOption.data('dev-fee'));
            $('#itfee').val(selectedOption.data('it-fee'));
        });
    });

    function updateTotalsAndIDs(subjIDToDelete = null) {
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
                var subjID = cells[0].textContent;
                if (subjID !== subjIDToDelete) {
                    totalUnits += parseInt(cells[4].textContent);
                    totalLecFee += parseFloat(cells[5].textContent);
                    totalLabFee += parseFloat(cells[6].textContent);
                    totalDevFee += parseFloat(cells[7].textContent);
                    itsubjFee.push(cells[8].textContent.trim());
                    subjIDs.push(subjID);
                }
            }
        }

        document.getElementById('totalunitInput').value = totalUnits;
        document.getElementById('totalLecFeeInput').value = totalLecFee.toFixed();
        document.getElementById('totalLabFeeInput').value = totalLabFee.toFixed();
        document.getElementById('totalDevFeeInput').value = totalDevFee.toFixed();
        document.getElementById('itsubjInput').value = itsubjFee;
        var subjIDString = subjIDs.join(',');
        document.getElementById('subjIDsInput').value = subjIDString;
        document.getElementById('subjIDsInputlog').value = subjIDString;
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

    //for Manual Adding of Subject using Modal
    document.getElementById('addSubjectBtn').addEventListener('click', function() {
        var selectedSubjectOption = document.querySelector('#subjectSelect option:checked');
        if (!selectedSubjectOption) {
            alert('Please select a subject.');
            return;
        }

        var selectedSubjectText = selectedSubjectOption.textContent;
        var sub_name = selectedSubjectText; 

        var selectedSubjectPkeyText = document.getElementById('subjecID').value;
        var selectedSubjectCodeText = document.getElementById('sub_code').value;
        var selectedSubjectTitleText = document.getElementById('sub_title').value;
        var selectedSubjectUnitText = document.getElementById('subUnit').value;
        var selectedSubjectlecFeeText = document.getElementById('lecFee').value;
        var selectedSubjectlabFeeText = document.getElementById('labFee').value;
        var selectedSubjectdevFeeText = document.getElementById('devFee').value;
        var selectedSubjectitFeeText = document.getElementById('itfee').value;

        var subjIDInput = document.getElementById('subjIDsInput');
        var existingIDs = subjIDInput.value.trim(); 
        var newID = selectedSubjectPkeyText || ''; 
        var updatedIDs = existingIDs ? existingIDs + ',' + newID : newID; 
        subjIDInput.value = updatedIDs; 


        var subjIDInput = document.getElementById('subjIDsInputlog');
        var existingIDs = subjIDInput.value.trim(); 
        var newID = selectedSubjectPkeyText || ''; 
        var updatedIDs = existingIDs ? existingIDs + ',' + newID : newID; 
        subjIDInput.value = updatedIDs; 

        var xhr = new XMLHttpRequest();
        xhr.open('GET', getfetchSubjectRoute + '?dd=' + encodeURIComponent(selectedSubjectText), true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var subjectDetails = JSON.parse(xhr.responseText);
                    var id = subjectDetails.id; 
                    var subCode = subjectDetails.subCode; 
                    var sub_title = subjectDetails.sub_title;
                    var subUnit = subjectDetails.subUnit;
                    var lecFee = subjectDetails.lecFee;
                    var labFee = subjectDetails.labFee;
                    var devFee = subjectDetails.devFee;
                    var itfee = subjectDetails.itfee;

                    var tableBody = document.getElementById('subjectTable').getElementsByTagName('tbody')[0];
                    var row = tableBody.insertRow();
                    row.insertCell(0).textContent = id || selectedSubjectPkeyText;
                    row.insertCell(1).textContent = subCode || selectedSubjectCodeText; 
                    row.insertCell(2).textContent = sub_name;
                    row.insertCell(3).textContent = sub_title || selectedSubjectTitleText;
                    row.insertCell(4).textContent = subUnit || selectedSubjectUnitText;
                    row.insertCell(5).textContent = lecFee || selectedSubjectlecFeeText;
                    row.insertCell(6).textContent = labFee || selectedSubjectlabFeeText;
                    row.insertCell(7).textContent = devFee || selectedSubjectdevFeeText;
                    row.insertCell(8).textContent = itfee || selectedSubjectitFeeText;

                    // Create and append remove button
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
                    updateTotalsAndIDs();

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
                        var itFeeCondition = itsubjInput.value.split(',').includes('Yes');

                        // Get totalDevFeeInput value once
                        if (item.accountName === 'DEVELOPMENTAL FEE') {
                            var devFeeInputVal = document.getElementById('totalDevFeeInput').value;
                            if (devFeeInputVal && !isNaN(devFeeInputVal)) {
                                devFeeExtra = parseFloat(devFeeInputVal);
                            }
                        }

                        var amount = item.amountFee === '0' 
                            ? (item.accountName.startsWith('TUITION') ? totalLecFeeInput.value
                            : (item.accountName === 'LAB FEE' ? totalLabFeeInput.value
                            : (item.accountName === 'IT FEE' && itFeeCondition ? '500' : '0')))
                            : item.amountFee;
                        
                            // If item is DEVELOPMENTAL FEE, add the extra dev fee
                        if (item.accountName === 'DEVELOPMENTAL FEE') {
                            amount = (parseFloat(amount) || 0) + devFeeExtra;
                        }

                        row.insertCell(2).textContent = amount;

                        if (data.indexOf(item) > 0) {
                            fundnameCodeInput.value += ', ';
                            accountNameInput.value += ', ';
                            amountFeeInput.value += ', ';
                        }

                        if (item.amountFee !== '0' || item.accountName === 'IT FEE') {
                            fundnameCodeInput.value += (fundnameCodeInput.value.trim().length > 0 ? ' ' : '') + item.fundname_code;
                            accountNameInput.value += (accountNameInput.value.trim().length > 0 ? ' ' : '') + item.accountName;
                            amountFeeInput.value += (amountFeeInput.value.trim().length > 0 ? ' ' : '') + amount;
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
                    // Add success notification
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Student fee data fetched successfully.',
                        showClass: {
                            popup: 'my-custom-show-animation'
                        },
                        hideClass: {
                            popup: ''
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
</script>