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

                            <form method="post" action="{{ route('post_admission_apply') }}" enctype="multipart/form-data" id="admissionApply">
                                {{ csrf_field() }}
                                
                                <div id="card-1">
                                    <div class="card card-secondary card-outline">
                                        <div class="card-body">
                                            <h6 class="card-title text-dark">
                                                <p><strong>Dear Applicant:</strong><br><br>
                                                 
                                                <strong>Privacy policy</strong> explains how we collect, use, and protect your personal information. By using the service, you agree to the terms of the University privacy policy <br><br>

                                                Thank you so much!   
                                                </p>
                                            </h6>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title m-0">Personal Information</h5>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <div class="form-row">
                                                    <div class="col-md-12">
                                                        <label>Lastname <i style="color: red">*</i></label>
                                                        <input type="text" class="form-control form-control-sm" placeholder="Enter Lastname" oninput="this.value = this.value.toUpperCase()" value="{{old('lastname')}}" name="lastname">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <div class="form-row">
                                                    <div class="col-md-12">
                                                        <label>Firstname <i style="color: red">*</i></label>
                                                        <input type="text" class="form-control form-control-sm" placeholder="Enter Firstname" oninput="this.value = this.value.toUpperCase()"  name="firstname" value="{{old('firstname')}}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <div class="form-row">
                                                    <div class="col-md-12">
                                                        <label>Middlename</label>
                                                        <input type="text" class="form-control form-control-sm" placeholder="Enter Middlename" oninput="this.value = this.value.toUpperCase()" value="{{old('mname')}}" name="mname">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <div class="form-row">
                                                    <div class="col-md-12">
                                                        <label>Ext</label>
                                                        <select class="form-control form-control-sm" name="ext">
                                                            <option>N/A</option>
                                                            <option value="Jr." @if (old('ext') == "Jr.") {{ 'selected' }} @endif>Jr.</option>
                                                            <option value="Sr." @if (old('ext') == "Sr.") {{ 'selected' }} @endif>Sr.</option>
                                                            <option value="III" @if (old('ext') == "III") {{ 'selected' }} @endif>III</option>
                                                            <option value="IV" @if (old('ext') == "IV") {{ 'selected' }} @endif>IV</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <div class="form-row">
                                                    <div class="col-md-6">
                                                        <label>Birthday <i style="color: red">*</i></label>
                                                        <input type="date" class="form-control form-control-sm" name="bday" id="bday" onchange="calculateAge()">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Age <i style="color: red">*</i></label>
                                                        <input type="text" class="form-control form-control-sm" name="age" id="age" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <div class="form-row">
                                                    <div class="col-md-12">
                                                        <label>Mobile Number <i style="color: red">*</i></label>
                                                        <input type="number" class="form-control form-control-sm" placeholder="Enter Mobile Number" name="contact" value="{{old('contact')}}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <div class="form-row">
                                                    <div class="col-md-12">
                                                        <label>Email Address <i style="color: red">*</i></label>
                                                        <input type="email" class="form-control form-control-sm" placeholder="e.g john@gmail.com" name="email" value="{{old('email')}}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <div class="form-row">
                                                    <div class="col-md-6">
                                                        <label>Civil Status <i style="color: red">*</i></label>
                                                        <select class="form-control form-control-sm" name="civil_status">
                                                            <option disabled selected>Select</option>
                                                            <option value="Single" @if (old('civil_status') == "Single") {{ 'selected' }} @endif>Single</option>
                                                            <option value="Married" @if (old('civil_status') == "Married") {{ 'selected' }} @endif>Married</option>
                                                            <option value="Divorced" @if (old('civil_status') == "Divorced") {{ 'selected' }} @endif>Divorced</option>
                                                            <option value="Widowed" @if (old('civil_status') == "Widowed") {{ 'selected' }} @endif>Widowed</option>
                                                            <option value="Separated" @if (old('civil_status') == "Separated") {{ 'selected' }} @endif>Separated</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Gender <i style="color: red">*</i></label>
                                                        <select class="form-control form-control-sm" name="gender">
                                                            <option value="">Select</option>
                                                            <option value="Male" @if (old('gender') == "Male") {{ 'selected' }} @endif>Male</option>
                                                            <option value="Female" @if (old('gender') == "Female") {{ 'selected' }} @endif>Female</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <div class="form-row">
                                                    <div class="col-md-12">
                                                        <label>Religion <i style="color: red">*</i></label>
                                                        <input type="text" class="form-control form-control-sm" placeholder="Enter Religion" oninput="this.value = this.value.toUpperCase()" name="religion" value="{{old('religion')}}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <div class="form-row">
                                                    <div class="col-md-6">
                                                        <label>House No.</label>
                                                        <input type="text" name="hnum" class="form-control form-control-sm" placeholder="Enter House No." id="viewdatastudHnum" oninput="this.value = this.value.toUpperCase()">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Street/Barangay. <i style="color: red">*</i></label>
                                                        <input type="text" name="brgy" class="form-control form-control-sm" placeholder="Enter Barangay" id="viewdatastudBrgy" oninput="this.value = this.value.toUpperCase()">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Municipality/City. <i style="color: red">*</i></label>
                                                        <select name="city" class="form-control form-control-sm select2bs4" id="viewdatastudCity">
                                                            <option value="">Select City</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Province <i style="color: red">*</i></label>
                                                        <input type="text" name="province" class="form-control form-control-sm" placeholder="Auto" id="viewdatastudProvince">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Region. <i style="color: red">*</i></label>
                                                        <input type="text" name="region" class="form-control form-control-sm" placeholder="Auto" id="viewdatastudRegion">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Zip Code. <i style="color: red">*</i></label>
                                                        <input type="text" name="zcode" class="form-control form-control-sm" placeholder="Auto" id="viewdatastudZcode">
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label>Your Address</label>
                                                        <input type="text" class="form-control form-control-sm" id="viewdatastudAddress" placeholder="Present Address" name="address" value="{{old('address')}}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div id="card-2" style="display: none;">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <div class="form-row">
                                                    <div class="col-md-12">
                                                        <label>Admission Type <i style="color: red">*</i></label>
                                                        <select class="form-control form-control-sm form-control-sm" name="type" id="admissionType">
                                                            <option value="">Select</option>
                                                            <option value="1" @if (old('type') == 1) {{ 'selected' }} @endif>New</option>
                                                            <option value="2" @if (old('type') == 2) {{ 'selected' }} @endif>Returnee</option>
                                                            <option value="3" @if (old('type') == 3) {{ 'selected' }} @endif>Transferee</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <div class="form-row">
                                                    <div class="col-md-12">
                                                        <label>Preferred Campus <i style="color: red">*</i></label>
                                                        <select class="form-control form-control-sm" name="campus" id="campus">
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
                                                            <option value="VE" @if (old('type') == 'VE') {{ 'selected' }} @endif>Valladolid</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <div class="form-row">
                                                    <div class="col-md-12">
                                                        <label>Admission Testing Date. <i style="color: red">*</i></label>
                                                        <select class="form-control form-control-sm" name="d_admissionselect">
                                                            <option value="">Select</option>
                                                        </select>
                                                        <input type="hidden" id="selectedDate" name="d_admission" placeholder="Selected Date">
                                                        <input type="hidden" id="selectedTime" name="time" placeholder="Selected Time">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="new-returnee-form" id="newReturneeForm" style="display: none;">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                                    <h4>For New Student <span style="font-size: 12pt;color:#ff0000;">(For New Applicant only)</span></h4>
                                                </div>
                                                <div class="form-group mt-3">
                                                    <div class="form-row">
                                                        <div class="col-md-6">
                                                            <label>Last School Attended <i style="color: red">*</i></label>
                                                            <input type="text" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()" name="lstsch_attended" value="{{old('lstsch_attended')}}">
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label>Strand <i style="color: red">*</i></label>
                                                            <select class="level form-control form-control-sm" name="strand" style="text-transform: uppercase;">
                                                                <option value="">Select</option>
                                                                @foreach ($strand as $strand)
                                                                <option value="{{ $strand->code }}">{{ $strand->strand }} - {{ $strand->code }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="transferee-form" id="transfereeForm" style="display: none;">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                                    <h4>For Transferee <span style="font-size: 12pt;color:#ff0000;">(Input for Transferees only)</span></h4>
                                                </div>
                                                <div class="form-group mt-3">
                                                    <div class="form-row">
                                                        <div class="col-md-6">
                                                            <label>College/University last attended <i style="color: red">*</i></label>
                                                            <input type="text" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()" name="suc_lst_attended">
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label>Course <i style="color: red">*</i></label>
                                                            <select class="form-control form-control-sm" name="course" style="text-transform: uppercase;">
                                                                <option value="">Select Course</option>
                                                                @foreach ($program as $programs)
                                                                <option value="{{ $programs->code }}">{{ $programs->program }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                                <h4>Course Preference</h4>
                                            </div>
                                            <div class="form-group mt-3">
                                                <div class="form-row">
                                                    <div class="col-md-6">
                                                        <label>Course Preference 1 <i style="color: red">*</i></label>
                                                        <select class="form-control form-control-sm" name="preference_1" style="text-transform: uppercase;">
                                                            <option value="">Select Course Preference</option>
                                                            
                                                        </select>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label>Course Preference 2 <i style="color: red">*</i></label>
                                                        <select class="form-control form-control-sm" name="preference_2" style="text-transform: uppercase;">
                                                            <option value="">Select Course Preference</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="card-3" style="display: none;">
                                    <div class="card card-secondary card-outline">
                                        <div class="card-body">
                                            <h6 class="card-title text-dark">
                                                <p class="text-danger">(Upload any one(1) of the following Proof of Income & Evidence of Disadvantaged Stituation together with your valid School ID: )</p><br>
                                                 
                                                <strong><i class="fa-solid fa-caret-right"></i> Income Tax Return or Certificate of Tax Exemption from BIR</strong><br>
                                                <strong><i class="fa-solid fa-caret-right"></i> NCIP: Certificate of IP Membership</strong><br>
                                                <strong><i class="fa-solid fa-caret-right"></i> 4Ps: Certification from DSWD</strong><br>
                                                <strong><i class="fa-solid fa-caret-right"></i> PWD: PWD ID or Certification from DSWD</strong><br>
                                                <strong><i class="fa-solid fa-caret-right"></i> Solo Parent: Certification from DSWD</strong><br>
                                                <strong><i class="fa-solid fa-caret-right"></i> Resident of Community in Armed Conflict: Certification from LGU</strong><br>
                                                <strong><i class="fa-solid fa-caret-right"></i> Valid School ID</strong><br><br>

                                                Thank you so much!   
                                                </p>
                                            </h6>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title m-0">Documents Information</h5>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <div class="form-row">
                                                    <div class="col-md-12">
                                                        <label>Upload School ID <i style="color: red">*</i></label>
                                                        <input type="file" name="doc_image" class="form-control form-control-sm" id="fileInput" accept="image/*" onchange="handleFileUpload()">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="progress-section d-flex align-items-center justify-content-between mt-3">
                                    <button type="button" class="btn btn-default" id="back-btn" onclick="prevCard(currentCard - 1)" style="display: none;">Back</button>
                                    <button type="button" class="btn btn-primary" id="next-btn" onclick="nextCard(currentCard + 1)">Next</button>
                                    <button type="submit" class="btn btn-primary" id="submit-btn" style="display: none;">Submit</button>

                                    <div class="progress-container d-flex align-items-center">
                                        <div class="progress" style="width: 60%; margin-right: 10px; background-color: gray; border-radius: 20px;">
                                            <div id="progress-bar" class="progress-bar bg-primary" role="progressbar" style="width: 0%;" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <span id="progress-text">Page 1 of <span id="total-pages"></span></span>
                                    </div>

                                    <a href="#" onclick="clearForm()" class="btn btn-default" style="color: #5e5df0; text-decoration: underline;">Clear form</a>
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
            <i class="text-dark">CPSU - COAS V.2.0: Maintained and Managed by Management Information System Office (MISO) under the Leadership of Dr. Aladino C. Moraca Copyright © 2023 CPSU, All Rights Reserved</i>
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

    <script src="{{ asset('js/validation/apply/applyValidation.js') }}"></script>

    <script src="{{ asset('js/ajax/enrolment/studentAddSerialize.js') }}"></script>

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
        
        $(function () {
            $('.select2').select2();

            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4',
                height: '100'
            })
        });
    </script>

    <script>
        const programsRoute = '{{ route('getProgramsByCampus') }}';
        const examschedRoute = '{{ route('getExamSchedCampus') }}';
        function updateCoursePreferences(campus) {
            $.ajax({
                url: programsRoute + '?campus=' + campus,
                type: 'GET',
                success: function (data) {
                    updateOptions('preference_1', data.programs);
                    updateOptions('preference_2', data.programs);
                },
                error: function () {
                    console.error('Error fetching programs');
                }
            });
        }
        function updateExamSchedule(campus) {
            $.ajax({
                url: examschedRoute + '?campus=' + campus,
                type: 'GET',
                success: function (data) {
                    updateSchedOptions('d_admissionselect', data.schedtest);
                },
                error: function () {
                    console.error('Error fetching Schedule Test');
                }
            });
        }
        function updateOptions(selectName, options) {
            const select = $('select[name=' + selectName + ']');
            select.empty();
            select.append('<option value="">Select Course Preference</option>');
            $.each(options, function (key, value) {
                select.append('<option value="' + value.code + '">' + value.program + '</option>');
            });
        }
        function updateSchedOptions(selectName, options) {
            const select = $('select[name=' + selectName + ']');
            select.empty();
            select.append('<option disabled selected> --Select Testing Schedule-- </option>');
            $.each(options, function (key, value) {
                const formattedDate = moment(value.date).format('MMMM DD, YYYY');
                const formattedTime = moment(value.time, 'HH:mm').format('hh:mm A');

                select.append('<option value="' + value.date + '">' + formattedDate + ' ' + formattedTime + '</option>');
            });
        }
        $('select[name="d_admissionselect"]').change(function() {
            const selectedText = $(this).find('option:selected').text();

            // Separate date and time based on the format "January 01, 2024 09:00 AM"
            const dateTime = moment(selectedText, 'MMMM D, YYYY hh:mm A'); 

            // Format the date to 'YYYY-MM-DD' and time to 'HH:mm:ss'
            const formattedDate = dateTime.format('YYYY-MM-DD');
            const formattedTime = dateTime.format('HH:mm:ss');

            // Populate the date and time input fields
            $('#selectedDate').val(formattedDate);
            $('#selectedTime').val(formattedTime);
        });

        $('#campus').change(function () {
            const selectedCampus = $(this).val();
            updateCoursePreferences(selectedCampus);
            updateExamSchedule(selectedCampus);
        });
    </script>

    {{-- <script type="text/javascript">
        setTimeout(function () {
            $("#alert").delay(4500).fadeOut(5000);
        }, 0); 
    </script> --}}

    <script>
        // Initialize variables
        let currentCard = 1;
        const totalCards = document.querySelectorAll('[id^="card-"]').length;

        // Display total pages dynamically
        document.getElementById("total-pages").textContent = totalCards;

        function updateProgressBar() {
            const progressPercentage = (currentCard / totalCards) * 100;
            document.getElementById("progress-bar").style.width = progressPercentage + "%";
            document.getElementById("progress-text").textContent = `Page ${currentCard} of ${totalCards}`;

            // Show or hide buttons based on current card
            document.getElementById("back-btn").style.display = currentCard > 1 ? "inline-block" : "none";
            document.getElementById("next-btn").style.display = currentCard < totalCards ? "inline-block" : "none";
            document.getElementById("submit-btn").style.display = currentCard === totalCards ? "inline-block" : "none";
        }

        function nextCard(cardNumber) {
            if (cardNumber > totalCards) return;
            document.getElementById(`card-${currentCard}`).style.display = "none";
            document.getElementById(`card-${cardNumber}`).style.display = "block";
            currentCard = cardNumber;
            updateProgressBar();
        }

        function prevCard(cardNumber) {
            if (cardNumber < 1) return;
            document.getElementById(`card-${currentCard}`).style.display = "none";
            document.getElementById(`card-${cardNumber}`).style.display = "block";
            currentCard = cardNumber;
            updateProgressBar();
        }

        function clearForm() {
            document.querySelectorAll("input").forEach(input => input.value = "");
            currentCard = 1;
            nextCard(currentCard);
        }

        updateProgressBar(); // Initialize progress bar on load
    </script>

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

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var admissionType = document.getElementById('admissionType');
            var newReturneeForm = document.getElementById('newReturneeForm');
            var transfereeForm = document.getElementById('transfereeForm');

            // Show/hide forms based on the initial value
            toggleFormSections(admissionType.value);

            // Add event listener for change event
            admissionType.addEventListener('change', function() {
                toggleFormSections(this.value);
            });

            function toggleFormSections(value) {
                if (value == 1 || value == 2) { // New or Returnee
                    newReturneeForm.style.display = 'block';
                    transfereeForm.style.display = 'none';
                } else if (value == 3) { // Transferee
                    newReturneeForm.style.display = 'none';
                    transfereeForm.style.display = 'block';
                } else { // Hide all if no selection
                    newReturneeForm.style.display = 'none';
                    transfereeForm.style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>
   