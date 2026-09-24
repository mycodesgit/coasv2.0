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
                            <li class="breadcrumb-item active mt-1">Configure Admission</li>
                        </ol>
                    </div>
                </div>
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                    <div>
                        <h1 class="h4 fw-bold mb-1" style="letter-spacing: -0.02em;">Configure Admission</h1>
                        <p class="text-muted small mb-0">View, manage admission statuses across all programs.</p>
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-12">
                        <ul class="nav nav-pills bg-light p-2 rounded-2 d-inline-flex mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="pills-one-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-one" type="button" role="tab"
                                    aria-controls="pills-one" aria-selected="true">
                                    Programs
                                </button>
                            </li>
                            &nbsp;
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-two-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-two" type="button" role="tab"
                                    aria-controls="pills-two" aria-selected="false" tabindex="-1">
                                    Strand
                                </button>
                            </li>
                            &nbsp;
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-three-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-three" type="button" role="tab"
                                    aria-controls="pills-three" aria-selected="false" tabindex="-1">
                                    Admission Date
                                </button>
                            </li>
                            &nbsp;
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-four-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-four" type="button" role="tab"
                                    aria-controls="pills-four" aria-selected="false" tabindex="-1">
                                    Time Schedule
                                </button>
                            </li>
                            &nbsp;
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-five-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-five" type="button" role="tab"
                                    aria-controls="pills-five" aria-selected="false" tabindex="-1">
                                    Venue
                                </button>
                            </li>
                            &nbsp;
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-six-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-six" type="button" role="tab"
                                    aria-controls="pills-six" aria-selected="false" tabindex="-1">
                                    Year
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content mt-3" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-one" role="tabpanel" aria-labelledby="pills-one-tab" tabindex="0">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <div class="card card-animate">
                                            <div class="card-header pt-3">
                                                <h6 class="card-title">
                                                    <i class="ti ti-plus"></i> Add Programs
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <form method="post" action="{{ route('add_Program') }}" id="adProg">
                                                    @csrf

                                                    <input type="hidden" name="campus" class="form-control  form-control-sm" value="{{ request('campus') }}">

                                                    <div class="form-group">
                                                        <div class="row g-3">
                                                            <div class="col-md-12">
                                                                <label class="form-label fw-semibold">Program Code: <span class="text-danger">*</span></label>
                                                                <input type="text" name="code" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()">
                                                            </div>

                                                            <div class="col-md-12">
                                                                <label class="form-label fw-semibold">Program Name: <span class="text-danger">*</span></label>
                                                                <input type="text" name="program" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()">
                                                            </div>

                                                            <div class="col-md-12">
                                                                <label>&nbsp;</label>
                                                                <button type="submit" class="btn btn-success btn-sm btn-block">Save</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="card card-animate">
                                            <div class="card-header pt-3">
                                                <h6 class="card-title">
                                                    <i class="ti ti-list"></i> List of Programs
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive mt-1 p-2">
                                                    <table id="confprog" class="table table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th>Code</th>
                                                                <th>Program</th>
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

                            <div class="tab-pane fade" id="pills-two" role="tabpanel" aria-labelledby="pills-two-tab" tabindex="0">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <div class="card">
                                            <div class="card-header pt-3">
                                                <h6 class="card-title">
                                                    <i class="ti ti-plus"></i> Add Strand
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <form method="post" action="{{ route('add_Strand') }}" id="adStrand">
                                                    @csrf

                                                    <input type="hidden" name="campus" class="form-control  form-control-sm" value="{{ request('campus') }}">

                                                    <div class="form-group">
                                                        <div class="row g-2">
                                                            <div class="col-md-12">
                                                                <label class="form-label fw-semibold">Strand Code: <span class="text-danger">*</span></label>
                                                                <input type="text" name="code" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()">
                                                            </div>

                                                            <div class="col-md-12">
                                                                <label class="form-label fw-semibold">Strand Name: <span class="text-danger">*</span></label>
                                                                <input type="text" name="strand" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()">
                                                            </div>

                                                            <div class="col-md-12">
                                                                <label>&nbsp;</label>
                                                                <button type="submit" class="btn btn-success btn-sm btn-block">Save</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="card">
                                            <div class="card-header pt-3">
                                                <h6 class="card-title">
                                                    <i class="ti ti-list"></i> List of Strand
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive mt-1 p-2">
                                                    <table id="confstrand" class="table table-hover" style="width: 100% !important">
                                                        <thead>
                                                            <tr>
                                                                <th>Code</th>
                                                                <th>Strand</th>
                                                                <th style="text-align: center !important;">Action</th>
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

                            <div class="tab-pane fade" id="pills-three" role="tabpanel" aria-labelledby="pills-three-tab" tabindex="0">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <div class="card">
                                            <div class="card-header pt-3">
                                                <h6 class="card-title">
                                                    <i class="ti ti-plus"></i> Add Admission Date
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <form method="post" action="{{ route('add_admission_date') }}" id="adDateCon">
                                                    @csrf

                                                    <input type="hidden" name="campus" class="form-control  form-control-sm" value="{{ request('campus') }}">

                                                    <div class="form-group">
                                                        <div class="form-row">
                                                            <div class="col-md-12">
                                                                <label class="form-label fw-semibold">Admission Date: <span class="text-danger">*</span></label>
                                                                <input type="date" name="date" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()">
                                                            </div>

                                                            <div class="col-md-12">
                                                                <label>&nbsp;</label>
                                                                <button type="submit" class="btn btn-success btn-sm btn-block">Save</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="card">
                                            <div class="card-header pt-3">
                                                <h6 class="card-title">
                                                    <i class="ti ti-list"></i> List of Admission Date
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive mt-1 p-2">
                                                    <table id="adDate" class="table table-hover" style="width: 100% !important">
                                                        <thead>
                                                            <tr>
                                                                <th>Campus</th>
                                                                <th>Date</th>
                                                                <th style="text-align: center !important;">Action</th>
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

                            <div class="tab-pane fade" id="pills-four" role="tabpanel" aria-labelledby="pills-four-tab" tabindex="0">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <div class="card">
                                            <div class="card-header pt-3">
                                                <h6 class="card-title">
                                                    <i class="ti ti-plus"></i> Add Time Schedule
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <form method="post" action="{{ route('add_admission_time') }}" id="adTimeCon">
                                                    @csrf

                                                    <input type="hidden" name="campus" class="form-control  form-control-sm" value="{{ request('campus') }}">

                                                    <div class="form-group">
                                                        <div class="row g-3">
                                                            <div class="col-md-12" data-refresh="dateDropdown">
                                                                <label class="form-label fw-semibold">Date: <span class="text-danger">*</span></label>
                                                                <select class="form-control form-control-sm" name="date" id="dateDropdown" style="text-transform: uppercase;">

                                                                </select>
                                                            </div>

                                                            <div class="mt-3 col-md-12">
                                                                <label class="form-label fw-semibold">Time: <span class="text-danger">*</span></label>
                                                                <input type="time" name="time" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()">
                                                            </div>

                                                            <div class="mt-3 col-md-12">
                                                                <label class="form-label fw-semibold">Slots: <span class="text-danger">*</span></label>
                                                                <input type="number" name="slots" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()">
                                                            </div>

                                                            <div class="col-md-12">
                                                                <label>&nbsp;</label>
                                                                <button type="submit" class="btn btn-success btn-sm btn-block">Save</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="card">
                                            <div class="card-header pt-3">
                                                <h6 class="card-title">
                                                    <i class="ti ti-list"></i> List of Time Schedule
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive mt-1 p-2">
                                                    <table id="adTime" class="table table-hover" style="width: 100% !important">
                                                        <thead>
                                                            <tr>
                                                                <th>Campus</th>
                                                                <th>Date</th>
                                                                <th>Time</th>
                                                                <th>Slots</th>
                                                                <th style="text-align: center !important;">Action</th>
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

                            <div class="tab-pane fade" id="pills-five" role="tabpanel" aria-labelledby="pills-five-tab" tabindex="0">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <div class="card">
                                            <div class="card-header pt-3">
                                                <h6 class="card-title">
                                                    <i class="ti ti-plus"></i> Add Venue
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <form method="post" action="{{ route('add_admission_venue') }}" id="adVenueCon">
                                                    @csrf

                                                    <input type="hidden" name="campus" class="form-control  form-control-sm" value="{{ request('campus') }}">

                                                    <div class="form-group">
                                                        <div class="row g-3">
                                                            <div class="col-md-12">
                                                                <label class="form-label fw-semibold">Admission Year: <span class="text-danger">*</span></label>
                                                                <select class="form-control form-control-sm" name="adyear">
                                                                    @foreach($curryear as $datacurryear)
                                                                        <option>{{ $datacurryear->adyear }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="mt-3 col-md-12">
                                                                <label class="form-label fw-semibold">Admission Venue: <span class="text-danger">*</span></label>
                                                                <input type="text" name="venue" class="form-control form-control-sm" oninput="var words = this.value.split(' '); for(var i = 0; i < words.length; i++){ words[i] = words[i].substr(0,1).toUpperCase() + words[i].substr(1); } this.value = words.join(' ');">
                                                            </div>

                                                            <div class="col-md-12">
                                                                <label>&nbsp;</label>
                                                                <button type="submit" class="btn btn-success btn-sm btn-block">Save</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="card">
                                            <div class="card-header pt-3">
                                                <h6 class="card-title">
                                                    <i class="ti ti-list"></i> List of Venue
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive mt-1 p-2">
                                                    <table id="adVenue" class="table table-hover" style="width: 100% !important">
                                                        <thead>
                                                            <tr>
                                                                <th>Campus</th>
                                                                <th>Venue</th>
                                                                <th style="text-align: center !important;">Action</th>
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

                            <div class="tab-pane fade" id="pills-six" role="tabpanel" aria-labelledby="pills-six-tab" tabindex="0">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <div class="card">
                                            <div class="card-header pt-3">
                                                <h6 class="card-title">
                                                    <i class="ti ti-plus"></i> Add Year
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <form method="post" action="{{ route('add_admission_venue') }}" id="adYearCon">
                                                    @csrf

                                                    <input type="hidden" name="campus" class="form-control  form-control-sm" value="{{ request('campus') }}">

                                                    <div class="form-group">
                                                        <div class="form-row">
                                                            <div class="col-md-12">
                                                                <label class="form-label fw-semibold">Admission Year: <span class="text-danger">*</span></label>
                                                                <input type="number" name="adyear" class="form-control form-control-sm">
                                                            </div>

                                                            <div class="col-md-12">
                                                                <label>&nbsp;</label>
                                                                <button type="submit" class="btn btn-success btn-sm btn-block">Save</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="card">
                                            <div class="card-header pt-3">
                                                <h6 class="card-title">
                                                    <i class="ti ti-list"></i> List of Year
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive mt-1 p-2">
                                                    <table id="adYear" class="table table-hover" style="width: 100% !important">
                                                        <thead>
                                                            <tr>
                                                                <th>Year</th>
                                                                <th>Status</th>
                                                                <th style="text-align: center !important;">Action</th>
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

    <div class="modal fade" id="editProgramModal" tabindex="-1" role="dialog" aria-labelledby="editProgramModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProgramModalLabel">Edit Program Name</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editProgramForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editProgramId">
                        <div class="form-group">
                            <label for="editProgramcode">Program Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editProgramcode" name="code" oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class="form-group mt-3">
                            <label for="editProgram">Program Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editProgram" name="program" oninput="this.value = this.value.toUpperCase()">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editStrandModal" tabindex="-1" role="dialog" aria-labelledby="editStrandModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editStrandModalLabel">Edit Strand Name</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editStrandForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editStrandId">
                        <div class="form-group mt-3">
                            <label for="editStrandcode">Strand Code: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editStrandcode" name="code" oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class="form-group mt-3">
                            <label for="editStrand">Strand Name: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editStrand" name="strand" oninput="this.value = this.value.toUpperCase()">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editDateModal" tabindex="-1" role="dialog" aria-labelledby="editDateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDateModalLabel">Edit Date Name</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editDateForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editDateId">
                        <div class="form-group">
                            <label for="editDate">Admission Date</label>
                            <input type="date" class="form-control" id="editDate" name="date">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editDateTimeModal" tabindex="-1" role="dialog" aria-labelledby="editDateTimeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDateTimeModalLabel">Edit DateTime</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editDateTimeForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editDateTimeId">
                        <div class="form-group mt-3">
                            <label for="editDateAssign">Admission Date: <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="editDateAssign" name="date">
                        </div>
                        <div class="form-group mt-3">
                            <label for="editTimeAssign">Admission Time: <span class="text-danger">*</span></label>
                            <input type="time" class="form-control" id="editTimeAssign" name="time">
                        </div>
                        <div class="form-group mt-3">
                            <label for="editSlotAssign">Admission Slot: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editSlotAssign" name="slots">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editVenueModal" tabindex="-1" role="dialog" aria-labelledby="editVenueModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editVenueModalLabel">Edit Venue</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editVenueForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editVenueId">
                        <div class="form-group">
                            <label for="editVenue">Admission Venue: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editVenue" name="venue">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editYearModal" tabindex="-1" role="dialog" aria-labelledby="editYearModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editYearModalLabel">Edit Year Name</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editYearForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editYearId">
                        <div class="form-group mt-3">
                            <label for="editYear">Admission Year: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editYear" name="adyear">
                        </div>
                        <div class="form-group mt-3">
                            <label for="editYearStatus">Admission Year Status: <span class="text-danger">*</span></label>
                            <select class="form-control form-control-sm" name="status" id="editYearStatus">
                                <option value="On">On</option>
                                <option value="Off">Off</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        var adProgramRoute = "{{ route('add_Program') }}";
        var fetchProgramRoute = "{{ route('configure_admissionajax') }}";
        var programUpdateRoute = "{{ route('programUpdate', ['id' => ':id']) }}";
        var programDeleteRoute = "{{ route('programDelete', ['id' => ':id']) }}";

        var adStrandRoute = "{{ route('add_Strand') }}";
        var fetchStrandRoute = "{{ route('configure_admissionstrandajax') }}";
        var strandUpdateRoute = "{{ route('strandUpdate', ['id' => ':id']) }}";
        var strandDeleteRoute = "{{ route('strandDelete', ['id' => ':id']) }}";

        var adDateRoute = "{{ route('add_admission_date') }}";
        var fetchDateRoute = "{{ route('configure_admissiondateajax') }}";
        var dateUpdateRoute = "{{ route('dateUpdate', ['id' => ':id']) }}";
        var dateDeleteRoute = "{{ route('dateDelete', ['id' => ':id']) }}";

        var adDateTimeRoute = "{{ route('add_admission_time') }}";
        var fetchDateTimeRoute = "{{ route('configure_admissiondatetimeajax') }}";
        var fetchDateAjaxRoute = "{{ route('fetchDates') }}";
        var dateTimeUpdateRoute = "{{ route('timeUpdate', ['id' => ':id']) }}";
        var dateTimeDeleteRoute = "{{ route('timeDelete', ['id' => ':id']) }}";

        var adVenueRoute = "{{ route('add_admission_venue') }}";
        var fetchVenueRoute = "{{ route('configure_admissionvenueajax') }}";
        var venueUpdateRoute = "{{ route('venueUpdate', ['id' => ':id']) }}";
        var venueDeleteRoute = "{{ route('venueDelete', ['id' => ':id']) }}";

        var adYearRoute = "{{ route('add_admission_year') }}";
        var fetchYearRoute = "{{ route('configure_admissionyearajax') }}";
        var yearUpdateRoute = "{{ route('yearUpdate', ['id' => ':id']) }}";
        var yearDeleteRoute = "{{ route('yearDelete', ['id' => ':id']) }}";

        var isCampus = '{{ Auth::guard('web')->user()->campus }}';
    </script>
@endsection
