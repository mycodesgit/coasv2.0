@extends('layouts.master_ossa')

@section('title')
    CISS V.1.0 || Ossa Student RFID Registration
@endsection

@section('workspace')
    <style>
        /* Scale ID card to fit page exactly */
        .id-frontcard {
            width: 85.6mm;
            height: 54mm;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
            /* background: linear-gradient(135deg,#d8f3dc,#f1f5d6);
            font-family: Arial, sans-serif; */
            display: flex;
            flex-direction: column;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
            /* background-image: url("{{ asset('uilibs/images/studentidimage/IDfront.webp') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat; */
        }

        .id-backcard {
            width: 85.6mm;
            height: 54mm;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
            /* background: linear-gradient(135deg,#d8f3dc,#f1f5d6);
            font-family: Arial, sans-serif; */
            display: flex;
            flex-direction: column;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
            /* background-image: url("{{ asset('uilibs/images/studentidimage/IDfront.webp') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat; */
        }

        /* Header */
        .id-header {
            /* background: #0f766e;
            color: white; */
            padding: 2px 10px 10px 10px;
        }

        .id-header h6 {
            margin: 0;
            font-weight: 700;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 10px;
        }

        .id-header small {
            font-size: 8px;
            opacity: .9;
            margin-top: -10px;
            padding-left: 34px;
        }

        /* Body */
        .id-body {
            padding: 6px 10px;
            display: flex;
            gap: 10px;
            align-items: center;
            flex: 1;
        }

        .student-photo {
            margin-top: -15px;
            width: 75px;
            height: 90px;
            border: 2px solid #2c7a7b;
            border-radius: 6px;
            overflow: hidden;
            flex-shrink: 0;
        }

        .pic {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .student-info {
            margin-top: -17px !important;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .student-info h5 {
            font-weight: 700;
            color: #0f766e;
            margin-bottom: 4px;
            font-size: 12px;
        }




        .student-name {
            margin-left: -5px;
            background-color: #0a6c3f;
            padding-left: 8px;
            padding-top: 2px;
            padding-bottom: 3px;
            padding-right: 12px;
            /* border-radius: 0px 4px 4px 0px; */
            position: absolute;
            top: 124px !important;
            left: 120px;
            font-size: 8pt;
            font-weight: normal;
            color: #ffffff;
            z-index: 10;
            clip-path: polygon(100% 0%, 0% 0%, 0% 100%, 92% 100%, 96% 50%, 100% 0%);
        }

        .student-id {
            position: absolute;
            top: 160px;
            left: 125px;
            font-size: 8pt;
        }

        .student-course {
            position: absolute;
            top: 185px;
            left: 125px;
            font-size: 8pt;
        }

        /* .info-row {
            display: flex;
            font-size: 9px;
            margin-bottom: 2px;
        }

        .info-label {
            width: 60px;
            font-weight: 600;
            color: #333;
        } */

        .barcode {
            margin-top: 4px;
            height: 25px;
            background: repeating-linear-gradient(
                90deg,
                #000,
                #000 2px,
                transparent 2px,
                transparent 4px
            );
        }

        /* Footer */
        .id-footer {
            /* background: #0f766e; */
            height: 18px;
        }

        img {
            max-width: 100%;
        }


        .id-body-back {
            padding: 16px 18px;
            font-family: Arial, sans-serif;
        }

        /* Top text */
        .emergency-text {
            font-size: 7px;
            font-style: italic;
            margin-bottom: 2px;
        }

        /* Grid layout */
        .back-grid {
            display: flex;
            justify-content: space-between;
            gap: 12px;
        }

        .back-col {
            flex: 1;
        }

        .back-col label {
            font-weight: 300;
            font-size: 5pt;
        }

        /* Lines */
        .line {
            border-bottom: 1px solid linear-gradient(135deg,#d8f3dc,#f1f5d6);
            height: 1px;
            margin-bottom: 10px;
        }

        /* Green divider */
        .green-line {
            height: 2px;
            background: #2f855a;
            margin: 1px 0;
        }

        /* Bottom note */
        .note-text {
            margin-top: 5px;
            text-align: center;
            line-height: 1.2;
            font-size: 5pt;
        }
    </style>

    <div class="row">
        <div class="col-12">
            <div class="mb-6">
                {{-- <h1 class="fs-5 mb-4 d-none d-md-block">Dashboard</h1> --}}
                <div class="card" style=" background-color: #e9ecef; margin-top: -10px">
                    <div class="card-body">
                        <ol class="breadcrumb" style="margin-bottom: -3px;">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}" class="btn btn-success btn-sm text-light">
                                    <i class="fas fa-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item mt-1">Ossa</li>
                            <li class="breadcrumb-item active mt-1">Student RFID Registration</li>
                        </ol>
                    </div>
                </div>
                <div class="row g-3 mb-3 mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="card">
                                            <div class="card-header pt-3">
                                                <h6 class="card-title">
                                                    <i class="ti ti-user-plus"></i> Register New Student
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <form id="adRFIDstud">
                                                    @csrf
                                                    <div class="row g-2">
                                                        <div class="col-md-12">
                                                            <label for="stdntID" class="form-label"> Student ID No.:</label>
                                                            <input type="text" name="stdntid" id="stdntID" class="form-control form-control-sm"
                                                                oninput="formatInput(this); this.value = this.value.toUpperCase(); fetchStudentName(this.value);">
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label for="studentName" class="form-label"> Name:</label>
                                                            <input type="text" id="studentName" class="form-control form-control-sm" readonly>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label for="studentCourse" class="form-label"> Course Year&Section:</label>
                                                            <input type="text" id="studentCourse" class="form-control form-control-sm" readonly>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label for="studentCivilStatus" class="form-label"> Civil Status</label>
                                                            <input type="text" id="studentCivilStatus" class="form-control form-control-sm" readonly>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label for="studentAddress" class="form-label"> Address:</label>
                                                            <textarea rows="3" id="studentAddress" class="form-control form-control-sm"></textarea>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label for="studentUniqueRFID" class="form-label"> RFID:</label>
                                                            <input type="text" id="studentUniqueRFID" name="stdntrfid" class="form-control form-control-sm" readonly>
                                                            <input type="text" id="rfidScanner" style="opacity:0; position:absolute;">
                                                        </div>
                                                        <div class="col-md-12">
                                                            <button type="submit" class="btn btn-success">Save changes</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 d-none d-md-block">
                                        <div class="card">
                                            <div class="card-header pt-3">
                                                <h6 class="card-title">
                                                    <i class="ti ti-address-book"></i> Student ID Front Template
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <div class="id-frontcard" style="background-image: url('{{ asset('uilibs/images/studentidimage/IDfront.webp') }}'); 
                                                        background-size: cover;
                                                        background-position: center;
                                                        background-repeat: no-repeat;">
                                                        <div class="id-header">
                                                            <div style="height: 35px">
                                                                <h6 style="margin-left: 100px; margin-top: 10px">
                                                                    <img src="{{ asset('uilibs/images/studentidimage/headerlogo.webp') }}" alt="logo" width="90%">
                                                                </h6>
                                                            </div>
                                                        </div>
                                                        <div class="id-body">
                                                            {{-- <div class="student-photo">
                                                                <img id="photo" src="{{ asset('uilibs/images/student.png') }}" class="pic">
                                                            </div> --}}
                                                            <div>
                                                                <div class="student-name" id="studentCardName"></div>
                                                                <div class="student-id" id="studentCardNo"></div>
                                                                <div class="student-course" id="studentCardCourse"></div>
                                                            </div>
                                                        </div>
        
                                                        <div class="id-footer"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 d-none d-md-block">
                                        <div class="card">
                                            <div class="card-header pt-3">
                                                <h6 class="card-title">
                                                    <i class="ti ti-address-book"></i> Student ID Back Template
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <div class="id-backcard">
                                                        <div class="id-body-back">
                                                            <p class="emergency-text">
                                                                In case of emergency, please contact:
                                                            </p>

                                                            <div class="back-grid">
                                                                <div class="back-col">
                                                                    <label>Person:</label>
                                                                    <div class="line"></div>

                                                                    <label>Number:</label>
                                                                    <div class="line"></div>

                                                                    <label>Address:</label>
                                                                    <div class="line"></div>
                                                                </div>

                                                                <div class="back-col">
                                                                    <label>Birthday:</label>
                                                                    <div class="line"></div>

                                                                    <label>Blood Type:</label>
                                                                    <div class="line"></div>

                                                                    <label>Contact Number:</label>
                                                                    <div class="line"></div>
                                                                </div>
                                                            </div>

                                                            <div class="green-line"></div>

                                                            <p class="note-text">
                                                                The bearer is a bonafide of the Central Philippines State University Kabankalan City, Negros Occidental. 
                                                                This card is non-transferable and available only on the semester period.
                                                                <br>
                                                                Report loss to the <b>OFFICE OF STUDENT SERVICES AND AFFAIRS.</b>
                                                            </p>
                                                            <center>
                                                                <div style="border-bottom: 1px solid #000000; width: 90px"></div>
                                                                <span style="font-size: 6pt; font-family: 'Poppins', sans-serif">ALADINO C. MORACA, PhD</span>
                                                            </center>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card">
                                            <div class="card-header pt-3">
                                                <h6 class="card-title">
                                                    <i class="ti ti-printer"></i> Print Student ID
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="mb-2">
                                                        <button onclick="printFrontIDonly()" class="btn btn-warning btn-sm btn-block">
                                                            Print Front Student ID
                                                        </button>
                                                    </div>
                                                    <div class="mb-2">
                                                        <button onclick="printOnlyID()" class="btn btn-secondary btn-sm btn-block">
                                                            Print Back Student ID
                                                        </button>
                                                    </div>
                                                    <div class="mb-2">
                                                        <button class="btn btn-success btn-sm btn-block">
                                                            Preview Print Student ID
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function formatInput(input) {
            let cleaned = input.value.replace(/[^A-Za-z0-9]/g, '');

            if (cleaned.length > 0) {
                let formatted = cleaned.substring(0, 4) + '-' + cleaned.substring(4, 8) + '-' + cleaned.substring(8, 9);
                input.value = formatted;
            } else {
                input.value = '';
            }
        }

        function handleDelete(event) {
            if (event.key === 'Backspace') {
                let input = event.target;
                let value = input.value;
                input.value = value.substring(0, value.length - 1);
                formatInput(input);
            }
        }

        function fetchStudentName(studid) {
            if (studid) {

                const url = `{{ route('getossaStudentById', ['id' => ':id']) }}`.replace(':id', studid);

                fetch(url)
                .then(response => response.json())
                .then(data => {

                    if (data.error) {

                        document.getElementById('studentName').value = 'Student not found';
                        document.getElementById('studentCourse').value = 'Data not found';
                        document.getElementById('studentCivilStatus').value = 'Data not found';
                        document.getElementById('studentAddress').value = 'Data not found';

                        document.getElementById('studentCardName').textContent = 'Data not found';
                        document.getElementById('studentCardNo').textContent = '';
                        document.getElementById('studentCardCourse').textContent = '';
                        document.getElementById('studentCardGender').textContent = '';

                    } else {

                        const fullName = `${data.lname}, ${data.fname}${data.mname ? ' ' + data.mname.charAt(0) + '.' : ''}${data.ext ? ' ' + data.ext : ''}`.toUpperCase();
                        const civilStatus = `${data.civil_status}`.toUpperCase();
                        const progName = `${data.progName || ''}`
                            .replace(/BACHELOR OF SCIENCE/i, 'BS')
                            .replace(/BACHELOR OF SECONDARY/i, 'BS')
                            .replace(/BACHELOR OF ELEMENTARY EDUCATION/i, 'BEED')
                            .toUpperCase();
                        const address = `${data.address}`.toUpperCase();

                        // INPUT FIELD
                        document.getElementById('studentName').value = fullName;
                        document.getElementById('studentCourse').value = progName;
                        document.getElementById('studentCivilStatus').value = civilStatus;
                        document.getElementById('studentAddress').value = address;

                        // ID CARD PREVIEW
                        document.getElementById('studentCardName').textContent = fullName;
                        document.getElementById('studentCardNo').textContent = data.stud_id;
                        document.getElementById('studentCardCourse').textContent = progName;
                        document.getElementById('studentCardGender').textContent = data.gender;

                        // ⭐ Focus RFID scanner after student is loaded
                        document.getElementById('rfidScanner').focus();

                    }

                })
                // .catch(error => {
                //     console.error('Error fetching student:', error);
                // });

            }

        }
    </script>
    <script>
        var rfidstudentCreateRoute = "{{ route('rfid.create') }}";
    </script>

    <script>
        function printFrontIDonly() {
            const front = document.querySelector('.id-frontcard').cloneNode(true);
            const back = document.querySelector('.id-backcard').cloneNode(true);
            
            const frontBg = "{{ asset('uilibs/images/studentidimage/IDfront.webp') }}";
            const printWindow = window.open('', '', 'width=400,height=300');

            printWindow.document.write(`
                <html>
                <head>
                    <title>Print ID</title>
                    <style>
                        @page {
                            size: 85.6mm 54mm;
                            margin: 0;
                        }

                        body {
                            margin: 0;
                            padding: 0;
                            -webkit-print-color-adjust: exact;
                            print-color-adjust: exact;
                        }

                        .page {
                            width: 85.6mm;
                            height: 54mm;
                            page-break-after: always;
                            display: flex;
                            justify-content: center;
                            align-items: center;
                        }

                        .page:last-child {
                            page-break-after: auto;
                        }

                        /* Scale ID card to fit page exactly */
                        .id-frontcard {
                            -webkit-print-color-adjust: exact;
                            print-color-adjust: exact;
                            width: 85.6mm;
                            height: 54mm;
                            border-radius: 14px;
                            overflow: hidden;
                            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
                            display: flex;
                            flex-direction: column;
                            box-sizing: border-box;
                            font-family: 'Poppins', sans-serif;
                            background-image: url("${frontBg}") !important;
                            background-size: cover;
                            background-position: center;
                            background-repeat: no-repeat;
                        }

                        .id-backcard {
                            width: 85.6mm;
                            height: 54mm;
                            border-radius: 14px;
                            overflow: hidden;
                            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
                            display: flex;
                            flex-direction: column;
                            box-sizing: border-box;
                            font-family: 'Poppins', sans-serif;
                        }

                        /* Header */
                        .id-header {
                            /* background: #0f766e; */
                            color: white;
                            padding: 2px 10px 10px 10px;
                        }

                        .id-header h6 {
                            margin: 0;
                            font-weight: 700;
                            letter-spacing: 1px;
                            display: flex;
                            align-items: center;
                            gap: 5px;
                            font-size: 10px;
                        }

                        .id-header small {
                            font-size: 8px;
                            opacity: .9;
                            margin-top: -10px;
                            padding-left: 34px;
                        }

                        /* Body */
                        .id-body {
                            padding: 6px 10px;
                            display: flex;
                            gap: 10px;
                            align-items: center;
                            flex: 1;
                        }

                        .student-photo {
                            margin-top: -15px;
                            width: 75px;
                            height: 90px;
                            border: 2px solid #2c7a7b;
                            border-radius: 6px;
                            overflow: hidden;
                            flex-shrink: 0;
                        }

                        .pic {
                            width: 100%;
                            height: 100%;
                            object-fit: cover;
                        }

                        .student-info {
                            margin-top: -17px !important;
                            flex: 1;
                            display: flex;
                            flex-direction: column;
                            justify-content: center;
                        }

                        .student-info h5 {
                            font-weight: 700;
                            color: #0f766e;
                            margin-bottom: 4px;
                            font-size: 12px;
                        }

                        .info-row {
                            display: flex;
                            font-size: 9px;
                            margin-bottom: 2px;
                        }

                        .info-label {
                            width: 60px;
                            font-weight: 600;
                            color: #333;
                        }

                        .barcode {
                            margin-top: 4px;
                            height: 25px;
                            background: repeating-linear-gradient(
                                90deg,
                                #000,
                                #000 2px,
                                transparent 2px,
                                transparent 4px
                            );
                        }

                        /* Footer */
                        .id-footer {
                            /* background: #0f766e; */
                            height: 18px;
                        }

                        img {
                            max-width: 100%;
                        }
                        .id-body-back {
                            padding: 16px 18px;
                            font-family: Arial, sans-serif;
                        }

                        /* Top text */
                        .emergency-text {
                            font-size: 7px;
                            font-style: italic;
                            margin-bottom: 2px;
                        }

                        /* Grid layout */
                        .back-grid {
                            display: flex;
                            justify-content: space-between;
                            gap: 12px;
                        }

                        .back-col {
                            flex: 1;
                        }

                        .back-col label {
                            font-weight: 300;
                            font-size: 5pt;
                        }

                        /* Lines */
                        .line {
                            border-bottom: 1px solid linear-gradient(135deg,#d8f3dc,#f1f5d6);
                            height: 1px;
                            margin-bottom: 10px;
                        }

                        /* Green divider */
                        .green-line {
                            height: 2px;
                            background: #2f855a;
                            margin: 1px 0;
                        }

                        /* Bottom note */
                        .note-text {
                            margin-top: 5px;
                            text-align: center;
                            line-height: 1.2;
                            font-size: 5pt;
                        }


                        .btn-block {
                            display: block;
                            width: 100%;
                        }
                    </style>
                </head>
                <body>
                    <!-- PAGE 1 = FRONT -->
            <div class="page">
                ${front.outerHTML}
            </div>

            <!-- PAGE 2 = BACK -->
            <div class="page">
                ${back.outerHTML}
            </div>
                </body>
                </html>
            `);

            printWindow.document.close();

            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 1000);
        }
    </script>
@endsection
