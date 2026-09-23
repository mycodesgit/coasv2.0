@extends('layouts.master_admission')

@section('title')
CISS V.1.0 || Admission
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
                            <li class="breadcrumb-item mt-1">Admission</li>
                            <li class="breadcrumb-item active mt-1">Examination Results</li>
                        </ol>
                    </div>
                </div>
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                    <div>
                        <h1 class="h4 fw-bold mb-1" style="letter-spacing: -0.02em;">Examination Results</h1>
                        <p class="text-muted small mb-0">View, manage, and process applicant examination results.</p>
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
                                <form method="GET" action="{{ route('resultlist_search') }}">
                                    @csrf

                                    <div class="form-group">
                                        <div class="row g-3">
                                            <div class="col-md-2">
                                                <label class="form-label fw-semibold">Year: <span class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm" id="year" name="year">
                                                    @foreach($curryear as $datacurryear)
                                                        <option>{{ $datacurryear->adyear }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label fw-semibold">Campus: <span class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm" name="campus" id="campus">
                                                    <option value="{{Auth::user()->campus}}">
                                                        @if (Auth::user()->campus == 'MC') Main
                                                            @elseif(Auth::user()->campus == 'VC') Victorias
                                                            @elseif(Auth::user()->campus == 'SCC') San Carlos
                                                            @elseif(Auth::user()->campus == 'HC') Hinigaran
                                                            @elseif(Auth::user()->campus == 'MP') Moises Padilla
                                                            @elseif(Auth::user()->campus == 'IC') Ilog
                                                            @elseif(Auth::user()->campus == 'CA') Candoni
                                                            @elseif(Auth::user()->campus == 'CC') Cauayan
                                                            @elseif(Auth::user()->campus == 'SC') Sipalay
                                                            @elseif(Auth::user()->campus == 'HinC') Hinobaan
                                                            @elseif(Auth::user()->campus == 'VE') Valladolid
                                                        @endif
                                                    </option>
                                                    @if(Auth::user()->role == 0 || (Auth::user()->campus == 'MC' && Auth::user()->role == 1))
                                                        <option value="MC">Main</option>
                                                        <option value="VC">Victorias</option>
                                                        <option value="SCC">San Carlos</option>
                                                        <option value="HC">Hinigaran</option>
                                                        <option value="MP">Moises Padilla</option>
                                                        <option value="IC">Ilog</option>
                                                        <option value="CA">Candoni</option>
                                                        <option value="CC">Cauayan</option>
                                                        <option value="SC">Sipalay</option>
                                                        <option value="HinC">Hinobaan</option>
                                                        <option value="VE">Valladolid</option>
                                                    @else
                                                    @endif
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Strand: <span class="text-danger">*</span></label>
                                                <select class="form-control  form-control-sm" name="strand">
                                                    <option value=""> --Select-- </option>
                                                    @foreach($strand as $datastrand)
                                                        <option value="{{ $datastrand->code }}">{{ $datastrand->strand }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="d-flex flex-column h-100">
                                                    <label class="form-label fw-semibold opacity-0 d-none d-md-block">Action</label>
                                                    <button type="submit" class="btn btn-success btn-sm">Search</button>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="d-flex flex-column h-100">
                                                    <label class="form-label fw-semibold opacity-0 d-none d-md-block">Action</label>
                                                    <button type="button" class="btn btn-outline-warning btn-sm" onclick="refreshPage()">Refresh Page</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <div class="page-header mt-3" style="border-bottom: 1px solid #04401f;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-users"></i> List of Examination Results Section
                                </h6>
                            </div>
                            <div class="card-body">
                                <table id="exresultlistTable" class="table table-hover" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>App ID</th>
                                            <th>Name</th>
                                            <th>Type</th>
                                            <th>Raw Score</th>
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

    <div class="modal fade" id="viewdataresultexamModal" role="dialog" aria-labelledby="viewdataresultexamModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewdataresultexamModalLabel">View Applicant Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editAppDataPersonalinfoForm">
                    <div class="modal-body">
                        <div class="page-header mt-1" style="border-bottom: 1px solid #04401f;">
                            <h4>Applicant Information</h4>
                        </div>
                        <input type="hidden" name="id" id="viewdataresultexamId">
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
                                    <input type="date" class="form-control form-control-sm" name="bday" id="viewdataresultexamBday">
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
                                    <input type="text" name="zcode" id="zipcode" class="form-control form-control-sm" readonly placeholder="Zip Code" style="background-color: #ddd !important; border: 1px solid #aaa;">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label>Address: <span class="text-danger">*</span></label>
                                    <input type="text" name="address" class="form-control form-control-sm" id="viewdatastudAddress" readonly style="background-color: #ddd !important; border: 1px solid #aaa;">
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
                        <button type="submit" class="btn btn-success">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="updateresultexamModal" role="dialog" aria-labelledby="updateresultexamModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateresultexamModalLabel">Update Test Result to Examinee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="updateTstResult">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="updateresultexamId">
                        <input type="hidden" name="rawscoredate" value="{{ \Carbon\Carbon::now() }}">
                        <div class="form-group">
                            <div class="col-md-12">
                                <label>Raw Score: <span class="text-danger">*</span></label>
                                <input type="number" class="form-control form-control-sm" name="raw_score" id="updateresultexamRawScore" min="0">
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <div class="col-md-12">
                                <label>Stanine: <span class="text-danger">*</span></label>
                                <input type="number" name="stanine" class="form-control form-control-sm" id="updateresultexamStanine" min="0" readonly>
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <div class="col-md-12">
                                <label>Remarks: <span class="text-danger">*</span></label>
                                <input type="text" name="percentile" class="form-control form-control-sm" id="updateresultexamPercent" readonly>
                            </div>
                        </div>
                        <div class="form-group bg-gray p-2" style="border-radius: 5px; display: none;" id="secondQualifiersGroup">
                            <div class="col-md-12">
                                <label><span class="badge badge-warning">Check if Second Qualifiers</span></label>
                                <div class="icheck-warning">
                                    <input type="checkbox" id="qualifier" name="qualifier" value="2">
                                    <label for="qualifier">
                                        <h6>Yes Second Qualifiers</h6>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="pushtocnfrmModal" role="dialog" aria-labelledby="pushtocnfrmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pushtocnfrmModalLabel">Are you sure?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="pushtocnfrmForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="pushtocnfrmId">
                        <div class="form-group">
                            <center>
                                <h3>Push the Examinee to Confirm List</h3>
                                <br>
                                <button type="submit" class="btn btn-success"><i class="fas fa-check"></i>  Yes!, Push to Confirm</button>
                            </center>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        var allresultRoute = "{{ route('getsrchexamineeResultList') }}";
        var updateTestResultRoute = "{{ route('examinee_resultmod_save', ['id' => ':id']) }}";
        var allAppUpdateRoute = "{{ route('applicantUpdate', ['id' => ':id']) }}";
        var pushtocnfrmRoute = "{{ route('examinee_confirmPreEnrolmentajax',  ['id' => ':id']) }}";
        var appidEncryptRoute = "{{ route('idcrypt') }}";

        var isCampus = '{{ Auth::guard('web')->user()->campus }}';
        var requestedCampus = '{{ request('campus') }}'

        var provincesRoute = '{{ route("getProvinces", "") }}';
        var citiesRoute = '{{ route("getCities", "") }}';
        var barangaysRoute = '{{ route("getBarangays", "") }}';
    </script>


    {{-- <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelector('input[name="raw_score"]').addEventListener('input', function () {
                var remarksValue = parseFloat(this.value);
                var secondQualifiersGroup = document.getElementById('secondQualifiersGroup');

                if (!isNaN(remarksValue) && remarksValue >= 17) {
                    secondQualifiersGroup.style.display = 'block';
                } else {
                    secondQualifiersGroup.style.display = 'none';
                }
            });
        });

        function refreshPage() {
            location.reload();
        }
    </script> --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const percentileInput = document.querySelector('input[name="percentile"]');
            const secondQualifiersGroup = document.getElementById('secondQualifiersGroup');

            percentileInput.addEventListener('input', function () {
                const value = this.value.trim().toLowerCase();

                if (value === 'failed') {
                    secondQualifiersGroup.style.display = 'block';
                } else {
                    secondQualifiersGroup.style.display = 'none';
                }
            });
        });

        function refreshPage() {
            location.reload();
        }
    </script>

@endsection
