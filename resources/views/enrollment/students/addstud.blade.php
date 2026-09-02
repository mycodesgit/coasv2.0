@extends('layouts.master_enrollment')

@section('title')
CISS V.1.0 || Enrollment
@endsection

@yield('sidemenu')

@section('workspace')
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
                            <li class="breadcrumb-item mt-1">Enrollment</li>
                            <li class="breadcrumb-item active mt-1">Add Student</li>
                        </ol>
                    </div>
                </div>
                <div class="row g-3 mb-3 mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <ul class="nav nav-pills mb-3 bg-light p-2 rounded-2 d-inline-flex" id="pills-tab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="pills-one-tab" data-bs-toggle="pill"
                                            data-bs-target="#pills-one" type="button" role="tab"
                                            aria-controls="pills-one" aria-selected="true">
                                            Add Under Graduate Student
                                        </button>
                                    </li>
                                    &nbsp;
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="pills-two-tab" data-bs-toggle="pill"
                                            data-bs-target="#pills-two" type="button" role="tab"
                                            aria-controls="pills-two" aria-selected="false" tabindex="-1">
                                            Add Graduate Student
                                        </button>
                                    </li>
                                </ul>
                                <div class="tab-content mt-3" id="pills-tabContent">
                                    <div class="tab-pane fade show active" id="pills-one" role="tabpanel" aria-labelledby="pills-one-tab" tabindex="0">
                                        <div>
                                            <form method="post" action="{{ route('studentUnderStore') }}" id="addStudentApply">
                                                @csrf

                                                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                                    <h4>Student Information</h4>
                                                </div>

                                                <input type="hidden" name="app_id" value="0">
                                                <input type="hidden" name="status" value="1">
                                                <input type="hidden" name="en_status" value="2">
                                                <input type="hidden" name="p_status" value="6">

                                                <div class="form-group" style="padding-left: 10px; padding-right: 10px">
                                                    <div class="row">
                                                        <div class="col-md-2 g-3">
                                                            <label>Admission Type: <span class="text-danger">*</span></label>
                                                            <select class="form-control form-control-sm" name="type" id="">
                                                                <option value="">Select</option>
                                                                <option value="1" @if (old('type') == 1) {{ 'selected' }} @endif>New</option>
                                                                <option value="2" @if (old('type') == 2) {{ 'selected' }} @endif>Returnee</option>
                                                                <option value="3" @if (old('type') == 3) {{ 'selected' }} @endif>Transferee</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2 g-3">
                                                            <label>Firstname: <span class="text-danger">*</span></label>
                                                            <input type="text" name="fname" oninput="this.value = this.value.toUpperCase()" class="form-control form-control-sm" id="viewdatastudFname">
                                                        </div>
                                                        <div class="col-md-2 g-3">
                                                            <label>Middlename: </label>
                                                            <input type="text" name="mname" oninput="this.value = this.value.toUpperCase()" class="form-control form-control-sm" id="viewdatastudMname">
                                                        </div>
                                                        <div class="col-md-2 g-3">
                                                            <label>Lastname: <span class="text-danger">*</span></label>
                                                            <input type="text" name="lname" oninput="this.value = this.value.toUpperCase()" class="form-control form-control-sm" id="viewdatastudLname">
                                                        </div>
                                                        <div class="col-md-2 g-3">
                                                            <label>Ext. name:</label>
                                                            <select class="form-control form-control-sm" name="ext" id="viewdatastudExt">
                                                                <option disabled selected> --Select-- </option>
                                                                <option value="">N/A</option>
                                                                <option value="Jr.">Jr.</option>
                                                                <option value="Sr.">Sr.</option>
                                                                <option value="III">III</option>
                                                                <option value="IV">IV</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2 g-3">
                                                            <label>Gender: <span class="text-danger">*</span></label>
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

                                                <div class="form-group" style="padding-left: 10px; padding-right: 10px">
                                                    <div class="row">
                                                        <div class="col-md-2 g-3">
                                                            <label>Civil Status: <span class="text-danger">*</span></label>
                                                            <select class="form-control form-control-sm" name="civil_status" id="viewdatastudcivilstat">
                                                                <option disabled selected>Select</option>
                                                                @foreach($civilStatuses as $status)
                                                                    <option value="{{ $status->cvlstat_name }}">
                                                                        {{ $status->cvlstat_name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        
                                                        <div class="col-md-2 g-3">
                                                            <label>Birthday: <span class="text-danger">*</span></label>
                                                            <input type="date" name="bday" class="form-control form-control-sm" id="bday" onchange="calculateAge()">
                                                        </div>
                                                        <div class="col-md-4 g-3">
                                                            <label>Birth Place:</label>
                                                            <input type="text" name="pbirth" oninput="this.value = this.value.toUpperCase()" class="form-control form-control-sm" id="viewdatastudBdayp">
                                                        </div>
                                                        <div class="col-md-2 g-3">
                                                            <label>Email Address: <span class="text-danger">*</span></label>
                                                            <input type="text" name="email" class="form-control form-control-sm" id="viewdatastudEmail">
                                                        </div>
                                                        <div class="col-md-2 g-3">
                                                            <label>Mobile:</label>
                                                            <input type="text" name="contact" class="form-control form-control-sm" id="viewdatastudMobile">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group" style="padding-left: 10px; padding-right: 10px">
                                                    <div class="row">
                                                        <div class="col-md-4 g-3">
                                                            <label>Region: <span class="text-danger">*</span></label>
                                                            <select id="region" class="form-control form-control-sm select2bs4">
                                                                <option value="">Select Region</option>
                                                                @foreach($regions as $region)
                                                                    <option value="{{ $region->region_id }}" data-name="{{ $region->name }}">{{ $region->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <input type="hidden" id="region_name" name="region">
                                                        </div>
                                                        <div class="col-md-4 g-3">
                                                            <label>Province: <span class="text-danger">*</span></label>
                                                            <select id="province" class="form-control form-control-sm select2bs4">
                                                                <option value="">Select Province</option>
                                                            </select>
                                                            <input type="hidden" id="province_name" name="province">
                                                        </div>
                                                        <div class="col-md-4 g-3">
                                                            <label>City/Municipality: <span class="text-danger">*</span></label>
                                                            <select id="city" class="form-control form-control-sm select2bs4">
                                                                <option value="">Select City</option>
                                                            </select>
                                                            <input type="hidden" id="city_name" name="city">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group" style="padding-left: 10px; padding-right: 10px">
                                                    <div class="row">
                                                        <div class="col-md-4 g-3">
                                                            <label>Barangay</label>
                                                            <select id="barangay" class="form-control form-control-sm select2bs4" style="text-transform: uppercase;">
                                                                <option value="">Select Barangay</option>
                                                            </select>
                                                            <input type="hidden" id="brgy_name" name="brgy">
                                                        </div>
                                                        <div class="col-md-4 g-3">
                                                            <label>House No. / Block / Purok</label>
                                                            <input type="text" name="hnum" id="viewdatastudHnum" class="form-control form-control-sm" placeholder="House No. / Block / Purok" style="text-transform: uppercase;">
                                                        </div>
                                                        <div class="col-md-4 g-3">
                                                            <label>Zipcode</label>
                                                            <input type="text" name="zcode" id="zipcode" class="form-control form-control-sm" readonly placeholder="Zip Code" readonly style="background-color: #ddd !important; border: 1px solid #aaa;">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group" style="padding-left: 10px; padding-right: 10px">
                                                    <div class="row">
                                                        <div class="col-md-4 g-3" @if(Auth::guard('web')->user()->role == 15) style="display:none" @endif>
                                                            <label>Religion</label>
                                                            <input type="text" name="religion" class="form-control form-control-sm" id="viewdatastudReligion">
                                                        </div>
                                                        <div class="col-md-4 g-3" @if(!Auth::guard('web')->user()->role == 15) style="display:none" @endif>
                                                            <label>Spouse/Parent/Guardian</label>
                                                            <input type="text" name="spouseparent" class="form-control form-control-sm" id="viewdatastudSpouseParent">
                                                        </div>
                                                        <div class="col-md-6 g-3">
                                                            <label>Address</label>
                                                            <input type="text" name="address" class="form-control form-control-sm" id="viewdatastudAddress" readonly style="background-color: #ddd !important; border: 1px solid #aaa;">
                                                        </div>
                                                        <div class="col-md-2 g-3">
                                                            <label>Age</label>
                                                            <input type="text" class="form-control form-control-sm" name="age" id="age" readonly style="background-color: #ddd !important; border: 1px solid #aaa;">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row mt-5 mb-3">
                                                    <div class="col-md-12">
                                                        <button type="submit" class="btn btn-success">
                                                            <i class="fas fa-check"></i> Save
                                                        </button>
                                                    </div>
                                                </div>

                                            </form>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="pills-two" role="tabpanel" aria-labelledby="pills-two-tab" tabindex="0">
                                        <div>
                                            <form method="post" action="{{ route('studentStore') }}" id="addStudentGradApply">
                                                @csrf

                                                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                                    <h4>Student Information</h4>
                                                </div>

                                                <input type="hidden" name="app_id" value="0">
                                                <input type="hidden" name="status" value="1">
                                                <input type="hidden" name="en_status" value="2">
                                                <input type="hidden" name="p_status" value="6">

                                                <div class="form-group" style="padding-left: 10px; padding-right: 10px">
                                                    <div class="row">
                                                        <div class="col-md-2 g-3">
                                                            <label>Admission Type</label>
                                                            <select class="form-control form-control-sm" name="type" id="">
                                                                <option value="">Select</option>
                                                                <option value="1" @if (old('type') == 1) {{ 'selected' }} @endif>New</option>
                                                                <option value="2" @if (old('type') == 2) {{ 'selected' }} @endif>Returnee</option>
                                                                <option value="3" @if (old('type') == 3) {{ 'selected' }} @endif>Transferee</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2 g-3">
                                                            <label>Firstname</label>
                                                            <input type="text" name="fname" oninput="this.value = this.value.toUpperCase()" class="form-control form-control-sm" id="viewdatastudFname">
                                                        </div>
                                                        <div class="col-md-2 g-3">
                                                            <label>Middlename</label>
                                                            <input type="text" name="mname" oninput="this.value = this.value.toUpperCase()" class="form-control form-control-sm" id="viewdatastudMname">
                                                        </div>
                                                        <div class="col-md-2 g-3">
                                                            <label>Lastname</label>
                                                            <input type="text" name="lname" oninput="this.value = this.value.toUpperCase()" class="form-control form-control-sm" id="viewdatastudLname">
                                                        </div>
                                                        <div class="col-md-2 g-3">
                                                            <label>Ext. name</label>
                                                            <select class="form-control form-control-sm" name="ext" id="viewdatastudExt">
                                                                <option disabled selected> --Select-- </option>
                                                                <option value="">N/A</option>
                                                                <option value="Jr.">Jr.</option>
                                                                <option value="Sr.">Sr.</option>
                                                                <option value="III">III</option>
                                                                <option value="IV">IV</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2 g-3">
                                                            <label>Gender</label>
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

                                                <div class="form-group" style="padding-left: 10px; padding-right: 10px">
                                                    <div class="row">
                                                        <div class="col-md-2 g-3">
                                                            <label>Civil Status</label>
                                                            <select class="form-control form-control-sm" name="civil_status" id="viewdatastudcivilstat">
                                                                <option disabled selected>Select</option>
                                                                @foreach($civilStatuses as $status)
                                                                    <option value="{{ $status->cvlstat_name }}">
                                                                        {{ $status->cvlstat_name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        
                                                        <div class="col-md-2 g-3">
                                                            <label>Birthday</label>
                                                            <input type="date" name="bday" class="form-control form-control-sm" id="bday" onchange="calculateAge()">
                                                        </div>
                                                        <div class="col-md-4 g-3">
                                                            <label>Birth Place</label>
                                                            <input type="text" name="pbirth" oninput="this.value = this.value.toUpperCase()" class="form-control form-control-sm" id="viewdatastudBdayp">
                                                        </div>
                                                        <div class="col-md-2 g-3">
                                                            <label>Email Address</label>
                                                            <input type="text" name="email" class="form-control form-control-sm" id="viewdatastudEmail">
                                                        </div>
                                                        <div class="col-md-2 g-3">
                                                            <label>Mobile</label>
                                                            <input type="text" name="contact" class="form-control form-control-sm" id="viewdatastudMobile">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group" style="padding-left: 10px; padding-right: 10px">
                                                    <div class="row">
                                                        <div class="col-md-4 g-3">
                                                            <label>Region</label>
                                                            <select id="region1" class="form-control form-control-sm select2bs4">
                                                                <option value="">Select Region</option>
                                                                @foreach($regions as $region)
                                                                    <option value="{{ $region->region_id }}" data-name="{{ $region->name }}">{{ $region->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <input type="hidden" id="region_name1" name="region">
                                                        </div>
                                                        <div class="col-md-4 g-3">
                                                            <label>Province</label>
                                                            <select id="province1" class="form-control form-control-sm select2bs4">
                                                                <option value="">Select Province</option>
                                                            </select>
                                                            <input type="hidden" id="province_name1" name="province">
                                                        </div>
                                                        <div class="col-md-4 g-3">
                                                            <label>City/Municipality</label>
                                                            <select id="city1" class="form-control form-control-sm select2bs4">
                                                                <option value="">Select City</option>
                                                            </select>
                                                            <input type="hidden" id="city_name1" name="city">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group" style="padding-left: 10px; padding-right: 10px">
                                                    <div class="row">
                                                        <div class="col-md-4 g-3">
                                                            <label>Barangay</label>
                                                            <select id="barangay1" class="form-control form-control-sm select2bs4" style="text-transform: uppercase;">
                                                                <option value="">Select Barangay</option>
                                                            </select>
                                                            <input type="hidden" id="brgy_name1" name="brgy">
                                                        </div>
                                                        <div class="col-md-4 g-3">
                                                            <label>House No. / Block / Purok</label>
                                                            <input type="text" name="hnum" id="viewdatastudHnum1" class="form-control form-control-sm" placeholder="House No. / Block / Purok" style="text-transform: uppercase;">
                                                        </div>
                                                        <div class="col-md-4 g-3">
                                                            <label>Zipcode</label>
                                                            <input type="text" name="zcode" id="zipcode1" class="form-control form-control-sm" readonly placeholder="Zip Code" readonly style="background-color: #ddd !important; border: 1px solid #aaa;">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group mb-3" style="padding-left: 10px; padding-right: 10px">
                                                    <div class="row">
                                                        <div class="col-md-2 g-3" @if(Auth::guard('web')->user()->role == 15) style="display:none" @endif>
                                                            <label>Religion</label>
                                                            <input type="text" name="religion" class="form-control form-control-sm" id="viewdatastudReligion">
                                                        </div>
                                                        <div class="col-md-4 g-3" @if(!Auth::guard('web')->user()->role == 15) style="display:none" @endif>
                                                            <label>Spouse/Parent/Guardian</label>
                                                            <input type="text" name="spouseparent" class="form-control form-control-sm" id="viewdatastudSpouseParent">
                                                        </div>
                                                        <div class="col-md-6 g-3">
                                                            <label>Address</label>
                                                            <input type="text" name="address" class="form-control form-control-sm" id="viewdatastudAddress1" readonly style="background-color: #ddd !important; border: 1px solid #aaa;">
                                                        </div>
                                                        <div class="col-md-2 g-3">
                                                            <label>Age</label>
                                                            <input type="text" class="form-control form-control-sm" name="age" id="age" readonly style="background-color: #ddd !important; border: 1px solid #aaa;">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                                    <h4>School Records</h4>
                                                </div>

                                                <div class="form-group" style="padding-left: 10px; padding-right: 10px">
                                                    <div class="row">
                                                        <div class="col-md-8 g-3">
                                                            <label>Elementary</label>
                                                            <input type="text" name="elementary" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()">
                                                        </div>
                                                        <div class="col-md-4 g-3">
                                                            <label><span class="badge badge-warning">Year Graduated</span></label>
                                                            <input type="number" name="elemyeargrad" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group" style="padding-left: 10px; padding-right: 10px">
                                                    <div class="row">
                                                        <div class="col-md-8 g-3">
                                                            <label>Secondary</label>
                                                            <input type="text" name="highschool" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()">
                                                        </div>
                                                        <div class="col-md-4 g-3">
                                                            <label><span class="badge badge-warning">Year Graduated</span></label>
                                                            <input type="number" name="highschoolyeargrad" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group" style="padding-left: 10px; padding-right: 10px">
                                                    <div class="row">
                                                        <div class="col-md-8 g-3">
                                                            <label>Tertiary</label>
                                                            <input type="text" name="tertiary" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()">
                                                        </div>
                                                        <div class="col-md-4 g-3">
                                                            <label><span class="badge badge-warning">Year Graduated</span></label>
                                                            <input type="number" name="tertiaryyeargrad" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group mb-3" style="padding-left: 10px; padding-right: 10px">
                                                    <div class="row">
                                                        <div class="col-md-8 g-3">
                                                            <label>Course</label>
                                                            <input type="text" name="tertiarycourse" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()">
                                                        </div>
                                                        <div class="col-md-4 g-3">
                                                            <label><span class="badge badge-warning">Major</span></label>
                                                            <input type="text" name="tertiarymajor" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                                    
                                                </div>

                                                <div class="form-group mt-2" style="padding-left: 10px; padding-right: 10px">
                                                    <div class="row">
                                                        <div class="col-md-12 g-3">
                                                            <label for="masterscourse">Master's Course (<span style="font-style: italic;">if with master's degree/units</span>):</label>
                                                            <input type="text" name="masterscourse" id="masterscourse" class="form-control form-control-sm input-details text-bold" oninput="this.value = this.value.toUpperCase()">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group" style="padding-left: 10px; padding-right: 10px">
                                                    <div class="row">
                                                        <div class="col-md-6 g-3">
                                                            <label for="masterspeciallization">Major/Area of Specialization:</label>
                                                            <input type="text" name="masterspeciallization" id="masterspeciallization" class="form-control form-control-sm input-details text-bold" oninput="this.value = this.value.toUpperCase()">
                                                        </div>
                                                        <div class="col-md-6 g-3">
                                                            <label for="masterschool">School:</label>
                                                            <input type="text" name="masterschool" id="masterschool" class="form-control form-control-sm input-details text-bold" oninput="this.value = this.value.toUpperCase()">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group" style="padding-left: 10px; padding-right: 10px">
                                                    <div class="row">
                                                        <div class="col-md-2 g-3">
                                                            <label for="masternounit">Number of Units:</label>
                                                            <input type="number" name="masternounit" id="masternounit" class="form-control form-control-sm input-details text-bold" oninput="this.value = this.value.toUpperCase()">
                                                        </div>
                                                        <div class="col-md-8 g-3">
                                                            <label for="masteraddress">Address:</label>
                                                            <input type="text" name="masteraddress" id="masteraddress" class="form-control form-control-sm input-details text-bold" oninput="this.value = this.value.toUpperCase()">
                                                        </div>
                                                        <div class="col-md-2 g-3">
                                                            <label for="masterinclyear">Inclusive years:</label>
                                                            <input type="text" name="masterinclyear" id="masterinclyear" class="form-control form-control-sm input-details text-bold" oninput="this.value = this.value.toUpperCase(); formatYear(this)" maxlength="9">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group mt-5" style="padding-left: 10px; padding-right: 10px">
                                                    <div class="row">
                                                        <div class="col-md-12 g-3">
                                                            <label for="doctorcourse">Doctorate's Course (<span style="font-style: italic;">if with Doctorate's degree/units</span>):</label>
                                                            <input type="text" name="doctorcourse" id="doctorcourse" class="form-control form-control-sm input-details text-bold" oninput="this.value = this.value.toUpperCase()">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group" style="padding-left: 10px; padding-right: 10px">
                                                    <div class="row">
                                                        <div class="col-md-6 g-3">
                                                            <label for="mastersCourse">Major/Area of Specialization:</label>
                                                            <input type="text" name="doctorspecialization" id="doctorspecialization" class="form-control form-control-sm input-details text-bold" oninput="this.value = this.value.toUpperCase()">
                                                        </div>
                                                        <div class="col-md-6 g-3">
                                                            <label for="doctorschool">School:</label>
                                                            <input type="text" name="doctorschool" id="doctorschool" class="form-control form-control-sm input-details text-bold" oninput="this.value = this.value.toUpperCase()">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group" style="padding-left: 10px; padding-right: 10px">
                                                    <div class="row">
                                                        <div class="col-md-2 g-3">
                                                            <label for="doctornounit">Number of Units:</label>
                                                            <input type="number" name="doctornounit" id="doctornounit" class="form-control form-control-sm input-details text-bold" oninput="this.value = this.value.toUpperCase()">
                                                        </div>
                                                        <div class="col-md-8 g-3">
                                                            <label for="doctoraddress">Address:</label>
                                                            <input type="text" name="doctoraddress" id="doctoraddress" class="form-control form-control-sm input-details text-bold" oninput="this.value = this.value.toUpperCase()">
                                                        </div>
                                                        <div class="col-md-2 g-3">
                                                            <label for="doctorinclyear">Inclusive years:</label>
                                                            <input type="text" name="doctorinclyear" id="doctorinclyear" class="form-control form-control-sm input-details text-bold" oninput="this.value = this.value.toUpperCase(); formatYear(this)" maxlength="9">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row mt-5 mb-3">
                                                    <div class="col-md-12">
                                                        <button type="submit" class="btn btn-success">
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
            </div>
        </div>
    </div>
    <script>
        var studentAddNewRoute = "{{ route('studentUnderStore') }}";
        var studentAddNewUnderGradeRoute = "{{ route('studentUnderGradStore') }}";

        var provincesRoute = '{{ route("getProvinces", "") }}';
        var citiesRoute = '{{ route("getCities", "") }}';
        var barangaysRoute = '{{ route("getBarangays", "") }}';
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
