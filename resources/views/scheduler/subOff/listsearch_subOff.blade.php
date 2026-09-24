@extends('layouts.master_classScheduler')

@section('title')
CISS V.1.0 || Class Scheduler
@endsection


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
                            <li class="breadcrumb-item mt-1">Class Scheduler</li>
                            <li class="breadcrumb-item active mt-1">Subject Offered</li>
                        </ol>
                    </div>
                </div>
                <!-- Dashboard Header -->
                <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                    <div>
                        <h1 class="h4 fw-bold mb-1">Subject Offering</h1>
                        <p class="text-muted small mb-0">Offer subjects per programs based on curriculum every academic year & semester.</p>
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
                                <form method="GET" action="{{ route('subjectsOffered_search') }}" id="classEnroll">
                                    @csrf

                                    <div class="form-group">
                                        <div class="row g-3">
                                            @if(Auth::guard('web')->user()->role == 0)
                                                <div class="col-md-2">
                                                    <label class="form-label fw-semibold">Campus: <span class="text-danger">*</span></label>
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
                                                <label class="form-label fw-semibold">Academic Year: <span class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm" name="schlyear">
                                                    @foreach($sy as $datasy)
                                                        <option value="{{ $datasy->schlyear }}" {{ request('schlyear') == $datasy->schlyear || ($loop->first && !request('schlyear')) ? 'selected' : '' }}>{{ $datasy->schlyear }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label fw-semibold">Semester: <span class="text-danger">*</span></label>
                                                <select class="form-control  form-control-sm" name="semester">
                                                    <option disabled selected>---Select---</option>
                                                    <option value="1" {{ request('semester') == '1' ? 'selected' : '' }}>First Semester</option>
                                                    <option value="2" {{ request('semester') == '2' ? 'selected' : '' }}>Second Semester</option>
                                                    <option value="3" {{ request('semester') == '3' ? 'selected' : '' }}>Summer</option>
                                                </select>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="d-flex flex-column h-100">
                                                    <label class="form-label fw-semibold opacity-0 d-none d-md-block">Action</label>
                                                    <button type="submit" class="btn btn-success btn-sm btn-block">Search</button>
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
                        <ul class="nav nav-pills bg-light p-2 card-animate rounded-2 d-inline-flex mb-2" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="pills-one-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-one" type="button" role="tab"
                                    aria-controls="pills-one" aria-selected="true">
                                    List of Subject Offered
                                </button>
                            </li>
                            &nbsp;
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-two-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-two" type="button" role="tab"
                                    aria-controls="pills-two" aria-selected="false" tabindex="-1">
                                    Add New Subject to be offer
                                </button>
                            </li>
                            @if(Auth::guard('web')->user()->campus == 'MC')
                                &nbsp;
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="pills-three-tab" data-bs-toggle="pill"
                                        data-bs-target="#pills-three" type="button" role="tab"
                                        aria-controls="pills-three" aria-selected="false" tabindex="-1">
                                        Add New Subject using Template
                                    </button>
                                </li>
                            @endif
                        </ul>
                    </div>
                    <div class="col-md-12">
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-one" role="tabpanel" aria-labelledby="pills-one-tab" tabindex="0">
                                <div class="card card-animate">
                                    <div class="card-header pt-3">
                                        <h6 class="card-title">
                                            <i class="ti ti-server"></i> List of Subject Offered Section
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive p-2">
                                            <table id="subofferedlist" class="table table-hover" style="width: 100%">
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
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-two" role="tabpanel" aria-labelledby="pills-two-tab" tabindex="0">
                                <div class="card">
                                    <div class="card-header pt-3">
                                        <h6 class="card-title">
                                            <i class="ti ti-plus"></i> Add New Subject Offering
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <form method="POST" action="{{ route('subjectsOfferedCreate') }}" id="subjOffer">

                                            @csrf

                                            <input type="hidden" value="{{ request('schlyear') }}" name="schlyear" readonly>
                                            <input type="hidden" value="{{ request('semester') }}" name="semester" readonly>
                                            <input type="hidden" value="{{ Auth::guard('web')->user()->campus }}" name="campus" readonly>
                                            <input type="hidden" value="{{ Auth::guard('web')->user()->id }}" name="postedBy" readonly>
                                            <input type="hidden" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" name="datePosted" readonly>

                                            <div class="mt-1">
                                                <div class="form-group">
                                                    <div class="row g-3">
                                                        <div class="col-md-8">
                                                            <label class="form-label fw-semibold">Subject Name: <span class="text-danger">*</span></label>
                                                            <select class="form-control form-control-sm select2bs4" id="subCode">
                                                                <option disabled selected>---Select---</option>
                                                                @foreach($subjects as $sub)
                                                                    <option value="{{ $sub->sub_code }}" data-sub-code="{{ $sub->sub_code }}" data-lec-unit="{{ $sub->sublecredit }}" data-lab-unit="{{ $sub->sublabcredit }}">{{ $sub->sub_name }} - {{ $sub->sub_title }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <label class="form-label fw-semibold">Subject Code: <span class="text-danger">*</span></label>
                                                            <input type="text" name="subCode" id="subcode" class="form-control form-control-sm" readonly="">
                                                        </div>

                                                        <div class="col-md-4 mt-2">
                                                            <label class="form-label fw-semibold">Lecture Credit: <span class="text-danger">*</span></label>
                                                            <input type="number" name="lecUnit" id="lecUnit" class="form-control form-control-sm" readonly="">
                                                        </div>

                                                        <div class="col-md-4 mt-2">
                                                            <label class="form-label fw-semibold">Laboratory Credit: <span class="text-danger">*</span></label>
                                                            <input type="number" name="labUnit" id="labUnit" class="form-control form-control-sm" readonly="">
                                                        </div>

                                                        <div class="col-md-4 mt-2">
                                                            <label class="form-label fw-semibold">Total Credit: <span class="text-danger">*</span></label>
                                                            <input type="number" name="subUnit" id="subUnit" class="form-control form-control-sm" readonly="">
                                                        </div>

                                                        <div class="col-md-4 mt-2">
                                                            <label class="form-label fw-semibold">Subject Year&Section: <span class="text-danger">*</span></label>
                                                            <select class="form-control form-control-sm select2bs4" name="subSec">
                                                                <option disabled selected>---Select---</option>
                                                                @foreach($class as $classes)
                                                                    <option value="{{ $classes->progAcronym }} {{ $classes->classSection }}">{{ $classes->progAcronym }} {{ $classes->classSection }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="col-md-2 mt-2">
                                                            <label class="form-label fw-semibold">Lecture Fee: <span class="text-danger">*</span></label>
                                                            <input type="number" name="lecFee" id="lecFee" class="form-control form-control-sm" value="0" min="0" readonly>
                                                        </div>

                                                        <div class="col-md-2 mt-2">
                                                            <label class="form-label fw-semibold">Laboratory Fee: <span class="text-danger">*</span></label>
                                                            <input type="number" name="labFee" id="labFee" class="form-control form-control-sm" value="0" min="0" readonly>
                                                        </div>

                                                        <div class="col-md-4 mt-2">
                                                            <label class="form-label fw-semibold">Developmental Fee: <span class="text-danger">*</span></label>
                                                            <input type="number" name="devFee" id="devFee" class="form-control form-control-sm" value="0" min="0" readonly>
                                                        </div>

                                                        <div class="col-md-2 mt-2">
                                                            <label class="form-label fw-semibold">Max Student: <span class="text-danger">*</span></label>
                                                            <input type="number" name="maxstud" class="form-control form-control-sm" value="0" min="0">
                                                        </div>

                                                        <div class="col-md-2 mt-2">
                                                            <label class="form-label fw-semibold">Template: <span class="text-danger">*</span></label>
                                                            <select class="form-control form-control-sm" name="isTemp">
                                                                <option value="Yes">Yes</option>
                                                                <option value="No">No</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-md-2 mt-2">
                                                            <label class="form-label fw-semibold">OJT/Thesis: <span class="text-danger">*</span></label>
                                                            <select class="form-control form-control-sm" name="isOJT" id="isOJT">
                                                                <option value="No">No</option>
                                                                @if(request('semester') == '3')
                                                                    {{-- <option value="YesThesis">Yes, it's Thesis</option> --}}
                                                                    <option value="YesPrac">Yes, it's Practicum</option>
                                                                @endif
                                                                <option value="Yes">Yes, it's OJT</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-md-2 mt-2">
                                                            <label class="form-label fw-semibold">Type: <span class="text-danger">*</span></label>
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
                                                            <label class="form-label fw-semibold">Fund: <span class="text-danger">*</span></label>
                                                            <select class="form-control form-control-sm" id="fundSelect">
                                                                <option value="">No Account</option>
                                                                @foreach($funds as $fund)
                                                                    <option value="{{ $fund->fund_id }}" data-account-name="{{ $fund->account_name }}">{{ $fund->fund_id }} - {{ $fund->account_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="col-md-2 mt-2">
                                                            <label class="form-label fw-semibold">IT Subject: <span class="text-danger">*</span></label>
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
                                </div>
                            </div>

                            <div class="tab-pane fade" id="pills-three" role="tabpanel" aria-labelledby="pills-three-tab" tabindex="0">
                                <div class="card">
                                    <div class="card-header pt-3">
                                        <h6 class="card-title">
                                            <i class="ti ti-file"></i> Add New Subject Offering Templated
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <form method="GET" action="" id="studSubjectShowTemplate">
                                            @csrf

                                            <input type="hidden" name="campus" value="{{ Auth::guard('web')->user()->campus }}">
                                            <input type="hidden" name="schlyear" value="{{ request('schlyear') }}">
                                            <input type="hidden" name="semester" value="{{ request('semester') }}">

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6 mt-3">
                                                        <label>Subject Year&Section: <span class="text-danger">*</span></label>
                                                        <select class="form-control form-control-sm select2bs4" name="subSec" id="subSecSelect">
                                                            <option disabled selected>---Select---</option>
                                                            @foreach($class as $classes)
                                                                <option value="{{ $classes->progAcronym }} {{ $classes->classSection }}" data-prog-code="{{ $classes->progCode }}" data-year="{{ explode('-', $classes->classSection)[0] }}" data-section="{{ $classes->classSection }}" data-prog-acronym="{{ $classes->progAcronym }}">{{ $classes->progAcronym }} {{ $classes->classSection }}</option>
                                                            @endforeach
                                                        </select>
                                                        <input type="hidden" id="hiddenProgYear" name="progYear">
                                                        <input type="hidden" id="hiddenProgYearSection" name="progYearSection">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="form-row">
                                                    <div class="col-md-6">
                                                        <label>&nbsp;</label>
                                                        <button type="submit" class="form-control form-control-sm btn btn-success">Show Subject Offer Template</button>
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
        </div>
    </div>

    <div class="modal fade mt-6" id="editStudSubOfferModal">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editFundModalLabel"><i class="ti ti-pencil"></i> Edit Subject Offer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editStudSubOfferForm" action="{{ route('subjectsOfferedUpdate') }}" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editSubOfferId">
                        <input type="hidden" value="{{ Auth::guard('web')->user()->id }}" name="postedBy" readonly>
                        <input type="hidden" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" name="datePosted" readonly>
                        <div class="mt-1">
                            <div class="form-group">
                                <div class="row g-3">
                                    <div class="col-md-8">
                                        <label class="form-label fw-bold">Select New Subject here: <span class="text-danger">*</span></label>
                                        <select class="form-control form-control-sm select2" id="subCodeEdit">
                                            <option disabled selected>---Select---</option>
                                            @foreach($subjects as $sub)
                                                <option value="{{ $sub->sub_code }}" data-sub-codeedit="{{ $sub->sub_code }}" data-lec-unitedit="{{ $sub->sublecredit }}" data-lab-unitedit="{{ $sub->sublabcredit }}">{{ $sub->sub_name }} - {{ $sub->sub_title }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Subject Code: <span class="text-danger">*</span></label>
                                        <input type="text" name="subCode" id="subcodeEdit" class="form-control form-control-sm" readonly>
                                    </div>

                                    <div class="col-md-4 mt-2">
                                        <label class="form-label fw-bold">Subject Name: <span class="text-danger">*</span></label>
                                        <input type="text" id="subnameEdit"  class="form-control form-control-sm" readonly>
                                    </div>


                                    <div class="col-md-8 mt-2">
                                        <label class="form-label fw-bold">Subject Title: <span class="text-danger">*</span></label>
                                        <input type="text" id="subtitleEdit" class="form-control form-control-sm" readonly>
                                    </div>

                                    <div class="col-md-4 mt-2">
                                        <label class="form-label fw-bold">Lecture Credit: <span class="text-danger">*</span></label>
                                        <input type="number" name="lecUnit" id="lecUnitEdit" class="form-control form-control-sm" readonly>
                                    </div>

                                    <div class="col-md-4 mt-2">
                                        <label class="form-label fw-bold">Laboratory Credit: <span class="text-danger">*</span></label>
                                        <input type="number" name="labUnit" id="labUnitEdit" class="form-control form-control-sm" readonly>
                                    </div>

                                    <div class="col-md-4 mt-2">
                                        <label class="form-label fw-bold">Total Credit: <span class="text-danger">*</span></label>
                                        <input type="number" name="subUnit" id="subUnitEdit" class="form-control form-control-sm" readonly>
                                    </div>

                                    <div class="col-md-4 mt-2">
                                        <label class="form-label fw-bold">Subject Year&Section: <span class="text-danger">*</span></label>
                                        <select class="form-control form-control-sm select2" name="subSec" id="subsecEdit">
                                            <option disabled selected>---Select---</option>
                                            @foreach($class as $classes)
                                                <option value="{{ $classes->progAcronym }} {{ $classes->classSection }}">{{ $classes->progAcronym }} {{ $classes->classSection }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-2 mt-2">
                                        <label class="form-label fw-bold">Lecture Fee: <span class="text-danger">*</span></label>
                                        <input type="number" name="lecFee" id="editlecfee" class="form-control form-control-sm" value="0" min="0">
                                    </div>

                                    <div class="col-md-2 mt-2">
                                        <label class="form-label fw-bold">Laboratory Fee: <span class="text-danger">*</span></label>
                                        <input type="number" name="labFee" id="editlabfee" class="form-control form-control-sm" value="0" min="0">
                                    </div>

                                    <div class="col-md-4 mt-2">
                                        <label class="form-label fw-bold">Developmental Fee: <span class="text-danger">*</span></label>
                                        <input type="number" name="devFee" id="editdevfee" class="form-control form-control-sm" value="0" min="0">
                                    </div>

                                    <div class="col-md-2 mt-2">
                                        <label class="form-label fw-bold">Max Student: <span class="text-danger">*</span></label>
                                        <input type="number" name="maxstud" id="editmaxstud" class="form-control form-control-sm" value="0" min="0">
                                    </div>

                                    <div class="col-md-2 mt-2">
                                        <label class="form-label fw-bold">Template: <span class="text-danger">*</span></label>
                                        <select class="form-control form-control-sm" name="isTemp" id="isTempSelect">
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>

                                    <div class="col-md-2 mt-2">
                                        <label class="form-label fw-bold">OJT/Thesis: <span class="text-danger">*</span></label>
                                        <select class="form-control form-control-sm" name="isOJT" id="isOJTSelect">
                                            <option value="No">No</option>
                                            @if(request('semester') == '3')
                                                {{-- <option value="YesThesis">Yes, it's Thesis</option> --}}
                                                <option value="YesPrac">Yes, it's Practicum</option>
                                            @endif
                                            <option value="Yes">Yes, it's OJT</option>
                                        </select>
                                    </div>

                                    <div class="col-md-2 mt-2">
                                        <label class="form-label fw-bold">Type: <span class="text-danger">*</span></label>
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
                                        <label class="form-label fw-bold">Fund: <span class="text-danger">*</span></label>
                                        <select class="form-control form-control-sm" id="fundSelectEdit">
                                            <option disabled selected> --Select-- </option>
                                            <option value="" id="noAccountOption">No Account</option>
                                            @foreach($funds as $fund)
                                                <option value="{{ $fund->account_name }}" >{{ $fund->fund_id }} - {{ $fund->account_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-2 mt-2">
                                        <label class="form-label fw-bold">IT Subject: <span class="text-danger">*</span></label>
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
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal"><i class="ti ti-restore"></i> Close</button>
                        <button type="button" class="btn btn-success"><i class="ti ti-device-floppy"></i>  Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal for displaying subjects --}}
    <div class="modal fade mt-6" id="subjectsModal" tabindex="-1" role="dialog" aria-labelledby="subjectsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="subjectsModalLabel">Subjects for Selected Year & Section</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
