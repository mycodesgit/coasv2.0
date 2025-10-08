<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>CISS V.1.0 - Portal</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('template/plugins/fontawesome-free-V6/css/all.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('template/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('template/plugins/toastr/toastr.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('template/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('template/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('template/dist/css/coas-style.css') }}">
    <link rel="stylesheet" href="{{ asset('template/dist/css/track-style.css') }}">
    <link rel="stylesheet" href="{{ asset('template/dist/css/upload-image.css') }}">
    <!-- Logo  -->
    <link rel="shortcut icon" type="" href="{{ asset('template/img/CPSU_L.png') }}">
    <style>
        .progress-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-radius: 20px !important;
        }
        .progress-container {
            display: flex;
            align-items: center;
            border-radius: 20px !important;
        }
        .progress-bar {
            transition: width 0.3s ease;
            border-radius: 20px !important;
        }
    </style>
</head>

<body class="hold-transition layout-top-nav layout-navbar-fixed text-sm">

    <div class="wrapper">
        <nav class="main-header navbar navbar-expand-md navbar-light" style="background-color: #04401f">
            <div class="container-fluid">
                <a href="" class="" style="color: #fff;font-family: Courier;">
                    CISS V.1.0
                </a>
                <div class="" style="z-index: 999">
                    <img src="{{ asset('template/img/CPSU_L.png') }}" style="width:80px;" class="center-top">
                </div>

                <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button" style="color: #fff">
                             
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="content-wrapper" style="background-color: #daf1ea !important">
            <div class="content">
                <div class="">
                    <div class="row" style="padding-top: 15px;">
                        <div class="col-lg-6 offset-lg-3 col-lg-offset-4 col-lg-center px-3">
                            <div class="card">
                                <div class="card-body">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item">
                                            <a href="{{ route('admission-portal') }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-home"></i>
                                            </a>
                                        </li>
                                        <li class="breadcrumb-item active mt-1">Apply Admission</li>
                                    </ol>
                                </div>
                            </div>
                            <div class="card card-secondary card-outline">
                                <div class="card-body">
                                    <h6 class="card-title text-dark">
                                        <strong>To all applicants who has missing documents, please re-upload your documents.<br><br>
                                        <div>
                                            <label for="checkboxPrimaryAgree1">
                                                <span style="font-weight: bold;">Thank you.</span>
                                            </label>
                                        </div>
                                        </strong>
                                    </h6>
                                </div>
                            </div>


                            <form id="fileUploadForm" method="post" action="{{ route('uploadDocuments') }}" enctype="multipart/form-data">
                                @csrf

                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Search Applicant</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-row">
                                            <div class="col-md-4">
                                                <label for="search_lastname">Last Name</label>
                                                <input type="text" id="search_lastname" class="form-control form-control-sm" name="lname" placeholder="Enter Last Name" oninput="this.value = this.value.toUpperCase()">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="search_firstname">First Name</label>
                                                <input type="text" id="search_firstname" class="form-control form-control-sm" name="fname" placeholder="Enter First Name" oninput="this.value = this.value.toUpperCase()">
                                            </div>
                                            <div class="col-md-4">
                                                <label>Preferred Campus <i style="color: red">*</i></label>
                                                <select class="form-control form-control-sm" name="campus" id="campus">
                                                    <option disabled selected>Select</option>
                                                    <option value="MC">Main</option>
                                                    <option value="VC">Victorias</option>
                                                    <option value="SCC">San Carlos</option>
                                                    <option value="MP">Moises Padilla</option>
                                                    <option value="HC">Hinigaran</option>
                                                    <option value="IC">Ilog</option>
                                                    <option value="CA">Candoni</option>
                                                    <option value="CC">Cauayan</option>
                                                    <option value="SC">Sipalay</option>
                                                    <option value="HinC">Hinobaan</option>
                                                </select>
                                            </div>
                                        </div>
                                        <button type="button" id="searchApplicant" class="btn btn-info mt-3">Search</button>
                                    </div>
                                </div>

                                <!-- Auto-filled Applicant Information -->
                                <div id="card-1" style="display: none;">
                                    <div class="card mt-3">
                                        <div class="card-body">
                                            <label for="admissionid">Your Admission ID</label>
                                            <input type="text" id="admissionid" name="admissionid" class="form-control" readonly>
                                            <input type="hidden" id="fname" name="fname" class="form-control" readonly>
                                            <input type="hidden" id="lname" name="lname" class="form-control" readonly>
                                            <input type="hidden" id="primaryid" name="id" class="form-control" readonly>
                                        </div>
                                    </div>
                                    
                                    <p>
                                        @if(Session::has('success'))
                                            <div class="alert alert-success" id="alert">{{ Session::get('success')}} {{ Session::get('admission_id')}}</div>
                                        @elseif (Session::has('fail'))
                                            <div class="alert alert-danger" id="alert">{{Session::get('fail')}}</div>
                                        @endif
                                    </p>

                                
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title m-0">Please re-upload the missing documents listed below</h5>
                                        </div>
                                    </div>

                                    <div class="card" id="validid">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <div class="form-row">
                                                    <div class="col-md-12">
                                                        <label>Upload School ID <i style="color: red">*</i></label>
                                                        <input type="file" name="studiddoc_image" class="form-control form-control-sm" id="fileInput" accept="image/*">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card" id="appreq">
                                        <div class="card-body">
                                            <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                                <h4>Application Requirement <span style="font-size: 12pt;color:#ff0000;"></span></h4>
                                            </div>

                                            <!-- Selection -->
                                            <div class="form-group">
                                                <label for="applicantType">Select Applicant Type <i style="color: red">*</i></label>
                                                <select id="applicantType" class="form-control form-control-sm">
                                                    <option value="">-- Select an option --</option>
                                                    <option value="grade12">Grade 12 Students (Graduating)</option>
                                                    <option value="shsGrad">Senior High School Graduates</option>
                                                    <option value="apptransferee">Transferees</option>
                                                    <option value="als">ALS Graduates/Passers</option>
                                                    <option value="lifelong">Life-long Learner</option>
                                                </select>
                                            </div>

                                            <!-- Upload Sections -->
                                            <div class="form-group upload-section" id="grade12" style="">
                                                <label>Upload Grade 12 Requirement <i style="color: red">*</i></label>
                                                <input type="file" name="grade12File" class="form-control form-control-sm file-input" accept="image/*">
                                            </div>

                                            <div class="form-group upload-section" id="shsGrad" style="display:none;">
                                                <label>Upload SHS Graduate Requirement <i style="color: red">*</i></label>
                                                <input type="file" name="shsFile" class="form-control form-control-sm file-input" accept="image/*">
                                            </div>

                                            <div class="form-group upload-section" id="apptransferee" style="display:none;">
                                                <label>Upload Transferee Requirement <i style="color: red">*</i></label>
                                                <input type="file" name="transfereeFile" class="form-control form-control-sm file-input" accept="image/*">
                                            </div>

                                            <div class="form-group upload-section" id="als" style="display:none;">
                                                <label>Upload ALS Requirement <i style="color: red">*</i></label>
                                                <input type="file" name="alsFile" class="form-control form-control-sm file-input" accept="image/*">
                                            </div>

                                            <div class="form-group upload-section" id="lifelong" style="display:none;">
                                                <label>Upload Life-long Learner Requirement <i style="color: red">*</i></label>
                                                <input type="file" name="lifelongFile" class="form-control form-control-sm file-input" accept="image/*">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card" id="proofdoc">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <div class="form-row">
                                                    <div class="col-md-12">
                                                        <label>Select one (1) Proof of Equity Target Documents from Parents/Legal Guardians <i style="color: red">*</i></label>
                                                        <div class="form-group clearfix">
                                                            <div class="icheck-primary">
                                                                <input type="radio" id="radioPrimary1" name="typefileproofupload" value="4Ps">
                                                                <label for="radioPrimary1" style="width: 100%;">
                                                                    <div class="alert alert-default alert-dismissible bg-gray" style="margin-top: -10px;">
                                                                        <h5 style="text-align:left !important; color:gold">4Ps MEMBERS:</h5>
                                                                        <span style="font-weight: normal; font-style: italic;">
                                                                            • Upload a 4Ps Certification from MSWD/DSWD showing the household number and beneficiary's name.<br>
                                                                            • A 4Ps Household ID is also acceptable.
                                                                        </span>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                            <div class="icheck-primary">
                                                                <input type="radio" id="radioPrimary2" name="typefileproofupload" value="IPs">
                                                                <label for="radioPrimary2" style="width: 100%;">
                                                                    <div class="alert alert-default alert-dismissible bg-gray col-md-12" style="margin-top: -10px">
                                                                        <h5 style="text-align:left !important; color:gold">INDIGENOUS PEOPLE (IPs):</h5>
                                                                        <span style="font-weight: normal; font-style: italic;">
                                                                            • Upload an original IP Certificate from the Municipal or City Indigenous Office with an Original signature and your valid ID.
                                                                        </span>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                            <div class="icheck-primary">
                                                                <input type="radio" id="radioPrimary3" name="typefileproofupload" value="RACA">
                                                                <label for="radioPrimary3" style="width: 100%;">
                                                                    <div class="alert alert-default alert-dismissible bg-gray col-md-12" style="margin-top: -10px">
                                                                        <h5 style="text-align:left !important; color:gold">RESIDENT OF ARMED CONFLICT AREAS:</h5>
                                                                        <span style="font-weight: normal; font-style: italic;">
                                                                            • Upload a Certification from DILG with your name.
                                                                        </span>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                            <div class="icheck-primary">
                                                                <input type="radio" id="radioPrimary4" name="typefileproofupload" value="PWABO">
                                                                <label for="radioPrimary4" style="width: 100%;">
                                                                    <div class="alert alert-default alert-dismissible bg-gray col-md-12" style="margin-top: -10px">
                                                                        <h5 style="text-align:left !important; color:gold">PARENTS WHO ARE BUSINESS OWNERS:</h5>
                                                                        <span style="font-weight: normal; font-style: italic;">
                                                                            • Upload a BIR Form 1701A-2021.
                                                                        </span>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                            <div class="icheck-primary">
                                                                <input type="radio" id="radioPrimary5" name="typefileproofupload" value="PEIGPA">
                                                                <label for="radioPrimary5" style="width: 100%;">
                                                                    <div class="alert alert-default alert-dismissible bg-gray col-md-12" style="margin-top: -10px">
                                                                        <h5 style="text-align:left !important; color:gold">PARENTS EMPLOYED IN GOVERNMENT OR PRIVATE AGENCIES:</h5>
                                                                        <span style="font-weight: normal; font-style: italic;">
                                                                            • Upload a BIR Form 2316-2021 or Certification from Human Resource Office showing salary  and compensation details.
                                                                        </span>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                            <div class="icheck-primary">
                                                                <input type="radio" id="radioPrimary6" name="typefileproofupload" value="PWA">
                                                                <label for="radioPrimary6" style="width: 100%;">
                                                                    <div class="alert alert-default alert-dismissible bg-gray col-md-12" style="margin-top: -10px">
                                                                        <h5 style="text-align:left !important; color:gold">PARENTS WORKING ABROAD:</h5>
                                                                        <span style="font-weight: normal; font-style: italic;">
                                                                            • Upload an Overseas Employment Contract.
                                                                        </span>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                            <div class="icheck-primary">
                                                                <input type="radio" id="radioPrimary7" name="typefileproofupload" value="Low Income">
                                                                <label for="radioPrimary7" style="width: 100%;">
                                                                    <div class="alert alert-default alert-dismissible bg-gray col-md-12" style="margin-top: -10px">
                                                                        <h5 style="text-align:left !important; color:gold">APPLICANT WITH DECEASED PARENTS:</h5>
                                                                        <span style="font-weight: normal; font-style: italic;">
                                                                            • Upload a Barangay Certification confirming both parents are deceased.
                                                                        </span>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                            <div class="icheck-primary">
                                                                <input type="radio" id="radioPrimary8" name="typefileproofupload" value="Solo Parent">
                                                                <label for="radioPrimary8" style="width: 100%;">
                                                                    <div class="alert alert-default alert-dismissible bg-gray col-md-12" style="margin-top: -10px">
                                                                        <h5 style="text-align:left !important; color:gold">SOLO/SINGLE PARENTS:</h5>
                                                                        <span style="font-weight: normal; font-style: italic;">
                                                                            • Upload a Solo Parent ID/Certification from MSWD/DSWD or a Notarized Sworn Statement indicating current status (Single, Solo parent, Separated, Widow/er).
                                                                        </span>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                            <div class="icheck-primary">
                                                                <input type="radio" id="radioPrimary9" name="typefileproofupload" value="PWDs">
                                                                <label for="radioPrimary9" style="width: 100%;">
                                                                    <div class="alert alert-default alert-dismissible bg-gray col-md-12" style="margin-top: -10px">
                                                                        <h5 style="text-align:left !important; color:gold">PERSON WITH DISABILITIES (PWDs):</h5>
                                                                        <span style="font-weight: normal; font-style: italic;">
                                                                            • For applicants: Upload a PWD ID.<br>
                                                                            • For family members: Upload a PWD Certificate from DSWD stating the relationship with the applicant.
                                                                        </span>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                            <div class="icheck-primary">
                                                                <input type="radio" id="radioPrimary10" name="typefileproofupload" value="CORCF">
                                                                <label for="radioPrimary10" style="width: 100%;">
                                                                    <div class="alert alert-default alert-dismissible bg-gray col-md-12" style="margin-top: -10px">
                                                                        <h5 style="text-align:left !important; color:gold">CHILDREN OF RICE/CORN FARMERS:</h5>
                                                                        <span style="font-weight: normal; font-style: italic;">
                                                                            • Upload a Certificate of Membership from the local Department of Agriculture listing the parent/guardian's name.<br>
                                                                            • Upload a Certification issued by an Association or Cooperative President.<br>
                                                                            • Upload a Certification from DA/DAR/CAO confirming status as an Agrarian Reform Beneficiary.
                                                                        </span>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                            <div class="icheck-primary">
                                                                <input type="radio" id="radioPrimary11" name="typefileproofupload" value="COCF">
                                                                <label for="radioPrimary11" style="width: 100%;">
                                                                    <div class="alert alert-default alert-dismissible bg-gray col-md-12" style="margin-top: -10px">
                                                                        <h5 style="text-align:left !important; color:gold">CHILDREN OF COCONUT FARMERS:</h5>
                                                                        <span style="font-weight: normal; font-style: italic;">
                                                                            • Upload a Certificate of Membership from the local coconut association listing the parent/guardian's name.
                                                                        </span>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                            <div class="icheck-primary">
                                                                <input type="radio" id="radioPrimary12" name="typefileproofupload" value="COF">
                                                                <label for="radioPrimary12" style="width: 100%;">
                                                                    <div class="alert alert-default alert-dismissible bg-gray col-md-12" style="margin-top: -10px">
                                                                        <h5 style="text-align:left !important; color:gold">CHILDREN OF FISHERFOLK:</h5>
                                                                        <span style="font-weight: normal; font-style: italic;">
                                                                            • Upload a Certificate of Membership from a local fisherfolk association or a certification issued by the Association President. <br>
                                                                            • Note: The association must be active.
                                                                        </span>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                            <div class="icheck-primary">
                                                                <input type="radio" id="radioPrimary13" name="typefileproofupload" value="COTJDO">
                                                                <label for="radioPrimary13" style="width: 100%;">
                                                                    <div class="alert alert-default alert-dismissible bg-gray col-md-12" style="margin-top: -10px">
                                                                        <h5 style="text-align:left !important; color:gold">CHILDREN OF TRICYCLE/JEEPNEY DRIVERS/OPERATORS:</h5>
                                                                        <span style="font-weight: normal; font-style: italic;">
                                                                            • Upload a Certificate of Membership from a local TODA listing the parent/guardian's name.
                                                                        </span>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                            <div class="icheck-primary">
                                                                <input type="radio" id="radioPrimary14" name="typefileproofupload" value="UHNFIS">
                                                                <label for="radioPrimary14" style="width: 100%;">
                                                                    <div class="alert alert-default alert-dismissible bg-gray col-md-12" style="margin-top: -10px">
                                                                        <h5 style="text-align:left !important; color:gold">UNCLASSIFIED (UNEMPLOYED HOUSEWIVED/HOUSEHUSBAND, NO FIXED INCOME, SELFSUPPORTING):</h5>
                                                                        <span style="font-weight: normal; font-style: italic;">
                                                                            • Upload a Notarized Sworn Statement indicating parents/guardian name, source of income, and income amount.
                                                                        </span>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label id="uploadLabel">Upload Proof of Equity Target Documents from Parents/Legal Guardians <i style="color: red">*</i></label>
                                                        <input type="file" name="proofdoc_image" class="form-control form-control-sm" accept="image/*">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="progress-section d-flex align-items-center justify-content-between mt-3">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>

                                <br><br>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="main-footer text-sm text-center" style="background-color: #daf1ea; border-top: none;">
            <div class="float-right d-none d-sm-inline "></div>
            <i class="text-dark">CISS V.1.0: Maintained and Managed by Management Information System Office (MISO) under the Leadership of Dr. Aladino C. Moraca Copyright © 2023 CPSU, All Rights Reserved</i>
        </footer>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('template/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('template/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('template/dist/js/coas.min.js') }}"></script>
    <!-- Context -->
    <script src="{{ asset('js/basic/contextmenucoas.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('template/plugins/select2/js/select2.full.min.js') }}"></script>

    <!-- jquery-validation -->
    <script src="{{ asset('template/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('template/plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <!-- Toastr -->
    <script src="{{ asset('template/plugins/toastr/toastr.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('template/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- Moment -->
    <script src="{{ asset('template/plugins/moment/moment.min.js') }}"></script>

    <script>
        let historyLock = setInterval(() => {
            history.pushState(null, null, location.href);
        }, 100); 

        document.getElementById('searchApplicant').addEventListener('click', function () {
            let lastname = document.getElementById('search_lastname').value.trim();
            let firstname = document.getElementById('search_firstname').value.trim();
            let campus = document.getElementById('campus').value;

            if (!lastname || !firstname || campus === 'Select') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Incomplete Fields',
                    text: 'Please fill all search fields.',
                    confirmButtonText: 'OK'
                });
                return;
            }

            fetch('{{ route("searchApplicant") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ lastname, firstname, campus })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('admissionid').value = data.applicant.admission_id;
                    document.getElementById('primaryid').value = data.applicant.primaryid;
                    document.getElementById('lname').value = data.applicant.lname;
                    document.getElementById('fname').value = data.applicant.fname;

                    document.getElementById('card-1').style.display = 'block';

                    document.getElementById('validid').style.display = 'none';
                    document.getElementById('appreq').style.display = 'none';
                    document.getElementById('proofdoc').style.display = 'none';

                    let allowed = [];
                    try {
                        allowed = JSON.parse(data.applicant.reuploadallow);
                    } catch (e) {
                        console.warn('Invalid reuploadallow format:', e);
                    }

                    if (Array.isArray(allowed)) {
                        allowed.forEach(id => {
                            let card = document.getElementById(id);
                            if (card) {
                                card.style.display = 'block';
                            }
                        });
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'Applicant Found',
                        text: 'The applicant data has been retrieved successfully.',
                        confirmButtonText: 'OK'
                    });

                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Not Found',
                        text: 'No applicant found with the provided details. Only Applicant needed to re-upload documents will be show',
                        confirmButtonText: 'OK'
                    });

                    document.getElementById('card-1').style.display = 'none';
                    document.getElementById('validid').style.display = 'none';
                    document.getElementById('appreq').style.display = 'none';
                    document.getElementById('proofdoc').style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while searching. Please try again later.',
                    confirmButtonText: 'OK'
                });
            });
        });

        $(document).on('submit', '#fileUploadForm', function(e) {
            e.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                url: "{{ route('uploadDocuments') }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        console.log('Updated files:', response.data);

                        let countdown = 5; 
                        Swal.fire({
                            icon: 'success',
                            title: 'Successful',
                            html: `Your documents have been uploaded successfully.<br><b>Redirecting in <span id="countdown">${countdown}</span> seconds...</b>`,
                            timer: countdown * 1000,
                            showConfirmButton: false,
                            didOpen: () => {
                                const countdownElement = Swal.getHtmlContainer().querySelector('#countdown');
                                const interval = setInterval(() => {
                                    countdown--;
                                    countdownElement.textContent = countdown;
                                    if (countdown <= 0) {
                                        clearInterval(interval);
                                    }
                                }, 1000);
                            },
                            willClose: () => {
                                window.location.href = "{{ route('repupredirectexpire') }}"; 
                            }
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON.error || 'An error occurred. Please try again.'
                    });
                }
            });
        });
    </script>

    <script>
        const applicantType = document.getElementById('applicantType');
        const sections = document.querySelectorAll('.upload-section');

        applicantType.addEventListener('change', function() {
            sections.forEach(section => {
                const input = section.querySelector('.file-input');

                // Always clear the file input when switching
                input.value = "";

                if (section.id === this.value) {
                    section.style.display = 'block';
                    input.setAttribute('required', true);
                } else {
                    section.style.display = 'none';
                    input.removeAttribute('required');
                }
            });
        });
    </script>
</body>
</html>
   