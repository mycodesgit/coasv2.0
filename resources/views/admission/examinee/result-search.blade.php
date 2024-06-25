@extends('layouts.master_admission')

@section('title')
CISS v.1.0 || Examinee Search List Result
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
            <li class="breadcrumb-item active mt-1">Examinee Search List Result</li>
        </ol>

        <div class="page-header">
            <form method="GET" action="{{ route('srchexamineeResultList') }}">
                {{ csrf_field() }}

                <div class="custom-container">
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">Year</span></label>
                                <select class="form-control form-control-sm" id="year" name="year"></select>
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
                                    @if (Auth::user()->isAdmin == 0)
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
        <div class="page-header mt-2" style="border-bottom: 1px solid #04401f;"></div>
        <div class="mt-5">
            <div class="">
                <table id="exresultlistTable" class="table table-hover">
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


<div class="modal fade" id="viewdataresultexamModal" role="dialog" aria-labelledby="viewdataresultexamModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewdataresultexamModalLabel">View Examinee Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="">
                <div class="modal-body">
                    <div class="page-header mt-3" style="border-bottom: 1px solid #04401f;">
                        <h4>Applicant Information</h4>
                    </div>
                    <input type="hidden" name="id" id="viewdataresultexamId">
                    <div class="form-group mt-3">
                        <div class="form-row">
                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">Admission ID</span></label>
                                <input type="text" class="form-control form-control-sm" name="" id="viewdataresultexamAdID" readonly>
                            </div>
                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">Admission Type</span></label>
                                <input type="text" class="form-control form-control-sm" name="" id="viewdataresultexamType" readonly>
                            </div>
                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">Preffered Campus</span></label>
                                <input type="text" class="form-control form-control-sm" name="" id="viewdataresultexamCampus" readonly>
                            </div>
                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">Firstname</span></label>
                                <input type="text" class="form-control form-control-sm" name="" id="viewdataresultexamFname" readonly>
                            </div>
                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">Middlename</span></label>
                                <input type="text" name="" class="form-control form-control-sm" id="viewdataresultexamMname" readonly>
                            </div>
                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">Lastname</span></label>
                                <input type="text" name="" class="form-control form-control-sm" id="viewdataresultexamLname" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">Ext.</span></label>
                                <input type="text" class="form-control form-control-sm" name="" id="viewdataresultexamExt" readonly>
                            </div>
                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">Gender</span></label>
                                <input type="text" class="form-control form-control-sm" name="" id="viewdataresultexamGender" readonly>
                            </div>
                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">Birthday</span></label>
                                <input type="text" class="form-control form-control-sm" name="" id="viewdataresultexamBday" readonly>
                            </div>
                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">Age</span></label>
                                <input type="text" class="form-control form-control-sm" name="" id="viewdataresultexamAge" readonly>
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
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="updateresultexamModal" role="dialog" aria-labelledby="updateresultexamModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateresultexamModalLabel">Update Test Result to Examinee</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="updateTstResult">
                <div class="modal-body">
                    <input type="hidden" name="id" id="updateresultexamId">
                    <div class="form-group">
                        <div class="col-md-12">
                            <label><span class="badge badge-secondary">Raw Score</span></label>
                            <input type="number" class="form-control form-control-sm" name="raw_score" id="updateresultexamRawScore" min="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <label><span class="badge badge-secondary">Remarks</span></label>
                            <input type="text" name="percentile" class="form-control form-control-sm" id="updateresultexamPercent" readonly>
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

<div class="modal fade" id="pushtocnfrmModal" role="dialog" aria-labelledby="pushtocnfrmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pushtocnfrmModalLabel">Are you sure you want to Push the Examinee to Confirm List?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="pushtocnfrmForm">
                <div class="modal-body">
                    <input type="hidden" name="id" id="pushtocnfrmId">
                    <div class="form-group">
                        <center><button type="submit" class="btn btn-primary"><i class="fas fa-check"></i>  Yes!, Push to Confirm</button></center>
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
    var allresultRoute = "{{ route('getsrchexamineeResultList') }}";
    var updateTestResultRoute = "{{ route('examinee_resultmod_save', ['id' => ':id']) }}";
    var pushtocnfrmRoute = '{{ route('examinee_confirmPreEnrolmentajax',  ['id' => ':id']) }}';
    var appidEncryptRoute = "{{ route('idcrypt') }}";

    var isCampus = '{{ Auth::guard('web')->user()->campus }}';
    var requestedCampus = '{{ request('campus') }}'
</script>


@endsection

@section('script')