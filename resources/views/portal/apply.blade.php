<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>CPSU || COAS - Portal</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('template/plugins/fontawesome-free-V6/css/all.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('template/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('template/dist/css/coas-style.css') }}">
    <link rel="stylesheet" href="{{ asset('template/dist/css/track-style.css') }}">
    <link rel="stylesheet" href="{{ asset('template/dist/css/upload-image.css') }}">
    <!-- Logo  -->
    <link rel="shortcut icon" type="" href="{{ asset('template/img/CPSU_L.png') }}">
</head>

<body class="hold-transition layout-top-nav layout-navbar-fixed text-sm">

    <div class="wrapper">
        <nav class="main-header navbar navbar-expand-md navbar-light" style="background-color: #04401f">
            <div class="container-fluid">
                <a href="" class="" style="color: #fff;font-family: Courier;">
                    COAS V.2.0
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

        <div class="content-wrapper">
            <div class="content">
                <div class="container">
                    <div class="row" style="padding-top: 15px;">
                        <div class="col-lg-12">
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

                                    <p>
                                        @if(Session::has('success'))
                                            <div class="alert alert-success" id="alert">{{ Session::get('success')}} {{ Session::get('admission_id')}}</div>
                                        @elseif (Session::has('fail'))
                                            <div class="alert alert-danger" id="alert">{{Session::get('fail')}}</div>
                                        @endif
                                    </p>

                                    @php
                                        date_default_timezone_set('Asia/Manila');

                                        $now = now();
                                        $startTime = now()->setHour(8)->setMinute(0)->setSecond(0);
                                        $endTime = now()->setHour(17)->setMinute(0)->setSecond(0);
                                    @endphp

                                    {{-- Debugging statements --}}
                                    {{-- {{ "Current Time: " . $now->format('g:i A') }}
                                    {{ "Start Time: " . $startTime->format('g:i A') }}
                                    {{ "End Time: " . $endTime->format('g:i A') }} --}}

                                    {{-- @if(!$now->isWeekday() || $now->lt($startTime) || $now->gte($endTime))
                                        <img src="{{ asset('template/img/limit-has-been-reached.jpg') }}" width="100%" class="img-responsive">
                                    @else --}}

                                    <div>
                                        <form method="post" action="{{ route('post_admission_apply') }}" enctype="multipart/form-data" id="admissionApply">
                                            {{ csrf_field() }}

                                            <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                                <h4>Applicant Information</h4>
                                            </div>

                                            <div class="form-group mt-2">
                                                <div class="form-row">
                                                    <div class="col-md-2">
                                                        <label><span class="badge badge-secondary">Admission No.</span></label>
                                                        <input type="text" class="form-control form-control-sm" name="" placeholder="Auto-generated" readonly>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label><span class="badge badge-secondary">Admission Type</span></label>
                                                        <select class="form-control form-control-sm" name="type">
                                                            <option value="">Select</option>
                                                            <option value="1" @if (old('type') == 1) {{ 'selected' }} @endif>New</option>
                                                            <option value="2" @if (old('type') == 2) {{ 'selected' }} @endif>Returnee</option>
                                                            <option value="3" @if (old('type') == 3) {{ 'selected' }} @endif>Transferee</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label><span class="badge badge-secondary">Preferred Campus</span></label>
                                                        <select class="form-control form-control-sm" name="campus">
                                                            <option disabled selected>Select</option>
                                                            <option value="MC" @if (old('type') == 'MC') {{ 'selected' }} @endif>Main</option>
                                                            <option value="VC" @if (old('type') == 'VC') {{ 'selected' }} @endif>Victorias</option>
                                                            <option value="SCC" @if (old('type') == 'SCC') {{ 'selected' }} @endif>San Carlos</option>
                                                            <option value="MP" @if (old('type') == 'MP') {{ 'selected' }} @endif>Moises Padilla</option>
                                                            <option value="HC" @if (old('type') == 'HC') {{ 'selected' }} @endif>Hinigaran</option>
                                                            <option value="IC" @if (old('type') == 'IC') {{ 'selected' }} @endif>Ilog</option>
                                                            <option value="CA" @if (old('type') == 'CA') {{ 'selected' }} @endif>Candoni</option>
                                                            <option value="CC" @if (old('type') == 'CC') {{ 'selected' }} @endif>Cauayan</option>
                                                            <option value="SC" @if (old('type') == 'SC') {{ 'selected' }} @endif>Sipalay</option>
                                                            <option value="HinC" @if (old('type') == 'HinC') {{ 'selected' }} @endif>Hinobaan</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label><span class="badge badge-secondary">Lastname</span></label>
                                                        <input type="text" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()" value="{{old('lastname')}}" name="lastname">
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label><span class="badge badge-secondary">Firstname</span></label>
                                                        <input type="text" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()"  name="firstname" value="{{old('firstname')}}">
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label><span class="badge badge-secondary">Middlename</span></label>
                                                        <input type="text" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()" value="{{old('mname')}}" name="mname">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="form-row">
                                                    <div class="col-md-2">
                                                        <label><span class="badge badge-secondary">Ext.</span></label>
                                                        <select class="form-control form-control-sm" name="ext">
                                                            <option>N/A</option>
                                                            <option value="Jr." @if (old('ext') == "Jr.") {{ 'selected' }} @endif>Jr.</option>
                                                            <option value="Sr." @if (old('ext') == "Sr.") {{ 'selected' }} @endif>Sr.</option>
                                                            <option value="III" @if (old('ext') == "III") {{ 'selected' }} @endif>III</option>
                                                            <option value="IV" @if (old('ext') == "IV") {{ 'selected' }} @endif>IV</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label><span class="badge badge-secondary">Gender</span></label>
                                                        <select class="form-control form-control-sm" name="gender">
                                                            <option value="">Select</option>
                                                            <option value="Male" @if (old('gender') == "Male") {{ 'selected' }} @endif>Male</option>
                                                            <option value="Female" @if (old('gender') == "Female") {{ 'selected' }} @endif>Female</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label><span class="badge badge-secondary">Birthdate</span></label>
                                                        <input type="date" class="form-control form-control-sm" name="bday" id="bday" onchange="calculateAge()">
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label><span class="badge badge-secondary">Age</span></label>
                                                        <input type="text" class="form-control form-control-sm" name="age" id="age" readonly>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label><span class="badge badge-secondary">Mobile</span></label>
                                                        <input type="number" class="form-control form-control-sm" name="contact" value="{{old('contact')}}">
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label><span class="badge badge-secondary">Email Address</span></label>
                                                        <input type="email" class="form-control form-control-sm" placeholder="e.g john@gmail.com" name="email" value="{{old('email')}}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="form-row">
                                                    <div class="col-md-2">
                                                        <label><span class="badge badge-secondary">Civil Status</span></label>
                                                        <select class="form-control form-control-sm" name="civil_status">
                                                            <option disabled selected>Select</option>
                                                            <option value="Single" @if (old('civil_status') == "Single") {{ 'selected' }} @endif>Single</option>
                                                            <option value="Married" @if (old('civil_status') == "Married") {{ 'selected' }} @endif>Married</option>
                                                            <option value="Divorced" @if (old('civil_status') == "Divorced") {{ 'selected' }} @endif>Divorced</option>
                                                            <option value="Widowed" @if (old('civil_status') == "Widowed") {{ 'selected' }} @endif>Widowed</option>
                                                            <option value="Separated" @if (old('civil_status') == "Separated") {{ 'selected' }} @endif>Separated</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label><span class="badge badge-secondary">Religion</span></label>
                                                        <input type="text" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()" name="religion" value="{{old('religion')}}">
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label><span class="badge badge-secondary">Parent's Monthly Income</span></label>
                                                        <input type="number" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()" name="monthly_income" value="{{old('monthly_income')}}">
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label><span class="badge badge-secondary">Address</span></label>
                                                        <input type="text" class="form-control form-control-sm" oninput="var words = this.value.split(' '); for(var i = 0; i < words.length; i++){ words[i] = words[i].substr(0,1).toUpperCase() + words[i].substr(1); } this.value = words.join(' ');" placeholder="Present Address" name="address" value="{{old('address')}}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                                <h4>For New Student <span style="font-size: 12pt;color:#ff0000;">(Input for New Applicant only)</span></h4>
                                            </div>

                                            <div class="form-group mt-2">
                                                <div class="form-row">
                                                    <div class="col-md-6">
                                                        <label><span class="badge badge-secondary">Last School Attended</span></label>
                                                        <input type="text" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()" name="lstsch_attended" value="{{old('lstsch_attended')}}">
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label><span class="badge badge-secondary">Strand</span></label>
                                                        <select class="level form-control form-control-sm" name="strand" style="text-transform: uppercase;">
                                                            <option value="">Select</option>
                                                            @foreach ($strand as $strand)
                                                            <option value="{{ $strand->code }}">{{ $strand->strand }} - {{ $strand->code }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                                <h4>For Transferee <span style="font-size: 12pt;color:#ff0000;">(Input for Transferees only)</span></h4>
                                            </div>

                                            <div class="form-group mt-2">
                                                <div class="form-row">
                                                    <div class="col-md-6">
                                                        <label><span class="badge badge-secondary">College/University last attended</span></label>
                                                        <input type="text" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()" name="suc_lst_attended">
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label><span class="badge badge-secondary">Course</span></label>
                                                        <select class="form-control form-control-sm" name="course" style="text-transform: uppercase;">
                                                            <option value="">Select Course</option>
                                                            @foreach ($program as $programs)
                                                            <option value="{{ $programs->code }}">{{ $programs->program }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                                <h4>Course Preference</h4>
                                            </div>

                                            <div class="form-group mt-2">
                                                <div class="form-row">
                                                    <div class="col-md-6">
                                                        <label><span class="badge badge-secondary">Course Preference 1</span></label>
                                                        <select class="form-control form-control-sm" name="preference_1" style="text-transform: uppercase;">
                                                            <option value="">Select Course Preference</option>
                                                            @foreach ($program as $programs)
                                                            <option value="{{ $programs->code }}">{{ $programs->program }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label><span class="badge badge-secondary">Course Preference 2</span></label>
                                                        <select class="form-control form-control-sm" name="preference_2" style="text-transform: uppercase;">
                                                            <option value="">Select Course Preference</option>
                                                            @foreach ($program as $programs)
                                                            <option value="{{ $programs->code }}">{{ $programs->program }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="page-header mt-5" style="border-bottom: 1px solid #04401f;">
                                                <h4>Documents <span style="font-size: 11pt;color:#ff0000;">(Upload any one(1)  of the following Proof of Income & Evidence of Disadvantaged Stituation together with your <strong>valid School ID:</strong>)</span></h4>
                                            </div>

                                            <div class="form-group mt-2">
                                                <div class="form-row">
                                                    <div class="col-md-6">
                                                        <ul class="list-unstyled">
                                                            <li class="mt-3">
                                                                <h6>
                                                                    <i class="fa-solid fa-caret-right"></i> Income Tax Return or Certificate of Tax Exemption from BIR
                                                                </h6>
                                                            </li>
                                                            <li>
                                                                <h6>
                                                                    <i class="fa-solid fa-caret-right"></i> NCIP: Certificate of IP Membership
                                                                </h6>
                                                            </li>
                                                            <li>
                                                                <h6>
                                                                    <i class="fa-solid fa-caret-right"></i> 4Ps: Certification from DSWD
                                                                </h6>
                                                            </li>
                                                            <li>
                                                                <h6>
                                                                    <i class="fa-solid fa-caret-right"></i> PWD: PWD ID or Certification from DSWD
                                                                </h6>
                                                            </li>
                                                            <li>
                                                                <h6>
                                                                    <i class="fa-solid fa-caret-right"></i> Solo Parent: Certification from DSWD
                                                                </h6>
                                                            </li>
                                                            <li>
                                                                <h6>
                                                                    <i class="fa-solid fa-caret-right"></i> Resident of Community in Armed Conflict: Certification from LGU
                                                                </h6>
                                                            </li>
                                                            <li>
                                                                <h6>
                                                                    <i class="fa-solid fa-caret-right"></i> Valid School ID
                                                                </h6>
                                                            </li>
                                                        </ul>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="">
                                                            <label><span class="badge badge-secondary">Upload</span></label>
                                                            <input type="file" name="doc_image" class="form-control form-control-sm" id="fileInput" accept="image/*" onchange="handleFileUpload()">
                                                        </div>
                                                        <div class="uploaded" id="uploadedFile" style="display: none;">
                                                            <i class="far fa-file-image"></i>
                                                            <div class="file">
                                                                <div class="file__name">
                                                                    <p id="fileName"></p>
                                                                    <i class="fas fa-times" onclick="removeUploadedFile()"></i>
                                                                </div>
                                                                <div class="progress">
                                                                    <div id="progressBar" class="progress-bar bg-success progress-bar-striped progress-bar-animated" style="width:0%"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-4">
                                                <div class="col-8">
                                                    <div class="icheck-primary">
                                                        <input type="checkbox" id="remember" name="remember">
                                                        <label for="remember">I have read and agree to the following <a data-toggle="modal" data-target="#terms"><span style="color:#ffc107;">terms and conditions</span></a></label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-5 mb-3">
                                                <div class="col-md-12 col-6 d-flex justify-content-center">
                                                    <button type="submit" class="btn btn-primary btn-lg">
                                                        <i class="fas fa-check"></i> Apply
                                                    </button>
                                                </div>
                                            </div>

                                        </form>
                                    </div>

                                    {{-- @endif --}}
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="main-footer text-sm text-center" style="background-color: #04401f;">
            <div class="float-right d-none d-sm-inline "></div>
            <i class="text-light">CPSU - COAS V.2.0: Maintained and Managed by Management Information System Office (MISO) under the Leadership of Dr. Aladino C. Moraca Copyright © 2023 CPSU, All Rights Reserved</i>
        </footer>
    </div>
    @include('portal.modal-terms')
    <!-- jQuery -->
    <script src="{{ asset('template/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('template/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('template/dist/js/coas.min.js') }}"></script>
    <!-- Context -->
    <script src="{{ asset('js/basic/contextmenucoas.js') }}"></script>

    <!-- jquery-validation -->
    <script src="{{ asset('template/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('template/plugins/jquery-validation/additional-methods.min.js') }}"></script>

    <script src="{{ asset('js/validation/apply/applyValidation.js') }}"></script>

    <script>
        function calculateAge() {
            var birthday = document.getElementById('bday').value;
            var today = new Date();
            var birthDate = new Date(birthday);
            var age = today.getFullYear() - birthDate.getFullYear();

            if (today.getMonth() < birthDate.getMonth() || (today.getMonth() === birthDate.getMonth() && today.getDate() < birthDate.getDate())) {
                age--;
            }

            document.getElementById('age').value = age;
        }
    </script>

    {{-- <script type="text/javascript">
        setTimeout(function () {
            $("#alert").delay(4500).fadeOut(5000);
        }, 0); 
    </script> --}}

    <script>
        function uploadFile() {
            document.getElementById('fileInput').click();
        }

        function handleFileUpload() {
            var fileInput = document.getElementById('fileInput');
            var uploadedFile = document.getElementById('uploadedFile');
            var fileNameElement = document.getElementById('fileName');
            var progressBar = document.getElementById('progressBar');

            uploadedFile.style.display = 'flex';

            fileNameElement.innerText = fileInput.files[0].name;

            var progress = 0;
            var interval = setInterval(function () {
                progress += 10;
                progressBar.style.width = progress + '%';

                if (progress >= 100) {
                    clearInterval(interval);
                }
            }, 500);
        }

        function removeUploadedFile() {
            var uploadedFile = document.getElementById('uploadedFile');
            var progressBar = document.getElementById('progressBar');
            uploadedFile.style.display = 'none';
            progressBar.style.width = '0%';
        }
    </script>
</body>
</html>
   