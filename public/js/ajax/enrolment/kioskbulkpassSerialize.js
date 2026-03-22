$(document).ready(function() {
    var urlParams = new URLSearchParams(window.location.search);
    var schlyear = urlParams.get('schlyear') || ''; 
    var semester = urlParams.get('semester') || '';
    var campus = urlParams.get('campus') || ''; 

    var dataTable = $('#courseEn').DataTable({
        "ajax": {
            "url": courseEnrollReadRoute,
            "type": "GET",
            "data": { 
                "schlyear": schlyear,
                "semester": semester,
                "campus": campus
            }
        },
        info: true,
        responsive: true,
        lengthChange: true,
        searching: true,
        paging: true,
        buttons: [
                'excel', 'pdf'
            ],
        "columns": [
            { data: 'progCod' },
            { data: 'progName' },
            { data: 'progAcronym' },
            { 
                data: 'studYear',
                render: function(data, type, full, meta) {
                    return full.studYear + '-' + full.studSec;
                }
            },
            { 
                data: 'studentCount',
                render: function(data, type, full, meta) {
                    if (type === 'display') {
                        return '<strong>' + data + '</strong>';
                    }
                    return data;
                }
            },
            { data: 'maleCount' },
            { data: 'femaleCount' },
            {data: 'id',
                render: function(data, type, row) {
                    if (type === 'display') {
                        var link = '<a href="#" class="btn btn-success btn-sm btn-viewstudent text-light" data-program="' + row.progCod + '" data-year="' + row.studYear + '" data-section="' + row.studSec + '" data-schlyear="' + row.schlyear + '" data-semester="' + row.semester + '">' +
                            '<i class="fas fa-eye"></i>' +
                            '</a>';
                        return link;
                    } else {
                        return data;
                    }
                },
            },

        ],
        "createdRow": function (row, data, index) {
            $(row).attr('id', 'tr-' + data.id); 
        },
        dom: 'Bfrtip'
    }).buttons().container().appendTo('#courseEn_wrapper .col-md-6:eq(0)');
});

$(document).ready(function () {
    $('#courseEn').on('click', '.btn-viewstudent', function () {
        const programCode = $(this).data('program');
        const studYear = $(this).data('year');
        const studSec = $(this).data('section');
        const schlyear = $(this).data('schlyear');
        const semester = $(this).data('semester');

        const pdfUrl = studentcourseEnrollPDFReadRoute +
            "?progCod=" + programCode +
            "&studYear=" + studYear +
            "&studSec=" + studSec +
            "&schlyear=" + schlyear +
            "&semester=" + semester;

        loadEnrolledStudents(programCode, studYear, studSec, schlyear, semester);

        $('#pdfIframe').attr('src', pdfUrl);
    });

    // Loading students and binding events
    function loadEnrolledStudents(programCode, studYear, studSec, schlyear, semester, autoGeneratePasswords = false) {
        $.ajax({
            url: studentcourseEnrollReadRoute, // Your route for fetching enrolled students
            method: 'GET',
            data: {
                progCod: programCode,
                studYear: studYear,
                studSec: studSec,
                schlyear: schlyear,
                semester: semester
            },
            success: function (response) {
                const studentEnrolledTable = $('#studentEnrolledTable');
                studentEnrolledTable.empty();

                if (response.data.length > 0) {
                    response.data.forEach(function (enroll) {
                        const semesterText = getSemesterBadge(enroll.semester);
                        const password = autoGeneratePasswords ? generatePassword() : ''; // Generate password if flag is true
                        const row = `<tr>
                            <td><span>${enroll.studentID}</span><input type="hidden" name="studentID[]" class="form-control form-control-sm border-0" value="${enroll.studentID}" readonly></td>
                            <td>${enroll.lname}, ${enroll.fname}</td>
                            <td>${enroll.progAcronym} ${enroll.studYear}-${enroll.studSec}</td>
                            <td>${semesterText}</td>
                            <td><span class="password-display">${password}</span><input type="hidden" name="password[]" class="form-control form-control-sm border-0 passwordInput" value="${password}" readonly></td>
                        </tr>`;
                        console.log("Generated Password: ", password);
                        studentEnrolledTable.append(row);
                    });

                    // Initialize DataTable after rendering rows
                    if (!$.fn.DataTable.isDataTable("#exampleme")) {
                        const table = $("#exampleme").DataTable({
                            responsive: false,
                            lengthChange: false,
                            autoWidth: false,
                            paging: false,
                            //buttons: ["excel"]
                        });
                        table.buttons().container().appendTo('#exampleme_wrapper .col-md-6:eq(0)');
                    } else {
                        
                    }
                    
                } else {
                    studentEnrolledTable.append('<tr><td colspan="5" class="text-center">No Students Enrolled.</td></tr>');
                }

                // Trigger modal to show after students are loaded
                $('#viewStudEnrollModal').modal('show');

                // ** Ensure event binding only happens once **
                $('#generateAllPasswords').off('click').on('click', function () {
                    loadEnrolledStudents(programCode, studYear, studSec, schlyear, semester, true); // Reload with passwords
                    Swal.fire('Generated', 'Passwords generated for all students.', 'success');
                });

                $('#saveAllPasswords').off('click').on('click', function () {
                    const formData = $('#studentEnrolledTable input').serialize(); // This will include studentID[] and password[]
                
                    // Check if any password is empty
                    let hasEmpty = false;
                    $('#studentEnrolledTable input[name="password[]"]').each(function () {
                        if ($(this).val().trim() === '') {
                            hasEmpty = true;
                        }
                    });
                
                    if (hasEmpty) {
                        Swal.fire('Warning', 'Some passwords are empty.', 'warning');
                        return;
                    }
                
                    $.ajax({
                        url: savebulkPassRoute,
                        type: 'POST',
                        data: formData, // Append CSRF token
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (res) {
                            Swal.fire('Success', res.message || 'Passwords saved successfully.', 'success').then(() => {
                                // Just reload the PDF iframe
                                const currentPdfUrl = $('#pdfIframe').attr('src');
                                $('#pdfIframe').attr('src', currentPdfUrl); // Refresh PDF
                            });
                        },
                        error: function () {
                            Swal.fire('Error', 'Failed to save passwords.', 'error');
                        }
                    });
                });                
                
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                alert('An error occurred while fetching the enrollment.');
            }
        });
    }

    // Function to generate a random password
    function generatePassword() {
        const digits = String(Math.floor(Math.random() * 10000)).padStart(4, '0');
        const suffix = ['K', 'U', 'G'];
        const char = suffix[Math.floor(Math.random() * suffix.length)];
        return digits + char;
    }

    // Semester badge for display
    function getSemesterBadge(sem) {
        switch (sem) {
            case 1: return '<span class="badge bg-info">1st Sem</span>';
            case 2: return '<span class="badge bg-info">2nd Sem</span>';
            case 3: return '<span class="badge bg-secondary">Summer</span>';
            default: return 'Unknown Semester';
        }
    }
});


