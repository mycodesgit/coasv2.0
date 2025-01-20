@extends('layouts.master_enrollment')

@section('title')
CISS V.1.0 || Add Student
@endsection

@section('sideheader')
<h4>Enrollment</h4>
@endsection

@yield('sidemenu')

@section('workspace')

<style>
    .input-details {
        border: none;
        border-bottom: 2px solid #ccc;
        padding: 0;
        outline: none;
        box-shadow: none;
    }
</style>
<div class="card">
    <div class="card-body">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-home"></i>
                </a>
            </li>
            <li class="breadcrumb-item mt-1">Enrollment</li>
            <li class="breadcrumb-item active mt-1">Add Student</li>
        </ol>

        <p>
            @if(Session::has('success'))
                <div class="alert alert-success" id="alert">{{ Session::get('success')}} {{ Session::get('admission_id')}}</div>
            @elseif (Session::has('error'))
                <div class="alert alert-danger" id="alert">{{Session::get('error')}}</div>
            @endif
        </p>

        <div>
            <div class="card-outline-tabs">
                <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs-five-tab" role="tablist">
                        <li class="nav-item ml-1">
                            <a class="nav-link active text-dark text-bold" id="custom-tabs-one-tab" data-toggle="pill" href="#custom-tabs-one" role="tab" aria-controls="custom-tabs-one" aria-selected="true">
                                Add Under Graduate Student
                            </a>
                        </li>
                        <li class="nav-item ml-1">
                            <a class="nav-link text-dark text-bold" id="custom-tabs-two-tab" data-toggle="pill" href="#custom-tabs-two" role="tab" aria-controls="custom-tabs-two" aria-selected="false">
                                Add Graduate Student
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-four-tabContent">
                        <div class="tab-pane fade show active" id="custom-tabs-one" role="tabpanel" aria-labelledby="custom-tabs-one-tab">
                            <form method="post" action="" id="addunderStudentApply">
                                @csrf

                                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                    <h4>Student Information <span style="color: red; font-size: 10pt;">(Note: You can add students who do not exist in the system, such as returnees or transferees from other schools.)</span></h4>
                                </div>

                                <input type="hidden" name="app_id" value="0">
                                <input type="hidden" name="status" value="1">
                                <input type="hidden" name="en_status" value="2">
                                <input type="hidden" name="p_status" value="5">

                                <div class="form-group mt-3">
                                    <div class="form-row">
                                        <div class="col-md-2">
                                            <label><span class="badge badge-success">Stud ID No.</span></label>
                                            <input type="text" name="stud_id" class="form-control form-control-sm" oninput="formatInput(this); this.value = this.value.toUpperCase()" autofocus>
                                        </div>
                                        <div class="col-md-2">
                                            <label><span class="badge badge-secondary">Type</span></label>
                                            <select class="form-control form-control-sm" name="type" id="">
                                                <option value="">Select</option>
                                                <option value="2" @if (old('type') == 2) {{ 'selected' }} @endif>Returnee</option>
                                                <option value="3" @if (old('type') == 3) {{ 'selected' }} @endif>Transferee</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label><span class="badge badge-secondary">Firstname</span></label>
                                            <input type="text" name="fname" class="form-control form-control-sm" id="viewdatastudFname">
                                        </div>
                                        <div class="col-md-2">
                                            <label><span class="badge badge-secondary">Middlename</span></label>
                                            <input type="text" name="mname" class="form-control form-control-sm" id="viewdatastudMname">
                                        </div>
                                        <div class="col-md-2">
                                            <label><span class="badge badge-secondary">Lastname</span></label>
                                            <input type="text" name="lname" class="form-control form-control-sm" id="viewdatastudLname">
                                        </div>
                                        <div class="col-md-2">
                                            <label><span class="badge badge-secondary">Ext. name</span></label>
                                            <select class="form-control form-control-sm" name="ext" id="viewdatastudExt">
                                                <option disabled selected> --Select-- </option>
                                                <option value="">N/A</option>
                                                <option value="Jr.">Jr.</option>
                                                <option value="Sr.">Sr.</option>
                                                <option value="III">III</option>
                                                <option value="IV">IV</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="form-row">
                                        <div class="col-md-2">
                                            <label><span class="badge badge-secondary">Gender</span></label>
                                            <select class="form-control form-control-sm" name="gender" id="viewdatastudGender">
                                                <option disabled selected>Select</option>
                                                @foreach($genderStatuses as $gdrstatus)
                                                    <option value="{{ $gdrstatus->genderstat_name }}">
                                                        {{ $gdrstatus->genderstat_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-2">
                                            <label><span class="badge badge-secondary">Civil Status</span></label>
                                            <select class="form-control form-control-sm" name="civil_status" id="viewdatastudcivilstat">
                                                <option disabled selected>Select</option>
                                                @foreach($civilStatuses as $status)
                                                    <option value="{{ $status->cvlstat_name }}">
                                                        {{ $status->cvlstat_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-2">
                                            <label><span class="badge badge-secondary">Birthday</span></label>
                                            <input type="date" name="bday" class="form-control form-control-sm" id="bday" onchange="calculateAge()">
                                        </div>
                                        <div class="col-md-4">
                                            <label><span class="badge badge-secondary">Birth Place</span></label>
                                            <input type="text" name="pbirth" oninput="this.value = this.value.toUpperCase()" class="form-control form-control-sm" id="viewdatastudBdayp">
                                        </div>
                                        <div class="col-md-2">
                                            <label><span class="badge badge-secondary">Email Address</span></label>
                                            <input type="text" name="email" class="form-control form-control-sm" id="viewdatastudEmail">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="form-row">
                                        <div class="col-md-2">
                                            <label><span class="badge badge-secondary">Mobile</span></label>
                                            <input type="number" name="contact" class="form-control form-control-sm" id="viewdatastudMobile">
                                        </div>
                                        <div class="col-md-2">
                                            <label><span class="badge badge-secondary">House No.</span></label>
                                            <input type="text" name="hnum" class="form-control form-control-sm" id="viewdatastudHnum" oninput="this.value = this.value.toUpperCase()">
                                        </div>
                                        <div class="col-md-2">
                                            <label><span class="badge badge-secondary">Street/Barangay</span></label>
                                            <input type="text" name="brgy" class="form-control form-control-sm" id="viewdatastudBrgy" oninput="this.value = this.value.toUpperCase()">
                                        </div>
                                        <div class="col-md-2">
                                            <label><span class="badge badge-secondary">Munipality/City</span></label>
                                            <select name="city" class="form-control form-control-sm select2bs4" id="viewdatastudCity">
                                                <option value="">Select City</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label><span class="badge badge-secondary">Province</span></label>
                                            <input type="text" name="province" class="form-control form-control-sm" id="viewdatastudProvince">
                                        </div>
                                        <div class="col-md-2">
                                            <label><span class="badge badge-secondary">Region</span></label>
                                            <input type="text" name="region" class="form-control form-control-sm" id="viewdatastudRegion">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="form-row">
                                        <div class="col-md-2">
                                            <label><span class="badge badge-secondary">Zip Code</span></label>
                                            <input type="text" name="zcode" class="form-control form-control-sm" id="viewdatastudZcode">
                                        </div>
                                        <div class="col-md-2" @if(Auth::guard('web')->user()->role == 15) style="display:none" @endif>
                                            <label><span class="badge badge-secondary">Religion</span></label>
                                            <input type="text" name="religion" class="form-control form-control-sm" id="viewdatastudReligion">
                                        </div>
                                        <div class="col-md-4" @if(!Auth::guard('web')->user()->role == 15) style="display:none" @endif>
                                            <label><span class="badge badge-secondary">Spouse/Parent/Guardian</span></label>
                                            <input type="text" name="spouseparent" class="form-control form-control-sm" id="viewdatastudSpouseParent">
                                        </div>
                                        <div class="col-md-6">
                                            <label><span class="badge badge-secondary">Address</span></label>
                                            <input type="text" name="address" class="form-control form-control-sm" id="viewdatastudAddress" readonly style="background-color: #ddd !important; border: 1px solid #aaa;">
                                        </div>
                                        <div class="col-md-2">
                                            <label><span class="badge badge-secondary">Age</span></label>
                                            <input type="text" class="form-control form-control-sm" name="age" id="age" readonly style="background-color: #ddd !important; border: 1px solid #aaa;">
                                        </div>
                                    </div>
                                </div>

                                <div class="page-header mt-3" style="border-bottom: 1px solid #04401f;">
                                    <h4>Family Information</h4>
                                </div>

                                <div class="form-group mt-3">
                                    <div class="form-row">
                                        <div class="col-md-2">
                                            <label><span class="badge badge-secondary">Father's Name</span></label>
                                            <input type="text" class="form-control form-control-sm" name="stud_father" id="viewdatastudfather">
                                        </div>
                                        <div class="col-md-2">
                                            <label><span class="badge badge-secondary">Mother's Name</span></label>
                                            <input type="text" class="form-control form-control-sm" name="stud_mother" id="viewdatastudmother">
                                        </div>
                                        <div class="col-md-2">
                                            <label><span class="badge badge-secondary">Guardian's Name</span></label>
                                            <input type="text" class="form-control form-control-sm" name="stud_guardian" id="viewdatastudguardian">
                                        </div>
                                        <div class="col-md-2">
                                            <label><span class="badge badge-secondary">Monthly Income</span></label>
                                            <input type="text" class="form-control form-control-sm" name="monthly_income" id="viewdatastudprntincome">
                                        </div>
                                        <div class="col-md-2">
                                            <label><span class="badge badge-secondary">Contact Number</span></label>
                                            <input type="text" class="form-control form-control-sm" name="monthly_income" id="viewdatastudpcontact">
                                        </div>
                                    </div>
                                </div>

                                <div class="page-header mt-3" style="border-top: 1px solid #04401f;"></div>
                                <div class="row mt-1 mb-3">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="fas fa-check"></i> Save
                                        </button>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="tab-pane fade show" id="custom-tabs-two" role="tabpanel" aria-labelledby="custom-tabs-two-tab">
                            <form method="post" action="{{ route('studentStore') }}" id="addStudentApply">
                                @csrf

                                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                    <h4>Student Information</h4>
                                </div>

                                <input type="hidden" name="app_id" value="0">
                                <input type="hidden" name="status" value="1">
                                <input type="hidden" name="en_status" value="2">
                                <input type="hidden" name="p_status" value="6">

                                <div class="form-group mt-3">
                                    <div class="form-row">
                                        <div class="col-md-2">
                                            <label><span class="badge badge-secondary">Admission Type</span></label>
                                            <select class="form-control form-control-sm" name="type" id="">
                                                <option value="">Select</option>
                                                <option value="1" @if (old('type') == 1) {{ 'selected' }} @endif>New</option>
                                                <option value="2" @if (old('type') == 2) {{ 'selected' }} @endif>Returnee</option>
                                                <option value="3" @if (old('type') == 3) {{ 'selected' }} @endif>Transferee</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label><span class="badge badge-secondary">Firstname</span></label>
                                            <input type="text" name="fname" class="form-control form-control-sm" id="viewdatastudFname">
                                        </div>
                                        <div class="col-md-2">
                                            <label><span class="badge badge-secondary">Middlename</span></label>
                                            <input type="text" name="mname" class="form-control form-control-sm" id="viewdatastudMname">
                                        </div>
                                        <div class="col-md-2">
                                            <label><span class="badge badge-secondary">Lastname</span></label>
                                            <input type="text" name="lname" class="form-control form-control-sm" id="viewdatastudLname">
                                        </div>
                                        <div class="col-md-2">
                                            <label><span class="badge badge-secondary">Ext. name</span></label>
                                            <select class="form-control form-control-sm" name="ext" id="viewdatastudExt">
                                                <option disabled selected> --Select-- </option>
                                                <option value="">N/A</option>
                                                <option value="Jr.">Jr.</option>
                                                <option value="Sr.">Sr.</option>
                                                <option value="III">III</option>
                                                <option value="IV">IV</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label><span class="badge badge-secondary">Gender</span></label>
                                            <select class="form-control form-control-sm" name="gender" id="viewdatastudGender">
                                                <option disabled selected>Select</option>
                                                @foreach($genderStatuses as $gdrstatus)
                                                    <option value="{{ $gdrstatus->genderstat_name }}">
                                                        {{ $gdrstatus->genderstat_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="form-row">
                                        <div class="col-md-2">
                                            <label><span class="badge badge-secondary">Civil Status</span></label>
                                            <select class="form-control form-control-sm" name="civil_status" id="viewdatastudcivilstat">
                                                <option disabled selected>Select</option>
                                                @foreach($civilStatuses as $status)
                                                    <option value="{{ $status->cvlstat_name }}">
                                                        {{ $status->cvlstat_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-2">
                                            <label><span class="badge badge-secondary">Birthday</span></label>
                                            <input type="date" name="bday" class="form-control form-control-sm" id="bday" onchange="calculateAge()">
                                        </div>
                                        <div class="col-md-4">
                                            <label><span class="badge badge-secondary">Birth Place</span></label>
                                            <input type="text" name="pbirth" oninput="this.value = this.value.toUpperCase()" class="form-control form-control-sm" id="viewdatastudBdayp">
                                        </div>
                                        <div class="col-md-2">
                                            <label><span class="badge badge-secondary">Email Address</span></label>
                                            <input type="text" name="email" class="form-control form-control-sm" id="viewdatastudEmail">
                                        </div>
                                        <div class="col-md-2">
                                            <label><span class="badge badge-secondary">Mobile</span></label>
                                            <input type="text" name="contact" class="form-control form-control-sm" id="viewdatastudMobile">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="form-row">
                                        <div class="col-md-2">
                                            <label><span class="badge badge-secondary">House No.</span></label>
                                            <input type="text" name="hnum" class="form-control form-control-sm" id="viewdatastudHnum" oninput="this.value = this.value.toUpperCase()">
                                        </div>
                                        <div class="col-md-2">
                                            <label><span class="badge badge-secondary">Street/Barangay</span></label>
                                            <input type="text" name="brgy" class="form-control form-control-sm" id="viewdatastudBrgy" oninput="this.value = this.value.toUpperCase()">
                                        </div>
                                        <div class="col-md-2">
                                            <label><span class="badge badge-secondary">Munipality/City</span></label>
                                            <select name="city" class="form-control form-control-sm select2bs4" id="viewdatastudCity">
                                                <option value="">Select City</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label><span class="badge badge-secondary">Province</span></label>
                                            <input type="text" name="province" class="form-control form-control-sm" id="viewdatastudProvince">
                                        </div>
                                        <div class="col-md-2">
                                            <label><span class="badge badge-secondary">Region</span></label>
                                            <input type="text" name="region" class="form-control form-control-sm" id="viewdatastudRegion">
                                        </div>
                                        <div class="col-md-2">
                                            <label><span class="badge badge-secondary">Zip Code</span></label>
                                            <input type="text" name="zcode" class="form-control form-control-sm" id="viewdatastudZcode">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="form-row">
                                        <div class="col-md-2" @if(Auth::guard('web')->user()->role == 15) style="display:none" @endif>
                                            <label><span class="badge badge-secondary">Religion</span></label>
                                            <input type="text" name="religion" class="form-control form-control-sm" id="viewdatastudReligion">
                                        </div>
                                        <div class="col-md-4" @if(!Auth::guard('web')->user()->role == 15) style="display:none" @endif>
                                            <label><span class="badge badge-secondary">Spouse/Parent/Guardian</span></label>
                                            <input type="text" name="spouseparent" class="form-control form-control-sm" id="viewdatastudSpouseParent">
                                        </div>
                                        <div class="col-md-6">
                                            <label><span class="badge badge-secondary">Address</span></label>
                                            <input type="text" name="address" class="form-control form-control-sm" id="viewdatastudAddress" readonly style="background-color: #ddd !important; border: 1px solid #aaa;">
                                        </div>
                                        <div class="col-md-2">
                                            <label><span class="badge badge-secondary">Age</span></label>
                                            <input type="text" class="form-control form-control-sm" name="age" id="age" readonly style="background-color: #ddd !important; border: 1px solid #aaa;">
                                        </div>
                                    </div>
                                </div>

                                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                    <h4>School Records</h4>
                                </div>

                                <div class="form-group mt-3">
                                    <div class="form-row">
                                        <div class="col-md-8">
                                            <label><span class="badge badge-secondary">Elementary</span></label>
                                            <input type="text" name="elementary" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()">
                                        </div>
                                        <div class="col-md-4">
                                            <label><span class="badge badge-warning">Year Graduated</span></label>
                                            <input type="number" name="elemyeargrad" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="form-row">
                                        <div class="col-md-8">
                                            <label><span class="badge badge-secondary">Secondary</span></label>
                                            <input type="text" name="highschool" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()">
                                        </div>
                                        <div class="col-md-4">
                                            <label><span class="badge badge-warning">Year Graduated</span></label>
                                            <input type="number" name="highschoolyeargrad" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="form-row">
                                        <div class="col-md-8">
                                            <label><span class="badge badge-secondary">Tertiary</span></label>
                                            <input type="text" name="tertiary" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()">
                                        </div>
                                        <div class="col-md-4">
                                            <label><span class="badge badge-warning">Year Graduated</span></label>
                                            <input type="number" name="tertiaryyeargrad" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="form-row">
                                        <div class="col-md-8">
                                            <label><span class="badge badge-secondary">Course</span></label>
                                            <input type="text" name="tertiarycourse" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()">
                                        </div>
                                        <div class="col-md-4">
                                            <label><span class="badge badge-warning">Major</span></label>
                                            <input type="text" name="tertiarymajor" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()">
                                        </div>
                                    </div>
                                </div>

                                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                    
                                </div>

                                <div class="form-group mt-2">
                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <label for="masterscourse">Master's Course (<span style="font-style: italic;">if with master's degree/units</span>):</label>
                                            <input type="text" name="masterscourse" id="masterscourse" class="form-control form-control-sm input-details text-bold" oninput="this.value = this.value.toUpperCase()">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <label for="masterspeciallization">Major/Area of Specialization:</label>
                                            <input type="text" name="masterspeciallization" id="masterspeciallization" class="form-control form-control-sm input-details text-bold" oninput="this.value = this.value.toUpperCase()">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="masterschool">School:</label>
                                            <input type="text" name="masterschool" id="masterschool" class="form-control form-control-sm input-details text-bold" oninput="this.value = this.value.toUpperCase()">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="form-row">
                                        <div class="col-md-2">
                                            <label for="masternounit">Number of Units:</label>
                                            <input type="number" name="masternounit" id="masternounit" class="form-control form-control-sm input-details text-bold" oninput="this.value = this.value.toUpperCase()">
                                        </div>
                                        <div class="col-md-8">
                                            <label for="masteraddress">Address:</label>
                                            <input type="text" name="masteraddress" id="masteraddress" class="form-control form-control-sm input-details text-bold" oninput="this.value = this.value.toUpperCase()">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="masterinclyear">Inclusive years:</label>
                                            <input type="text" name="masterinclyear" id="masterinclyear" class="form-control form-control-sm input-details text-bold" oninput="this.value = this.value.toUpperCase(); formatYear(this)" maxlength="9">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mt-5">
                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <label for="doctorcourse">Doctorate's Course (<span style="font-style: italic;">if with Doctorate's degree/units</span>):</label>
                                            <input type="text" name="doctorcourse" id="doctorcourse" class="form-control form-control-sm input-details text-bold" oninput="this.value = this.value.toUpperCase()">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <label for="mastersCourse">Major/Area of Specialization:</label>
                                            <input type="text" name="doctorspecialization" id="doctorspecialization" class="form-control form-control-sm input-details text-bold" oninput="this.value = this.value.toUpperCase()">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="doctorschool">School:</label>
                                            <input type="text" name="doctorschool" id="doctorschool" class="form-control form-control-sm input-details text-bold" oninput="this.value = this.value.toUpperCase()">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="form-row">
                                        <div class="col-md-2">
                                            <label for="doctornounit">Number of Units:</label>
                                            <input type="number" name="doctornounit" id="doctornounit" class="form-control form-control-sm input-details text-bold" oninput="this.value = this.value.toUpperCase()">
                                        </div>
                                        <div class="col-md-8">
                                            <label for="doctoraddress">Address:</label>
                                            <input type="text" name="doctoraddress" id="doctoraddress" class="form-control form-control-sm input-details text-bold" oninput="this.value = this.value.toUpperCase()">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="doctorinclyear">Inclusive years:</label>
                                            <input type="text" name="doctorinclyear" id="doctorinclyear" class="form-control form-control-sm input-details text-bold" oninput="this.value = this.value.toUpperCase(); formatYear(this)" maxlength="9">
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-5 mb-3">
                                    <div class="col-md-12 col-6 d-flex justify-content-center">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="fas fa-check"></i> Save
                                        </button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>

<script>
    var studentAddNewRoute = "{{ route('studentStore') }}";
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
</script>

<script>
    function formatYear(input) {
        let value = input.value.replace(/[^\d]/g, '');
        if (value.length >= 4) {
            input.value = value.substring(0, 4) + '-' + value.substring(4, 8);
        } else {
            input.value = value;
        }
    }
</script>

@endsection
