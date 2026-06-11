@extends('layouts.master_enrollment')

@section('title')
CISS V.1.0 || Enrollment
@endsection

@yield('sidemenu')

@section('workspace')
    <div class="row">
        <div class="col-12">
            <div class="mb-6">
                {{-- <h1 class="fs-5 mb-4 d-none d-md-block">Dashboard</h1> --}}
                <div class="card" style=" background-color: #e9ecef; margin-top: -10px">
                    <div class="card-body">
                        <ol class="breadcrumb" style="margin-bottom: -3px;">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}" class="btn btn-success btn-sm text-light">
                                    <i class="fas fa-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item mt-1">Enrollment</li>
                            <li class="breadcrumb-item active mt-1">Student Evaluation</li>
                        </ol>
                    </div>
                </div>
                <div class="row g-3 mb-3 mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                    <h4>Student Evaluation</h4>
                                </div>
                                <div class="row">
                                    <div class="col-md-12"> 
                                        <form method="GET" action="{{ route('loadstudsub_searchview') }}" id="enrollStud" class="mb-4">
                                            @csrf 

                                            <div class="form-group mt-2" style="padding: 10px">
                                                <div class="row g-3">
                                                    <div class="col-md-3">
                                                        <label>Student ID Number: <span class="text-danger">*</span></label>
                                                        <input type="text" name="stud_id" class="form-control form-control-sm" oninput="formatInput(this); this.value = this.value.toUpperCase()" autofocus>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label>School Year: <span class="text-danger">*</span></label>
                                                        <select class="form-control form-control-sm" name="schlyear">
                                                            @foreach($sy as $datasy)
                                                                <option value="{{ $datasy->schlyear }}">{{ $datasy->schlyear }}</option>
                                                            @endforeach
                                                            {{-- <option value="2025-2026">2025-2026</option>
                                                            <option value="2022-2023">2022-2023</option> --}}
                                                        </select>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label>Semester: <span class="text-danger">*</span></label>
                                                        <select class="form-control form-control-sm" name="semester">
                                                            <option disabled selected>Select</option>
                                                            <option value="1" @if (old('type') == 1) {{ 'selected' }} @endif>First Semester</option>
                                                            <option value="2" @if (old('type') == 2) {{ 'selected' }} @endif>Second Semester</option>
                                                            <option value="3" @if (old('type') == 3) {{ 'selected' }} @endif>Summer</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label>&nbsp;</label>
                                                        <button type="submit" class="form-control form-control-sm btn btn-success btn-sm">OK</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>

                                        <div class="page-header" style="border-bottom: 1px solid #04401f;"></div>

                                        <ul class="nav nav-pills mt-3 mb-3 bg-light p-2 rounded-2 d-inline-flex" id="pills-tab" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link active" id="pills-one-tab" data-bs-toggle="pill"
                                                    data-bs-target="#pills-one" type="button" role="tab"
                                                    aria-controls="pills-one" aria-selected="true">
                                                    Student Record
                                                </button>
                                            </li>
                                            &nbsp;
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="pills-supeval-tab" data-bs-toggle="pill"
                                                    data-bs-target="#pills-supeval" type="button" role="tab"
                                                    aria-controls="pills-supeval" aria-selected="false" tabindex="-1">
                                                    Load Student Subjects
                                                </button>
                                            </li>
                                        </ul>
                                        <div class="tab-content mt-1" id="pills-tabContent">
                                            <div class="tab-pane fade show active" id="pills-one" role="tabpanel" aria-labelledby="pills-one-tab" tabindex="0">
                                                <div class="bg-light p-2 rounded-2">
                                                    <iframe src="{{ route('studevalRead_listsearchpdf', ['stud_id' => request('stud_id')]) }}" style="width: 100%; height: 600px;" frameborder="0" class="mt-3"></iframe>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="pills-supeval" role="tabpanel" aria-labelledby="pills-supeval-tab" tabindex="0">
                                                <div class="row">
                                                    <div class="col-md-10 scrolling-column">
                                                        <div class="card mt-2" style="background-color: #e9ecef">
                                                            <div class="card-body pr-2 pl-2 pt-2 pb-2">
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-md-2">
                                                                            <label>Student ID</label>
                                                                            <input type="text" name="" class="form-control form-control-sm" value="{{ $student->stud_id }}" readonly>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <label>Last Name</label>
                                                                            <input type="text" name="" class="form-control form-control-sm" value="{{ $student->lname }}" readonly>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <label>First Name</label>
                                                                            <input type="text" name="" class="form-control form-control-sm" value="{{ $student->fname }}" readonly>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <label>Middle Name</label>
                                                                            <input type="text" name="" class="form-control form-control-sm" value="{{ $student->mname }}" readonly>
                                                                        </div>
                                                                        <div class="col-md-1">
                                                                            <label>Ext. Name</label>
                                                                            <input type="text" name="" class="form-control form-control-sm" value="{{ $student->ext }}" readonly>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card mt-2" style="background-color: #e9ecef">
                                                            <div class="card-body pr-2 pl-2 pt-2 pb-2">
                                                                <form method="POST" action="{{ route('studEvalEnrollmentCreate') }}" id="AddenrollStud">
                                                                    @csrf
                                                                    
                                                                    <input type="hidden" value="{{ request('schlyear') }}" name="schlyear" id="schlyearInput" readonly>
                                                                    <input type="hidden" value="{{ request('semester') }}" name="semester" id="semesterInput" readonly>
                                                                    <input type="hidden" value="{{ $student->stud_id }}" name="studentID" id="studentID" readonly>
                                                                    <input type="hidden" value="{{ $student->campus }}" name="campus" id="campusInput" readonly>
                                                                    <input type="hidden" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" name="postedDate" readonly>
                                                                    <input type="hidden" value="{{ Auth::guard('web')->user()->id }}" name="postedBy" readonly>

                                                                    <div class="form-group">
                                                                        <div class="row">
                                                                            <div class="col-md-3">
                                                                                <label>Course Year&Section</label>
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
                                                                                <label>Program Name</label>
                                                                                <input type="text" id="programNameInput" name="" class="form-control form-control-sm" readonly>
                                                                            </div>

                                                                            <div class="col-md-2">
                                                                                <label>Total Units</label>
                                                                                <input type="text" id="totalunitInput" name="studUnit" class="form-control form-control-sm" readonly>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <div class="row mt-3">
                                                                            <div class="col-md-6">
                                                                                <label>Student Level</label>
                                                                                <select class="form-control form-control-sm" name="studLevel" id="studLevel">
                                                                                    @foreach ($studlvl as $data)
                                                                                        <option value="{{ $data->id }}">{{ $data->studLevel }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label>Year Level</label>
                                                                                <input type="text" id="yearsectionInput" name="" class="form-control form-control-sm" readonly>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <div class="row mt-3">
                                                                            <div class="col-md-6">
                                                                                <label>Scholarship</label>
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
                                                                                <label>Major</label>
                                                                                <select class="form-control form-control-sm" name="studMajor">
                                                                                    <option disabled selected> --Select--</option>
                                                                                    @foreach ($mamisub as $mamisubjects)
                                                                                        <option value="{{ $mamisubjects->submamiID }}">{{ $mamisubjects->submamiName }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>

                                                                            <div class="col-md-3">
                                                                                <label>Minor</label>
                                                                                <select class="form-control form-control-sm" name="studMinor">
                                                                                    <option disabled selected> --Select--</option>
                                                                                    @foreach ($mamisub as $mamisubjects)
                                                                                        <option value="{{ $mamisubjects->submamiID }}">{{ $mamisubjects->submamiName }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <div class="row mt-3">
                                                                            <div class="col-md-3">
                                                                                <label>Status</label>
                                                                                <select class="form-control form-control-sm" name="studStatus">
                                                                                    @foreach ($studstat as $data)
                                                                                        <option value="{{ $data->id }}">{{ $data->studentStatName }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>

                                                                            <div class="col-md-3">
                                                                                <label>Type</label>
                                                                                <select class="form-control form-control-sm" name="studType">
                                                                                    <option disabled selected> --Select--</option>
                                                                                    @foreach ($studtype as $data)
                                                                                        <option value="{{ $data->id }}">{{ $data->studentTypeName }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>

                                                                            <div class="col-md-3">
                                                                                <label>Transferee/Shiftee</label>
                                                                                <select class="form-control form-control-sm" name="transferee">
                                                                                    <option disabled selected> --Select--</option>
                                                                                    @foreach ($shiftrans as $data)
                                                                                        <option value="{{ $data->id }}">{{ $data->studentShiftTransDesc }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>

                                                                            <div class="col-md-3">
                                                                                <label>4P's Beneficiaries</label>
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

                                                        <div class="card mt-2" style="background-color: #e9ecef">
                                                            <div class="card-body pr-2 pl-2 pt-2 pb-2">
                                                                <div class="table-responsive">
                                                                    <table id="subjectTable" class="table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>ID</th>
                                                                                <th>Subj Code</th>
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
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2 sticky-column">
                                                        <div class="card mt-2" style="background-color: #e9ecef">
                                                            <div class="card-body">
                                                                <a href="{{ route('loadstudsub') }}" class="col-md-12 btn btn-success btn-sm">New</a>
                                                                <a href="" class="col-md-12 btn btn-success btn-sm mt-2 btnprim" id="addSubjectModalBtn" data-bs-toggle="modal" data-bs-target="#modal-addSub">Add Subject</a>
                                                                <button type="button" class="col-md-12 btn btn-success btn-sm mt-2 btnprim" id="assessButton" style="display: none;">Assess</button>
                                                                <button type="button" class="col-md-12 btn btn-success btn-sm mt-2 btnprim" id="submitEvalButton">Save</button>
                                                            </div>
                                                        </div>

                                                        <div class="card mt-2" style="background-color: #e9ecef">
                                                            <div class="card-body">
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            Tuition: <input type="text" id="totalLecFeeInput" class="form-control form-control-sm" readonly>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            Lab Fee: <input type="text" id="totalLabFeeInput" class="form-control form-control-sm" readonly>
                                                                        </div>
                                                                        <div class="col-md12">
                                                                            Dev Fee: <input type="text" id="totalDevFeeInput" class="form-control form-control-sm" readonly>
                                                                        </div>
                                                                        <div class="col-md-12 mt-1">
                                                                            <input type="text" id="itsubjInput" class="form-control form-control-sm" readonly>
                                                                        </div>
                                                                    </div>
                                                                </div>      
                                                                <input type="hidden" id="subjIDsInput" name="subjIDs" class="form-control form-control-sm" readonly>
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
            </div>
        </div>
    </div>

    <div class="modal fade mt-6" id="modal-addSub">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">
                        <i class="fas fa-plus"></i> Add New
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-12">
                                <label>Subjects</label><br>
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
                    
                    <button type="button" class="btn btn-success mt-2" id="addSubjectBtn">
                        <i class="fas fa-save"></i> Add
                    </button>
                </div>
                <div class="modal-footer justify-content-between">
                    <span class="float-right">Total of {{ $subjectCount }} subjects offered this semester</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        var fetchTemplateRoute  = "{{ route('fetchSubjects') }}";
        var getfetchSubjectRoute  = "{{ route('coursefetchSubjects') }}";
        var fetchFeeDataRoute  = "{{ route('fetchFeeSubjects') }}";
        var saveEvalEnrollmentRoute  = "{{ route('studEvalEnrollmentCreate') }}";
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
