@extends('layouts.master_adminkiosk')

@section('title')
CISS V.1.0 || Kiosk Admin
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
                            <li class="breadcrumb-item mt-1">Kiosk</li>
                            <li class="breadcrumb-item active mt-1">Bulk Generation Kiosk Account</li>
                        </ol>
                    </div>
                </div>
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                    <div>
                        <h1 class="h4 fw-bold mb-1" style="letter-spacing: -0.02em;">Student Kiosk Bulk Registration Overview</h1>
                        <p class="text-muted small mb-0">Overview of daily transaction logs for registered students only.</p>
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-12">
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-users"></i> List of students registered in kiosk
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row p-2">
                                    <div class="col-md-12">
                                        <form method="GET" action="{{ route('adminbulkkioskShow') }}" id="bulkKiosk">
                                            @csrf

                                            <div class="form-group">
                                                <div class="row g-3">
                                                    @if(Auth::guard('web')->user()->role == '0' || Auth::guard('web')->user()->lname == 'Arlos')
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
                                                        </select>
                                                    </div>
                                                    @endif
                                                    <div class="col-md-3">
                                                        <label class="form-label fw-semibold">Academic Year: <span class="text-danger">*</span></label>
                                                        <select class="form-control form-control-sm" name="schlyear">
                                                            @foreach($sy as $datasy)
                                                                <option value="{{ $datasy->schlyear }}">{{ $datasy->schlyear }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label class="form-label fw-semibold">Semester: <span class="text-danger">*</span></label>
                                                        <select class="form-control form-control-sm" name="semester">
                                                            <option disabled selected>Select</option>
                                                            <option value="1">First Semester</option>
                                                            <option value="2">Second Semester</option>
                                                            <option value="3">Summer</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <div class="d-flex flex-column h-100">
                                                            <label class="form-label fw-semibold opacity-0 d-none d-md-block">Action</label>
                                                            <button type="submit" class="form-control form-control-sm btn btn-success btn-sm">OK</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>

                                        <div class="page-header mt-3" style="border-bottom: 1px solid #04401f;"></div>

                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <div class="table-responsive p-2">
                                                    <table id="courseEn" class="table table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th>Code</th>
                                                                <th width="30%">Program Name</th>
                                                                <th>Acronym</th>
                                                                <th>Year&Section</th>
                                                                <th>No. of Stud</th>
                                                                <th>Male</th>
                                                                <th>Female</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            {{-- @foreach($data as $claEn)
                                                                <tr>
                                                                    <td>{{ $claEn->progCod }}</td>
                                                                    <td>{{ $claEn->progName }}</td>
                                                                    <td>{{ $claEn->progAcronym }}</td>
                                                                    <td>{{ $claEn->studYear }}-{{ $claEn->studSec }}</td>
                                                                    <td><strong>{{ $claEn->studentCount }}</strong></td>
                                                                    <td>{{ $claEn->maleCount }}</td>
                                                                    <td>{{ $claEn->femaleCount }}</td>
                                                                </tr>
                                                            @endforeach --}}
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

    <div class="modal fade mt-6" id="viewStudEnrollModal" role="dialog" aria-labelledby="viewStudEnrollModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <input type="text" name="id" id="viewStudEnrollId" hidden>
                    <h5 class="modal-title" id="viewStudEnrollModalLabel">Students Enroll</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-pills bg-light p-2 rounded-2 d-inline-flex" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pills-one-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-one" type="button" role="tab"
                                aria-controls="pills-one" aria-selected="true">
                                Student List
                            </button>
                        </li>
                        &nbsp;
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-two-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-two" type="button" role="tab"
                                aria-controls="pills-two" aria-selected="false" tabindex="-1">
                                PDF View
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content mt-1" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-one" role="tabpanel" aria-labelledby="pills-one-tab" tabindex="0">

                            <div class="page-header mt-3" style="border-bottom: 1px solid #04401f;"></div>

                            <div class="mt-3">
                                <button id="generateAllPasswords" class="btn btn-warning btn-sm mt-3">Generate All Passwords</button>
                                <button id="saveAllPasswords" class="btn btn-outline-success btn-sm mt-3">Save All Passwords</button>
                            </div>

                            <div class="table-responsive mt-3 p-2">
                                <table class="table table-bordered" id="exampleme" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>Student ID No.</th>
                                            <th>Name</th>
                                            <th>Course</th>
                                            <th>Semester</th>
                                            <th>Password</th>
                                        </tr>
                                    </thead>
                                    <tbody id="studentEnrolledTable">
                                        <!-- Enrollment history will be inserted here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-two" role="tabpanel" aria-labelledby="pills-two-tab" tabindex="0">
                            <div>
                                <iframe id="pdfIframe" src="" style="width: 100%; height: 500px;" frameborder="0" class="mt-3"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var courseEnrollReadRoute = @if(Auth::guard('web')->user()->role == 15)
            "{{ route('getstudCurrSearchGradSchool') }}";
        @else
            "{{ route('getstudCurrBulkSearch') }}";
        @endif
        var studentcourseEnrollReadRoute = "{{ route('fetchStudEnrollmentlist') }}";
        var studentcourseEnrollPDFReadRoute = "{{ route('exportEnrollmentKioskPassPDF') }}";
        var savebulkPassRoute = "{{ route('adminkioskCreateBatch') }}";
    </script>
@endsection
