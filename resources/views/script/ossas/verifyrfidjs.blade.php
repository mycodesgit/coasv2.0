<script>
    const rfidInput = document.getElementById('rfidScanner');
    const visibleRfidField = document.getElementById('studentUniqueRFID');

    rfidInput.addEventListener('input', function() {
        visibleRfidField.value = this.value.trim();
    });

    // Main scan handler
    rfidInput.addEventListener('change', function() { 
        const stdntrfid = this.value.trim();

        if (!stdntrfid) return;

        fetchStudentByRFID(stdntrfid);
    });

    function fetchStudentByRFID(stdntrfid) {
         console.log(stdntrfid);
        fetch('{{ route("verifyStudentByRFID") }}', {   
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',              
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ stdntrfid: stdntrfid })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.student) {
                fillStudentData(data.student);
            } else {
                showNotFound();
                clearAllFields();
            }
           
        })
        .catch(err => {
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Something went wrong while fetching data.'
            });
            clearAllFields();
        })
        .finally(() => {
            rfidInput.value = '';
            rfidInput.focus();
        });
    }

    function fillStudentData(student) {
        // Form fields
        document.getElementById('stdntID').value         = student.studntid || '';
        document.getElementById('studentName').value     = student.fullname || '';
        document.getElementById('studentCourse').value   = student.progcourse || '';
        document.getElementById('studentCivilStatus').value = student.civil_status || '';
        document.getElementById('studentAddress').value  = student.address || '';
        document.getElementById('studentUniqueRFID').value = student.stdntrfid || '';

        // ID Card preview
        document.getElementById('studentCardName').textContent   = student.fullname || '—';
        document.getElementById('studentCardNo').textContent     = student.studntid || '—';
        document.getElementById('studentCardCourse').textContent = student.progcourse || '—';
        document.getElementById('studentCardGender').textContent = student.gender || '—';

        // Photo (if you store photo path)
        // if (student.photo) {
        //     document.getElementById('photo').src = student.photo;
        // } else {
        //     document.getElementById('photo').src = '{{ asset("uilibs/images/user.png") }}';
        // }
    }

    function clearAllFields() {
        const fields = [
            'stdntID', 'studentName', 'studentCourse', 'studentCivilStatus',
            'studentAddress', 'studentUniqueRFID',
            'studentCardName', 'studentCardNo', 'studentCardCourse', 'studentCardGender'
        ];

        fields.forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                if (el.tagName === 'TEXTAREA' || el.tagName === 'INPUT') {
                    el.value = '';
                } else {
                    el.textContent = '—';
                }
            }
        });

        document.getElementById('photo').src = '{{ asset("uilibs/images/user.png") }}';
    }

    function showNotFound() {
        Swal.fire({
            icon: 'warning',
            title: 'No Data Found',
            text: 'No student record found for this RFID.',
            timer: 2500,
            showConfirmButton: false
        });
    }

    // Optional: keep focus always on scanner
    document.addEventListener('click', () => rfidInput.focus());
</script>