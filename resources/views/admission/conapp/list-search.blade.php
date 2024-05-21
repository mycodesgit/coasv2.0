@extends('layouts.master_admission')

@section('title')
COAS - V2.0 || Confirmed/Unconfirm Applicants
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
            <li class="breadcrumb-item active mt-1">Confirmed/Unconfirm Applicants</li>
        </ol>

        <div class="page-header">
            <form method="GET" action="{{ route('srchconfirmList') }}">
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
                <table id="confrmlistTable" class="table table-hover">
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

<div class="modal fade" id="interviewresultexamModal" role="dialog" aria-labelledby="interviewresultexamModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="interviewresultexamModalLabel">Assign Interview Result to Applicant</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="interviewResultForm">
                <div class="modal-body">
                    <input type="hidden" name="id" id="interviewExamId">
                    <input type="hidden" id="campus">

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-6">
                                <label for="interviewresultName"><span class="badge badge-secondary">Applicant Name</span></label>
                                <input type="text" class="form-control form-control-sm" id="interviewresultName" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="interviewresultStrand"><span class="badge badge-secondary">Strand</span></label>
                                <input type="text" class="form-control form-control-sm" id="interviewresultStrand" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-6">
                                <label for="coursePref1"><span class="badge badge-secondary">Course Preference 1</span></label>
                                <input type="text" class="form-control form-control-sm" id="coursePref1" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="coursePref2"><span class="badge badge-secondary">Course Preference 2</span></label>
                                <input type="text" class="form-control form-control-sm" id="coursePref2" readonly>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-6">
                                <label for="interviewresultRating"><span class="badge badge-secondary">Rating</span></label>
                                <input type="number" class="form-control form-control-sm" name="rating" id="interviewresultRating" min="0">
                            </div>
                            <div class="col-md-6">
                                <label for="interviewRemarks"><span class="badge badge-secondary">Remarks</span></label>
                                <select class="form-control form-control-sm" name="remarks" id="interviewRemarks">
                                    <option disabled selected>Select</option>
                                    <option value="1">Interview Completed</option>
                                    <option value="2">Interview Pending</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-2">
                        <div class="form-row">
                            <div class="col-md-12">
                                <label><span class="badge badge-secondary">Course</span></label>
                                <select class="form-control form-control-sm" name="course" id="course" style="text-transform: uppercase;">
                                    <option value="">Select Course Preference</option>
                                    
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-12">
                                <label><span class="badge badge-secondary">Comments</span></label>
                                <textarea class="form-control" name="reason" id="interReason" rows="2"></textarea>
                                <span style="font-size: 9pt; font-weight: normal; font-style: italic; color: #dc3545;">Optional</span>
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

<div class="modal fade" id="pushtoAcceptModal" role="dialog" aria-labelledby="pushtoAcceptModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pushtoAcceptModalLabel">Are you sure you want to Push the Examinee to Confirm List?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="pushtoAcceptForm">
                <div class="modal-body">
                    <input type="text" name="id" id="pushtoAcceptId">
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
    var allAppConfirmRoute = "{{ route('getsrchconfirmList') }}";
    var updateConfirmRoute = "{{ route('save_applicantmod_rating', ['id' => ':id']) }}";
    var pushtoAcceptRoute = '{{ route('examinee_pushAcceptajax',  ['id' => ':id']) }}';
    var appidEncryptRoute = "{{ route('idcrypt') }}";
    var progCampRoute = "{{ route('getCampPrograms') }}";

    var isCampus = '{{ Auth::guard('web')->user()->campus }}';
    var requestedCampus = '{{ request('campus') }}'
</script>


@endsection

@section('script')