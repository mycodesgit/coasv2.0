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
{{-- @php
    date_default_timezone_set('Asia/Manila');

    $now = now();
    $startTime = now()->setHour(8)->setMinute(0)->setSecond(0);
    $endTime = now()->setHour(17)->setMinute(0)->setSecond(0);
@endphp
@if (!$now->isWeekday() || $now->lt($startTime) || $now->gte($endTime))
    @include('maintenanceserver')
@else --}}
<body class="hold-transition layout-top-nav layout-navbar-fixed text-sm">

    <div class="wrapper">
        <nav class="main-header navbar navbar-expand-md navbar-light" style="background-color: #04401f">
            <div class="container-fluid">
                <a href="" class="" style="color: #fff;font-family: Courier;">
                    CISS V.1.0
                </a>
                <div class="" style="z-index: 999">
                    <img src="{{ asset('template/img/cpsulogov4.png') }}" style="width:80px;" class="center-top">
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

                            @if(!$now->isWeekday() || $now->lt($startTime) || $now->gte($endTime))
                                <img src="{{ asset('template/img/limit-has-been-reached.jpg') }}" width="100%" class="img-responsive">
                            @else

                            <form method="post" action="{{ route('post_admission_apply') }}" enctype="multipart/form-data" id="admissionApply">
                                {{ csrf_field() }}
                                
                                <div id="card-1">
                                    <p>
                                        @if(Session::has('success'))
                                            <div class="alert alert-success" id="alert">{{ Session::get('success')}} {{ Session::get('admission_id')}}</div>
                                        @elseif (Session::has('fail'))
                                            <div class="alert alert-danger" id="alert">{{Session::get('fail')}}</div>
                                        @endif
                                    </p>
                                    <div class="card card-secondary card-outline">
                                        <div class="card-body">
                                            <h6 class="card-title text-dark">
                                                <strong>In relation to the Data Privacy Act of 2012, I am willing to share the information for Admission Testing Application purposes only and to hold the University absolutely free from liability that may arise resulting from he sharing of the information to be gathered.<br><br>
                                                <div class="icheck-primary col-md-12">
                                                    <input type="checkbox" id="checkboxPrimaryAgree1" name="studagree" value="Yes I agree">
                                                    <label for="checkboxPrimaryAgree1">
                                                        <span style="font-weight: bold;">I agree</span>
                                                    </label>
                                                </div>
                                                </strong>
                                            </h6>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <div class="form-row">
                                                    <div class="col-md-12">
                                                        <label>Email Address <i style="color: red">*</i></label>
                                                        <input type="email" class="form-control form-control-sm" placeholder="e.g john@gmail.com" name="email" id="email" value="{{old('email')}}">
                                                    </div>
                                                    <div id="verification-message" style="display: none; color: blue; margin-top: 5px;">
                                                        Wait, email is verifying...
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="card-2" style="display: none;">
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
                                                        <label>Last Name <i style="color: red">*</i></label>
                                                        <input type="text" class="form-control form-control-sm" placeholder="Enter Last Name" oninput="this.value = this.value.toUpperCase()" value="{{old('lastname')}}" name="lastname">
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
                                                        <label>First Name <i style="color: red">*</i></label>
                                                        <input type="text" class="form-control form-control-sm" placeholder="Enter First Name" oninput="this.value = this.value.toUpperCase()"  name="firstname" value="{{old('firstname')}}">
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
                                                        <label>Middle Name</label>
                                                        <input type="text" class="form-control form-control-sm" placeholder="Enter Middle Name" oninput="this.value = this.value.toUpperCase()" value="{{old('mname')}}" name="mname">
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
                                                        <label>Name Extension (Jr., Sr., II, III, IV)</label>
                                                        <select class="form-control form-control-sm" name="ext">
                                                            <option disabled selected> --Select-- </option>
                                                            <option value="">N/A</option>
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
                                                        <label>Sex <i style="color: red">*</i></label>
                                                        <select class="form-control form-control-sm" name="gender" id="genderSelect">
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
                                                        {{-- <input type="text" class="form-control form-control-sm" placeholder="Enter Religion" oninput="this.value = this.value.toUpperCase()" name="religion" value="{{old('religion')}}"> --}}
                                                        <select class="form-control form-control-sm" name="religion" id="religionSelect" onchange="handleReligionChange()">
                                                            <option value="" disabled selected>Select Religion</option>
                                                            <option value="ROMAN CATHOLIC">ROMAN CATHOLIC</option>
                                                            <option value="CHRISTIAN">CHRISTIAN</option>
                                                            <option value="BAPTIST">BAPTIST</option>
                                                            <option value="METHODIST">METHODIST</option>
                                                            <option value="IGLESIA NI CRISTO">IGLESIA NI CRISTO</option>
                                                            <option value="SEVENTH-DAY ADVENTIST">SEVENTH-DAY ADVENTIST</option>
                                                            <option value="JEHOVAH'S WITNESS">JEHOVAH'S WITNESS</option>
                                                            <option value="ISLAM">ISLAM</option>
                                                            <option value="BUDDHISM">BUDDHISM</option>
                                                            <option value="HINDUISM">HINDUISM</option>
                                                            <option value="JUDAISM">JUDAISM</option>
                                                            <option value="ATHEIST">ATHEIST</option>
                                                            <option value="AGNOSTIC">AGNOSTIC</option>
                                                            <option value="OTHER">OTHER (SPECIFY)</option>
                                                        </select>
                                                        <input type="text" id="otherReligionInput" class="form-control form-control-sm mt-2" placeholder="Please specify religion" style="display: none;"
                                                            oninput="this.value = this.value.toUpperCase()"
                                                        >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- <div class="card">
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
                                    </div> --}}

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <div class="form-row">
                                                    <div class="col-md-3">
                                                        <label><span class="badge badge-secondary">Region</span></label>
                                                        <select id="region" class="form-control form-control-sm select2bs4">
                                                            <option value="">Select Region</option>
                                                            @foreach($regions as $region)
                                                                <option value="{{ $region->region_id }}" data-name="{{ $region->name }}">{{ $region->name }}</option>
                                                            @endforeach
                                                            <input type="hidden" id="region_name" name="region">
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label><span class="badge badge-secondary">Province</span></label>
                                                        <select id="province" class="form-control form-control-sm select2bs4">
                                                            <option value="">Select Province</option>
                                                        </select>
                                                        <input type="hidden" id="province_name" name="province">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label><span class="badge badge-secondary">City/Municipality</span></label>
                                                        <select id="city" class="form-control form-control-sm select2bs4">
                                                            <option value="">Select City</option>
                                                        </select>
                                                        <input type="hidden" id="city_name" name="city">
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
                                                        <label><span class="badge badge-secondary">Barangay</span></label>
                                                        <select id="barangay" class="form-control form-control-sm select2bs4" style="text-transform: uppercase;">
                                                            <option value="">Select Barangay</option>
                                                        </select>
                                                        <input type="hidden" id="brgy_name" name="brgy">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label><span class="badge badge-secondary">House No. / Block / Purok</span></label>
                                                        <input type="text" name="hnum" id="viewdatastudHnum" class="form-control form-control-sm" placeholder="House No. / Block / Purok" style="text-transform: uppercase;">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label><span class="badge badge-secondary">Zipcode</span></label>
                                                        <input type="text" name="zcode" id="zipcode" class="form-control form-control-sm" readonly placeholder="Zip Code" readonly style="background-color: #ddd !important; border: 1px solid #aaa;">
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
                                                        <label><span class="badge badge-secondary">Address</span></label>
                                                        <input type="text" name="address" class="form-control form-control-sm" id="viewdatastudAddress" readonly style="background-color: #ddd !important; border: 1px solid #aaa;">
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
                                                        <label>Are you the first college student in your family? <i style="color: red">*</i></label>
                                                        <div class="icheck-primary">
                                                            <input type="radio" id="radioPrimaryQO1" name="qstion1" value="Yes">
                                                            <label for="radioPrimaryQO1">
                                                                Yes
                                                            </label>
                                                        </div>
                                                        <div class="icheck-primary">
                                                            <input type="radio" id="radioPrimaryQO2" name="qstion1" value="No">
                                                            <label for="radioPrimaryQO2">
                                                                No
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card" id="genderQuestion" style="display: none;">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <div class="form-row">
                                                    <div class="col-md-12">
                                                        <label>Are you the first <span id="genderPlaceholder">FEMALE</span> student in your family to study in college? <i style="color: red">*</i></label>
                                                        <div class="icheck-primary">
                                                            <input type="radio" id="radioPrimaryQO3" name="qstion2" value="Yes">
                                                            <label for="radioPrimaryQO3">
                                                                Yes
                                                            </label>
                                                        </div>
                                                        <div class="icheck-primary">
                                                            <input type="radio" id="radioPrimaryQO4" name="qstion2" value="No">
                                                            <label for="radioPrimaryQO4">
                                                                No
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div id="card-3" style="display: none;">
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
                                                            {{-- <option value="3" @if (old('type') == 3) {{ 'selected' }} @endif>Transferee</option> --}}
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
                                                            <option value="MC" @if (old('type') == 'MC') {{ 'selected' }} disabled @endif>Main</option>
                                                            <option value="VC" @if (old('type') == 'VC') {{ 'selected' }} disabled @endif>Victorias</option>
                                                            <option value="SCC" @if (old('type') == 'SCC') {{ 'selected' }} disabled @endif>San Carlos</option>
                                                            <option value="MP" @if (old('type') == 'MP') {{ 'selected' }} disabled @endif>Moises Padilla</option>
                                                            {{-- <option value="HC" @if (old('type') == 'HC') {{ 'selected' }} disabled @endif>Hinigaran</option> --}}
                                                            <option value="IC" @if (old('type') == 'IC') {{ 'selected' }} disabled @endif>Ilog</option>
                                                            <option value="CA" @if (old('type') == 'CA') {{ 'selected' }} disabled @endif>Candoni</option>
                                                            <option value="CC" @if (old('type') == 'CC') {{ 'selected' }} disabled @endif>Cauayan</option>
                                                            <option value="SC" @if (old('type') == 'SC') {{ 'selected' }} disabled @endif>Sipalay</option>
                                                            {{-- <option value="HinC" @if (old('type') == 'HinC') {{ 'selected' }} disabled @endif>Hinobaan (Open)</option> --}}
                                                            <option value="VE" @if (old('type') == 'VE') {{ 'selected' }} disabled @endif>Valladolid</option>
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
                                                        <input type="hidden" id="selectedDateTimeID" name="dateID" placeholder="Selected Date Time ID">
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
                                                            <label>Full Name School Last Attended <i style="color: red">*</i></label>
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
                                                            <select class="form-control form-control-sm" name="course" id="courseSelect" style="text-transform: uppercase;">
                                                                <option value="">Select Course</option>
                                                                @foreach ($program as $programs)
                                                                    <option value="{{ $programs->code }}">{{ $programs->program }}</option>
                                                                @endforeach
                                                                <option value="OTHER">Other (Specify)</option>
                                                            </select>
                                                            <input type="text" class="form-control form-control-sm mt-2" id="otherCourseInput" name="course" placeholder="Please specify course" style="display: none; text-transform: uppercase;" />

                                                            <script>
                                                                document.addEventListener('DOMContentLoaded', function() {
                                                                    const courseSelect = document.getElementById('courseSelect');
                                                                    const otherCourseInput = document.getElementById('otherCourseInput');
                                                                    courseSelect.addEventListener('change', function() {
                                                                        if (this.value === 'OTHER') {
                                                                            otherCourseInput.style.display = 'block';
                                                                            otherCourseInput.required = true;
                                                                        } else {
                                                                            otherCourseInput.style.display = 'none';
                                                                            otherCourseInput.required = false;
                                                                            otherCourseInput.value = '';
                                                                        }
                                                                    });
                                                                });
                                                            </script>
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

                                <div id="card-4" style="display: none;">
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
                                                        <label>Parent's Monthly Income <i style="color: red">*</i></label>
                                                        <input type="number" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()" name="monthly_income" value="{{old('monthly_income')}}">
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
                                                        <label>Upload School ID / Valid ID <i style="color: red">*</i></label>
                                                        <input type="file" name="studiddoc_image" class="form-control form-control-sm" id="fileInput" accept="image/*">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
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

                                    <div class="card">
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
                                </div>

                                <div class="progress-section d-flex align-items-center justify-content-between mt-3">
                                    <button type="button" class="btn btn-default" id="back-btn" onclick="prevCard(currentCard - 1)" style="display: none;">Back</button>
                                    <button type="button" class="btn btn-primary" id="next-btn" onclick="nextCard(currentCard + 1)" style="display: none;" disabled>Next</button>
                                    {{-- <button type="button" class="btn btn-info" id="ok-btn">OK</button> --}}
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

                            @endif
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

    @if(!$now->isWeekday() || $now->lt($startTime) || $now->gte($endTime))
    @else
        <script src="{{ asset('js/validation/apply/applyValidation.js') }}"></script>
        <script>
            var provincesRoute = "{{ route('getPortalProvinces', '') }}";
            var citiesRoute = "{{ route('getPortalCities', '') }}";
            var barangaysRoute = "{{ route('getPortalBarangays', '') }}";

            function updateAddress() {
                let hnum = $('#viewdatastudHnum').val(); // house number input
                let barangay = $('#barangay').find(':selected').data('name');
                let city = $('#city').find(':selected').data('name');
                let province = $('#province').find(':selected').data('name');
                let region = $('#region').find(':selected').data('name');
                let zipcode = $('#zipcode').val(); // hidden or text input for zip

                // Filter out undefined or empty values, then join with comma
                let fullAddress = [hnum, barangay, city, province, region, zipcode].filter(Boolean).join(', ');

                $('#viewdatastudAddress').val(fullAddress);
                $('#viewdatastudHnum').on('input', updateAddress);
            }

            $(document).ready(function () {
                $('#region').on('change', function () {
                    var regionId = $(this).val();
                    var regionName = $(this).find(':selected').data('name');
                    $('#region_name').val(regionName);
                    updateAddress(); // Update address

                    $('#province').empty().append('<option disabled selected>Loading...</option>');
                    $.get(provincesRoute + '/' + regionId, function (data) {
                        $('#province').html('<option disabled selected>Select Province</option>');
                        data.forEach(p => $('#province').append(`<option value="${p.province_id}" data-name="${p.name}">${p.name}</option>`));
                    });
                });

                $('#province').on('change', function () {
                    var provinceId = $(this).val();
                    var provinceName = $(this).find(':selected').data('name');
                    $('#province_name').val(provinceName);
                    updateAddress(); // Update address

                    $('#city').empty().append('<option disabled selected>Loading...</option>');
                    $.get(citiesRoute + '/' + provinceId, function (data) {
                        $('#city').html('<option disabled selected>Select City</option>');
                        data.forEach(c => $('#city').append(`<option value="${c.city_id}" data-name="${c.name}" data-zip="${c.zip_code}">${c.name}</option>`));
                    });
                });

                $('#city').on('change', function () {
                    var cityName = $(this).find(':selected').data('name');
                    var zip = $(this).find(':selected').data('zip');

                    $('#city_name').val(cityName);
                    $('#zipcode').val(zip || '');
                    updateAddress(); // Update address

                    var cityId = $(this).val();
                    $('#barangay').empty().append('<option disabled selected>Loading...</option>');
                    $.get(barangaysRoute + '/' + cityId, function (data) {
                        $('#barangay').html('<option disabled selected>Select Barangay</option>');
                        data.forEach(b => $('#barangay').append(`<option value="${b.id}" data-name="${b.name}" style="text-transform: uppercase;">${b.name}</option>`));
                    });
                });

                $('#barangay').on('change', function () {
                    var brgyName = $(this).find(':selected').data('name');
                    $('#brgy_name').val(brgyName);
                    updateAddress(); // Update address
                });
            });
            // $(document).ready(function() {
            //     var cityData = {
            //         "Manila": { province: "Metro Manila", region: "NCR", zcode: "1000" },
            //         "Quezon City": { province: "Metro Manila", region: "NCR", zcode: "1100" },
            //         "Caloocan": { province: "Metro Manila", region: "NCR", zcode: "1400" },
            //         "Las Piñas": { province: "Metro Manila", region: "NCR", zcode: "1740" },
            //         "Makati": { province: "Metro Manila", region: "NCR", zcode: "1200" },
            //         "Malabon": { province: "Metro Manila", region: "NCR", zcode: "1470" },
            //         "Mandaluyong": { province: "Metro Manila", region: "NCR", zcode: "1550" },
            //         "Marikina": { province: "Metro Manila", region: "NCR", zcode: "1800" },
            //         "Muntinlupa": { province: "Metro Manila", region: "NCR", zcode: "1770" },
            //         "Navotas": { province: "Metro Manila", region: "NCR", zcode: "1485" },
            //         "Parañaque": { province: "Metro Manila", region: "NCR", zcode: "1700" },
            //         "Pasay": { province: "Metro Manila", region: "NCR", zcode: "1300" },
            //         "Pasig": { province: "Metro Manila", region: "NCR", zcode: "1600" },
            //         "San Juan": { province: "Metro Manila", region: "NCR", zcode: "1500" },
            //         "Taguig": { province: "Metro Manila", region: "NCR", zcode: "1630" },
            //         "Valenzuela": { province: "Metro Manila", region: "NCR", zcode: "1440" },
            //         "Cebu City": { province: "Cebu", region: "Region VII", zcode: "6000" },
            //         "Mandaue": { province: "Cebu", region: "Region VII", zcode: "6014" },
            //         "Lapu-Lapu": { province: "Cebu", region: "Region VII", zcode: "6015" },
            //         "Davao City": { province: "Davao del Sur", region: "Region XI", zcode: "8000" },
            //         "Baguio City": { province: "Benguet", region: "CAR", zcode: "2600" },
            //         "Iloilo City": { province: "Iloilo", region: "Region VI", zcode: "5000" },
            //         "Zamboanga City": { province: "Zamboanga del Sur", region: "Region IX", zcode: "7000" },

            //         // Negros Occidental
            //         "BACOLOD": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6100" },
            //         "BAGO": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6101" },
            //         "BINALBAGAN": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6107" },
            //         "CADIZ": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6121" },
            //         "CALATRAVA": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6126" },
            //         "CANDONI": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6110" },
            //         "CAUAYAN": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6112" },
            //         "DON SALVADOR BENEDICTO": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6133" },
            //         "ENRIQUE MAGALONA": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6118" },
            //         "ESCALANTE": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6124" },
            //         "HIMAMAYLAN": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6108" },
            //         "HINIGARAN": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6106" },
            //         "HINOBA-AN": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6114" },
            //         "ILOG": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6109" },
            //         "ISABELA": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6128" },
            //         "KABANKALAN CITY": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6111" },
            //         "LA CARLOTA": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6130" },
            //         "LA CASTELLANA": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6131" },
            //         "MANAPLA": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6120" },
            //         "MOISES PADILLA": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6132" },
            //         "PARAISO FABRICA": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6123" },
            //         "PONTEVEDRA": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6105" },
            //         "SAGAY": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6122" },
            //         "SAN CARLOS CITY": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6127" },
            //         "SAN ENRIQUE": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6104" },
            //         "SILAY": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6116" },
            //         "SILAY HAWAIIAN CENTRAL": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6117" },
            //         "SIPALAY": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6113" },
            //         "TALISAY": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6115" },
            //         "TOBOSO": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6125" },
            //         "VALLADOLID": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6103" },
            //         "VICTORIAS": { province: "NEGROS OCC.", region: "REGION VI", zcode: "6119" },

            //         // Negros Oriental
            //         "AMLAN": { province: "NEGROS OR.", region: "REGION VI", zcode: "6203" },
            //         "AYUNGON": { province: "NEGROS OR.", region: "REGION VII", zcode: "6210" },
            //         "BACONG": { province: "NEGROS OR.", region: "REGION VII", zcode: "6216" },
            //         "BAIS CITY": { province: "NEGROS OR.", region: "REGION VII", zcode: "6206" },
            //         "BASAY": { province: "NEGROS OR.", region: "REGION VII", zcode: "6222" },
            //         "BAYAWAN": { province: "NEGROS OR.", region: "REGION VII", zcode: "6221" },
            //         "BINDOY": { province: "NEGROS OR.", region: "REGION VII", zcode: "6209" },
            //         "CANLAON CITY": { province: "NEGROS OR.", region: "REGION VII", zcode: "6223" },
            //         "DAUIN": { province: "NEGROS OR.", region: "REGION VII", zcode: "6217" },
            //         "DUMAGUETE CITY": { province: "NEGROS OR.", region: "REGION VII", zcode: "6200" },
            //         "GUIHULNGAN": { province: "NEGROS OR.", region: "REGION VII", zcode: "6214" },
            //         "JIMALALUD": { province: "NEGROS OR.", region: "REGION VII", zcode: "6212" },
            //         "LA LIBERTAD": { province: "NEGROS OR.", region: "REGION VII", zcode: "6213" },
            //         "MABINAY": { province: "NEGROS OR.", region: "REGION VII", zcode: "6207" },
            //         "MANJUYOD": { province: "NEGROS OR.", region: "REGION VII", zcode: "6208" },
            //         "PAMPLONA": { province: "NEGROS OR.", region: "REGION VII", zcode: "6205" },
            //         "SAN JOSE": { province: "NEGROS OR.", region: "REGION VII", zcode: "6202" },
            //         "SANTA CATALINA": { province: "NEGROS OR.", region: "REGION VII", zcode: "6220" },
            //         "SIATON": { province: "NEGROS OR.", region: "REGION VII", zcode: "6219" },
            //         "SIBULAN": { province: "NEGROS OR.", region: "REGION VII", zcode: "6201" },
            //         "TANJAY": { province: "NEGROS OR.", region: "REGION VII", zcode: "6204" },
            //         "TAYASAN": { province: "NEGROS OR.", region: "REGION VII", zcode: "6211" },
            //         "VALENCIA": { province: "NEGROS OR.", region: "REGION VII", zcode: "6215" },
            //         "VALLEHERMOSO": { province: "NEGROS OR.", region: "REGION VII", zcode: "6224" },
            //         "ZAMBOANGUITA": { province: "NEGROS OR.", region: "REGION VII", zcode: "6218" },


            //         "Cagayan de Oro": { province: "Misamis Oriental", region: "Region X", zcode: "9000" },
            //         "General Santos": { province: "South Cotabato", region: "Region XII", zcode: "9500" },
            //         "Butuan": { province: "Agusan del Norte", region: "Caraga", zcode: "8600" },
            //         "Cotabato City": { province: "Maguindanao", region: "BARMM", zcode: "9600" },
            //         "Dagupan": { province: "Pangasinan", region: "Region I", zcode: "2400" },
            //         "Naga": { province: "Camarines Sur", region: "Region V", zcode: "4400" },
            //         "Olongapo": { province: "Zambales", region: "Region III", zcode: "2200" },
            //         "Ormoc": { province: "Leyte", region: "Region VIII", zcode: "6541" },
            //         "Puerto Princesa": { province: "Palawan", region: "MIMAROPA", zcode: "5300" },
            //         "Tacloban": { province: "Leyte", region: "Region VIII", zcode: "6500" },

            //         "Zamboanga City": { province: "Zamboanga del Sur", region: "Region IX", zcode: "7000" },
            //         "Antipolo": { province: "Rizal", region: "CALABARZON", zcode: "1870" },
            //         "Lucena": { province: "Quezon", region: "CALABARZON", zcode: "4301" },
            //         "San Pablo": { province: "Laguna", region: "CALABARZON", zcode: "4000" },
            //         "Calamba": { province: "Laguna", region: "CALABARZON", zcode: "4027" },
            //         "Batangas City": { province: "Batangas", region: "CALABARZON", zcode: "4200" },
            //         "Lipa": { province: "Batangas", region: "CALABARZON", zcode: "4217" },
            //         "San Fernando": { province: "La Union", region: "Region I", zcode: "2500" },
            //         "Urdaneta": { province: "Pangasinan", region: "Region I", zcode: "2428" },
            //         "Vigan": { province: "Ilocos Sur", region: "Region I", zcode: "2700" },
            //         "Laoag": { province: "Ilocos Norte", region: "Region I", zcode: "2900" },
            //         "Cabanatuan": { province: "Nueva Ecija", region: "Region III", zcode: "3100" },
            //         "San Jose del Monte": { province: "Bulacan", region: "Region III", zcode: "3023" },
            //         "Angeles": { province: "Pampanga", region: "Region III", zcode: "2009" },
            //         "Tarlac City": { province: "Tarlac", region: "Region III", zcode: "2300" },
            //         "San Fernando": { province: "Pampanga", region: "Region III", zcode: "2000" },
            //         "Balanga": { province: "Bataan", region: "Region III", zcode: "2100" },
            //         "Malolos": { province: "Bulacan", region: "Region III", zcode: "3000" },
            //         "Meycauayan": { province: "Bulacan", region: "Region III", zcode: "3020" },
            //         "Gapan": { province: "Nueva Ecija", region: "Region III", zcode: "3105" },
            //         "San Jose": { province: "Nueva Ecija", region: "Region III", zcode: "3121" },
            //         "Tagum": { province: "Davao del Norte", region: "Region XI", zcode: "8100" },
            //         "Panabo": { province: "Davao del Norte", region: "Region XI", zcode: "8105" },
            //         "Samal": { province: "Davao del Norte", region: "Region XI", zcode: "8119" },
            //         "Digos": { province: "Davao del Sur", region: "Region XI", zcode: "8002" },
            //         "Mati": { province: "Davao Oriental", region: "Region XI", zcode: "8200" },
            //         "Tagaytay": { province: "Cavite", region: "CALABARZON", zcode: "4120" },
            //         "Trece Martires": { province: "Cavite", region: "CALABARZON", zcode: "4109" },
            //         "Dasmariñas": { province: "Cavite", region: "CALABARZON", zcode: "4114" },
            //         "Cavite City": { province: "Cavite", region: "CALABARZON", zcode: "4100" },
            //         "Biñan": { province: "Laguna", region: "CALABARZON", zcode: "4024" },
            //         "Santa Rosa": { province: "Laguna", region: "CALABARZON", zcode: "4026" },
            //         "Tagum": { province: "Davao del Norte", region: "Region XI", zcode: "8100" },
            //         "Valencia": { province: "Bukidnon", region: "Region X", zcode: "8709" },
            //         "Malaybalay": { province: "Bukidnon", region: "Region X", zcode: "8700" },
            //         "Surigao City": { province: "Surigao del Norte", region: "Caraga", zcode: "8400" },
            //         "Cabadbaran": { province: "Agusan del Norte", region: "Caraga", zcode: "8605" },
            //         "Bislig": { province: "Surigao del Sur", region: "Caraga", zcode: "8311" },
            //         "Bayugan": { province: "Agusan del Sur", region: "Caraga", zcode: "8502" },
            //         "Koronadal": { province: "South Cotabato", region: "Region XII", zcode: "9506" },
            //         "Kidapawan": { province: "North Cotabato", region: "Region XII", zcode: "9400" },
            //         "Tacurong": { province: "Sultan Kudarat", region: "Region XII", zcode: "9800" },
            //         "Valencia": { province: "Bukidnon", region: "Region X", zcode: "8709" },
            //         "Pagadian": { province: "Zamboanga del Sur", region: "Region IX", zcode: "7016" },
            //         "Dipolog": { province: "Zamboanga del Norte", region: "Region IX", zcode: "7100" },
            //         "Isabela": { province: "Basilan", region: "BARMM", zcode: "7300" },
            //         "Iligan": { province: "Lanao del Norte", region: "Region X", zcode: "9200" },
            //         "Oroquieta": { province: "Misamis Occidental", region: "Region X", zcode: "7207" },
            //         "Ozamis": { province: "Misamis Occidental", region: "Region X", zcode: "7200" },
            //         "Tangub": { province: "Misamis Occidental", region: "Region X", zcode: "7214" },
            //         "Bais": { province: "Negros Oriental", region: "Region VII", zcode: "6206" },
            //         "Bayawan": { province: "Negros Oriental", region: "Region VII", zcode: "6221" },
            //         "Canlaon": { province: "Negros Oriental", region: "Region VII", zcode: "6223" },
            //         "Guihulngan": { province: "Negros Oriental", region: "Region VII", zcode: "6214" },
            //         "Tanjay": { province: "Negros Oriental", region: "Region VII", zcode: "6204" },
            //         "Toledo": { province: "Cebu", region: "Region VII", zcode: "6038" },
            //         "Talisay": { province: "Cebu", region: "Region VII", zcode: "6045" },
            //         "Naga": { province: "Cebu", region: "Region VII", zcode: "6037" },
            //         "Carcar": { province: "Cebu", region: "Region VII", zcode: "6019" },
            //         "Danao": { province: "Cebu", region: "Region VII", zcode: "6004" },
            //         "Bogo": { province: "Cebu", region: "Region VII", zcode: "6010" },
            //         "Tagbilaran": { province: "Bohol", region: "Region VII", zcode: "6300" },
            //         "Balanga": { province: "Bataan", region: "Region III", zcode: "2100" },
            //         "Tuguegarao": { province: "Cagayan", region: "Region II", zcode: "3500" },
            //         "Santiago": { province: "Isabela", region: "Region II", zcode: "3311" },
            //         "Cauayan": { province: "Isabela", region: "Region II", zcode: "3305" },
            //         "Baybay": { province: "Leyte", region: "Region VIII", zcode: "6521" },
            //         "Borongan": { province: "Eastern Samar", region: "Region VIII", zcode: "6800" },
            //         "Calbayog": { province: "Samar", region: "Region VIII", zcode: "6710" },
            //         "Catbalogan": { province: "Samar", region: "Region VIII", zcode: "6700" },
            //         "Maasin": { province: "Southern Leyte", region: "Region VIII", zcode: "6600" },
            //         "Baybay": { province: "Leyte", region: "Region VIII", zcode: "6521" },
            //         "Bayugan": { province: "Agusan del Sur", region: "Caraga", zcode: "8502" }
            //         // Add more cities as needed
            //     };

            //     var $citySelect = $('#viewdatastudCity');
            //     var $provinceInput = $('#viewdatastudProvince');
            //     var $regionInput = $('#viewdatastudRegion');
            //     var $zcodeInput = $('#viewdatastudZcode');

            //     function updateAddress() {
            //         var hnum = document.getElementById('viewdatastudHnum').value;
            //         var brgy = document.getElementById('viewdatastudBrgy').value;
            //         var city = document.getElementById('viewdatastudCity').value;
            //         var province = document.getElementById('viewdatastudProvince').value;
            //         var region = document.getElementById('viewdatastudRegion').value;
            //         var zcode = document.getElementById('viewdatastudZcode').value;

            //         // Concatenate the values with commas
            //         var address = [hnum, brgy, city, province, region, zcode].filter(Boolean).join(', ');

            //         // Update the Address field
            //         document.getElementById('viewdatastudAddress').value = address;
            //     }

            //     document.getElementById('viewdatastudHnum').addEventListener('input', updateAddress);
            //     document.getElementById('viewdatastudBrgy').addEventListener('input', updateAddress);
            //     document.getElementById('viewdatastudCity').addEventListener('change', updateAddress);
            //     document.getElementById('viewdatastudProvince').addEventListener('input', updateAddress);
            //     document.getElementById('viewdatastudRegion').addEventListener('input', updateAddress);
            //     document.getElementById('viewdatastudZcode').addEventListener('input', updateAddress);

            //     // Populate the city dropdown
            //     var sortedCities = Object.keys(cityData).sort();

            //     // Populate the city dropdown
            //     sortedCities.forEach(function(city) {
            //         $citySelect.append('<option value="' + city + '">' + city + '</option>');
            //     });

            //     // Event listener for city dropdown change
            //     $citySelect.change(function() {
            //         var selectedCity = $(this).val();
            //         var cityInfo = cityData[selectedCity];

            //         // Remove highlight class from all options
            //         $citySelect.find('option').removeClass('highlight');

            //         // Add highlight class to selected option
            //         $citySelect.find('option[value="' + selectedCity + '"]').addClass('highlight');
                    
            //         if (cityInfo) {
            //             $provinceInput.val(cityInfo.province);
            //             $regionInput.val(cityInfo.region);
            //             $zcodeInput.val(cityInfo.zcode);
            //         } else {
            //             $provinceInput.val('');
            //             $regionInput.val('');
            //             $zcodeInput.val('');
            //         }
            //     });
            // });
        </script>

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

        {{-- <script>
            $(document).ready(function () {
                $('#email').on('blur', function () {
                    let email = $(this).val();
                    if (email.endsWith('@gmail.com')) {
                        $('#verification-message').show(); 
                    }

                    $('#next-btn').hide(); 

                    $.ajax({
                        url: '{{ route('checkEmail') }}',
                        method: 'POST',
                        data: { email: email, _token: '{{ csrf_token() }}' },
                        success: function (response) {
                            console.log("Server response:", response);
                            $('#verification-message').hide(); 

                            if (response.valid) {
                                $('#next-btn').show(); 
                                $('#error-message').hide(); 
                                //$('#ok-btn').hide();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Valid Email',
                                    text: 'This email is registered with Google.',
                                });
                            } else {
                                $('#next-btn').hide(); 
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Invalid Email',
                                    text: 'This email is not registered with Google.',
                                });
                            }
                        },
                        error: function () {
                            $('#verification-message').hide(); 
                            $('#next-btn').hide(); 
                        }
                    });
                });
            });
        </script> --}}

        <script>
            $(document).ready(function () {
                $('#email').on('blur', function () {
                    let email = $(this).val();

                    // Check if the email input is not empty
                    if (email.trim() !== '') {
                        $('#next-btn').show(); // Enable the "Next" button
                    } else {
                        $('#next-btn').hide(); // Hide the button if the email is empty
                    }
                });
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
                // Re-select previous value if still exists
                // select.val(currentVal);
            }
            function updateSchedOptions(selectName, options) {
                const select = $('select[name=' + selectName + ']');
                select.empty();
                select.append('<option disabled selected> --Select Testing Schedule-- </option>');
                $.each(options, function (key, value) {
                    const formattedDate = moment(value.date).format('MMMM DD, YYYY');
                    const formattedTime = moment(value.time, 'HH:mm').format('hh:mm A');
                    const slots = value.slots;
                    const primDateTimeID = value.id;

                    //select.append('<option value="' + value.date + '">' + formattedDate + ' ' + formattedTime + ' (Available Slots: ' + slots + ')</option>');
                    if (slots === 0) {
                        select.append('<option disabled>' + formattedDate + ' ' + formattedTime + ' (Slots is Full)</option>');
                    } else {
                        select.append('<option value="' + value.date + '" data-primdatetimeid="' + primDateTimeID + '">' + formattedDate + ' ' + formattedTime + ' (Available Slots: ' + slots + ')</option>');
                    }
                });
                // Keep selected value if still available
                // select.val(currentVal);
            }
            $('select[name="d_admissionselect"]').change(function() {
                const selectedOption = $(this).find('option:selected');
                const selectedText = selectedOption.text();

                // Separate date and time based on the format "January 01, 2024 09:00 AM"
                const dateTime = moment(selectedText, 'MMMM D, YYYY hh:mm A'); 

                const formattedDate = dateTime.format('YYYY-MM-DD');
                const formattedTime = dateTime.format('HH:mm:ss');
                const dateTimeID = selectedOption.data('primdatetimeid');

                $('#selectedDate').val(formattedDate);
                $('#selectedTime').val(formattedTime);
                $('#selectedDateTimeID').val(dateTimeID);
            });

            // Listen for the 'scheduleUpdated' event
            $(document).on('scheduleUpdated', function() {
                const selectedCampus = $('#campus').val(); 
                updateExamSchedule(selectedCampus); 
            });

            $('#campus').change(function () {
                const selectedCampus = $(this).val();
                updateCoursePreferences(selectedCampus);
                updateExamSchedule(selectedCampus);
            });

            //  Auto refresh every 30 seconds (you can adjust)
            // setInterval(function() {
            //     const selectedCampus = $('#campus').val();
            //     if (selectedCampus) {
            //         updateExamSchedule(selectedCampus);
            //     }
            // }, 30000);
        </script>

        {{-- <script type="text/javascript">
            setTimeout(function () {
                $("#alert").delay(4500).fadeOut(5000);
            }, 0); 
        </script> --}}
        <script>
            document.getElementById("genderSelect").addEventListener("change", function() {
                const selectedGender = this.value;
                const genderPlaceholder = document.getElementById("genderPlaceholder");
                const genderQuestion = document.getElementById("genderQuestion");

                if (selectedGender === "Female" || selectedGender === "Male") {
                    genderPlaceholder.textContent = selectedGender.toUpperCase(); // Set the gender dynamically
                    genderQuestion.style.display = "block";
                } else {
                    genderQuestion.style.display = "none"; // Hide the question if no valid gender is selected
                }
            });
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
        <script>
            document.querySelectorAll('input[name="typefileproofupload"]').forEach(radio => {
                radio.addEventListener('change', function() {
                const selectedText = this.closest('.icheck-primary').querySelector('h5').innerText.trim();
                const label = document.getElementById('uploadLabel');
                label.innerHTML = `Upload Proof of Equity Target Documents from Parents/Legal Guardians – <b style="color:#007AFF">${selectedText}</b> <i style="color: red">*</i>`;
                });
            });
        </script>
        <script>
            function handleReligionChange() {
                const select = document.getElementById('religionSelect');
                const otherInput = document.getElementById('otherReligionInput');
                
                if (select.value === 'OTHER') {
                    otherInput.style.display = 'block';
                    otherInput.setAttribute('name', 'religion'); // include in form submission
                    otherInput.focus();
                } else {
                    otherInput.style.display = 'none';
                    otherInput.removeAttribute('name'); // prevent duplicate field in submission
                }
            }
        </script>
    @endif
</body>
{{-- @endif --}}
</html>
   