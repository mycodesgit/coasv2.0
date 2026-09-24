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
                            <li class="breadcrumb-item active mt-1">Curriculum Programs</li>
                        </ol>
                    </div>
                </div>
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                    <div>
                        <h1 class="h4 fw-bold mb-1" style="letter-spacing: -0.02em;">Curriculum Programs</h1>
                        <p class="text-muted small mb-0">Manage Curriculum Programs</p>
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-12">
                        <ul class="nav nav-pills bg-light p-2 rounded-2 d-inline-flex mb-2" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="pills-one-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-one" type="button" role="tab"
                                    aria-controls="pills-one" aria-selected="true">
                                    Add Subject Offer Per Sem
                                </button>
                            </li>
                            &nbsp;
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-two-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-two" type="button" role="tab"
                                    aria-controls="pills-two" aria-selected="false" tabindex="-1">
                                    List of Curriculumn
                                </button>
                            </li>
                        </ul>
                    </div>

                    <div class="col-md-4">
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-plus"></i> Add New
                                </h6>
                            </div>
                            <div class="card-body">
                                <form method="post" id="adCurriculumForm">
                                    @csrf

                                    <input type="hidden" name="campus" class="form-control  form-control-sm" value="{{ Auth::guard('web')->user()->campus; }}">

                                    <div class="form-group mt-2">
                                        <div class="row g-3">
                                            <div class="mt-2 col-md-4">
                                                <label class="form-label fw-semibold">Programs: <span class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm" name="progCode" id="progCode">
                                                    @foreach ($curriculum as $programs)
                                                        <option value="{{ $programs->progCod }}">
                                                            {{ $programs->progAcronym }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mt-2 col-md-4">
                                                <label class="form-label fw-semibold">Semester: <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-control-sm" name="semester" value="{{ request()->query('semester') }}" readonly>
                                            </div>

                                            <div class="mt-2 col-md-4">
                                                <label class="form-label fw-semibold">Year Level: <span class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm" name="yrlvl" id="yrlvl">
                                                    <option disabled selected>---Select---</option>
                                                    <option value="1">1st Year</option>
                                                    <option value="2">2nd Year</option>
                                                    <option value="3">3rd Year</option>
                                                    <option value="4">4th Year</option>
                                                </select>
                                            </div>
                                            <input type="hidden" id="combinedValue" name="subSec" value="">

                                            <div class="mt-2 col-md-12">
                                                <label class="form-label fw-semibold">Subjects: <span class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm select2bs4" id="subCode">
                                                    <option disabled selected>---Select---</option>
                                                    @foreach($subjects as $sub)
                                                        <option value="{{ $sub->sub_code }}" data-sub-code="{{ $sub->sub_code }}" data-lec-unit="{{ $sub->sublecredit }}" data-lab-unit="{{ $sub->sublabcredit }}">{{ $sub->sub_name }} - {{ $sub->sub_title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mt-2 col-md-12">
                                                <label class="form-label fw-semibold">Pre-Requisite: <span class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm select2bs4" name="prerequisite">
                                                    <option disabled selected>---Select---</option>
                                                    @foreach($subjects as $sub)
                                                        <option value="{{ $sub->sub_code }}">{{ $sub->sub_name }} - {{ $sub->sub_title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mt-2 col-md-4">
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
                                                <label class="form-label fw-semibold">Lecture Fee: <span class="text-danger">*</span></label>
                                                <input type="number" name="lecFee" id="lecFee" class="form-control form-control-sm" value="0" min="0" readonly>
                                            </div>

                                            <div class="col-md-4 mt-2">
                                                <label class="form-label fw-semibold">Laboratory Fee: <span class="text-danger">*</span></label>
                                                <input type="number" name="labFee" id="labFee" class="form-control form-control-sm" value="0" min="0" readonly>
                                            </div>

                                            <div class="col-md-4 mt-2">
                                                <label class="form-label fw-semibold">Dev. Fee: <span class="text-danger">*</span></label>
                                                <input type="number" name="devFee" id="devFee" class="form-control form-control-sm" value="0" min="0" readonly>
                                            </div>

                                            <div class="col-md-4 mt-2">
                                                <label class="form-label fw-semibold">OJT/Thesis: <span class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm" name="isOJT" id="isOJT">
                                                    <option value="No">No</option>
                                                    @if(request('semester') == '3')
                                                        <option value="YesThesis">Yes, it's Thesis</option>
                                                        <option value="YesPrac">Yes, it's Practicum</option>
                                                    @endif
                                                    <option value="Yes">Yes, it's OJT</option>
                                                </select>
                                            </div>

                                            <div class="col-md-4 mt-2">
                                                <label class="form-label fw-semibold">Template: <span class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm" name="isTemp">
                                                    <option value="Yes">Yes</option>
                                                    <option value="No">No</option>
                                                </select>
                                            </div>

                                            <div class="col-md-6 mt-2">
                                                <label class="form-label fw-semibold">Fund: <span class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm" id="fundSelect">
                                                    <option value="">No Account</option>
                                                    @foreach($funds as $fund)
                                                        <option value="{{ $fund->fund_id }}" data-account-name="{{ $fund->account_name }}">{{ $fund->fund_id }} - {{ $fund->account_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-6 mt-2">
                                                <label class="form-label fw-semibold">IT Subject: <span class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm" id="itfee" name="itfee">
                                                    <option value="No">No</option>
                                                    <option value="Yes">Yes, IT Subject</option>
                                                </select>
                                            </div>

                                            <input type="hidden" id="fundIdInput" name="fund" class="form-control form-control-sm" readonly>
                                            <input type="hidden" id="accountNameInput" name="fundAccount" class="form-control form-control-sm" readonly>

                                            <div class="col-md-12">
                                                <label>&nbsp;</label>
                                                <button type="submit" class="form-control form-control-sm btn btn-success btn-sm">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-one" role="tabpanel" aria-labelledby="pills-one-tab" tabindex="0">
                                <div class="card card-animate">
                                    <div class="card-header pt-3">
                                        <h6 class="card-title">
                                            <i class="ti ti-server"></i> List of subjects
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive p-2">
                                            <table id="currTable" class="table table-hover" style="width: 100%">
                                                <thead>
                                                    <tr>
                                                        <th>YrLevel</th>
                                                        <th>Code</th>
                                                        <th>Subject</th>
                                                        <th>Lec</th>
                                                        <th>Lab</th>
                                                        <th>Units</th>
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
                                            <i class="ti ti-file"></i> List of subjects
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <iframe src="{{ route('currpdfview', ['progCod' => request('progCod')]) }}" width="100%" height="800"></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var curriculumReadRoute = "{{ route('curriculum.show') }}";
        var curriculumCreateRoute = "{{ route('curriculum.store') }}";
    </script>
@endsection
