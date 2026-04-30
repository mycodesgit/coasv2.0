@extends('layouts.master_faculty')

@section('title')
CISS V.1.0 || Confirmed Applicant
@endsection

@section('workspace')
    <div class="row">
        <div class="col-12">
            <div class="mb-6">
                <h1 class="fs-5 mb-4 d-none d-md-block">Confirmed Applicants</h1>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-search"></i> Search Confirmed Applicants
                                </h6>
                            </div>
                            <div class="card-body">
                                <form method="GET" action="{{ route('confirm.store') }}">
                                    @csrf

                                    <div class="form-group mt-1">
                                        <div class="row g-3">
                                            <div class="col-md-2">
                                                <label>Year: <span class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm" id="year" name="year">
                                                    @foreach($curryear as $datacurryear)
                                                        <option>{{ $datacurryear->adyear }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label>Strand: <span class="text-danger">*</span></label>
                                                <select class="form-control  form-control-sm" name="strand">
                                                    <option value=""> --Select-- </option>
                                                    @foreach($strand as $datastrand)
                                                        <option value="{{ $datastrand->code }}">{{ $datastrand->strand }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-2">
                                                <label>&nbsp;</label>
                                                <button type="submit" class="form-control form-control-sm btn btn-success btn-sm">Search</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <hr>

                                <div class="table-responsive mt-3 p-2">
                                    <table id="confrmlistTable" class="table table-hover" style="width: 100% !important">
                                        <thead>
                                            <tr>
                                                <th>App ID</th>
                                                <th>Name</th>
                                                <th>Type</th>
                                                <th>Contact No.</th>
                                                <th>Remarks</th>
                                                <th>Exam Sched</th>
                                                <th>Campus</th>
                                                <th>Strand</th>
                                                <th id="actionColumnHeader" style="display: none;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewdataresultexamModal" role="dialog" aria-labelledby="viewdataresultexamModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewdataresultexamModalLabel">View Applicant Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="viewdataresultexamId">

                        <div class="page-header mt-1" style="border-bottom: 1px solid #04401f;">
                            <h4>Student Information <span style="font-size: 12pt;color:#ff0000;">(Input for New Applicant only)</span></h4>
                        </div>
                        <div class="form-group mt-3">
                            <div class="row g-3">
                                <div class="col-md-2">
                                    <label>Admission ID: <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm" name="admission_id" id="viewdataresultexamAdID" readonly>
                                </div>
                                <div class="col-md-2">
                                    <label>Admission Type: <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm" name="type" id="viewdataresultexamType" readonly>
                                </div>
                                <div class="col-md-2">
                                    <label>Preffered Campus: <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm" name="campus" id="viewdataresultexamCampus" readonly>
                                </div>
                                <div class="col-md-2">
                                    <label>Firstname: <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm" name="fname" id="viewdataresultexamFname" oninput="this.value = this.value.toUpperCase()">
                                </div>
                                <div class="col-md-2">
                                    <label>Middlename: <span class="text-danger">*</span></label>
                                    <input type="text" name="mname" class="form-control form-control-sm" id="viewdataresultexamMname" oninput="this.value = this.value.toUpperCase()">
                                </div>
                                <div class="col-md-2">
                                    <label>Lastname: <span class="text-danger">*</span></label>
                                    <input type="text" name="lname" class="form-control form-control-sm" id="viewdataresultexamLname" oninput="this.value = this.value.toUpperCase()">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <div class="row g-3">
                                <div class="col-md-2">
                                    <label>Ext.: <span class="text-danger">*</span></label>
                                    <select class="form-control form-control-sm" name="ext" id="viewdataresultexamExt">
                                        <option>N/A</option>
                                        <option value="Jr." @if (old('ext') == "Jr.") {{ 'selected' }} @endif>Jr.</option>
                                        <option value="Sr." @if (old('ext') == "Sr.") {{ 'selected' }} @endif>Sr.</option>
                                        <option value="III" @if (old('ext') == "III") {{ 'selected' }} @endif>III</option>
                                        <option value="IV" @if (old('ext') == "IV") {{ 'selected' }} @endif>IV</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label>Gender: <span class="text-danger">*</span></label>
                                    <select class="form-control form-control-sm" name="gender" id="viewdataresultexamGender">
                                        <option value="">Select</option>
                                        <option value="Male" @if (old('gender') == "Male") {{ 'selected' }} @endif>Male</option>
                                        <option value="Female" @if (old('gender') == "Female") {{ 'selected' }} @endif>Female</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label>Birthday: <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm" name="" id="viewdataresultexamBday" readonly>
                                </div>
                                <div class="col-md-2">
                                    <label>Civil Status: <span class="text-danger">*</span></label>
                                    <select class="form-control form-control-sm" name="civil_status" id="viewdataresultexamcvilstat">
                                        <option disabled selected>Select</option>
                                        <option value="Single" @if (old('civil_status') == "Single") {{ 'selected' }} @endif>Single</option>
                                        <option value="Married" @if (old('civil_status') == "Married") {{ 'selected' }} @endif>Married</option>
                                        <option value="Divorced" @if (old('civil_status') == "Divorced") {{ 'selected' }} @endif>Divorced</option>
                                        <option value="Widowed" @if (old('civil_status') == "Widowed") {{ 'selected' }} @endif>Widowed</option>
                                        <option value="Separated" @if (old('civil_status') == "Separated") {{ 'selected' }} @endif>Separated</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label>Mobile: <span class="text-danger">*</span></label>
                                    <input type="text" name="" class="form-control form-control-sm" id="viewdataresultexamMobile" readonly>
                                </div>
                                <div class="col-md-2">
                                    <label>Email Address: <span class="text-danger">*</span></label>
                                    <input type="text" name="" class="form-control form-control-sm" id="viewdataresultexamEmail" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label>Address: <span class="text-danger">*</span></label>
                                    <input type="text" name="" class="form-control form-control-sm" id="viewdataresultexamAddress" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="page-header mt-3" style="border-bottom: 1px solid #04401f;">
                            <h4>For New Student <span style="font-size: 12pt;color:#ff0000;">(Input for New Applicant only)</span></h4>
                        </div>

                        <div class="form-group mt-3">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label>Last School Attended: <span class="text-danger">*</span></label>
                                    <input type="text" name="" class="form-control form-control-sm" id="viewdataresultexamLSA" readonly>
                                </div>

                                <div class="col-md-6">
                                    <label>Strand: <span class="text-danger">*</span></label>
                                    <input type="text" name="" class="form-control form-control-sm" id="viewdataresultexamStrand" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="page-header mt-3" style="border-bottom: 1px solid #04401f;">
                            <h4>For Transferee <span style="font-size: 12pt;color:#ff0000;">(Input for Transferees only)</span></h4>
                        </div>

                        <div class="form-group mt-3">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label>College/University last attended: <span class="text-danger">*</span></label>
                                    <input type="text" name="" class="form-control form-control-sm" id="viewdataresultexamCUla" readonly>
                                </div>

                                <div class="col-md-6">
                                    <label>Course: <span class="text-danger">*</span></label>
                                    <input type="text" name="" class="form-control form-control-sm" id="viewdataresultexamCUlac" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="page-header mt-3" style="border-bottom: 1px solid #04401f;">
                            <h4>Course Preference</h4>
                        </div>

                        <div class="form-group mt-3">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label>Course Preference 1: <span class="text-danger">*</span></label>
                                    <input type="text" name="" class="form-control form-control-sm" id="viewdataresultexamCP1" readonly>
                                </div>

                                <div class="col-md-6">
                                    <label>Course Preference 1: <span class="text-danger">*</span></label>
                                    <input type="text" name="" class="form-control form-control-sm" id="viewdataresultexamCP2" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="interviewresultexamModal" role="dialog" aria-labelledby="interviewresultexamModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="interviewresultexamModalLabel">Assign Interview Result to Applicant</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="interviewResultForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="interviewExamId">
                        <input type="hidden" id="campus">

                        <div class="form-group">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="interviewresultName">Applicant Name: <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm" id="interviewresultName" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="interviewresultStrand">Strand: <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm" id="interviewresultStrand" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="coursePref1">Course Preference 1: <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm" id="coursePref1" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="coursePref2">Course Preference 2: <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm" id="coursePref2" readonly>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group mt-3">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="interviewresultRating">Rating: <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control form-control-sm" name="rating" id="interviewresultRating" min="0">
                                </div>
                                <div class="col-md-6">
                                    <label for="interviewRemarks">Remarks: <span class="text-danger">*</span></label>
                                    <select class="form-control form-control-sm" name="remarks" id="interviewRemarks">
                                        <option disabled selected>Select</option>
                                        <option value="1">Interview Completed</option>
                                        <option value="2">Interview Pending</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label>Course: <span class="text-danger">*</span></label>
                                    <select class="form-control form-control-sm" name="course" id="course" style="text-transform: uppercase;">
                                        <option value="">Select Course Preference</option>
                                        
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <label>Comments: <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="reason" id="interReason" rows="2"></textarea>
                                    <span style="font-size: 9pt; font-weight: normal; font-style: italic; color: #dc3545;">Optional</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success text-light">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="pushtoAcceptModal" role="dialog" aria-labelledby="pushtoAcceptModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pushtoAcceptModalLabel">Are you sure?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="pushtoAcceptForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="pushtoAcceptId">
                        <div class="form-group">
                            <center>
                                <h3>Push the Applicant to Accepted Applicant List</h3>
                                <br>
                                <button type="submit" class="btn btn-success"><i class="fas fa-check"></i>  Yes!, Push to Accepted Applicants</button>
                            </center>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        var allAppConfirmRoute = "{{ route('confirm.show') }}";
        var updateConfirmRoute = "{{ route('savefacapplicantmod_rating', ['id' => ':id']) }}";
        var pushtoAcceptRoute = "{{ route('examineefacpushAcceptajax',  ['id' => ':id']) }}";
        var appidEncryptRoute = "{{ route('idFacCrypt') }}";
        var progCampRoute = "{{ route('getFacCampPrograms') }}";

        var isCampus = "{{ Auth::guard('faculty')->user()->campus }}";
        var requestedCampus = "{{ Auth::guard('faculty')->user()->campus }}";
    </script>
@endsection
