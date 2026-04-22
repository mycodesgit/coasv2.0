@extends('layouts.master_ossa')

@section('title')
    CISS V.1.0 || Ossa Student RFID Registration
@endsection

@section('workspace')
    <style>
        .id-frontcard {
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
            background-color: #ffffff;
        }
        .id-header {
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
        .id-body {
            padding: 6px 10px;
            display: flex;
            gap: 10px;
            align-items: center;
            flex: 1;
        }
        .photo-wrapper {
            margin-top: -20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .student-photo {
            margin-top: -28px;
            margin-left: 1px;
            width: 86px;
            height: 106px;
            border: 1px solid #eff317;        
            box-shadow: 0 0 0 2px #116e36;
            border-radius: 6px;
            overflow: hidden;
            flex-shrink: 0;
        }
        .pic {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .signature-icon {
            font-size: 19px;
            color: #116e36;
            position: relative;
        }
        .student-name {
            margin-left: -5px;
            background-color: #0a6c3f;
            padding-left: 8px;
            padding-top: 2px;
            padding-bottom: 3px;
            padding-right: 12px;
            position: absolute;
            top: 124px !important;
            left: 120px;
            font-size: 8pt;
            font-weight: bold;
            color: #ffffff;
            z-index: 10;
            clip-path: polygon(100% 0%, 0% 0%, 0% 100%, 92% 100%, 96% 50%, 100% 0%);
        }
        .student-id {
            position: absolute;
            font-weight: bold;
            font-family: "Poppins", sans-serif !important;
            top: 160px;
            left: 125px;
            font-size: 8pt;
        }
        .student-course {
            position: absolute;
            font-weight: bold;
            font-family: "Poppins", sans-serif !important;
            top: 185px;
            left: 125px;
            font-size: 8pt;
        }
        .id-footer {
            height: 18px;
        }
        img {
            max-width: 100%;
        }
        .id-body-back {
            padding: 16px 18px;
            font-family: Arial, sans-serif;
            background-color: #ffffff;
        }
        .emergency-text {
            margin-top: -5px;
            font-size: 7px;
            font-style: italic;
            margin-bottom: 0px;
        }
        .back-grid {
            margin-top: -6px;
            display: flex;
            justify-content: space-between;
            gap: 5px;
        }
        .back-col:first-child {
            flex: 3;
            margin-top: -5px;
        }
        .back-col:last-child {
            flex: 1; 
            margin-top: -5px;
        }
        .back-grid-second {
            margin-top: 10px;
            display: flex;
            justify-content: space-between;
            gap: 5px;
        }
        .back-col-second:first-child {
            flex: 2;
            margin-top: -5px;
        }

        .back-col-second:nth-child(2) {
            flex: 1;
            margin-top: -5px;
        }

        .back-col-second:last-child {
            flex: 1; 
            margin-top: -5px;
        }
        .back-col label {
            font-weight: 300;
            font-size: 5pt;
        }
        .back-col-second label {
            font-weight: 300;
            font-size: 5pt;
        }
        .back-col-full{
            margin-top: -5px !important;
        }
        .back-col-full label{
            font-weight: 300;
            font-size: 5pt;
        }
        .linedata {
            padding-top: 2px !important;
            font-size: 5pt !important;
        }
        .line {
            border-bottom: 1px solid #000;
            height: 1px;
            font-size: 7pt;
        }
        .green-line {
            margin-top: 9px !important;
            height: 2px;
            background: #2f855a;
            margin: 1px 0;
        }
        .note-text {
            margin-top: 5px;
            text-align: center;
            line-height: 1.1;
            font-size: 5pt;
        }
        .signature-block {
            text-align: center;
            margin-top: 5px;
        }

        .signature-line {
            border-bottom: 1px solid #000;
            width: 100px;
            margin: 0 auto 2px auto;
        }

        .signature-name {
            font-size: 6pt;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            line-height: 1.2;
        }

        .signature-title {
            font-size: 5pt;
            font-family: 'Poppins', sans-serif;
            margin-top: -1px;
        }
        #qrcode {
            width: 50px;
            height: 50px;
            position: absolute;
            float: right;
            bottom: 30px !important;
            right: 60px !important;
            border: 1px solid #ffffff;
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
                                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                    <h4>Student RFID Registration</h4>
                                </div>
                                <div class="row mt-3 g-3">
                                    <div class="col-md-3">
                                        <div class="card">
                                            <div class="card-header pt-3">
                                                <h6 class="card-title">
                                                    <i class="ti ti-user-plus"></i> Register New Student
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <form id="adRFIDstud" enctype="multipart/form-data">
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
                                                            <label for="studPhoto" class="form-label"> Image:</label>
                                                            <input type="text" id="studPhoto" name="studphoto" class="form-control form-control-sm" readonly>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label for="studSignature" class="form-label"> Image:</label>
                                                            <input type="text" id="studSignature" name="studsignature" class="form-control form-control-sm" readonly>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <button type="submit" class="btn btn-success text-light">
                                                                <i class="fas fa-save"></i> Save changes
                                                            </button>
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
                                            <div class="card-body" style="background-color: #e3eee4">
                                                <div class="table-responsive">
                                                    <div class="id-frontcard" style="background-image: url('{{ asset('uilibs/images/studentidimage/IDfrontoldphotoframe.webp') }}'); 
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
                                                            <div class="photo-wrapper">
                                                                <div class="student-photo">
                                                                    <img id="photo" class="pic">
                                                                </div>
                                                                <div class="signature-icon">
                                                                    {{-- <span id="studentCardSignature"><i class="ti ti-signature"></i></span> --}}
                                                                    <img id="studentCardSignature" style="max-width:50px; display:block;" />
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <div class="student-name" id="studentCardName">NAME</div>
                                                                <div class="student-id" id="studentCardNo"></div>
                                                                <div class="student-course" id="studentCardCourse"></div>
                                                            </div>
                                                        </div>
        
                                                        <div class="">
                                                            <div id="qrcode"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="card">
                                            <div class="card-header pt-3">
                                                <h6 class="card-title">
                                                    <i class="ti ti-address-book"></i> Student ID Back Template
                                                </h6>
                                            </div>
                                            <div class="card-body" style="background-color: #e3eee4">
                                                <div class="table-responsive">
                                                    <div class="id-backcard">
                                                        <div class="id-body-back">
                                                            <p class="emergency-text">
                                                                In case of emergency, please contact:
                                                            </p>

                                                            <div class="back-grid">
                                                                <div class="back-col">
                                                                    <label>Person:</label>
                                                                    <div class="linedata">&nbsp;</div>
                                                                    <div class="line"></div>
                                                                </div>
                                                                <div class="back-col">
                                                                    <label>Number:</label>
                                                                    <div class="linedata">&nbsp;</div>
                                                                    <div class="line"></div>
                                                                </div>
                                                            </div>

                                                            <div class="back-grid-second">
                                                                <div class="back-col-second">
                                                                    <label>Birthday:</label>
                                                                    <div class="linedata" id="studentCardBirthday">&nbsp;</div>
                                                                    <div class="line"></div>
                                                                </div>
                                                                <div class="back-col-second">
                                                                    <label>Blood Type:</label>
                                                                    <div class="linedata">&nbsp;</div>
                                                                    <div class="line"></div>
                                                                </div>
                                                                <div class="back-col-second">
                                                                    <label>Contact No.:</label>
                                                                    <div class="linedata" id="studentCardContact">&nbsp;</div>
                                                                    <div class="line"></div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="back-col-full">
                                                                <label>Address:</label>
                                                                <div class="linedata" id="studentCardAddress">&nbsp;</div>
                                                                <div class="line"></div>
                                                            </div>

                                                            {{-- <div class="green-line"></div> --}}

                                                            <p class="note-text">
                                                                The bearer is a bonafide of the Central Philippines State University Kabankalan City, Negros Occidental. 
                                                                This card is non-transferable and available only on the semester period.
                                                                <br>
                                                                Report loss to the <b>OFFICE OF STUDENT SERVICES AND AFFAIRS.</b>
                                                            </p>
                                                            <center>
                                                                <div class="signature-block">
                                                                    <div class="signature-line"></div>
                                                                    <div class="signature-name">ALADINO C. MORACA, PhD</div>
                                                                    <div class="signature-title">President</div>
                                                                </div>
                                                            </center>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 d-none d-md-block">
                                        <div class="card">
                                            <div class="card-header pt-3">
                                                <h6 class="card-title">
                                                    <i class="ti ti-photo"></i> Capture Image
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <div class="id-camera text-center">
                                                        <div id="cameraPlaceholder" style="display:flex; justify-content:center; align-items:center; height:200px; background:#f1f1f1; border-radius:8px;">
                                                            <i class="ti ti-camera" style="font-size:48px; color:#888;"></i>
                                                        </div>

                                                        <video id="webcam" autoplay playsinline width="100%" style="border-radius:8px; display:none;"></video>

                                                        <div class="mt-2">
                                                            <button type="button" class="btn btn-success text-light" onclick="startCamera()" id="btnStart">
                                                                <i class="ti ti-camera me-1"></i>Turn On Camera
                                                            </button>

                                                            <button type="button" class="btn btn-danger text-light" onclick="stopCamera()" id="btnStop" style="display: none">
                                                                <i class="ti ti-camera-off"></i> Turn Off Camera
                                                            </button>

                                                            <button type="button" class="btn btn-success text-light" onclick="capturePhoto()" id="btnCapture" style="display: none">
                                                                <i class="ti ti-camera"></i> Capture
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="card">
                                            <div class="card-header pt-3">
                                                <h6 class="card-title">
                                                    <i class="ti ti-photo"></i> eSignature Capture
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <div class="id-signature text-center">
                                                        {{-- <div id="signaturePlaceholder" style="display:flex; justify-content:center; align-items:center; height:150px; background:#f1f1f1; border-radius:8px;"> --}}
                                                            <canvas id="signatureCanvas" style="background:#f1f1f1; width:100%; height:150px; border-radius:8px;"></canvas>
                                                        {{-- </div> --}}

                                                        <div class="mt-2">
                                                            <button type="button" class="btn btn-outline-warning" onclick="resetSignature()" id="btnResetSignature">
                                                                <i class="ti ti-refresh me-1"></i>Reset Signature
                                                            </button>

                                                            <button type="button" class="btn btn-success text-light" onclick="saveSignature()" id="btnCaptureSignature">
                                                                <i class="ti ti-signature"></i> Capture
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="card">
                                            <div class="card-header pt-3">
                                                <h6 class="card-title">
                                                    <i class="ti ti-printer"></i> Print Student ID Card
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="mb-2">
                                                        <button onclick="printFrontIDonly()" class="btn btn-outline-warning btn-block">
                                                            <i class="fas fa-print"></i> Print
                                                        </button>
                                                    </div>
                                                    {{-- <div class="mb-2">
                                                        <button onclick="printOnlyID()" class="btn btn-secondary btn-sm btn-block">
                                                            Print Back Student ID
                                                        </button>
                                                    </div> --}}
                                                    <div class="mb-2">
                                                        <button class="btn btn-success text-light btn-block" style="cursor:pointer;" data-bs-toggle="modal" data-bs-target="#idPreviewModal">
                                                            <i class="fas fa-eye"></i> Preview
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

    <div class="modal fade mt-6" id="idPreviewModal" tabindex="-1" role="dialog" aria-labelledby="subjectsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="subjectsModalLabel">Preview ID</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex flex-column align-items-center" style="height: 680px">
                    <div class="id-frontcard" style="
                            transform: scale(1.5);
                            transform-origin: top center;
                            background-image: url('{{ asset('uilibs/images/studentidimage/IDfrontoldphotoframe.webp') }}'); 
                            background-size: cover;
                            background-position: center;
                            background-repeat: no-repeat;
                        ">

                        <div class="id-header">
                            <div style="height: 35px">
                                <h6 style="margin-left: 100px; margin-top: 10px">
                                    <img src="{{ asset('uilibs/images/studentidimage/headerlogo.webp') }}" width="90%">
                                </h6>
                            </div>
                        </div>

                        <div class="id-body">
                            <div style="margin-top: -20px;
                                    display: flex;
                                    flex-direction: column;
                                    align-items: center;">
                                <div style="
                                    margin-top: -30px;
                                    margin-left: -1px;
                                    width: 88px;
                                    height: 106px;
                                    border: 1px solid #eff317;        
                                    box-shadow: 0 0 0 2px #116e36;
                                    border-radius: 6px;
                                    overflow: hidden;
                                    flex-shrink: 0;
                                    ">
                                    <img id="previewPhoto" class="pic">
                                </div>
                                <div class="signature-icon">
                                    {{-- <span id="studentCardSignaturePreview"><i class="ti ti-signature"></i></span> --}}
                                    <img id="studentCardSignaturePreview" style="max-width:80px; display:block;" />
                                </div>
                            </div>

                            <div>
                                <div style="
                                    margin-left: -5px;
                                    background-color: #0a6c3f;
                                    padding-left: 8px;
                                    padding-top: 2px;
                                    padding-bottom: 3px;
                                    padding-right: 12px;
                                    position: absolute;
                                    top: 60px !important;
                                    left: 104px;
                                    font-size: 8pt;
                                    font-family: 'Poppins', sans-serif !important;
                                    font-weight: bold;
                                    color: #ffffff;
                                    z-index: 10;
                                    clip-path: polygon(100% 0%, 0% 0%, 0% 100%, 92% 100%, 96% 50%, 100% 0%);" id="previewName"></div>
                                <div style="position: absolute;
                                            font-family: 'Poppins', sans-serif !important;
                                            font-weight: bold;
                                            top: 95px;
                                            left: 110px;
                                            font-size: 7pt;" 
                                    id="previewId">
                                </div>
                                <div style="position: absolute;
                                            font-family: 'Poppins', sans-serif !important;
                                            font-weight: bold;
                                            top: 120px;
                                            left: 110px;
                                            font-size: 7pt;" 
                                    id="previewCourse">
                                </div>
                            </div>
                        </div>

                        <div>
                            <div style="width: 50px;
                                        height: 50px;
                                        float: right;
                                        position: absolute;
                                        bottom: 10px !important;
                                        right: 15px !important;
                                        border: 1px solid #ffffff;" 
                                id="previewQr">
                            </div>
                        </div>
                    </div>
                    <br><br>
                    <div class="id-backcard" style="transform: scale(1.5);
                            transform-origin: middle center; margin-top: 130px;">
                        <div class="id-body-back">
                            <p class="emergency-text">
                                In case of emergency, please contact:
                            </p>

                            <div class="back-grid">
                                <div class="back-col">
                                    <label>Person:</label>
                                    <div class="linedata">&nbsp;</div>
                                    <div class="line"></div>
                                </div>
                                <div class="back-col">
                                    <label>Number:</label>
                                    <div class="linedata">&nbsp;</div>
                                    <div class="line"></div>
                                </div>
                            </div>

                            <div class="back-grid-second">
                                <div class="back-col-second">
                                    <label>Birthday:</label>
                                    <div class="linedata" id="studentCardBirthdayPreview">&nbsp;</div>
                                    <div class="line"></div>
                                </div>
                                <div class="back-col-second">
                                    <label>Blood Type:</label>
                                    <div class="linedata">&nbsp;</div>
                                    <div class="line"></div>
                                </div>
                                <div class="back-col-second">
                                    <label>Contact No.:</label>
                                    <div class="linedata" id="studentCardContactPreview"></div>
                                    <div class="line"></div>
                                </div>
                            </div>
                            
                            <div class="back-col-full">
                                <label>Address:</label>
                                <div class="linedata" id="studentCardAddressPreview">&nbsp;</div>
                                <div class="line"></div>
                            </div>

                            {{-- <div class="green-line"></div> --}}

                            <p class="note-text">
                                The bearer is a bonafide of the Central Philippines State University Kabankalan City, Negros Occidental. 
                                This card is non-transferable and available only on the semester period.
                                <br>
                                Report loss to the <b>OFFICE OF STUDENT SERVICES AND AFFAIRS.</b>
                            </p>
                            <center>
                                <div class="signature-block">
                                    <div class="signature-line"></div>
                                    <div class="signature-name">ALADINO C. MORACA, PhD</div>
                                    <div class="signature-title">President</div>
                                </div>
                            </center>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        var rfidstudentCreateRoute = "{{ route('rfid.create') }}";
    </script>

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
 
        let currentEncryptedId = '';

        // =====================
        // QR GENERATOR (MAIN)
        // =====================
        function generateQR(value) {
            const qrContainer = document.getElementById('qrcode');

            qrContainer.innerHTML = '';

            if (!value) return;

            new QRCode(qrContainer, {
                text: value,
                width: 80,
                height: 80,
                correctLevel: QRCode.CorrectLevel.H
            });
        }


        // =====================
        // FETCH STUDENT
        // =====================
        function fetchStudentName(studid) {
            if (!studid) return;

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
                        document.getElementById('studentCardAddress').textContent = '';
                        document.getElementById('studentCardAddressPreview').textContent = '';
                        document.getElementById('studentCardBirthday').textContent = '';
                        document.getElementById('studentCardBirthdayPreview').textContent = '';
                        document.getElementById('studentCardContact').textContent = '';
                        document.getElementById('studentCardContactPreview').textContent = '';
                        document.getElementById('studentCardSignature').textContent = '';
                        document.getElementById('studentCardSignaturePreview').textContent = '';


                        document.getElementById('qrcode').innerHTML = '';
                        currentEncryptedId = '';

                    } else {

                        const fullName = `${data.lname}, ${data.fname}${data.mname ? ' ' + data.mname.charAt(0) + '.' : ''}${data.ext && data.ext.toLowerCase() !== 'n/a' ? ' ' + data.ext : ''}`.toUpperCase();

                        const civilStatus = `${data.civil_status}`.toUpperCase();

                        const progName = `${data.progName || ''}`
                            .replace(/BACHELOR OF ARTS/i, 'BA')
                            .replace(/BACHELOR OF SCIENCE/i, 'BS')
                            .replace(/BACHELOR OF SECONDARY/i, 'BS')
                            .replace(/BACHELOR OF ELEMENTARY EDUCATION/i, 'BEED')
                            .replace(/BACHELOR OF SCIENCE IN AGRICULTURAL AND BIOSYSTEMS ENGINEERING/i, 'BSABE')
                            .toUpperCase();

                        const address = `${data.address}`.toUpperCase();

                        // =====================
                        // TEXT FIELDS
                        // =====================
                        document.getElementById('studentName').value = fullName;
                        document.getElementById('studentCourse').value = progName;
                        document.getElementById('studentCivilStatus').value = civilStatus;
                        document.getElementById('studentAddress').value = address;

                        document.getElementById('studentCardName').textContent = fullName;
                        document.getElementById('studentCardNo').textContent = data.stud_id;
                        document.getElementById('studentCardCourse').textContent = progName;
                        document.getElementById('studentCardAddress').textContent = address;
                        document.getElementById('studentCardAddressPreview').textContent = address;
                        document.getElementById('studentCardBirthday').textContent = data.bday;
                        document.getElementById('studentCardBirthdayPreview').textContent = data.bday;
                        document.getElementById('studentCardContact').textContent = data.contact;
                        document.getElementById('studentCardContactPreview').textContent = data.contact;
                        document.getElementById('studentCardSignature').textContent = data.gender;
                        document.getElementById('studentCardSignaturePreview').textContent = data.gender;


                        // =====================
                        // STORE ENCRYPTED ID (GLOBAL)
                        // =====================
                        currentEncryptedId = data.encrypted_id;

                        // =====================
                        // GENERATE MAIN QR (ENCRYPTED)
                        // =====================
                        generateQR(currentEncryptedId);

                        console.log("QR VALUE:", currentEncryptedId);

                        document.getElementById('rfidScanner').focus();
                    }

                })
                .catch(error => {
                    console.error('Error fetching student:', error);
                });
        }


        // =====================
        // PREVIEW MODAL
        // =====================
        const modal = document.getElementById('idPreviewModal');

        modal.addEventListener('show.bs.modal', function () {

            document.getElementById('previewName').textContent =
                document.getElementById('studentCardName').textContent;

            document.getElementById('previewId').textContent =
                document.getElementById('studentCardNo').textContent;

            document.getElementById('previewCourse').textContent =
                document.getElementById('studentCardCourse').textContent;

            document.getElementById('previewPhoto').src =
                document.getElementById('photo').src;

            // =====================
            // PREVIEW QR (ENCRYPTED)
            // =====================
            const qrContainer = document.getElementById('previewQr');
            qrContainer.innerHTML = '';

            if (currentEncryptedId) {
                new QRCode(qrContainer, {
                    text: currentEncryptedId,
                    width: 100,
                    height: 100,
                    correctLevel: QRCode.CorrectLevel.H
                });
            }
        });
    </script>

    <script>
        let video = document.getElementById('webcam');
        let canvas = document.createElement('canvas');
        let stream = null;

        function startCamera() {
            navigator.mediaDevices.getUserMedia({ video: true, audio: false })
            .then(s => {
                stream = s;
                video.srcObject = stream;

                video.style.display = 'block';
                document.getElementById('cameraPlaceholder').style.display = 'none';

                document.getElementById('btnStart').style.display = 'none';
                document.getElementById('btnStop').style.display = 'inline-block';
                document.getElementById('btnCapture').style.display = 'inline-block';
            })
            .catch(err => {
                console.error("Camera error:", err);
                alert("Unable to access camera");
            });
        }

        function stopCamera() {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                stream = null;
            }

            video.srcObject = null;
            video.style.display = 'none';

            document.getElementById('cameraPlaceholder').style.display = 'flex';

            document.getElementById('btnStart').style.display = 'inline-block';
            document.getElementById('btnStop').style.display = 'none';
            document.getElementById('btnCapture').style.display = 'none';
        }

        function capturePhoto() {
            const photo = document.getElementById('photo');

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;

            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

            const imageData = canvas.toDataURL('image/png');
            photo.src = imageData;

            const studId = document.getElementById('studentCardNo').textContent;
            document.getElementById('studPhoto').value = imageData;
            console.log("Captured image length:", imageData.length);
        }
    </script>

    
    
    <script>
        function printFrontIDonly() {
            const front = document.querySelector('.id-frontcard').cloneNode(true);
            const back = document.querySelector('.id-backcard').cloneNode(true);
            
            const frontBg = "{{ asset('uilibs/images/studentidimage/IDfrontoldphotoframe.webp') }}";
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
                        .photo-wrapper {
                            margin-top: -35px !important;
                            display: flex;
                            flex-direction: column;
                            align-items: center;
                        }
                        .student-photo {
                            margin-top: -28px;
                            margin-left: 1px;
                            width: 85px;
                            height: 106px;
                            border: 1px solid #eff317;        
                            box-shadow: 0 0 0 2px #116e36;
                            border-radius: 6px;
                            overflow: hidden;
                            flex-shrink: 0;
                        }
                        .pic {
                            width: 100%;
                            height: 100%;
                            object-fit: cover;
                        }
                        .signature-icon {
                            font-size: 19px;
                            color: #116e36;
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
                            position: absolute;
                            top: 63px !important;
                            left: 103px;
                            font-size: 8pt;
                            font-weight: bold;
                            color: #ffffff;
                            z-index: 10;
                            clip-path: polygon(100% 0%, 0% 0%, 0% 100%, 92% 100%, 96% 50%, 100% 0%);
                        }

                        .student-id {
                            position: absolute;
                            font-weight: bold;
                            font-family: "Poppins", sans-serif !important;
                            top: 98px;
                            left: 110px;
                            font-size: 8pt;
                        }

                        .student-course {
                            position: absolute;
                            font-weight: bold;
                            font-family: "Poppins", sans-serif !important;
                            top: 120px;
                            left: 110px;
                            font-size: 8pt;
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
                        .back-grid {
                            margin-top: 6px;
                            display: flex;
                            justify-content: space-between;
                            gap: 5px;
                        }
                        .back-col:first-child {
                            flex: 3;
                            margin-top: -5px;
                        }
                        .back-col:last-child {
                            flex: 1; 
                            margin-top: -5px;
                        }
                        .back-grid-second {
                            margin-top: 10px;
                            display: flex;
                            justify-content: space-between;
                            gap: 5px;
                        }
                        .back-col-second:first-child {
                            flex: 2;
                            margin-top: -5px;
                        }

                        .back-col-second:nth-child(2) {
                            flex: 1;
                            margin-top: -5px;
                        }

                        .back-col-second:last-child {
                            flex: 1; 
                            margin-top: -5px;
                        }
                        .back-col label {
                            font-weight: 300;
                            font-size: 5pt;
                        }
                        .back-col-second label {
                            font-weight: 300;
                            font-size: 5pt;
                        }
                        .back-col-full{
                            margin-top: -5px !important;
                        }
                        .back-col-full label{
                            font-weight: 300;
                            font-size: 5pt;
                        }

                        /* Lines */
                        .linedata {
                            padding-top: 2px !important;
                            font-size: 5pt !important;
                        }
                        .line {
                            border-bottom: 1px solid #000;
                            height: 1px;
                            margin-bottom: 8px;
                            font-size: 5pt !important;
                        }
                        .green-line {
                            margin-top: 9px !important;
                            height: 2px;
                            background: #2f855a;
                            margin: 1px 0;
                        }
                        .note-text {
                            margin-top: 5px;
                            text-align: center;
                            line-height: 1.1;
                            font-size: 4.5pt;
                        }
                        .signature-block {
                            text-align: center;
                            margin-top: 5px;
                        }

                        .signature-line {
                            margin-top: 25px !important;
                            border-bottom: 1px solid #000;
                            width: 100px;
                            margin: 0 auto 2px auto;
                        }

                        .signature-name {
                            font-size: 6pt;
                            font-family: 'Poppins', sans-serif;
                            font-weight: 600;
                            line-height: 1.2;
                        }

                        .signature-title {
                            font-size: 5pt;
                            font-family: 'Poppins', sans-serif;
                            margin-top: -1px;
                        }
                        #qrcode {
                            width: 50px;
                            height: 50px;
                            position: absolute;
                            float: right;
                            bottom: 10px !important;
                            right: 10px !important;
                            border: 1px solid #ffffff;
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
