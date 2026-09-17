@extends('layouts.master_yearbook')

@section('title')
CISS V.1.0 || YearBook
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
                            <li class="breadcrumb-item mt-1">Yearbook</li>
                            <li class="breadcrumb-item active mt-1">Releasing</li>
                        </ol>
                    </div>
                </div>
                <!-- Dashboard Header -->
                <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                    <div>
                        <h1 class="h4 fw-bold mb-1">Yearbooks Releasing</h1>
                        <p class="text-muted small mb-0">View student payed and manage yearbooks releasing.</p>
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-12">
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-list"></i> List of Student Payed
                                </h6>
                            </div>
                            <div class="card-body">
                                <form method="GET" action="{{ route('showReleaseResult') }}" id="enrollStud">
                                    @csrf   

                                    <div class="form-group">
                                        <div class="row g-3">
                                            <div class="col-md-3">
                                                <label class="form-label fw-semibold">School Year: <span class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm" name="schlyear" id="schlyear1">
                                                    @foreach($sy as $datasy)
                                                        <option value="{{ $datasy->schlyear }}">{{ $datasy->schlyear }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label fw-semibold">Semester: <span class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm" name="semester" id="semester">
                                                    <option disabled selected>Select</option>
                                                    <option value="1">First Semester</option>
                                                    <option value="2">Second Semester</option>
                                                    <option value="3">Summer</option>
                                                </select>
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label fw-semibold">Campus: <span class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm" name="campus" id="campus">
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
                                                </select>
                                            </div>

                                            <!-- ALIGNED BUTTON COLUMN -->
                                            <div class="col-md-4">
                                                <div class="d-flex flex-column h-100">
                                                    <label class="form-label fw-semibold opacity-0 d-none d-md-block">Action</label>
                                                    <button type="submit" class="btn btn-success btn-sm mt-auto">
                                                        <i class="fas fa-search me-1"></i> OK, Search
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                
                                <div class="page-header mt-3" style="border-bottom: 1px solid #04401f;"></div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-responsive mt-3 p-2">
                                            <table id="studpaidlistTable" class="table table-hover table-striped" style="width: 100%">
                                                <thead>
                                                    <tr>
                                                        <th>OR No.</th>
                                                        <th>Account</th>
                                                        <th>Amount</th>
                                                        <th>Date Paid</th>
                                                        <th>Stud ID</th>
                                                        <th>Name</th>
                                                        <th>Semester</th>
                                                        <th>Schlyear</th>
                                                        <th>Status</th>
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

    <!-- Release Yearbook Modal -->
    <div class="modal fade" id="releaseYearbookModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title"><i class="fas fa-hand-holding"></i> Release Yearbook</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="releaseYearbookForm">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="student_id" id="modalStudID">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Student ID / Name:</label>
                            <input type="text" id="modalStudentName" class="form-control form-control-sm" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Select Yearbook Edition: <span class="text-danger">*</span></label>
                            <select name="yearbook_id" id="modalYearbookSelect" class="form-control form-control-sm" required>
                                <option value="" disabled selected>-- Select Edition --</option>
                                @foreach($availableYearbooks as $yb)
                                    <option value="{{ $yb->id }}">
                                        {{ $yb->edition_title }} (SY {{ $yb->school_year }}) - Stock Left: {{ $yb->total_received }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Remarks / Notes:</label>
                            <textarea name="remarks" class="form-control form-control-sm" rows="2" placeholder="Optional notes..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-check"></i> Confirm Release</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        var ornostudReadRoute = "{{ route('getstudorreleaseRead') }}";
        var issueYearbookRoute = "{{ route('issueYearbookToStudent') }}";
    </script>
@endsection
