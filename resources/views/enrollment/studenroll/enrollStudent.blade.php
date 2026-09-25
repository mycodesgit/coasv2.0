@extends('layouts.master_enrollment')

@section('title')
CISS V.1.0 || Enrollment
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
                            <li class="breadcrumb-item mt-1">Enrollment</li>
                            <li class="breadcrumb-item active mt-1">Enroll Student</li>
                        </ol>
                    </div>
                </div>
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                    <div>
                        <h1 class="h4 fw-bold mb-1" style="letter-spacing: -0.02em;">Enroll Student</h1>
                        <p class="text-muted small mb-0">Complete the form below to enroll a new student.</p>
                    </div>
                    <div class="d-flex gap-2">
                        <div class="btn btn-light">
                            <i class="ti ti-circle-filled text-success"></i> Selected:
                            <i class="ti ti-calendar"></i>
                            {{ request('schlyear') }} -
                            {{ match ((int) request('semester')) {
                                1 => '1st Sem',
                                2 => '2nd Sem',
                                3 => 'Summer',
                                default => '',
                            } }}
                        </div>
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-10 scrolling-column">
                        <div class="card card-animate mb-3">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-user"></i> Student Information Section
                                </h6>
                            </div>
                            <div class="card-body card-body-bg-color">
                                <div class="row g-3">
                                    <div class="col-md-2">
                                        <label class="form-label fw-semibold">Student ID: <span class="text-danger">*</span></label>
                                        <input type="text" name="" class="form-control form-control-sm" id="student_id" value="{{ $student->stud_id }}" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Last Name: <span class="text-danger">*</span></label>
                                        <input type="text" name="" class="form-control form-control-sm" value="{{ $student->lname }}" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">First Name: <span class="text-danger">*</span></label>
                                        <input type="text" name="" class="form-control form-control-sm" value="{{ $student->fname }}" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Middle Name: <span class="text-danger">*</span></label>
                                        <input type="text" name="" class="form-control form-control-sm" value="{{ $student->mname }}" readonly>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label fw-semibold">Ext. Name: <span class="text-danger">*</span></label>
                                        <input type="text" name="" class="form-control form-control-sm" value="{{ $student->ext }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card card-animate mb-3">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-school"></i> Student Course Section
                                </h6>
                            </div>
                            <div class="card-body card-body-bg-color">
                                <form method="POST" action="{{ route('studEnrollmentCreate') }}" id="AddenrollStud">
                                    @csrf

                                    <input type="hidden" value="{{ request('schlyear') }}" name="schlyear" id="schlyearInput" readonly>
                                    <input type="hidden" value="{{ request('semester') }}" name="semester" id="semesterInput" readonly>
                                    <input type="hidden" value="{{ $student->stud_id }}" name="studentID" id="studentID" readonly>
                                    <input type="hidden" value="{{ $student->campus }}" name="campus" id="campusInput" readonly>
                                    <input type="hidden" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" name="postedDate" readonly>
                                    <input type="hidden" value="{{ Auth::guard('web')->user()->id }}" name="postedBy" readonly>

                                    <div class="form-group">
                                        <div class="row g-3">
                                            <div class="col-md-3">
                                                <label class="form-label fw-semibold">Course Year&Section: <span class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm" name="course" id="programNameSelect">
                                                    <option disabled selected> --Select --</option>
                                                    @foreach ($classEnrolls as $class)
                                                    @php
                                                        $yearsection = preg_replace('/\D/', '', $class->classSection);
                                                    @endphp
                                                    <option value="{{ $class->progAcronym }} {{ $class->classSection }}" data-pkey="{{ $class->subjID}}" data-section="{{ $class->classSection }}"  data-program-code="{{ $class->progCode }}" data-program-classid="{{ $class->clid }}" data-program-name="{{ $class->progName }}" data-year-section="{{ $class->yearleveldesc }}">
                                                        {{ $class->progAcronym }} {{ $class->classSection }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <input type="hidden" id="programIDInput" name="studClassID" class="form-control form-control-sm" readonly>
                                            <input type="hidden" id="programCodeInput" name="progCod" class="form-control form-control-sm" readonly>
                                            <input type="hidden" id="numericPart" name="studYear" placeholder="Numeric Part">
                                            <input type="hidden" id="alphabeticalPart" name="studSec" placeholder="Alphabetical Part">

                                            <div class="col-md-7">
                                                <label class="form-label fw-semibold">Program Name</label>
                                                <input type="text" id="programNameInput" name="" class="form-control form-control-sm" readonly>
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label fw-semibold">Total Units</label>
                                                <input type="text" id="totalunitInput" name="studUnit" class="form-control form-control-sm" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group mt-3">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Student Level: <span class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm" name="studLevel" id="studLevel">
                                                    <option disabled selected> --Select-- </option>
                                                    @foreach ($studlvl as $data)
                                                        <option value="{{ $data->id }}" {{ $data->id == 50 ? 'selected' : '' }}>{{ $data->studLevel }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Year Level</label>
                                                <input type="text" id="yearsectionInput" name="" class="form-control form-control-sm" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group mt-3">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Scholarship: <span class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm" name="studSch">
                                                    {{-- <option disabled selected> --SELECT SCHOLARSHIP-- </option>
                                                    @foreach ($studscholar as $data)
                                                        <option value="{{ $data->id }}">{{ $data->scholar_name }}</option>
                                                    @endforeach --}}
                                                    <option disabled {{ empty($selectedScholar) ? 'selected' : '' }}> --SELECT SCHOLARSHIP-- </option>
                                                    @foreach ($studscholar as $data)
                                                        <option value="{{ $data->id }}" {{ $selectedScholar == $data->id ? 'selected' : '' }}>
                                                            {{ $data->scholar_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label fw-semibold">Major: <span class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm" name="studMajor">
                                                    <option disabled selected> --Select--</option>
                                                    @foreach ($mamisub as $mamisubjects)
                                                        <option value="{{ $mamisubjects->submamiID }}" {{ $mamisubjects->id == 43 ? 'selected' : '' }}>{{ $mamisubjects->submamiName }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label fw-semibold">Minor: <span class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm" name="studMinor">
                                                    <option disabled selected> --Select--</option>
                                                    @foreach ($mamisub as $mamisubjects)
                                                        <option value="{{ $mamisubjects->submamiID }}" {{ $mamisubjects->id == 43 ? 'selected' : '' }}>{{ $mamisubjects->submamiName }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group mt-3">
                                        <div class="row g-3">
                                            <div class="col-md-3">
                                                <label class="form-label fw-semibold">Status: <span class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm" name="studStatus" id="assessIreggular">
                                                    @foreach ($studstat as $data)
                                                        <option value="{{ $data->id }}">{{ $data->studentStatName }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label fw-semibold">Type: <span class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm" name="studType" id="assessNewType">
                                                    <option disabled selected> --Select--</option>
                                                    @foreach ($studtype as $data)
                                                        <option value="{{ $data->id }}">{{ $data->studentTypeName }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label fw-semibold">Transferee/Shiftee: <span class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm" name="transferee" id="assesstranshift">
                                                    <option disabled selected> --Select--</option>
                                                    @foreach ($shiftrans as $data)
                                                        <option value="{{ $data->id }}" {{ $data->id == 3 ? 'selected' : '' }}>{{ $data->studentShiftTransDesc }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label fw-semibold">4P's Beneficiaries: <span class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm" name="fourPs">
                                                    <option disabled selected> --Select--</option>
                                                    <option value="0">NO</option>
                                                    <option value="1">YES</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card card-animate mb-3">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-book"></i> Student Subjects Section
                                </h6>
                            </div>
                            <div class="card-body card-body-bg-color table-responsive">
                                <table id="subjectTable" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>SubjCode</th>
                                            <th>Subject Name</th>
                                            <th>Descriptive Title</th>
                                            <th>Credit</th>
                                            <th>LecFee</th>
                                            <th>LabFee</th>
                                            <th>DevFee</th>
                                            <th>ITSubj</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card card-animate mb-3">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-receipt"></i> Student Appraisal Fees Section
                                </h6>
                            </div>
                            <div class="card-body card-body-bg-color table-responsive">
                                <table id="studFeeTable" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Fund</th>
                                            <th>Account</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                                <input type="hidden" id="fundnameCodeInput" name="fndCodes" class="form-control form-control-sm" readonly>
                                <input type="hidden" id="accountNameInput" name="accntNames" class="form-control form-control-sm" readonly>
                                <input type="hidden" id="amountFeeInput" name="amntFees" class="form-control form-control-sm" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2 sticky-column">
                        <div class="card card-animate mb-3">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-settings"></i> Enrollment Action Buttons
                                </h6>
                            </div>
                            <div class="card-body card-body-bg-color">
                                <a href="{{ route('searchStud') }}" class="col-md-12 btn btn-secondary btn-sm">Enroll New</a>
                                <a href="#" class="col-md-12 btn btn-info btn-sm mt-2" id="addSubjectModalBtn" data-bs-toggle="modal" data-bs-target="#modal-addSub">Add Subject</a>
                                <button type="button" class="col-md-12 btn btn-success btn-sm mt-2" id="assessButton">Assess</button>
                                <button type="button" class="col-md-12 btn btn-success btn-sm mt-2" id="submitButton">Save</button>
                                <form action="{{ route('studrfprint') }}" method="get" target="_blank">
                                    @csrf
                                    <input type="hidden" name="stud_id" value="{{ request('stud_id') }}">
                                    <input type="hidden" name="schlyear" value="{{ request('schlyear') }}">
                                    <input type="hidden" name="semester" value="{{ request('semester') }}">
                                    <button type="submit" class="form-control form-control-sm btn btn-success btn-sm mt-2 btnprim" id="printRFButton" target="_blank">
                                        Print RF
                                    </button>
                                </form>
                                {{-- <a href="" class="form-control form-control-sm btn btn-success btn-sm mt-2 btnprim">Check Conflict</a>
                                <a href="" class="form-control form-control-sm btn btn-success btn-sm mt-2 btnprim">Est. No. of Stud.</a> --}}
                            </div>
                        </div>

                        <div class="card card-animate">
                            <div class="card-body card-body-bg-color">
                                <div class="form-group">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            Tuition: <input type="text" id="totalLecFeeInput" class="form-control form-control-sm" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            Lab Fee: <input type="text" id="totalLabFeeInput" class="form-control form-control-sm" readonly>
                                        </div>
                                        <div class="col-md-12">
                                            Dev Fee: <input type="text" id="totalDevFeeInput" class="form-control form-control-sm" readonly>
                                        </div>
                                        <div class="col-md-12">
                                            <input type="text" id="itsubjInput" class="form-control form-control-sm" readonly>
                                        </div>
                                    </div>
                                </div>
                                <input type="text" id="subjIDsInput" name="subjIDs" class="form-control form-control-sm" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade mt-6" id="modal-addSub">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus"></i> Add New Subject
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <small>
                                    <span class="float-right">Total of {{ $subjectCount }} subjects offered this semester</span>
                                </small>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Subjects: <span class="text-danger">*</span></label><br>
                                <select class="form-control form-control-sm select2" name="dd" id="subjectSelect">
                                    <option disabled selected> --Select-- </option>
                                    @foreach($subjOffer as $subs)
                                        <option value="{{ $subs->sub_name }} {{ $subs->subSec }}"
                                                data-subp-sid="{{ $subs->id }}"
                                                data-sub-code="{{ $subs->sub_code }}"
                                                data-sub-title="{{ $subs->sub_title }}"
                                                data-sub-unit="{{ $subs->subUnit }}"
                                                data-lec-fee="{{ $subs->lecFee }}"
                                                data-lab-fee="{{ $subs->labFee }}"
                                                data-dev-fee="{{ $subs->devFee }}"
                                                data-it-fee="{{ $subs->itfee }}">
                                            {{ $subs->sub_name }} - {{ $subs->subSec }} {{ $subs->isType }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <input type="hidden" class="form-control form-control-sm" id="subjecID" readonly>
                    </div>
                    <div class="form-group">
                        <input type="hidden" class="form-control form-control-sm" id="sub_code" readonly>
                    </div>
                    <div class="form-group">
                        <input type="hidden" class="form-control form-control-sm" id="sub_title" readonly>
                    </div>
                    <div class="form-group">
                        <input type="hidden" class="form-control form-control-sm" id="subUnit" readonly>
                    </div>
                    <div class="form-group">
                        <input type="hidden" class="form-control form-control-sm" id="lecFee" readonly>
                    </div>
                    <div class="form-group">
                        <input type="hidden" class="form-control form-control-sm" id="labFee" readonly>
                    </div>
                    <div class="form-group">
                        <input type="hidden" class="form-control form-control-sm" id="devFee" readonly>
                    </div>
                    <div class="form-group">
                        <input type="hidden" class="form-control form-control-sm" id="itfee" readonly>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"><i class="ti ti-restore"></i> Close</button>
                    <button type="button" class="btn btn-success" id="addSubjectBtn"><i class="ti ti-plus"></i>  Add</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        var fetchTemplateRoute  = "{{ route('fetchSubjects') }}";
        var getfetchSubjectRoute  = "{{ route('coursefetchSubjects') }}";
        var fetchFeeDataRoute  = "{{ route('fetchFeeSubjects') }}";
        var saveEnrollmentRoute  = "{{ route('studEnrollmentCreate') }}";
        var checkEnrollmentRoute  = "{{ route('checkEnrollment') }}";

        document.addEventListener('DOMContentLoaded', function() {
        var scrollableColumn = document.querySelector('.scrolling-column');
            scrollableColumn.addEventListener('scroll', function() {
            var scrollTop = this.scrollTop;
            this.scrollTo({
                top: scrollTop,
                behavior: 'smooth'
                });
            });
        });


    </script>
@endsection


