<script>
    // ===== GLOBAL FUNCTIONS =====
    function loadStudents(page = 1) {
        const searchInput = document.getElementById('searchInput');
        const tableBody = document.getElementById('studentsTable');
        const pagination = document.getElementById('paginationLinks');
        
        if (!tableBody) return;
        
        const search = searchInput ? searchInput.value.trim() : '';
        const searchRoute = "{{ route('student.show') }}";

        // Show loading state
        tableBody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center">                
                    <div class="d-flex justify-content-center align-items-center py-2">
                        <i class="fas fa-spinner fa-spin me-2"></i>
                        <span>Searching Student...</span>
                    </div>
                </td>
            </tr>
        `;

        fetch(`${searchRoute}?search=${encodeURIComponent(search)}&page=${page}`, {
            headers: { 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(res => {
            tableBody.innerHTML = '';
            pagination.innerHTML = '';

            if (res.data.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center">No records found</td>
                    </tr>
                `;
                return;
            }

            res.data.forEach(student => {
                const campusMap = {
                    'MC': 'Main',
                    'VC': 'Victorias',
                    'SCC': 'San Carlos',
                    'HC': 'Hinigaran',
                    'MP': 'Moises Padilla',
                    'IC': 'Ilog',
                    'CA': 'Candoni',
                    'CC': 'Cauayan',
                    'SC': 'Sipalay',
                    'HinC': 'Hinobaan'
                };
                
                tableBody.innerHTML += `
                    <tr>
                        <td>${student.lname}, ${student.fname}</td>
                        <td>${student.stud_id}</td>
                        <td>${student.gender || 'N/A'}</td>
                        <td>${campusMap[student.campus] || student.campus || 'N/A'}</td>
                        <td>${student.civil_status || 'N/A'}</td>
                        <td>${student.enhiscourse || 'No Course'}</td>
                        <td>
                            <div class="d-flex align-items-center gap-1">
                                <a href="#" class="btn btn-success btn-sm border btn-studdataview" 
                                    data-id="${student.stdntid}" 
                                    data-studid="${student.stud_id}" 
                                    data-fname="${student.fname || ''}" 
                                    data-mname="${student.mname || ''}" 
                                    data-lname="${student.lname || ''}" 
                                    data-ext="${student.ext || ''}" 
                                    data-gender="${student.gender || ''}" 
                                    data-bday="${student.bday || ''}" 
                                    data-pbirth="${student.pbirth || ''}" 
                                    data-contact="${student.contact || ''}" 
                                    data-email="${student.email || ''}" 
                                    data-religion="${student.religion || ''}" 
                                    data-address="${student.address || ''}" 
                                    data-civil="${student.civil_status || ''}" 
                                    data-hnum="${student.hnum || ''}" 
                                    data-brgy="${student.brgy || ''}" 
                                    data-city="${student.city || ''}" 
                                    data-province="${student.province || ''}" 
                                    data-region="${student.region || ''}" 
                                    data-zcode="${student.zcode || ''}" 
                                    data-father="${student.stud_father || ''}" 
                                    data-mother="${student.stud_mother || ''}" 
                                    data-guardian="${student.stud_guardian || ''}" 
                                    data-income="${student.monthly_income || ''}" 
                                    data-pcontact="${student.guardian_contact || ''}" 
                                    data-lstschattended="${student.lstsch_attended || ''}" 
                                    data-lstschattendedyear="${student.lst_sch_attended_year || ''}" 
                                    data-suclstattended="${student.suc_lst_attended || ''}" 
                                    data-dateadmission="${student.date_admission || ''}" 
                                    data-enhiscourse="${student.enhiscourse || ''}"
                                    title="View Details">
                                    <i class="ti ti-eye" style="color: #fff"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                `;
            });

            // Pagination
            for (let i = 1; i <= res.last_page; i++) {
                pagination.innerHTML += `
                    <li class="page-item ${i === res.current_page ? 'active' : ''}">
                        <a class="page-link" href="#" data-page="${i}">${i}</a>
                    </li>
                `;
            }

            // Pagination click events
            pagination.querySelectorAll('.page-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    loadStudents(parseInt(this.dataset.page));
                });
            });
        })
        .catch(error => {
            console.error('Error loading students:', error);
            tableBody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center text-danger">Error loading data</td>
                </tr>
            `;
        });
    }

    function loadGradStudents(page = 1) {
        const searchInput = document.getElementById('searchGradInput');
        const tableBody = document.getElementById('graduatestudentsTable');
        const pagination = document.getElementById('paginationGradStudLinks');
        
        if (!tableBody) return;
        
        const search = searchInput ? searchInput.value.trim() : '';
        const searchRoute = "{{ route('student.fetch') }}";

        // Show loading state
        tableBody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center">                
                    <div class="d-flex justify-content-center align-items-center py-2">
                        <i class="fas fa-spinner fa-spin me-2"></i>
                        <span>Searching Student...</span>
                    </div>
                </td>
            </tr>
        `;

        fetch(`${searchRoute}?searchgradstud=${encodeURIComponent(search)}&page=${page}`, {
            headers: { 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(res => {
            tableBody.innerHTML = '';
            pagination.innerHTML = '';

            if (res.data.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center">No records found</td>
                    </tr>
                `;
                return;
            }

            res.data.forEach(gradstudent => {
                const campusMap = {
                    'MC': 'Main',
                    'VC': 'Victorias',
                    'SCC': 'San Carlos',
                    'HC': 'Hinigaran',
                    'MP': 'Moises Padilla',
                    'IC': 'Ilog',
                    'CA': 'Candoni',
                    'CC': 'Cauayan',
                    'SC': 'Sipalay',
                    'HinC': 'Hinobaan'
                };
                
                tableBody.innerHTML += `
                    <tr>
                        <td>${gradstudent.lname}, ${gradstudent.fname}</td>
                        <td>${gradstudent.stud_id}</td>
                        <td>${gradstudent.gender || 'N/A'}</td>
                        <td>${campusMap[gradstudent.campus] || gradstudent.campus || 'N/A'}</td>
                        <td>${gradstudent.civil_status || 'N/A'}</td>
                        <td>
                            <div class="d-flex align-items-center gap-1">
                                <a href="#" class="btn btn-success btn-sm border btn-studdataview" 
                                    data-id="${gradstudent.stdntid}" 
                                    data-studid="${gradstudent.stud_id}" 
                                    data-fname="${gradstudent.fname || ''}" 
                                    data-mname="${gradstudent.mname || ''}" 
                                    data-lname="${gradstudent.lname || ''}" 
                                    data-ext="${gradstudent.ext || ''}" 
                                    data-gender="${gradstudent.gender || ''}" 
                                    data-bday="${gradstudent.bday || ''}" 
                                    data-pbirth="${gradstudent.pbirth || ''}" 
                                    data-contact="${gradstudent.contact || ''}" 
                                    data-email="${gradstudent.email || ''}" 
                                    data-religion="${gradstudent.religion || ''}" 
                                    data-address="${gradstudent.address || ''}" 
                                    data-civil="${gradstudent.civil_status || ''}" 
                                    data-hnum="${gradstudent.hnum || ''}" 
                                    data-brgy="${gradstudent.brgy || ''}" 
                                    data-city="${gradstudent.city || ''}" 
                                    data-province="${gradstudent.province || ''}" 
                                    data-region="${gradstudent.region || ''}" 
                                    data-zcode="${gradstudent.zcode || ''}" 
                                    data-father="${gradstudent.stud_father || ''}" 
                                    data-mother="${gradstudent.stud_mother || ''}" 
                                    data-guardian="${gradstudent.stud_guardian || ''}" 
                                    data-income="${gradstudent.monthly_income || ''}" 
                                    data-pcontact="${gradstudent.guardian_contact || ''}" 
                                    data-lstschattended="${gradstudent.lstsch_attended || ''}" 
                                    data-lstschattendedyear="${gradstudent.lst_sch_attended_year || ''}" 
                                    data-suclstattended="${gradstudent.suc_lst_attended || ''}" 
                                    data-dateadmission="${gradstudent.date_admission || ''}" 
                                    data-enhiscourse="${gradstudent.enhiscourse || ''}"
                                    title="View Details">
                                    <i class="ti ti-eye" style="color: #fff"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                `;
            });

            // Pagination
            for (let i = 1; i <= res.last_page; i++) {
                pagination.innerHTML += `
                    <li class="page-item ${i === res.current_page ? 'active' : ''}">
                        <a class="page-link" href="#" data-page="${i}">${i}</a>
                    </li>
                `;
            }

            // Pagination click events
            pagination.querySelectorAll('.page-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    loadGradStudents(parseInt(this.dataset.page));
                });
            });
        })
        .catch(error => {
            console.error('Error loading students:', error);
            tableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center text-danger">Error loading data</td>
                </tr>
            `;
        });
    }

    function loadEnrollmentHistory(studentId) {
        const tableBody = document.getElementById('enrollmentHistoryTable');
        if (!tableBody) return;
        
        tableBody.innerHTML = '<tr><td colspan="6" class="text-center">Loading...</td></tr>';
        
        if (!studentId) {
            tableBody.innerHTML = '<tr><td colspan="6" class="text-center">No student ID provided</td></tr>';
            return;
        }
        
        const url = "{{ route('studenthistory.fetch', ['stdntid' => ':id']) }}".replace(':id', studentId);
        
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                let html = '';
                
                if (data.length === 0) {
                    html = '<tr><td colspan="6" class="text-center">No enrollment history found</td></tr>';
                } else {
                    data.forEach(function(record) {
                        let semesterText;
                        switch(parseInt(record.semester)) {
                            case 1:
                                semesterText = '<span class="badge bg-info">1st Sem</span>';
                                break;
                            case 2:
                                semesterText = '<span class="badge bg-info">2nd Sem</span>';
                                break;
                            case 3:
                                semesterText = '<span class="badge bg-secondary">Summer</span>';
                                break;
                            default:
                                semesterText = '<span class="badge bg-secondary">' + (record.semester || 'N/A') + '</span>';
                                break;
                        }
                        html += '<tr>';
                        html += '<td>' + (record.studentID || 'N/A') + '</td>';
                        html += '<td>' + (record.schlyear || 'N/A') + '</td>';
                        html += '<td>' + semesterText + '</td>';
                        html += '<td>' + (record.course || 'N/A') + '</td>';
                        html += '<td>' + (record.studYear || record.year_level || 'N/A') + '</td>';
                        html += '<td>' + (record.studSec || record.section || 'N/A') + '</td>';
                        html += '</tr>';
                    });
                }
                
                tableBody.innerHTML = html;
            },
            error: function(xhr, status, error) {
                console.error('Error loading enrollment history:', error);
                tableBody.innerHTML = '<tr><td colspan="6" class="text-center text-danger">Error loading enrollment history</td></tr>';
            }
        });
    }

    // ===== ROUTE VARIABLES =====
    var provincesRoute = '{{ route("getProvinces", ["region_id" => ":id"]) }}';
    var citiesRoute = '{{ route("getCities", ["province_id" => ":id"]) }}';
    var barangaysRoute = '{{ route("getBarangays", ["city_id" => ":id"]) }}';

    // ===== UPDATE ADDRESS FUNCTION =====
    function updateAddress() {
        const hnum = $('#viewdatastudHnum').val() || '';
        const barangay = $('#barangay').find(':selected').data('name') || '';
        const city = $('#city').find(':selected').data('name') || '';
        const province = $('#province').find(':selected').data('name') || '';
        const region = $('#region').find(':selected').data('name') || '';
        const zipcode = $('#zipcode').val() || '';

        const fullAddress = [hnum, barangay, city, province, region, zipcode]
            .filter(Boolean)
            .join(', ');

        $('#viewdatastudAddress').val(fullAddress);
    }

    // ===== DOCUMENT READY =====
    $(document).ready(function() {
        // Initialize Select2
        $('#viewdatastudModal').on('shown.bs.modal', function() {
            $('#region, #province, #city, #barangay').select2({
                theme: 'bootstrap4',
                dropdownParent: $('#viewdatastudModal')
            });
        });

        // Region change
        $('#region').on('change', function() {
            const regionId = $(this).val();
            const regionName = $(this).find(':selected').data('name');
            $('#region_name').val(regionName);
            updateAddress();

            if (regionId) {
                $('#province').empty().append('<option disabled selected>Loading...</option>');
                // Replace :id with the actual region ID
                const url = provincesRoute.replace(':id', regionId);
                
                $.get(url, function(data) {
                    $('#province').html('<option disabled selected>Select Province</option>');
                    data.forEach(p => {
                        $('#province').append(`<option value="${p.province_id}" data-name="${p.name}">${p.name}</option>`);
                    });
                }).fail(function() {
                    $('#province').html('<option disabled selected>Error loading provinces</option>');
                });
            }
        });

        // Province change
        $('#province').on('change', function() {
            const provinceId = $(this).val();
            const provinceName = $(this).find(':selected').data('name');
            $('#province_name').val(provinceName);
            updateAddress();

            if (provinceId) {
                $('#city').empty().append('<option disabled selected>Loading...</option>');
                // Replace :id with the actual province ID
                const url = citiesRoute.replace(':id', provinceId);
                
                $.get(url, function(data) {
                    $('#city').html('<option disabled selected>Select City</option>');
                    data.forEach(c => {
                        $('#city').append(`<option value="${c.city_id}" data-name="${c.name}" data-zip="${c.zip_code}">${c.name}</option>`);
                    });
                }).fail(function() {
                    $('#city').html('<option disabled selected>Error loading cities</option>');
                });
            }
        });

        // City change
        $('#city').on('change', function() {
            const cityName = $(this).find(':selected').data('name');
            const zip = $(this).find(':selected').data('zip');

            $('#city_name').val(cityName);
            $('#zipcode').val(zip || '');

            updateAddress();

            const cityId = $(this).val();

            if (cityId) {
                $('#barangay').empty().append('<option disabled selected>Loading...</option>');
                // Replace :id with the actual city ID
                const url = barangaysRoute.replace(':id', cityId);
                
                $.get(url, function(data) {
                    $('#barangay').html('<option disabled selected>Select Barangay</option>');
                    data.forEach(b => {
                        $('#barangay').append(`<option value="${b.id}" data-name="${b.name}">${b.name}</option>`);
                    });
                }).fail(function() {
                    $('#barangay').html('<option disabled selected>Error loading barangays</option>');
                });
            }
        });

        // Barangay change
        $('#barangay').on('change', function() {
            const brgyName = $(this).find(':selected').data('name');
            $('#brgy_name').val(brgyName);
            updateAddress();
        });
    });

    // View button click
    $(document).on('click', '.btn-studdataview', function(e) {
        e.preventDefault();
        
        const data = $(this).data();
        
        // Format birthday
        let formattedDate = '';
        if (data.bday) {
            const date = new Date(data.bday);
            formattedDate = date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }

        // Construct full name
        let fullName = '';
        if (data.fname) fullName += data.fname;
        if (data.mname) fullName += ' ' + data.mname.charAt(0) + '.';
        if (data.lname) fullName += ' ' + data.lname;
        if (data.ext) fullName += ' ' + data.ext;
        if (!fullName.trim()) fullName = 'Student Name';

        // Populate header
        $('#studentFullName').text(fullName);
        $('#studentIdDisplay').html('<i class="fas fa-id-card me-2"></i>Student ID: ' + (data.studid || 'N/A'));
        
        const courseInfo = data.enhiscourse || 'Not Enrolled';
        const enrollmentYear = data.dateadmission ? new Date(data.dateadmission).getFullYear() : 'N/A';
        const enrollmentDate = data.dateadmission ? 'Aug ' + enrollmentYear : 'N/A';
        
        $('#studentInfoDisplay').html(
            '<span><i class="fas fa-graduation-cap me-1"></i>' + courseInfo + '</span>' +
            '<span><i class="fas fa-calendar-alt me-1"></i>Enrolled: ' + enrollmentDate + '</span>' +
            '<span class="badge bg-warning text-dark"><i class="fas fa-circle me-1" style="font-size: 8px;"></i>Active</span>'
        );

        // Populate form fields
        $('#viewdatastudIdprim').val(data.id);
        $('#viewdatastudID').val(data.studid);
        $('#viewdatastudFname').val(data.fname);
        $('#viewdatastudMname').val(data.mname);
        $('#viewdatastudLname').val(data.lname);
        $('#viewdatastudExt').val(data.ext);
        $('#viewdatastudGender').val(data.gender).trigger('change');
        $('#viewdatastudBdaynotformat').val(data.bday);
        $('#viewdatastudBday').val(formattedDate);
        $('#viewdatastudBdayp').val(data.pbirth);
        $('#viewdatastudMobile').val(data.contact);
        $('#viewdatastudEmail').val(data.email);
        $('#viewdatastudReligion').val(data.religion);
        $('#viewdatastudAddress').val(data.address);
        $('#viewdatastudcivilstat').val(data.civil).trigger('change');
        $('#viewdatastudHnum').val(data.hnum);
        $('#barangay').val(data.brgy).trigger('change');
        $('#city').val(data.city).trigger('change');
        $('#province').val(data.province).trigger('change');
        $('#region').val(data.region).trigger('change');
        $('#zipcode').val(data.zcode);

        $('#viewdatastudfather').val(data.father);
        $('#viewdatastudmother').val(data.mother);
        $('#viewdatastudguardian').val(data.guardian);
        $('#viewdatastudprntincome').val(data.income);
        $('#viewdatastudpcontact').val(data.pcontact);

        $('#viewdatastudlstschattended').val(data.lstschattended);
        $('#viewdatastudlstschattendedyear').val(data.lstschattendedyear);
        $('#viewdatastudlstsucattnded').val(data.suclstattended);
        $('#viewdatastuddateadmission').val(data.dateadmission);

        // Load enrollment history
        loadEnrollmentHistory(data.studid);

        // Show modal
        $('#viewdatastudModal').modal('show');
    });

    // ===== UPDATE FORM SUBMISSION =====
    const studInfoUpdateRoute = "{{ route('student.update', ['id' => ':id']) }}";
    
    $('#editStudInfoForm').submit(function(event) {
        event.preventDefault();
        
        // Get the student ID from the hidden input
        const studentId = $('#viewdatastudIdprim').val();
        
        if (!studentId) {
            toastr.error('Student ID is missing');
            return;
        }
        
        // Replace :id with the actual student ID
        const url = studInfoUpdateRoute.replace(':id', studentId);
        const formData = $(this).serialize();
        
        // Disable submit button
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.text();
        submitBtn.prop('disabled', true).text('Saving...');

        $.ajax({
            url: url,
            type: "POST",
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    
                    // Close the modal
                    $('#viewdatastudModal').modal('hide');
                    
                    // Reload the table with current page
                    const currentPage = $('.page-item.active .page-link').data('page') || 1;
                    loadStudents(currentPage);
                    loadGradStudents(currentPage);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                let errorMessage = 'An error occurred';
                if (xhr.responseText) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        errorMessage = response.message || response.error || 'An error occurred';
                    } catch(e) {
                        errorMessage = 'An error occurred while updating';
                    }
                }
                toastr.error(errorMessage);
            },
            complete: function() {
                submitBtn.prop('disabled', false).text(originalText);
            }
        });
    });

    // ===== SEARCH FORM VALIDATION =====
    $('#searchForm').validate({
        rules: {
            searchstud: { required: true },
        },
        messages: {
            searchstud: { required: "Please Enter Search Student Last Name or Student ID" },
        },
        errorElement: 'span',
        errorPlacement: function(error, element) {
            error.addClass('invalid-feedback');
            element.closest('.col-md-4').append(error);
        },
        highlight: function(element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element) {
            $(element).removeClass('is-invalid');
        },
        submitHandler: function() {
            loadStudents(1);
        }
    });

    $('#searchGradForm').validate({
        rules: {
            searchgradstud: { required: true },
        },
        messages: {
            searchgradstud: { required: "Please Enter Search Student Last Name or Student ID" },
        },
        errorElement: 'span',
        errorPlacement: function(error, element) {
            error.addClass('invalid-feedback');
            element.closest('.col-md-4').append(error);
        },
        highlight: function(element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element) {
            $(element).removeClass('is-invalid');
        },
        submitHandler: function() {
            loadGradStudents(1);
        }
    });
</script>