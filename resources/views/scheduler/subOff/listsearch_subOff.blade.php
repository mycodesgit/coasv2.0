@extends('layouts.master_classScheduler')

@section('title')
CISS V.1.0 || Subject Offered
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

                <div class="mt-1">
                    <div class="form-group">
                        <div class="form-row">
                            @if(Auth::guard('web')->user()->role == 0)
                                <div class="col-md-2">
                                    <label><span class="badge badge-secondary">Campus</span></label>
                                    <select class="form-control form-control-sm" name="campus" id="campus">
                                        @if (Auth::user()->role == 0)
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
                            @endif

                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">Academic Year</span></label>
                                <select class="form-control form-control-sm" name="schlyear">
                                    @foreach($sy as $datasy)
                                        <option value="{{ $datasy->schlyear }}">{{ $datasy->schlyear }}</option>
                                    @endforeach
                                </select>
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
                            <a class="nav-link active text-dark text-bold" id="custom-tabs-one-tab" data-toggle="pill" href="#custom-tabs-one" role="tab" aria-controls="custom-tabs-one" aria-selected="true">List of Subject Offered</a>
                        </li>
                        <li class="nav-item ml-1">
                            <a class="nav-link text-dark text-bold" id="custom-tabs-two-tab" data-toggle="pill" href="#custom-tabs-two" role="tab" aria-controls="custom-tabs-two" aria-selected="false">Add New Subject to be offer</a>
                        </li>
                        @if(Auth::guard('web')->user()->campus == 'MC')
                            <li class="nav-item ml-1">
                                <a class="nav-link text-dark text-bold" id="custom-tabs-three-tab" data-toggle="pill" href="#custom-tabs-three" role="tab" aria-controls="custom-tabs-three" aria-selected="false">Add New Subject using Template</a>
                            </li>
                        @endif
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
                                        <th>LecFee</th>
                                        <th>LabFee</th>
                                        <th>DevFee</th>
                                        <th>Type</th>
                                        <th>Fund</th>
                                        <th>IT Subj</th>
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

                                <div class="mt-1">
                                    <div class="form-group">
                                        <div class="form-row">
                                            <div class="col-md-8">
                                                <label><span class="badge badge-primary">Subject Name</span></label>
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
                                                <label><span class="badge badge-primary">Subject Year&Section</span></label>
                                                <select class="form-control form-control-sm select2bs4" name="subSec">
                                                    <option disabled selected>---Select---</option>
                                                    @foreach($class as $classes)
                                                        <option value="{{ $classes->progAcronym }} {{ $classes->classSection }}">{{ $classes->progAcronym }} {{ $classes->classSection }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-2 mt-2">
                                                <label><span class="badge badge-warning">Lecture Fee</span></label>
                                                <input type="number" name="lecFee" id="lecFee" class="form-control form-control-sm" value="0" min="0" readonly>
                                            </div>

                                            <div class="col-md-2 mt-2">
                                                <label><span class="badge badge-warning">Laboratory Fee</span></label>
                                                <input type="number" name="labFee" id="labFee" class="form-control form-control-sm" value="0" min="0" readonly>
                                            </div>

                                            <div class="col-md-4 mt-2">
                                                <label><span class="badge badge-warning">Developmental Fee</span></label>
                                                <input type="number" name="devFee" id="devFee" class="form-control form-control-sm" value="0" min="0" readonly>
                                            </div>

                                            <div class="col-md-2 mt-2">
                                                <label><span class="badge badge-secondary">Max Student</span></label>
                                                <input type="number" name="maxstud" class="form-control form-control-sm" value="0" min="0">
                                            </div>

                                            <div class="col-md-2 mt-2">
                                                <label><span class="badge badge-success">Template</span></label>
                                                <select class="form-control form-control-sm" name="isTemp">
                                                    <option value="Yes">Yes</option>
                                                    <option value="No">No</option>
                                                </select>
                                            </div>

                                            <div class="col-md-2 mt-2">
                                                <label><span class="badge badge-warning">OJT/Thesis</span></label>
                                                <select class="form-control form-control-sm" name="isOJT" id="isOJT">
                                                    <option value="No">No</option>
                                                    @if(request('semester') == '3')
                                                        <option value="YesThesis">Yes, it's Thesis</option>
                                                        <option value="YesPrac">Yes, it's Practicum</option>
                                                    @endif
                                                    <option value="Yes">Yes, it's OJT</option>
                                                </select>
                                            </div>

                                            <div class="col-md-2 mt-2">
                                                <label><span class="badge badge-danger">Type</span></label>
                                                <select class="form-control form-control-sm" name="isType">
                                                    <option value="No">No</option>
                                                    <option value="Special">Special Class</option>
                                                    <option value="1st Cycle">1st Cycle</option>
                                                    <option value="2nd Cycle">2nd Cycle</option>
                                                    <option value="3rd Cycle">3rd Cycle</option>
                                                    <option value="4th Cycle">4th Cycle</option>
                                                    <option value="SHA 1st">SHA 1st Cycle</option>
                                                    <option value="SHA 2nd">SHA 2nd Cycle</option>
                                                    <option value="SHA 3rd">SHA 3rd Cycle</option>
                                                </select>
                                            </div>

                                            <div class="col-md-2 mt-2">
                                                <label><span class="badge badge-info">Fund</span></label>
                                                <select class="form-control form-control-sm" id="fundSelect">
                                                    <option value="">No Account</option>
                                                    @foreach($funds as $fund)
                                                        <option value="{{ $fund->fund_id }}" data-account-name="{{ $fund->account_name }}">{{ $fund->fund_id }} - {{ $fund->account_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-2 mt-2">
                                                <label><span class="badge badge-secondary">IT Subject</span></label>
                                                <select class="form-control form-control-sm" id="itfee" name="itfee">
                                                    <option value="No">No</option>
                                                    <option value="Yes">Yes, IT Subject</option>
                                                </select>
                                            </div>

                                            <input type="hidden" id="fundIdInput" name="fund" class="form-control form-control-sm" readonly>
                                            <input type="hidden" id="accountNameInput" name="fundAccount" class="form-control form-control-sm" readonly>

                                            <div class="col-md-2">
                                                <label>&nbsp;</label>
                                                <button type="submit" class="form-control form-control-sm btn btn-success btn-sm">Add</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="custom-tabs-three" role="tabpanel" aria-labelledby="custom-tabs-three-tab">
                            <form method="GET" action="" id="studSubjectShowTemplate">
                                @csrf
                                <div class="page-header mt-1" style="border-bottom: 1px solid #04401f;">
                                    <h5>Add Student Subject Template</h5>
                                </div>

                                <input type="hidden" name="campus" value="{{ Auth::guard('web')->user()->campus }}">
                                <input type="hidden" name="schlyear" value="{{ request('schlyear') }}">
                                <input type="hidden" name="semester" value="{{ request('semester') }}">

                                <div class="form-group">
                                    <div class="form-row">
                                        <div class="col-md-6 mt-3">
                                            <label><span class="badge badge-primary">Subject Year&Section</span></label>
                                            <select class="form-control form-control-sm select2bs4" name="subSec" id="subSecSelect">
                                                <option disabled selected>---Select---</option>
                                                @foreach($class as $classes)
                                                    <option value="{{ $classes->progAcronym }} {{ $classes->classSection }}" data-prog-code="{{ $classes->progCode }}" data-year="{{ explode('-', $classes->classSection)[0] }}">{{ $classes->progAcronym }} {{ $classes->classSection }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <label>&nbsp;</label>
                                            <button type="submit" class="form-control form-control-sm btn btn-primary">Show Subject Offer Template</button>
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
                    <input type="hidden" name="id" id="editSubOfferId">
                    <input type="hidden" value="{{ Auth::guard('web')->user()->id }}" name="postedBy" readonly>
                    <input type="hidden" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" name="datePosted" readonly>
                    <div class="container mt-1">
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-8">
                                    <label><span class="badge badge-info">Select New Subject here</span></label>
                                    <select class="form-control form-control-sm select2bs4" id="subCodeEdit">
                                        <option disabled selected>---Select---</option>
                                        @foreach($subjects as $sub)
                                            <option value="{{ $sub->sub_code }}" data-sub-codeedit="{{ $sub->sub_code }}" data-lec-unitedit="{{ $sub->sublecredit }}" data-lab-unitedit="{{ $sub->sublabcredit }}">{{ $sub->sub_name }} - {{ $sub->sub_title }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label><span class="badge badge-secondary">Subject Code</span></label>
                                    <input type="text" name="subCode" id="subcodeEdit" class="form-control form-control-sm" readonly>
                                </div>

                                <div class="col-md-4 mt-2">
                                    <label><span class="badge badge-secondary">Subject Name</span></label>
                                    <input type="text" id="subnameEdit"  class="form-control form-control-sm" readonly>
                                </div>


                                <div class="col-md-8 mt-2">
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
                                    <select class="form-control form-control-sm select2bs4" name="subSec" id="subsecEdit">
                                        <option disabled selected>---Select---</option>
                                        @foreach($class as $classes)
                                            <option value="{{ $classes->progAcronym }} {{ $classes->classSection }}">{{ $classes->progAcronym }} {{ $classes->classSection }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2 mt-2">
                                    <label><span class="badge badge-warning">Lecture Fee</span></label>
                                    <input type="number" name="lecFee" id="editlecfee" class="form-control form-control-sm" value="0" min="0">
                                </div>

                                <div class="col-md-2 mt-2">
                                    <label><span class="badge badge-warning">Laboratory Fee</span></label>
                                    <input type="number" name="labFee" id="editlabfee" class="form-control form-control-sm" value="0" min="0">
                                </div>

                                <div class="col-md-4 mt-2">
                                    <label><span class="badge badge-warning">Developmental Fee</span></label>
                                    <input type="number" name="devFee" id="editdevfee" class="form-control form-control-sm" value="0" min="0">
                                </div>

                                <div class="col-md-2 mt-2">
                                    <label><span class="badge badge-secondary">Max Student</span></label>
                                    <input type="number" name="maxstud" id="editmaxstud" class="form-control form-control-sm" value="0" min="0">
                                </div>

                                <div class="col-md-2 mt-2">
                                    <label><span class="badge badge-success">Template</span></label>
                                    <select class="form-control form-control-sm" name="isTemp" id="isTempSelect">
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>

                                <div class="col-md-2 mt-2">
                                    <label><span class="badge badge-warning">OJT/Thesis</span></label>
                                    <select class="form-control form-control-sm" name="isOJT" id="isOJTSelect">
                                        <option value="No">No</option>
                                        @if(request('semester') == '3')
                                            <option value="YesThesis">Yes, it's Thesis</option>
                                            <option value="YesPrac">Yes, it's Practicum</option>
                                        @endif
                                        <option value="Yes">Yes, it's OJT</option>
                                    </select>
                                </div>

                                <div class="col-md-2 mt-2">
                                    <label><span class="badge badge-danger">Type</span></label>
                                    <select class="form-control form-control-sm" name="isType" id="isTypeSelect">
                                        <option value="No">No</option>
                                        <option value="Special">Special Class</option>
                                        <option value="1st Cycle">1st Cycle</option>
                                        <option value="2nd Cycle">2nd Cycle</option>
                                        <option value="3rd Cycle">3rd Cycle</option>
                                        <option value="4th Cycle">4th Cycle</option>
                                        <option value="SHA 1st">SHA 1st Cycle</option>
                                        <option value="SHA 2nd">SHA 2nd Cycle</option>
                                        <option value="SHA 3rd">SHA 3rd Cycle</option>
                                    </select>
                                </div>

                                <div class="col-md-2 mt-2">
                                    <label><span class="badge badge-info">Fund</span></label>
                                    <select class="form-control form-control-sm" id="fundSelectEdit">
                                        <option disabled selected> --Select-- </option>
                                        <option value="" id="noAccountOption">No Account</option>
                                        @foreach($funds as $fund)
                                            <option value="{{ $fund->account_name }}" >{{ $fund->fund_id }} - {{ $fund->account_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2 mt-2">
                                    <label><span class="badge badge-secondary">IT Subject</span></label>
                                    <select class="form-control form-control-sm" name="itfee" id="itFeeSelect">
                                        <option value="No">No</option>
                                        <option value="Yes">Yes</option>
                                    </select>
                                </div>

                                <div class="col-md-2 mt-2"></div>
                                <div class="col-md-2 mt-2"></div>
                                <div class="col-md-2 mt-2"></div>
                                <div class="col-md-2 mt-2"></div>
                                <div class="col-md-2 mt-2">
                                    <input type="hidden" id="fundEdit" name="fund" class="form-control form-control-sm" readonly>
                                </div>

                                <div class="col-md-2 mt-2">
                                    <input type="hidden" id="fundAccountEdit" name="fundAccount" class="form-control form-control-sm" readonly>
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

{{-- Modal for displaying subjects --}}
<div class="modal fade" id="subjectsModal" tabindex="-1" role="dialog" aria-labelledby="subjectsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="subjectsModalLabel">Subjects for Selected Year & Section</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="modalCampus" value="{{ Auth::guard('web')->user()->campus }}">
                <input type="hidden" id="modalSchlyear" value="{{ request('schlyear') }}">
                <input type="hidden" id="modalSemester" value="{{ request('semester') }}">
                <input type="hidden" id="modalPostedBy" value="{{ Auth::guard('web')->user()->id  }}">
                
                <div class="table-responsive">
                    <table class="table table-striped text-sm" id="subjectsTable">
                        <thead class="">
                            <tr>
                                <th>SubCode</th>
                                <th>Subject</th>
                                <th>Desc</th>
                                <th>LecUnits</th>
                                <th>LabUnits</th>
                                <th>Units</th>
                                <th>LecFee</th>
                                <th>LabFee</th>
                                <th>DevFee</th>
                                <th>ITFee</th>
                                <th>Fund</th>
                                <th>Account</th>
                                <th>IsOJT</th>
                                <th>IsTemp</th>
                                <th>IsType</th>
                            </tr>
                        </thead>
                        <tbody id="subjectsTableBody">
                            {{-- Data will be populated via JavaScript --}}
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="saveSubjectsBtn">Save as Subject Offered</button>
            </div>
        </div>
    </div>
</div>

<script>
    var subOfferedReadRoute = "{{ Auth::guard('web')->user()->role == 15 ? route('getGradsubjectsOfferedRead') : route('getsubjectsOfferedRead') }}";
    var subOfferedNameReadRoute = "{{ route('fetchSubjectName') }}";
    var subOfferedCreateRoute = "{{ route('subjectsOfferedCreate') }}";
    var subOfferedUpdateRoute = "{{ route('subjectsOfferedUpdate') }}";
    var subOfferedDeleteRoute = "{{ route('subjectsOfferedDelete', ['id' => ':id']) }}";

    var isuserRole = '{{ Auth::guard('web')->user()->role == 0 }}';
</script>



@endsection
