@extends('layouts.master_enrollment')

@section('title')
CISS V.1.0 || Add Applicant
@endsection

@section('sideheader')
<h4>Enrollment</h4>
@endsection

@yield('sidemenu')

@section('workspace')
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
                            <select class="form-control form-control-sm" name="type" id="admissionType">
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
                            <input type="text" name="ext" class="form-control form-control-sm" id="viewdatastudExt">
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
                            <select name="city" class="form-control form-control-sm" id="viewdatastudCity">
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
                            <input type="text" name="religion" class="form-control form-control-sm" id="viewdatastudReligion">
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
                            <input type="text" name="" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class="col-md-4">
                            <label><span class="badge badge-warning">Year Graduated</span></label>
                            <input type="text" name="" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()">
                        </div>
                    </div>
                </div>

                <div class="form-group mt-3">
                    <div class="form-row">
                        <div class="col-md-8">
                            <label><span class="badge badge-secondary">Secondary</span></label>
                            <input type="text" name="" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class="col-md-4">
                            <label><span class="badge badge-warning">Year Graduated</span></label>
                            <input type="text" name="" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()">
                        </div>
                    </div>
                </div>

                <div class="form-group mt-3">
                    <div class="form-row">
                        <div class="col-md-8">
                            <label><span class="badge badge-secondary">Tertiary</span></label>
                            <input type="text" name="" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class="col-md-4">
                            <label><span class="badge badge-warning">Year Graduated</span></label>
                            <input type="text" name="" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()">
                        </div>
                    </div>
                </div>

                <div class="form-group mt-3">
                    <div class="form-row">
                        <div class="col-md-8">
                            <label><span class="badge badge-secondary">Course</span></label>
                            <input type="text" name="" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class="col-md-4">
                            <label><span class="badge badge-warning">Major</span></label>
                            <input type="text" name="" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()">
                        </div>
                    </div>
                </div>

                <div class="page-header mt-5" style="border-bottom: 1px solid #04401f;">
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

                <div class="new-returnee-form" id="newReturneeForm" style="display: none;">
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
                                <label><span class="badge badge-secondary">Type</span></label>
                                <select class="level form-control form-control-sm" name="lst_sch_type" style="text-transform: uppercase;">
                                    <option disabled selected>Select</option>
                                    <option value="PUBLIC">PUBLIC</option>
                                    <option value="PRIVATE">PRIVATE</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="transferee-form" id="transfereeForm" style="display: none;">
                    <div class="page-header" style="border-bottom: 1px solid #04401f;">
                        <h4>For Transferee <span style="font-size: 12pt;color:#ff0000;">(Input for Transferees only)</span></h4>
                    </div>

                    <div class="form-group mt-2">
                        <div class="form-row">
                            <div class="col-md-6">
                                <label><span class="badge badge-secondary">College/University last attended</span></label>
                                <input type="text" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()" name="suc_lst_attended" value="{{old('suc_lst_attended')}}">
                            </div>

                            <div class="col-md-6">
                                <label><span class="badge badge-secondary">Type</span></label>
                                <select class="level form-control form-control-sm" name="lst_sch_type" style="text-transform: uppercase;">
                                    <option disabled selected>Select</option>
                                    <option value="PUBLIC">PUBLIC</option>
                                    <option value="PRIVATE">PRIVATE</option>
                                </select>
                            </div>
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

<script>
    var studentAddNewRoute = "{{ route('studentStore') }}";
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
    document.addEventListener("DOMContentLoaded", function() {
        var admissionType = document.getElementById('admissionType');
        var newReturneeForm = document.getElementById('newReturneeForm');
        var transfereeForm = document.getElementById('transfereeForm');

        toggleFormSections(admissionType.value);

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
@endsection
