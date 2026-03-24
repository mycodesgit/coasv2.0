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
                <div class="card" style=" background-color: #e9ecef; margin-top: -10px">
                    <div class="card-body">
                        <ol class="breadcrumb" style="margin-bottom: -3px;">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}" class="btn btn-success btn-sm text-light">
                                    <i class="fas fa-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item mt-1">Admission</li>
                            <li class="breadcrumb-item active mt-1">List of Examinees</li>
                        </ol>
                    </div>
                </div>
                <div class="row g-3 mb-3 mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                    <h4>List of Examinees</h4>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <form method="GET" action="{{ route('srchexamineeList') }}">
                                            @csrf

                                            <div class="form-group mt-3">
                                                <div class="row g-3">
                                                    <div class="col-md-2">
                                                        <label>Year: <span class="text-danger">*</span></label>
                                                        <select class="form-control form-control-sm" id="year" name="year">
                                                            @foreach($curryear as $datacurryear)
                                                                <option>{{ $datacurryear->adyear }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label>Campus: <span class="text-danger">*</span></label>
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

                                        <div class="page-header mt-3" style="border-bottom: 1px solid #04401f;"></div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive mt-3 p-2">
                                                    <table id="examlistTable" class="table table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th>App ID</th>
                                                                <th>Name</th>
                                                                <th>Type</th>
                                                                <th>Contact No.</th>
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
                        <button type="submit" class="btn btn-success">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editUploadPhotoModal" role="dialog" aria-labelledby="editUploadPhotoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUploadPhotoModalLabel">Uploaded Photo/Document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editUploadPhotoId">
                        <div class="form-group">
                            <input type="hidden" id="editUploadPhotoDoc" class="form-control form-control-sm" >
                            <img id="uploadedPhoto" class="img-square" width="90%" src="" alt="Image">
                            <p id="noDocumentText" style="text-align: center;" class="big-text">No document uploaded</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editUploadReportCardModal" role="dialog" aria-labelledby="editUploadReportCardModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUploadReportCardModalLabel">Uploaded Report Card / TOR</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editUploadReportCardId">
                        <div class="form-group">
                            <input type="hidden" id="editUploadReportCardDoc" class="form-control form-control-sm" >
                            <img id="uploadedPhotoReportCard" class="img-square" width="90%" src="" alt="Image">
                            <p id="noDocumentTextReportCard" style="text-align: center;" class="big-text">No document uploaded</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editUploadPhotoProofModal" role="dialog" aria-labelledby="editUploadPhotoProofModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUploadPhotoProofModalLabel">Uploaded Photo/Document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editUploadPhotoProofId">
                        <div class="form-group">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="text-align: center; font-size: 13pt;"><i>Selected Proof/Evidence of Disadvantage Situation: </i>
                                            <input type="text" id="uploadedTypeProof" style="border: none; background-color: #fff !important; text-align: left; text-decoration: underline;" class="text-bold" readonly>
                                        </th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="form-group">
                            <input type="hidden" id="editUploadPhotoProofDoc" class="form-control form-control-sm" >
                            <img id="uploadedPhotoProof" class="img-square" width="90%" src="" alt="Image">
                            <p id="noDocumentTextProof" style="text-align: center;" class="big-text">No document uploaded</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="assignresultexamModal" role="dialog" aria-labelledby="assignresultexamModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="assignresultexamModalLabel">Assign Result to Examinee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="admissionAssignResult">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="assignresultexamId">
                        <div class="form-group">
                            <div class="col-md-12">
                                <label>Raw Score: <span class="text-danger">*</span></label>
                                <input type="number" class="form-control form-control-sm" name="raw_score" id="assignresultexamRawScore" min="0">
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <div class="col-md-12">
                                <label>Remarks: <span class="text-danger">*</span></label>
                                <input type="text" name="percentile" class="form-control form-control-sm" id="assignresultexamPercent" readonly>
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

    <div class="modal fade" id="pushtoresultModal" role="dialog" aria-labelledby="pushtoresultModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pushtoresultModalLabel">Are you sure?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="pushtoresultForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="pushtoresultId">
                        <div class="form-group">
                            <center>
                                <h3>Push the Applicant to Examination Result List</h3>
                                <br>
                                <button type="submit" class="btn btn-success"><i class="fas fa-check"></i>  Yes!, Push to Examinee</button>
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

    <div class="modal fade" id="editAssignSchedModal" role="dialog" aria-labelledby="editAssignSchedModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center" id="editAssignSchedModalLabel">Assign Schedule for Admission Test</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editAssignSchedForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editAssignSchedId">
                        <div class="form-group">
                            <center><label style="text-align: center; font-size: 15pt;"><span class="badge badge-primary">Date and Venue for Admission Test</span></label></center>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="text-align: center; font-size: 13pt;">Scheduled Date</th>
                                        <th style="text-align: center; font-size: 13pt;">Scheduled Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" id="schedDate" style="border: none; background-color: #fff !important; text-align: center;" class="text-bold" readonly></td>
                                        <td><input type="text" id="schedTime" style="border: none; background-color: #fff !important; text-align: center;" class="text-bold" readonly></td>
                                    </tr>
                                </tbody>
                            </table>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="text-align: center; font-size: 13pt;">Scheduled Venue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" id="schedVenue" style="border: none; background-color: #fff !important; text-align: center !important;" class="text-bold" disabled></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <hr>
                        <div class="form-group mt-4">
                            <center><label style="text-align: center; font-size: 15pt;"><span class="badge badge-warning">If no Date and Venue Select below</span></label></center>
                        </div>

                        <div class="form-group" id="formdatesched" onchange="updateDateTime()">
                            <label><span class="badge badge-secondary">Date of Admission Test</span></label>
                            <select class="form-control form-control-sm" name="dateID" id="editAssignDateIDs" onchange="updateDateTime()">
                                <option disabled selected> ---Select--- </option>
                                @foreach ($time1 as $dateItem)
                                    @if ($dateItem->slots === 0)
                                        <option value="{{ $dateItem->id }}" disabled class="text-danger">
                                            {{ Carbon\Carbon::parse($dateItem->date . ' ' . $dateItem->time)->format('F j Y g:i A') }} (Slots is Full)
                                        </option>
                                    @else
                                        <option value="{{ $dateItem->id }}">
                                            {{ Carbon\Carbon::parse($dateItem->date . ' ' . $dateItem->time)->format('F j Y g:i A') }} (Available Slots: {{ $dateItem->slots }})
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <input type="hidden" id="selectedDate" name="d_admission" class="form-control form-control-md" placeholder="Selected Date">
                        <input type="hidden" id="selectedTime" name="time" class="form-control form-control-md" placeholder="Selected Time">
                        <input type="hidden" id="selectedDateTimeID" name="dateID" class="form-control form-control-md" placeholder="Selected DateTimeID">

                        <div class="form-group">
                            <label><span class="badge badge-secondary">Venue</span></label>
                            <select class="form-control form-control-sm" name="venue" style="text-transform: uppercase;">
                                <option disabled selected> ---Select--- </option>
                                @foreach ($venue1 as $venueItem)
                                    <option value="{{ $venueItem->venue }}">
                                        {{ $venueItem->venue }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" id="changeTimeSchedButton" class="btn btn-info">Change Time Sched</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        var allExamlicantRoute = "{{ route('getsrchexamineeList') }}";
        var allExamAssignResultRoute = "{{ route('examinee_resultmod_save', ['id' => ':id']) }}";
        var allAppAssignSchedRoute = "{{ route('applicant_schedulemod_save', ['id' => ':id']) }}";
        var allAppUpdateRoute = "{{ route('applicantUpdate', ['id' => ':id']) }}";
        var allExamDeleteRoute = "{{ route('applicant_delete', ['id' => ':id']) }}";
        var pushtoresultRoute = "{{ route('examinee_confirmajax',  ['id' => ':id']) }}";
        var appidEncryptRoute = "{{ route('idcrypt') }}";
        var photoStorage = "{{ asset('storage/') }}";

        var isCampus = "{{ Auth::guard('web')->user()->campus }}";
        var requestedCampus = "{{ request('campus') }}";

        document.getElementById('changeTimeSchedButton').addEventListener('click', function() {
            var formDateSched = document.getElementById('formdatesched');
            // Toggle the display between block and none
            if (formDateSched.style.display === 'none') {
                formDateSched.style.display = 'block';
            } else {
                formDateSched.style.display = 'none';
            }
        });
    </script>

@endsection
