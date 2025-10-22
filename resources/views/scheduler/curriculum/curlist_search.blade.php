@extends('layouts.master_classScheduler')

@section('title')
CISS V.1.0 || Curriculumn
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
            <li class="breadcrumb-item active mt-1">Curriculumn</li>
        </ol>

        <p>
            @if(Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success')}}</div>
            @elseif (Session::has('fail'))
                <div class="alert alert-danger">{{Session::get('fail')}}</div>
            @endif
        </p>

        <div class="page-header">
            <form method="GET" action="{{ route('curRead_search') }}" id="curriculumSearch">
                @csrf

                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                    <h4>Curriculumn</h4>
                </div>

                <div class="mt-1">
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-3">
                                <label><span class="badge badge-secondary">Semester</span></label>
                                <select class="form-control  form-control-sm" name="semester">
                                    <option disabled selected>---Select---</option>
                                    <option value="1">First Semester</option>
                                    <option value="2">Second Semester</option>
                                    <option value="3">Summer</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label><span class="badge badge-secondary">Course</span></label>
                                <select class="form-control form-control-sm select2bs4" name="progCod" id="progCod">
                                    <option disabled selected>Select a course</option>
                                    @foreach ($program as $programs)
                                        <option value="{{ $programs->progCod }}">
                                            {{ $programs->progAcronym }}
                                        </option>
                                    @endforeach
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
        </div>

        <hr>

        <div class="row">
            <div class="col-md-12">
                <div class="card-outline-tabs">
                    <div class="card-header p-0 border-bottom-0">
                        <ul class="nav nav-tabs" id="custom-tabs-five-tab" role="tablist">
                            <li class="nav-item ml-1">
                                <a class="nav-link active text-dark text-bold" id="custom-tabs-one-tab" data-toggle="pill" href="#custom-tabs-one" role="tab" aria-controls="custom-tabs-one" aria-selected="true">Add Subject Offer Per Sem</a>
                            </li>
                            <li class="nav-item ml-1">
                                <a class="nav-link text-dark text-bold" id="custom-tabs-two-tab" data-toggle="pill" href="#custom-tabs-two" role="tab" aria-controls="custom-tabs-two" aria-selected="false">List of Curriculumn</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-four-tabContent">
                            <div class="tab-pane fade show active" id="custom-tabs-one" role="tabpanel" aria-labelledby="custom-tabs-one-tab">
                                <div class="row">
                                    <div class="col-md-3">
                                        <form method="post" action="{{ route('classEnrollCreate') }}"  id="adCurriculumForm">
                                            @csrf
                                            <div class="page-header mt-3" style="border-bottom: 1px solid #04401f;">
                                                <h5>Add</h5>
                                            </div>

                                            <input type="hidden" name="campus" class="form-control  form-control-sm" value="{{ Auth::guard('web')->user()->campus; }}">

                                            <div class="form-group mt-2">
                                                <div class="form-row">
                                                    <div class="mt-2 col-md-12">
                                                        <label><span class="badge badge-secondary">Programs</span></label>
                                                        <select class="form-control form-control-sm" name="progCode" id="">
                                                            @foreach ($curriculum as $programs)
                                                                <option value="{{ $programs->progCod }}">
                                                                    {{ $programs->progAcronym }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="mt-2 col-md-12">
                                                        <label><span class="badge badge-secondary">Semester</span></label>
                                                        <input type="text" class="form-control form-control-sm" name="semester" value="{{ request()->query('semester') }}" readonly>
                                                    </div>

                                                    <div class="mt-2 col-md-12">
                                                        <label><span class="badge badge-secondary">Subjects</span></label>
                                                        <select class="form-control form-control-sm select2bs4" id="subCode">
                                                            <option disabled selected>---Select---</option>
                                                            @foreach($subjects as $sub)
                                                                <option value="{{ $sub->sub_code }}" data-sub-code="{{ $sub->sub_code }}" data-lec-unit="{{ $sub->sublecredit }}" data-lab-unit="{{ $sub->sublabcredit }}">{{ $sub->sub_name }} - {{ $sub->sub_title }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="mt-2 col-md-4">
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
                                                        <label><span class="badge badge-warning">Lecture Fee</span></label>
                                                        <input type="number" name="lecFee" id="lecFee" class="form-control form-control-sm" value="0" min="0" readonly>
                                                    </div>

                                                    <div class="col-md-4 mt-2">
                                                        <label><span class="badge badge-warning">Laboratory Fee</span></label>
                                                        <input type="number" name="labFee" id="labFee" class="form-control form-control-sm" value="0" min="0" readonly>
                                                    </div>

                                                    <div class="col-md-4 mt-2">
                                                        <label><span class="badge badge-warning">Developmental Fee</span></label>
                                                        <input type="number" name="devFee" id="devFee" class="form-control form-control-sm" value="0" min="0" readonly>
                                                    </div>

                                                    <div class="col-md-4 mt-2">
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

                                                    <div class="col-md-4 mt-2">
                                                        <label><span class="badge badge-success">Template</span></label>
                                                        <select class="form-control form-control-sm" name="isTemp">
                                                            <option value="Yes">Yes</option>
                                                            <option value="No">No</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-6 mt-2">
                                                        <label><span class="badge badge-info">Fund</span></label>
                                                        <select class="form-control form-control-sm" id="fundSelect">
                                                            <option value="">No Account</option>
                                                            @foreach($funds as $fund)
                                                                <option value="{{ $fund->fund_id }}" data-account-name="{{ $fund->account_name }}">{{ $fund->fund_id }} - {{ $fund->account_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-6 mt-2">
                                                        <label><span class="badge badge-secondary">IT Subject</span></label>
                                                        <select class="form-control form-control-sm" id="itfee" name="itfee">
                                                            <option value="No">No</option>
                                                            <option value="Yes">Yes, IT Subject</option>
                                                        </select>
                                                    </div>

                                                    <input type="hidden" id="fundIdInput" name="fund" class="form-control form-control-sm" readonly>
                                                    <input type="hidden" id="accountNameInput" name="fundAccount" class="form-control form-control-sm" readonly>

                                                    <div class="col-md-12">
                                                        <label>&nbsp;</label>
                                                        <button type="submit" class="form-control form-control-sm btn btn-primary btn-sm">Save</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="col-md-9">
                                        <div class="table-responsive">
                                            <table id="currTable" class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Code</th>
                                                        <th>Subject</th>
                                                        <th>Lec</th>
                                                        <th>Lab</th>
                                                        <th>Units</th>
                                                        <th>LecFee</th>
                                                        <th>LabFee</th>
                                                        <th>DevFee</th>
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
