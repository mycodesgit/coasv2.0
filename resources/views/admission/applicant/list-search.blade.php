@extends('layouts.master_admission')

@section('title')
CISS V.1.0 || Applicant Search List
@endsection

@section('sideheader')
<h4>Admission</h4>
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
            <li class="breadcrumb-item mt-1">Admission</li>
            <li class="breadcrumb-item active mt-1">Applicant List</li>
        </ol>

        <div class="page-header">
            <form method="GET" action="{{ route('srchappList') }}">
                {{ csrf_field() }}

                <div class="custom-container">
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">Year</span></label>
                                <select class="form-control form-control-sm" id="year" name="year">
                                    @foreach($curryear as $datacurryear)
                                        <option>{{ $datacurryear->adyear }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">Campus</span></label>
                                <select class="form-control form-control-sm" name="campus">
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
                                    @if (Auth::user()->role == 0 || Auth::user()->role == 1)
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
                                <label><span class="badge badge-secondary">Strand</span></label>
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
                </div>
            </form>
            <h5>Search Results:  
                <small>
                    <i>Year-<b>{{ request('year') }}</b>,
                        Campus-<b>{{ request('campus') }}</b>,
                        Strand-@if(request('strand'))<b>{{ request('strand') }}</b>@else <b>All Strand</b> @endif
                    </i>
                </small>
            </h5>
        </div>
        <div class="page-header mt-1" style="border-bottom: 1px solid #04401f;"></div>
        <div class="mt-5">
            <div class="">
                <table id="applistTable" class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>App ID</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Contact No.</th>
                            <th>Date Applied</th>
                            <th>Campus</th>
                            <th>Strand</th>
                            <th id="actionColumnHeader" style="display: none;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @php $no = 1; @endphp
                        @foreach($data as $applicant)
                            @if ($applicant->p_status == 1)
                            <tr id="tr-{{ $applicant->id }}">
                                <td>{{ $no++ }}</td>
                                <td>{{ $applicant->admission_id }}</td>
                                <td style="text-transform: uppercase;">
                                    <b>{{$applicant->fname}} 
                                        @if($applicant->mname == null)
                                            @else {{ substr($applicant->mname,0,1) }}.
                                        @endif {{$applicant->lname}}  

                                        @if($applicant->ext == 'N/A') 
                                            @else{{$applicant->ext}}
                                        @endif
                                    </b>
                                </td>
                                <td>
                                    @if ($applicant->type == 1) New 
                                        @elseif($applicant->type == 2) Returnee 
                                        @elseif($applicant->type == 3) Transferee 
                                    @endif
                                </td>
                                <td>{{ $applicant->contact }}</td>
                                <td>{{ $applicant->created_at->format('M. d, Y') }}</td>
                                <td>{{ $applicant->campus }}</td>
                                @if (Auth::user()->campus == request('campus'))
                                <td style="text-align:center;">
                                    <div class="btn-group">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                            </button>
                                            <div class="dropdown-menu">
                                                <a href="{{  route('applicant_edit', encrypt($applicant->id ))}}" class="dropdown-item btn-edit">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                                <button value="{{ $applicant->id }}" class="dropdown-item examinee-delete">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                @endif
                            </tr>
                            @else
                            @endif
                        @endforeach --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="viewdataresultexamModal" role="dialog" aria-labelledby="viewdataresultexamModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewdataresultexamModalLabel">View Applicant Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editAppDataPersonalinfoForm">
                <div class="modal-body">
                    <div class="page-header mt-3" style="border-bottom: 1px solid #04401f;">
                        <h4>Applicant Information</h4>
                    </div>
                    <input type="hidden" name="id" id="viewdataresultexamId">
                    <div class="form-group mt-3">
                        <div class="form-row">
                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">Admission ID</span></label>
                                <input type="text" class="form-control form-control-sm" name="admission_id" id="viewdataresultexamAdID" readonly>
                            </div>
                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">Admission Type</span></label>
                                <input type="text" class="form-control form-control-sm" name="type" id="viewdataresultexamType" readonly>
                            </div>
                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">Preffered Campus</span></label>
                                <input type="text" class="form-control form-control-sm" name="campus" id="viewdataresultexamCampus" readonly>
                            </div>
                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">Firstname</span></label>
                                <input type="text" class="form-control form-control-sm" name="fname" id="viewdataresultexamFname" oninput="this.value = this.value.toUpperCase()">
                            </div>
                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">Middlename</span></label>
                                <input type="text" name="mname" class="form-control form-control-sm" id="viewdataresultexamMname" oninput="this.value = this.value.toUpperCase()">
                            </div>
                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">Lastname</span></label>
                                <input type="text" name="lname" class="form-control form-control-sm" id="viewdataresultexamLname" oninput="this.value = this.value.toUpperCase()">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">Ext.</span></label>
                                <select class="form-control form-control-sm" name="ext" id="viewdataresultexamExt">
                                    <option>N/A</option>
                                    <option value="Jr." @if (old('ext') == "Jr.") {{ 'selected' }} @endif>Jr.</option>
                                    <option value="Sr." @if (old('ext') == "Sr.") {{ 'selected' }} @endif>Sr.</option>
                                    <option value="III" @if (old('ext') == "III") {{ 'selected' }} @endif>III</option>
                                    <option value="IV" @if (old('ext') == "IV") {{ 'selected' }} @endif>IV</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">Gender</span></label>
                                <select class="form-control form-control-sm" name="gender" id="viewdataresultexamGender">
                                    <option value="">Select</option>
                                    <option value="Male" @if (old('gender') == "Male") {{ 'selected' }} @endif>Male</option>
                                    <option value="Female" @if (old('gender') == "Female") {{ 'selected' }} @endif>Female</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">Birthday</span></label>
                                <input type="text" class="form-control form-control-sm" name="" id="viewdataresultexamBday" readonly>
                            </div>
                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">Civil Status</span></label>
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
                                <label><span class="badge badge-secondary">Mobile</span></label>
                                <input type="text" name="" class="form-control form-control-sm" id="viewdataresultexamMobile" readonly>
                            </div>
                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">Email Address</span></label>
                                <input type="text" name="" class="form-control form-control-sm" id="viewdataresultexamEmail" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-12">
                                <label><span class="badge badge-secondary">Address</span></label>
                                <input type="text" name="" class="form-control form-control-sm" id="viewdataresultexamAddress" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="page-header" style="border-bottom: 1px solid #04401f;">
                        <h4>For New Student <span style="font-size: 12pt;color:#ff0000;">(Input for New Applicant only)</span></h4>
                    </div>

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-6">
                                <label><span class="badge badge-secondary">Last School Attended</span></label>
                                <input type="text" name="" class="form-control form-control-sm" id="viewdataresultexamLSA" readonly>
                            </div>

                            <div class="col-md-6">
                                <label><span class="badge badge-secondary">Strand</span></label>
                                <input type="text" name="" class="form-control form-control-sm" id="viewdataresultexamStrand" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="page-header" style="border-bottom: 1px solid #04401f;">
                        <h4>For Transferee <span style="font-size: 12pt;color:#ff0000;">(Input for Transferees only)</span></h4>
                    </div>

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-6">
                                <label><span class="badge badge-secondary">College/University last attended</span></label>
                                <input type="text" name="" class="form-control form-control-sm" id="viewdataresultexamCUla" readonly>
                            </div>

                            <div class="col-md-6">
                                <label><span class="badge badge-secondary">Course</span></label>
                                <input type="text" name="" class="form-control form-control-sm" id="viewdataresultexamCUlac" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="page-header" style="border-bottom: 1px solid #04401f;">
                        <h4>Course Preference</h4>
                    </div>

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-6">
                                <label><span class="badge badge-secondary">Course Preference 1</span></label>
                                <input type="text" name="" class="form-control form-control-sm" id="viewdataresultexamCP1" readonly>
                            </div>

                            <div class="col-md-6">
                                <label><span class="badge badge-secondary">Course Preference 1</span></label>
                                <input type="text" name="" class="form-control form-control-sm" id="viewdataresultexamCP2" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editUploadPhotoModal" role="dialog" aria-labelledby="editUploadPhotoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUploadPhotoModalLabel">Uploaded Photo/Document</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editUploadPhotoProofModal" role="dialog" aria-labelledby="editUploadPhotoProofModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUploadPhotoProofModalLabel">Uploaded Photo/Document</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="pushtoexamModal" role="dialog" aria-labelledby="pushtoexamModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pushtoexamModalLabel">Are you sure you want to Push the Applicant to Examinee List?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="pushtoexamForm">
                <div class="modal-body">
                    <input type="hidden" name="id" id="pushtoexamId">
                    <input type="text" name="email" id="pushtoexamEmail">
                    <div class="form-group">
                        <center><button type="submit" class="btn btn-primary"><i class="fas fa-check"></i>  Yes!, Push to Examinee</button></center>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var allApplicantRoute = "{{ route('getsrchappList') }}";
    var allAppAssignSchedRoute = "{{ route('applicant_schedulemod_save', ['id' => ':id']) }}";
    var allAppUpdateRoute = "{{ route('applicantUpdate', ['id' => ':id']) }}";
    var allAppDeleteRoute = "{{ route('applicant_delete', ['id' => ':id']) }}";
    var pushtoexamRoute = '{{ route('applicant_confirmajax',  ['id' => ':id']) }}';
    var appidEncryptRoute = "{{ route('idcrypt') }}";
    var photoStorage = "{{ asset('storage/') }}";

    var isCampus = '{{ Auth::guard('web')->user()->campus }}';
    var requestedCampus = '{{ request('campus') }}'
</script>

@endsection

@section('script')