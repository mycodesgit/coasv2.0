@extends('layouts.master_classScheduler')

@section('title')
COAS - V2.0 || Subject Offered
@endsection

@section('sideheader')
<h4>Option</h4>
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
            <li class="breadcrumb-item mt-1">Scheduler</li>
            <li class="breadcrumb-item active mt-1">Option</li>
            <li class="breadcrumb-item active mt-1">Subject Offered</li>
        </ol>

        <p>
            @if(Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success')}}</div>
            @elseif (Session::has('fail'))
                <div class="alert alert-danger">{{Session::get('fail')}}</div>
            @endif
        </p>

        <div class="page-header" style="border-bottom: 1px solid #04401f;">
            <form method="GET" action="{{ route('subjectsOffered_search') }}" id="classEnroll">
                {{ csrf_field() }}

                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                    <h4>Subject Offered</h4>
                </div>

                <div class="container mt-1">
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">Academic Year</span></label>
                                <select class="form-control form-control-sm" id="schlyear" name="syear"></select>
                            </div>

                            <div class="col-md-4">
                                <label><span class="badge badge-secondary">Semester</span></label>
                                <select class="form-control  form-control-sm" name="semester">
                                    <option disabled selected>---Select---</option>
                                    <option value="1">First Semester</option>
                                    <option value="2">Second Semester</option>
                                    <option value="3">Summer</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label>&nbsp;</label>
                                <button type="submit" class="form-control form-control-sm btn btn-success btn-sm">OK</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <h5>Search Results: {{ $totalSearchResults }} 
                <small>
                    <i>Year-<b>{{ request('schlyear') }}</b>,
                        Semester-<b>{{ request('semester') }}</b>,
                        Campus-<b>
                            @if (Auth::guard('web')->user()->campus == 'MC') Main 
                                @elseif(Auth::guard('web')->user()->campus == 'SCC') San Carlos 
                                @elseif(Auth::guard('web')->user()->campus == 'VC') Victorias 
                                @elseif(Auth::guard('web')->user()->campus == 'HC') Hinigaran 
                                @elseif(Auth::guard('web')->user()->campus == 'MP') Moises Padilla 
                                @elseif(Auth::guard('web')->user()->campus == 'HinC') Hinobaan 
                                @elseif(Auth::guard('web')->user()->campus == 'SC') Sipalay 
                                @elseif(Auth::guard('web')->user()->campus == 'IC') Ilog 
                                @elseif(Auth::guard('web')->user()->campus == 'CC') Cauayan 
                            @endif
                        </b>,
                    </i>
                </small>
            </h5>
        </div>

        <div class="mt-3">
            <div class="card-outline-tabs">
                <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs-five-tab" role="tablist">
                        <li class="nav-item ml-1">
                            <a class="nav-link active" id="custom-tabs-one-tab" data-toggle="pill" href="#custom-tabs-one" role="tab" aria-controls="custom-tabs-one" aria-selected="true">View</a>
                        </li>
                        <li class="nav-item ml-1">
                            <a class="nav-link" id="custom-tabs-two-tab" data-toggle="pill" href="#custom-tabs-two" role="tab" aria-controls="custom-tabs-two" aria-selected="false">Add</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-four-tabContent">
                        <div class="tab-pane fade show active" id="custom-tabs-one" role="tabpanel" aria-labelledby="custom-tabs-one-tab">
                            <table id="subofferedlist" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Program</th>
                                        <th>Semester</th>
                                        <th>Subject</th>
                                        <th>Lec</th>
                                        <th>Lab</th>
                                        <th>Units</th>
                                        <th>MaxStud</th>
                                        <th>FundAccount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="custom-tabs-two" role="tabpanel" aria-labelledby="custom-tabs-two-tab">                            
                            <form method="POST" action="{{ route('subjectsOfferedCreate') }}" id="subjOffer">

                                {{ csrf_field() }}

                                <input type="hidden" value="{{ request('schlyear') }}" name="schlyear" readonly>
                                <input type="hidden" value="{{ request('semester') }}" name="semester" readonly>
                                <input type="hidden" value="{{ Auth::guard('web')->user()->campus }}" name="campus" readonly>
                                <input type="hidden" value="{{ Auth::guard('web')->user()->id }}" name="postedBy" readonly>
                                <input type="hidden" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" name="datePosted" readonly>

                                <div class="container mt-1">
                                    <div class="form-group">
                                        <div class="form-row">
                                            <div class="col-md-8">
                                                <label><span class="badge badge-secondary">Subject Name</span></label>
                                                <select class="form-control form-control-sm select2bs4" id="subCode">
                                                    <option disabled selected>---Select---</option>
                                                    @foreach($subjects as $sub)
                                                        <option value="{{ $sub->sub_code }}" data-sub-code="{{ $sub->sub_code }}" data-lec-unit="{{ $sub->sublecredit }}" data-lab-unit="{{ $sub->sublabcredit }}">{{ $sub->sub_name }} - {{ $sub->sub_title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label><span class="badge badge-secondary">Subject Code</span></label>
                                                <input type="text" name="subCode" id="subcode" class="form-control form-control-sm" readonly="">
                                            </div>

                                            <div class="col-md-4 mt-2">
                                                <label><span class="badge badge-secondary">Lecture Credit</span></label>
                                                <input type="number" name="lecUnit" id="lecUnit" class="form-control form-control-sm" readonly="">
                                            </div>

                                            <div class="col-md-4 mt-2">
                                                <label><span class="badge badge-secondary">Laboratory Credit</span></label>
                                                <input type="number" name="labUnit" id="labUnit" class="form-control form-control-sm" readonly="">
                                            </div>

                                            <div class="col-md-4 mt-2">
                                                <label><span class="badge badge-secondary">Total Credit</span></label>
                                                <input type="number" name="subUnit" id="subUnit" class="form-control form-control-sm" readonly="">
                                            </div>

                                            <div class="col-md-4 mt-2">
                                                <label><span class="badge badge-secondary">Subject Year&Section</span></label>
                                                <select class="form-control form-control-sm select2bs4" name="subSec">
                                                    <option disabled selected>---Select---</option>
                                                    @foreach($class as $classes)
                                                        <option value="{{ $classes->progAcronym }} - {{ $classes->classSection }}">{{ $classes->progAcronym }} {{ $classes->classSection }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-4 mt-2">
                                                <label><span class="badge badge-secondary">Lecture Fee</span></label>
                                                <input type="number" name="lecFee" class="form-control form-control-sm" value="0" min="0">
                                            </div>

                                            <div class="col-md-4 mt-2">
                                                <label><span class="badge badge-secondary">Laboratory Fee</span></label>
                                                <input type="number" name="labFee" class="form-control form-control-sm" value="0" min="0">
                                            </div>

                                            <div class="col-md-2 mt-2">
                                                <label><span class="badge badge-secondary">Max Student</span></label>
                                                <input type="number" name="maxstud" class="form-control form-control-sm" value="0" min="0">
                                            </div>

                                            <div class="col-md-2 mt-2">
                                                <label><span class="badge badge-secondary">Template</span></label>
                                                <select class="form-control form-control-sm" name="isTemp">
                                                    <option value="Yes">Yes</option>
                                                    <option value="No">No</option>
                                                </select>
                                            </div>

                                            <div class="col-md-2 mt-2">
                                                <label><span class="badge badge-secondary">OJT</span></label>
                                                <select class="form-control form-control-sm" name="isOJT">
                                                    <option value="No">No</option>
                                                    <option value="Yes">Yes</option>
                                                </select>
                                            </div>

                                            <div class="col-md-2 mt-2">
                                                <label><span class="badge badge-secondary">Type</span></label>
                                                <select class="form-control form-control-sm" name="isType">
                                                    <option value="No">No</option>
                                                    <option value="Special">Special Class</option>
                                                </select>
                                            </div>

                                            <div class="col-md-4 mt-2">
                                                <label><span class="badge badge-secondary">Fund</span></label>
                                                <select class="form-control form-control-sm" id="fundSelect">
                                                    <option value="">No Account</option>
                                                    @foreach($funds as $fund)
                                                        <option value="{{ $fund->fund_id }}" data-account-name="{{ $fund->account_name }}">{{ $fund->fund_id }} - {{ $fund->account_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <input type="text" id="fundIdInput" name="fund" class="form-control form-control-sm" readonly>
                                            <input type="text" id="accountNameInput" name="fundAccount" class="form-control form-control-sm" readonly>

                                            <div class="col-md-2">
                                                <label>&nbsp;</label>
                                                <button type="submit" class="form-control form-control-sm btn btn-success btn-sm">Add</button>
                                            </div>
                                        </div>
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

<div class="modal fade" id="editStudSubOfferModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editFundModalLabel">Edit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editStudSubOfferForm" action="{{ route('subjectsOfferedUpdate') }}" method="POST">
                <div class="modal-body">
                    <input type="text" name="id" id="editSubOfferId">
                    <input type="hidden" value="{{ Auth::guard('web')->user()->id }}" name="postedBy" readonly>
                    <input type="hidden" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" name="datePosted" readonly>
                    <div class="container mt-1">
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-5">
                                    <label><span class="badge badge-secondary">Subject Name</span></label>
                                    <select class="form-control form-control-sm select2bs4" id="subCodeEdit">
                                        <option disabled selected>---Select---</option>
                                        @foreach($subjects as $sub)
                                            <option value="{{ $sub->sub_code }}" data-sub-codeedit="{{ $sub->sub_code }}" data-lec-unitedit="{{ $sub->sublecredit }}" data-lab-unitedit="{{ $sub->sublabcredit }}">{{ $sub->sub_name }} - {{ $sub->sub_title }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label><span class="badge badge-secondary">Subject Name</span></label>
                                    <input type="text" id="subnameEdit"  class="form-control form-control-sm" readonly>
                                </div>

                                <div class="col-md-4">
                                    <label><span class="badge badge-secondary">Subject Code</span></label>
                                    <input type="text" name="subCode" id="subcodeEdit" class="form-control form-control-sm" readonly>
                                </div>

                                <div class="col-md-12 mt-2">
                                    <label><span class="badge badge-secondary">Subject Title</span></label>
                                    <input type="text" id="subtitleEdit" class="form-control form-control-sm" readonly>
                                </div>

                                <div class="col-md-4 mt-2">
                                    <label><span class="badge badge-secondary">Lecture Credit</span></label>
                                    <input type="number" name="lecUnit" id="lecUnitEdit" class="form-control form-control-sm" readonly>
                                </div>

                                <div class="col-md-4 mt-2">
                                    <label><span class="badge badge-secondary">Laboratory Credit</span></label>
                                    <input type="number" name="labUnit" id="labUnitEdit" class="form-control form-control-sm" readonly>
                                </div>

                                <div class="col-md-4 mt-2">
                                    <label><span class="badge badge-secondary">Total Credit</span></label>
                                    <input type="number" name="subUnit" id="subUnitEdit" class="form-control form-control-sm" readonly>
                                </div>

                                <div class="col-md-4 mt-2">
                                    <label><span class="badge badge-secondary">Subject Year&Section</span></label>
                                    <input type="text" name="subSec" id="subsecEdit" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()">
                                </div>

                                <div class="col-md-4 mt-2">
                                    <label><span class="badge badge-secondary">Lecture Fee</span></label>
                                    <input type="number" name="lecFee" id="editlecfee" class="form-control form-control-sm" value="0" min="0">
                                </div>

                                <div class="col-md-4 mt-2">
                                    <label><span class="badge badge-secondary">Laboratory Fee</span></label>
                                    <input type="number" name="labFee" id="editlabfee" class="form-control form-control-sm" value="0" min="0">
                                </div>

                                <div class="col-md-2 mt-2">
                                    <label><span class="badge badge-secondary">Max Student</span></label>
                                    <input type="number" name="maxstud" id="editmaxstud" class="form-control form-control-sm" value="0" min="0">
                                </div>

                                <div class="col-md-2 mt-2">
                                    <label><span class="badge badge-secondary">Template</span></label>
                                    <select class="form-control form-control-sm" name="isTemp">
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>

                                <div class="col-md-2 mt-2">
                                    <label><span class="badge badge-secondary">OJT</span></label>
                                    <select class="form-control form-control-sm" name="isOJT">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>

                                <div class="col-md-2 mt-2">
                                    <label><span class="badge badge-secondary">Type</span></label>
                                    <select class="form-control form-control-sm" name="isType" id="isTypeSelect">
                                        <option value="No">No</option>
                                        <option value="Special">Special Class</option>
                                    </select>
                                </div>

                                <div class="col-md-4 mt-2">
                                    <label><span class="badge badge-secondary">Fund</span></label>
                                    <select class="form-control form-control-sm" id="fundSelectEdit">
                                        <option disabled selected> --Select-- </option>
                                        <option value="">No Account</option>
                                        @foreach($funds as $fund)
                                            <option value="{{ $fund->fund_id }}" data-account-nameedit="{{ $fund->account_name }}">{{ $fund->fund_id }} - {{ $fund->account_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2 mt-2"></div>
                                <div class="col-md-2 mt-2"></div>
                                <div class="col-md-2 mt-2"></div>
                                <div class="col-md-2 mt-2"></div>
                                <div class="col-md-2 mt-2">
                                    <input type="text" id="fundEdit" name="fund" class="form-control form-control-sm" readonly>
                                </div>

                                <div class="col-md-2 mt-2">
                                    <input type="text" id="fundAccountEdit" name="fundAccount" class="form-control form-control-sm" readonly>
                                </div>
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

<script>
    var subOfferedReadRoute = "{{ route('getsubjectsOfferedRead') }}";
    var subOfferedNameReadRoute = "{{ route('fetchSubjectName') }}";
    var subOfferedCreateRoute = "{{ route('subjectsOfferedCreate') }}";
    var subOfferedUpdateRoute = "{{ route('subjectsOfferedUpdate') }}";
</script>


@endsection
