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
                            <li class="breadcrumb-item active mt-1">Student Information</li>
                        </ol>
                    </div>
                </div>
                <div class="row g-3 mb-3 mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                    <h4>Student Information</h4>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <ul class="nav nav-pills bg-light p-2 mt-3 rounded-2" id="pills-tab" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link active" id="pills-one-tab" data-bs-toggle="pill"
                                                    data-bs-target="#pills-one" type="button" role="tab"
                                                    aria-controls="pills-one" aria-selected="true"> <i class="ti ti-user-bolt"></i>
                                                    Undergraduate Students
                                                </button>
                                            </li>
                                            &nbsp;
                                            @if(Auth::guard('web')->user()->campus == 'MC')
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="pills-two-tab" data-bs-toggle="pill"
                                                        data-bs-target="#pills-two" type="button" role="tab"
                                                        aria-controls="pills-two" aria-selected="false" tabindex="-1"> <i class="ti ti-user-code"></i>
                                                        Graduate Students
                                                    </button>
                                                </li>
                                                &nbsp;
                                            @endif
                                        </ul>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="tab-content mt-1" id="pills-tabContent">
                                                    <div class="tab-pane fade show active" id="pills-one" role="tabpanel" aria-labelledby="pills-one-tab" tabindex="0">
                                                        <form id="searchForm">
                                                            <div class="row g-2">
                                                                <div class="col-md-4">
                                                                    <input type="text" id="searchInput" name="searchstud" class="form-control form-control-md" placeholder="Search Student Last Name or First Name or Student ID">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <button type="submit" class="btn btn-success btn-md text-light">Search</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                        <hr>
                                                        <div class="table-responsive">
                                                            <table class="table table-striped" id="">
                                                                <thead class="">
                                                                    <tr>
                                                                        <th>Name</th>
                                                                        <th>StudID</th>
                                                                        <th>Gender</th>
                                                                        <th>Campus</th>
                                                                        <th>Civil Status</th>
                                                                        <th>Course</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="studentsTable">
                                                                    <tr>
                                                                        <td colspan="7" class="text-center">Search to load data</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                            <br>
                                                            <nav>
                                                                <ul class="pagination justify-content-center" id="paginationLinks"></ul>
                                                            </nav>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="pills-two" role="tabpanel" aria-labelledby="pills-two-tab" tabindex="0">
                                                        <form id="searchGradForm">
                                                            <div class="row g-2">
                                                                <div class="col-md-4">
                                                                    <input type="text" id="searchGradInput" name="searchgradstud" class="form-control form-control-md" placeholder="Search Student Last Name or First Name or Student ID">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <button type="submit" class="btn btn-success btn-md text-light">Search</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                        <hr>
                                                        <div class="table-responsive">
                                                            <table class="table table-striped" id="">
                                                                <thead class="">
                                                                    <tr>
                                                                        <th>Name</th>
                                                                        <th>StudentID</th>
                                                                        <th>Gender</th>
                                                                        <th>Campus</th>
                                                                        <th>Civil Status</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="graduatestudentsTable">
                                                                    <tr>
                                                                        <td colspan="7" class="text-center">Search to load data</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                            <br>
                                                            <nav>
                                                                <ul class="pagination justify-content-center" id="paginationGradStudLinks"></ul>
                                                            </nav>
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
    </div>

    <div class="modal fade mt-6" id="viewdatastudModal" role="dialog" aria-labelledby="viewdatastudModalLabel" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-xxl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewdatastudModalLabel">View Student Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card mt-3 border-0" style="background: linear-gradient(135deg, #04401f 0%, #0a6b3a 100%);">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="position-relative">
                                            <img src="{{ asset('uilibs/images/user.png') }}" 
                                                    alt="Student Photo" 
                                                    class="rounded-circle border border-white border-3" 
                                                    style="width: 90px; height: 90px; object-fit: cover;">
                                            <span class="position-absolute bottom-0 end-0 bg-white rounded-circle p-1" 
                                                    style="width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; border: 2px solid #04401f;">
                                                <i class="fas fa-camera text-success" style="font-size: 12px;"></i>
                                            </span>
                                        </div>
                                        <div class="text-white">
                                            <h3 class="fw-bold mb-1" id="studentFullName">Student Name</h3>
                                            <p class="mb-1 opacity-75" id="studentIdDisplay"><i class="fas fa-id-card me-2"></i>Student ID: N/A</p>
                                            <div class="d-flex gap-3 flex-wrap" id="studentInfoDisplay">
                                                <span><i class="fas fa-graduation-cap me-1"></i>Not Enrolled</span>
                                                <span><i class="fas fa-calendar-alt me-1"></i>Enrolled: N/A</span>
                                                <span class="badge bg-warning text-dark"><i class="fas fa-circle me-1" style="font-size: 8px;"></i>Active</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                    <div class="d-flex gap-2 justify-content-md-end">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="nav nav-pills bg-light p-2 mt-3 rounded-2" id="pills-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="pills-tabone-tab" data-bs-toggle="pill"
                                        data-bs-target="#pills-tabone" type="button" role="tab"
                                        aria-controls="pills-tabone" aria-selected="true"> <i class="fas fa"></i>
                                        Information
                                    </button>
                                </li>
                                &nbsp;
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="pills-tabtwo-tab" data-bs-toggle="pill"
                                        data-bs-target="#pills-tabtwo" type="button" role="tab"
                                        aria-controls="pills-tabtwo" aria-selected="false" tabindex="-1"> <i class="fas fa-graduation-cap"></i>
                                        History
                                    </button>
                                </li>
                            </ul>
                            <div class="tab-content mt-3" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-tabone" role="tabpanel" aria-labelledby="pills-tabone-tab" tabindex="0">
                                    <div class="card">
                                        <div class="card-body">
                                            <form id="editStudInfoForm">
                                                <div class="page-header mt-1" style="border-bottom: 1px solid #04401f;">
                                                    <h4>Student Information</h4>
                                                </div>
                                                <input type="hidden" name="id" id="viewdatastudIdprim">
                                                <div class="form-group mt-3">
                                                    <div class="row g-3">
                                                        <div class="col-md-2">
                                                            <label>Student ID No.</label>
                                                            <input type="text" class="form-control form-control-sm" name="" id="viewdatastudID" readonly>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Firstname</label>
                                                            <input type="text" name="fname" class="form-control form-control-sm" id="viewdatastudFname">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Middlename</label>
                                                            <input type="text" name="mname" class="form-control form-control-sm" id="viewdatastudMname">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Lastname</label>
                                                            <input type="text" name="lname" class="form-control form-control-sm" id="viewdatastudLname">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Ext. name</label>
                                                            <input type="text" name="ext" class="form-control form-control-sm" id="viewdatastudExt">
                                                        </div>
                                                        <div class="col-md-2">
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
                
                                                <div class="form-group mt-3">
                                                    <div class="row g-3">
                                                        <div class="col-md-3">
                                                            <label>Region</label>
                                                            <select id="region" class="form-control form-control-sm select2bs4">
                                                                <option value="">Select Region</option>
                                                                @foreach($regions as $region)
                                                                    <option value="{{ $region->region_id }}" data-name="{{ $region->name }}">{{ $region->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <input type="hidden" id="region_name" name="region">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>Province</label>
                                                            <select id="province" class="form-control form-control-sm select2bs4">
                                                                <option value="">Select Province</option>
                                                            </select>
                                                            <input type="hidden" id="province_name" name="province">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>City/Municipality</label>
                                                            <select id="city" class="form-control form-control-sm select2bs4">
                                                                <option value="">Select City</option>
                                                            </select>
                                                            <input type="hidden" id="city_name" name="city">
                                                        </div>
                                                    </div>
                                                </div>
                
                                                <div class="form-group mt-3">
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <label>Barangay</label>
                                                            <select id="barangay" class="form-control form-control-sm select2bs4">
                                                                <option value="">Select Barangay</option>
                                                            </select>
                                                            <input type="hidden" id="brgy_name" name="brgy">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label>House No. / Block / Purok</label>
                                                            <input type="text" name="hnum" id="viewdatastudHnum" class="form-control form-control-sm" placeholder="House No. / Block / Purok">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Zipcode</label>
                                                            <input type="text" name="zcode" id="zipcode" class="form-control form-control-sm" readonly placeholder="Zip Code" readonly style="background-color: #ddd !important; border: 1px solid #aaa;">
                                                        </div>
                                                    </div>
                                                </div>
                
                                                <div class="form-group mt-3">
                                                    <div class="row g-3">
                                                        <div class="col-md-12">
                                                            <label>Birth Place</label>
                                                            <input type="text" name="pbirth" class="form-control form-control-sm" id="viewdatastudBdayp">
                                                        </div>
                                                    </div>
                                                </div>
                
                                                <div class="form-group mt-3">
                                                    <div class="row g-3">
                                                        <div class="col-md-2">
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
                                                        <div class="col-md-2">
                                                            <label>Update Birthday</label>
                                                            <input type="date" name="bday" class="form-control form-control-sm" id="viewdatastudBdaynotformat">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label><span class="badge badge-warning">Birthday</span></label>
                                                            <input type="text" class="form-control form-control-sm" id="viewdatastudBday" readonly>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label><span class="badge badge-success">Mobile</span></label>
                                                            <input type="text" name="contact" class="form-control form-control-sm" id="viewdatastudMobile">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Email Address</label>
                                                            <input type="text" name="email" class="form-control form-control-sm" id="viewdatastudEmail">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Religion</label>
                                                            <input type="text" name="religion" class="form-control form-control-sm" id="viewdatastudReligion">
                                                        </div>
                                                    </div>
                                                </div>
                
                                                <div class="form-group mt-3">
                                                    <div class="row g-3">
                                                        <div class="col-md-12">
                                                            <label>Address</label>
                                                            <input type="text" name="address" class="form-control form-control-sm" id="viewdatastudAddress" readonly style="background-color: #ddd !important; border: 1px solid #aaa;">
                                                        </div>
                                                    </div>
                                                </div>
                
                                                <div class="page-header mt-5" style="border-bottom: 1px solid #04401f;">
                                                    <h4>Family Information</h4>
                                                </div>
                
                                                <div class="form-group mt-3">
                                                    <div class="row g-3">
                                                        <div class="col-md-2">
                                                            <label>Father's Name</label>
                                                            <input type="text" class="form-control form-control-sm" name="stud_father" id="viewdatastudfather">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Mother's Name</label>
                                                            <input type="text" class="form-control form-control-sm" name="stud_mother" id="viewdatastudmother">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Guardian's Name</label>
                                                            <input type="text" class="form-control form-control-sm" name="stud_guardian" id="viewdatastudguardian">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Monthly Income</label>
                                                            <input type="text" class="form-control form-control-sm" name="monthly_income" id="viewdatastudprntincome">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Contact Number</label>
                                                            <input type="text" class="form-control form-control-sm" name="guardian_contact" id="viewdatastudpcontact">
                                                        </div>
                                                    </div>
                                                </div>
                
                                                <div class="page-header mt-5" style="border-bottom: 1px solid #04401f;">
                                                    <h4>Other Information</h4>
                                                </div>
                
                                                <div class="form-group mt-3">
                                                    <div class="row g-3">
                                                        <div class="col-md-4">
                                                            <label>Last School Attended</label>
                                                            <input type="text" class="form-control form-control-sm" name="lstsch_attended" id="viewdatastudlstschattended">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Last S.Y. Attended</label>
                                                            <input type="text" class="form-control form-control-sm" name="lst_sch_attended_year" id="viewdatastudlstschattendedyear">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label>Last University Attended</label>
                                                            <input type="text" class="form-control form-control-sm" name="suc_lst_attended" id="viewdatastudlstsucattnded">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>Date of Admission</label>
                                                            <input type="text" class="form-control form-control-sm" name="date_admission" id="viewdatastuddateadmission" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group mt-5">
                                                    <di class="row">
                                                        <div class="col-md-12">
                                                            <button type="submit" class="btn btn-success">Save changes</button>
                                                        </div>
                                                    </di>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="pills-tabtwo" role="tabpanel" aria-labelledby="pills-tabtwo-tab" tabindex="0">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="page-header mt-1" style="border-bottom: 1px solid #04401f;">
                                                <h4>Enrollment History</h4>
                                            </div>
                                            <table id="ss" class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>StudentID</th>
                                                        <th>School Year</th>
                                                        <th>Semester</th>
                                                        <th>Course</th>
                                                        <th>Year Level</th>
                                                        <th>Section</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="enrollmentHistoryTable">
                                                    <!-- Enrollment history will be inserted here -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
        var studentsReadRoute = "{{ route('student.show') }}";
    </script>
@endsection
