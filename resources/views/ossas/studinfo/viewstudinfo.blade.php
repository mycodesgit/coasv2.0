@extends('layouts.master_ossa')

@section('title')
CISS V.1.0 || Ossa
@endsection

@yield('sidemenu')

@section('workspace')
    <div class="row">
        <div class="col-12">
            <div class="mb-6">
                {{-- <h1 class="fs-5 mb-4 d-none d-md-block">Dashboard</h1> --}}
                <div class="card mb-3" style=" background-color: #e9ecef; margin-top: -10px">
                    <div class="card-body">
                        <ol class="breadcrumb" style="margin-bottom: -3px;">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}" class="btn btn-success btn-sm text-light">
                                    <i class="fas fa-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item mt-1">Ossa</li>
                            <li class="breadcrumb-item active mt-1">Student Information</li>
                        </ol>
                    </div>
                </div>
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                    <div>
                        <h1 class="h4 fw-bold mb-1" style="letter-spacing: -0.02em;">Student Information</h1>
                        <p class="text-muted small mb-0">Manage student information.</p>
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-12">
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-search"></i> Search to show data
                                </h6>
                            </div>
                            <div class="card-body">
                                <ul class="nav nav-pills bg-light p-2 mb-3 rounded-2" id="pills-tab" role="tablist">
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

    <div class="modal fade mt-4" id="viewdatastudModal" role="dialog" aria-labelledby="viewdatastudModalLabel" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content border-0 shadow-lg">

                <!-- Modal Header -->
                <div class="modal-header bg-light border-bottom px-4 py-3">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle bg-success bg-opacity-10 p-2 text-success">
                            <i class="fas fa-user-graduate fs-5"></i>
                        </div>
                        <h5 class="modal-title fw-bold text-dark mb-0" id="viewdatastudModalLabel">Student Profile & Records</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body p-4 bg-light-subtle">

                    <!-- Student Banner Card -->
                    <div class="card border-0 shadow-sm rounded-3 mb-4 overflow-hidden" style="background: linear-gradient(135deg, #04401f 0%, #0a6b3a 100%);">
                        <div class="card-body p-4">
                            <div class="row align-items-center g-3">
                                <div class="col-12 col-md-8">
                                    <div class="d-flex align-items-center gap-3 flex-wrap flex-sm-nowrap">
                                        <div class="position-relative flex-shrink-0">
                                            <img src="{{ asset('uilibs/images/user.png') }}"
                                                alt="Student Photo"
                                                class="rounded-circle border border-white border-3 shadow-sm"
                                                style="width: 85px; height: 85px; object-fit: cover;">
                                            <span class="position-absolute bottom-0 end-0 bg-white rounded-circle p-1 shadow-sm"
                                                style="width: 26px; height: 26px; display: flex; align-items: center; justify-content: center; border: 2px solid #04401f;">
                                                <i class="fas fa-camera text-success" style="font-size: 11px;"></i>
                                            </span>
                                        </div>
                                        <div class="text-white">
                                            <h4 class="fw-bold mb-1" id="studentFullName">Student Name</h4>
                                            <p class="mb-2 opacity-75 small" id="studentIdDisplay"><i class="fas fa-id-card me-1"></i>Student ID: N/A</p>
                                            <div class="d-flex gap-2 flex-wrap align-items-center fs-7" id="studentInfoDisplay">
                                                <span class="badge bg-white text-dark bg-opacity-90 fw-normal"><i class="fas fa-graduation-cap me-1 text-success"></i>Not Enrolled</span>
                                                <span class="badge bg-white text-dark bg-opacity-90 fw-normal"><i class="fas fa-calendar-alt me-1 text-primary"></i>Enrolled: N/A</span>
                                                <span class="badge bg-warning text-dark fw-bold"><i class="fas fa-circle me-1" style="font-size: 6px;"></i>Active</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 text-md-end">
                                    <div class="d-flex gap-2 justify-content-md-end"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Tabs -->
                    <ul class="nav nav-pills bg-white p-1 rounded-3 shadow-sm border mb-4" id="pills-tab" role="tablist">
                        <li class="nav-item flex-fill text-center" role="presentation">
                            <button class="nav-link active fw-semibold w-100 py-2" id="pills-tabone-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-tabone" type="button" role="tab"
                                aria-controls="pills-tabone" aria-selected="true">
                                <i class="fas fa-user-edit me-2"></i>Personal & Academic Information
                            </button>
                        </li>
                        <li class="nav-item flex-fill text-center" role="presentation">
                            <button class="nav-link fw-semibold w-100 py-2" id="pills-tabtwo-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-tabtwo" type="button" role="tab"
                                aria-controls="pills-tabtwo" aria-selected="false" tabindex="-1">
                                <i class="fas fa-graduation-cap me-2"></i>Enrollment History
                            </button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" id="pills-tabContent">

                        <!-- TAB 1: STUDENT INFORMATION -->
                        <div class="tab-pane fade show active" id="pills-tabone" role="tabpanel" aria-labelledby="pills-tabone-tab" tabindex="0">
                            <form id="editStudInfoForm">
                                <input type="hidden" name="id" id="viewdatastudIdprim">

                                <!-- Section 1: Basic Identity -->
                                <div class="card shadow-sm rounded-3 mb-4">
                                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                                        <i class="fas fa-id-card text-success"></i>
                                        <h6 class="mb-0 fw-bold text-dark">Basic Identification & Name</h6>
                                    </div>
                                    <div class="card-body p-3 p-md-4">
                                        <div class="row g-3">
                                            <div class="col-12 col-sm-6 col-lg-2">
                                                <label class="form-label small fw-semibold text-secondary">Student ID No.</label>
                                                <input type="text" class="form-control form-control-sm bg-light" name="" id="viewdatastudID" readonly>
                                            </div>
                                            <div class="col-12 col-sm-6 col-lg-2">
                                                <label class="form-label small fw-semibold text-secondary">First Name</label>
                                                <input type="text" name="fname" class="form-control form-control-sm" id="viewdatastudFname">
                                            </div>
                                            <div class="col-12 col-sm-6 col-lg-2">
                                                <label class="form-label small fw-semibold text-secondary">Middle Name</label>
                                                <input type="text" name="mname" class="form-control form-control-sm" id="viewdatastudMname">
                                            </div>
                                            <div class="col-12 col-sm-6 col-lg-2">
                                                <label class="form-label small fw-semibold text-secondary">Last Name</label>
                                                <input type="text" name="lname" class="form-control form-control-sm" id="viewdatastudLname">
                                            </div>
                                            <div class="col-12 col-sm-6 col-lg-2">
                                                <label class="form-label small fw-semibold text-secondary">Ext. Name</label>
                                                <input type="text" name="ext" class="form-control form-control-sm" id="viewdatastudExt" placeholder="e.g. Jr, III">
                                            </div>
                                            <div class="col-12 col-sm-6 col-lg-2">
                                                <label class="form-label small fw-semibold text-secondary">Gender</label>
                                                <select class="form-select form-select-sm" name="gender" id="viewdatastudGender">
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
                                </div>

                                <!-- Section 2: Personal Details & Contact -->
                                <div class="card shadow-sm rounded-3 mb-4">
                                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                                        <i class="fas fa-user-tag text-success"></i>
                                        <h6 class="mb-0 fw-bold text-dark">Personal Details & Contact Information</h6>
                                    </div>
                                    <div class="card-body p-3 p-md-4">
                                        <div class="row g-3">
                                            <div class="col-12 col-sm-6 col-lg-2">
                                                <label class="form-label small fw-semibold text-secondary">Civil Status</label>
                                                <select class="form-select form-select-sm" name="civil_status" id="viewdatastudcivilstat">
                                                    <option disabled selected>Select</option>
                                                    @foreach($civilStatuses as $status)
                                                        <option value="{{ $status->cvlstat_name }}">
                                                            {{ $status->cvlstat_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-12 col-sm-6 col-lg-2">
                                                <label class="form-label small fw-semibold text-secondary">Update Birthday</label>
                                                <input type="date" name="bday" class="form-control form-control-sm" id="viewdatastudBdaynotformat">
                                            </div>
                                            <div class="col-12 col-sm-6 col-lg-2">
                                                <label class="form-label small fw-semibold text-secondary">
                                                    Current Birthday
                                                </label>
                                                <input type="text" class="form-control form-control-sm bg-light" id="viewdatastudBday" readonly>
                                            </div>
                                            <div class="col-12 col-sm-6 col-lg-2">
                                                <label class="form-label small fw-semibold text-secondary">
                                                    Mobile Number
                                                </label>
                                                <input type="text" name="contact" class="form-control form-control-sm" id="viewdatastudMobile">
                                            </div>
                                            <div class="col-12 col-sm-6 col-lg-2">
                                                <label class="form-label small fw-semibold text-secondary">Email Address</label>
                                                <input type="email" name="email" class="form-control form-control-sm" id="viewdatastudEmail">
                                            </div>
                                            <div class="col-12 col-sm-6 col-lg-2">
                                                <label class="form-label small fw-semibold text-secondary">Religion</label>
                                                <input type="text" name="religion" class="form-control form-control-sm" id="viewdatastudReligion">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label small fw-semibold text-secondary">Birth Place</label>
                                                <input type="text" name="pbirth" class="form-control form-control-sm" id="viewdatastudBdayp">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Section 3: Address Information -->
                                <div class="card shadow-sm rounded-3 mb-4">
                                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                                        <i class="fas fa-map-marker-alt text-success"></i>
                                        <h6 class="mb-0 fw-bold text-dark">Address Details</h6>
                                    </div>
                                    <div class="card-body p-3 p-md-4">
                                        <div class="row g-3">
                                            <div class="col-12 col-md-3">
                                                <label class="form-label small fw-semibold text-secondary">Region</label>
                                                <select id="region" class="form-select form-select-sm select2bs4">
                                                    <option value="">Select Region</option>
                                                    @foreach($regions as $region)
                                                        <option value="{{ $region->region_id }}" data-name="{{ $region->name }}">{{ $region->name }}</option>
                                                    @endforeach
                                                </select>
                                                <input type="hidden" id="region_name" name="region">
                                            </div>
                                            <div class="col-12 col-md-3">
                                                <label class="form-label small fw-semibold text-secondary">Province</label>
                                                <select id="province" class="form-select form-select-sm select2bs4">
                                                    <option value="">Select Province</option>
                                                </select>
                                                <input type="hidden" id="province_name" name="province">
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label small fw-semibold text-secondary">City/Municipality</label>
                                                <select id="city" class="form-select form-select-sm select2bs4">
                                                    <option value="">Select City</option>
                                                </select>
                                                <input type="hidden" id="city_name" name="city">
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label small fw-semibold text-secondary">Barangay</label>
                                                <select id="barangay" class="form-select form-select-sm select2bs4">
                                                    <option value="">Select Barangay</option>
                                                </select>
                                                <input type="hidden" id="brgy_name" name="brgy">
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <label class="form-label small fw-semibold text-secondary">House No. / Block / Purok</label>
                                                <input type="text" name="hnum" id="viewdatastudHnum" class="form-control form-control-sm" placeholder="House No. / Block / Purok">
                                            </div>
                                            <div class="col-12 col-md-2">
                                                <label class="form-label small fw-semibold text-secondary">Zipcode</label>
                                                <input type="text" name="zcode" id="zipcode" class="form-control form-control-sm bg-light" readonly placeholder="Zip Code">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label small fw-semibold text-secondary">Calculated Full Address</label>
                                                <input type="text" name="address" class="form-control form-control-sm bg-light" id="viewdatastudAddress" readonly>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label small fw-semibold text-secondary">Fetched Address</label>
                                                <input type="text" class="form-control form-control-sm bg-light" id="viewdatastudAddressfetch" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Section 4: Family Background -->
                                <div class="card shadow-sm rounded-3 mb-4">
                                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                                        <i class="fas fa-users text-success"></i>
                                        <h6 class="mb-0 fw-bold text-dark">Family Background</h6>
                                    </div>
                                    <div class="card-body p-3 p-md-4">
                                        <div class="row g-3">
                                            <div class="col-12 col-sm-6 col-lg-3">
                                                <label class="form-label small fw-semibold text-secondary">Father's Name</label>
                                                <input type="text" class="form-control form-control-sm" name="stud_father" id="viewdatastudfather">
                                            </div>
                                            <div class="col-12 col-sm-6 col-lg-3">
                                                <label class="form-label small fw-semibold text-secondary">Mother's Name</label>
                                                <input type="text" class="form-control form-control-sm" name="stud_mother" id="viewdatastudmother">
                                            </div>
                                            <div class="col-12 col-sm-6 col-lg-2">
                                                <label class="form-label small fw-semibold text-secondary">Guardian's Name</label>
                                                <input type="text" class="form-control form-control-sm" name="stud_guardian" id="viewdatastudguardian">
                                            </div>
                                            <div class="col-12 col-sm-6 col-lg-2">
                                                <label class="form-label small fw-semibold text-secondary">Monthly Income</label>
                                                <input type="text" class="form-control form-control-sm" name="monthly_income" id="viewdatastudprntincome">
                                            </div>
                                            <div class="col-12 col-sm-6 col-lg-2">
                                                <label class="form-label small fw-semibold text-secondary">Guardian Contact No.</label>
                                                <input type="text" class="form-control form-control-sm" name="guardian_contact" id="viewdatastudpcontact">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Section 5: Educational Background -->
                                <div class="card shadow-sm rounded-3 mb-4">
                                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                                        <i class="fas fa-school text-success"></i>
                                        <h6 class="mb-0 fw-bold text-dark">Educational Background</h6>
                                    </div>
                                    <div class="card-body p-3 p-md-4">
                                        <div class="row g-3">
                                            <div class="col-12 col-md-4">
                                                <label class="form-label small fw-semibold text-secondary">Last School Attended</label>
                                                <input type="text" class="form-control form-control-sm" name="lstsch_attended" id="viewdatastudlstschattended">
                                            </div>
                                            <div class="col-12 col-md-2">
                                                <label class="form-label small fw-semibold text-secondary">Last S.Y. Attended</label>
                                                <input type="text" class="form-control form-control-sm" name="lst_sch_attended_year" id="viewdatastudlstschattendedyear">
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <label class="form-label small fw-semibold text-secondary">Last University Attended</label>
                                                <input type="text" class="form-control form-control-sm" name="suc_lst_attended" id="viewdatastudlstsucattnded">
                                            </div>
                                            <div class="col-12 col-md-2">
                                                <label class="form-label small fw-semibold text-secondary">Date of Admission</label>
                                                <input type="text" class="form-control form-control-sm bg-light" name="date_admission" id="viewdatastuddateadmission" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Form Action Button -->
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-success px-4 py-2 shadow-sm fw-semibold">
                                        <i class="fas fa-save me-2"></i>Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- TAB 2: ENROLLMENT HISTORY -->
                        <div class="tab-pane fade" id="pills-tabtwo" role="tabpanel" aria-labelledby="pills-tabtwo-tab" tabindex="0">
                            <div class="card border-0 shadow-sm rounded-3">
                                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                                    <i class="fas fa-history text-success"></i>
                                    <h6 class="mb-0 fw-bold text-dark">Student Enrollment Logs</h6>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table id="ss" class="table table-hover align-middle mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="ps-4">Student ID</th>
                                                    <th>School Year</th>
                                                    <th>Semester</th>
                                                    <th>Course</th>
                                                    <th>Year Level</th>
                                                    <th class="pe-4">Section</th>
                                                </tr>
                                            </thead>
                                            <tbody id="enrollmentHistoryTable">
                                                <!-- Enrollment history dynamically inserted here -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer bg-light border-top px-4 py-2">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>

    <script>
        var studentsReadRoute = "{{ route('student.show') }}";
    </script>
@endsection
