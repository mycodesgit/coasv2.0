@extends('layouts.master_faculty')

@section('title')
CISS V.1.0 || Faculty Services
@endsection

@section('workspace')
    <div class="row">
        <div class="col-12">
            <div class="mb-6">
                <h1 class="fs-5 mb-4 d-none d-md-block">
                    <a href="{{ route('index.services') }}">
                        <i class="ti ti-arrow-left"></i> Services 
                    </a>
                    <span class="text-muted">/ Pre-enrollment</span>
                </h1>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-device-laptop"></i> Pre-enrollment
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="col-md-12"> 
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
                                                <iframe src="{{ route('studevalfacultypdf', ['stud_id' => request('stud_id')]) }}" style="width: 100%; height: 600px;" frameborder="0" class="mt-3"></iframe>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="pills-supeval" role="tabpanel" aria-labelledby="pills-supeval-tab" tabindex="0">
                                            <div class="row g-3">
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
                                                            <form method="POST" action="{{ route('studFacEvalEnrollmentCreate') }}" id="AddenrollStud">
                                                                @csrf
                                                                
                                                                <input type="hidden" value="{{ request('schlyear') }}" name="schlyear" id="schlyearInput" readonly>
                                                                <input type="hidden" value="{{ request('semester') }}" name="semester" id="semesterInput" readonly>
                                                                <input type="hidden" value="{{ $student->stud_id }}" name="studentID" id="studentID" readonly>
                                                                <input type="hidden" value="{{ $student->campus }}" name="campus" id="campusInput" readonly>
                                                                <input type="hidden" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" name="postedDate" readonly>
                                                                <input type="hidden" value="{{ Auth::guard('faculty')->user()->id }}" name="postedBy" readonly>
                                                                <input type="hidden" value="{{ $programEnHistory->id }}" name="id" readonly>

                                                                <div class="form-group">
                                                                    <div class="row g-3">
                                                                        <div class="col-md-3">
                                                                            <label>Course Year&Section: <span class="text-danger">*</span></label>
                                                                            <select class="form-control form-control-sm" name="course" id="programNameEditSelect">
                                                                                <option> --Select --</option>
                                                                                @foreach ($classEnrolls as $class)
                                                                                @php
                                                                                    $yearsection = preg_replace('/\D/', '', $class->classSection);
                                                                                    $combinedValue = $class->progCode . ' ' . $class->classSection;
                                                                                    $selected = ($combinedValue == $selectedProgValue) ? 'selected' : '';
                                                                                @endphp
                                                                                <option value="{{ $class->progAcronym }} {{ $class->classSection }}" data-pkey="{{ $class->subjID}}" data-section="{{ $class->classSection }}"  data-program-code="{{ $class->progCode }}" data-program-classid="{{ $class->clid }}" data-program-name="{{ $class->progName }}" data-year-section="{{ $class->yearleveldesc }}" {{ $selected }}>
                                                                                    {{ $class->progAcronym }} {{ $class->classSection }}
                                                                                </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>

                                                                        <input type="hidden" name="course" value="{{ $selectedStudCourse }}">
                                                                        <input type="hidden" id="editprogramIDInput" name="studClassID" class="form-control form-control-sm" readonly>
                                                                        <input type="hidden" id="editprogramCodeInput" name="progCod" class="form-control form-control-sm" readonly>
                                                                        <input type="hidden" id="editnumericPart" name="studYear" placeholder="Numeric Part">
                                                                        <input type="hidden" id="editalphabeticalPart" name="studSec" placeholder="Alphabetical Part">

                                                                        <div class="col-md-7">
                                                                            <label>Program Name: <span class="text-danger">*</span></label>
                                                                            <input type="text" id="editprogramNameInput" name="" class="form-control form-control-sm" readonly>
                                                                        </div>

                                                                        @php
                                                                            $totalUnits = 0;
                                                                            foreach ($subjectsEn as $dataen) {
                                                                                $totalUnits += $dataen->subUnit;
                                                                            }
                                                                        @endphp

                                                                        <div class="col-md-2">
                                                                            <label>Total Units: <span class="text-danger">*</span></label>
                                                                            <input type="text" id="totalunitInput" name="studUnit" class="form-control form-control-sm" value="{{ $totalUnits }}" readonly>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group mt-2">
                                                                    <div class="row g-3">
                                                                        <div class="col-md-6">
                                                                            <label>Student Level: <span class="text-danger">*</span></label>
                                                                            <select class="form-control form-control-sm" name="studLevel">
                                                                                <option disabled selected> --Select-- </option>
                                                                                @foreach ($studlvl as $data)
                                                                                    <option value="{{ $data->id }}" {{ $data->id == $selectedProgStudLevel ? 'selected' : '' }}>{{ $data->studLevel }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label>Year Level: <span class="text-danger">*</span></label>
                                                                            <input type="text" id="edityearsectionInput" name="" class="form-control form-control-sm" readonly>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group mt-2">
                                                                    <div class="row g-3">
                                                                        <div class="col-md-6">
                                                                            <label>Major: <span class="text-danger">*</span></label>
                                                                            <select class="form-control form-control-sm" name="studMajor">
                                                                                <option disabled selected> --Select--</option>
                                                                                @foreach ($mamisub as $mamisubjects)
                                                                                    <option value="{{ $mamisubjects->submamiID }}" {{ $mamisubjects->submamiID == $selectedStudMajor ? 'selected' : '' }}>{{ $mamisubjects->submamiName }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>

                                                                        <div class="col-md-6">
                                                                            <label>Minor: <span class="text-danger">*</span></label>
                                                                            <select class="form-control form-control-sm" name="studMinor">
                                                                                <option disabled selected> --Select--</option>
                                                                                @foreach ($mamisub as $mamisubjects)
                                                                                    <option value="{{ $mamisubjects->submamiID }}" {{ $mamisubjects->submamiID == $selectedStudMinor ? 'selected' : '' }}>{{ $mamisubjects->submamiName }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group mt-2">
                                                                    <div class="row g-3">
                                                                        <div class="col-md-3">
                                                                            <label>Status: <span class="text-danger">*</span></label>
                                                                            <select class="form-control form-control-sm" name="studStatus">
                                                                                @foreach ($studstat as $data)
                                                                                    <option value="{{ $data->id }}" {{ $data->id == $selectedStudStatus ? 'selected' : '' }}>{{ $data->studentStatName }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>

                                                                        <div class="col-md-3">
                                                                            <label>Type: <span class="text-danger">*</span></label>
                                                                            <select class="form-control form-control-sm" name="studType">
                                                                                <option disabled selected> --Select--</option>
                                                                                @foreach ($studtype as $data)
                                                                                    <option value="{{ $data->id }}" {{ $data->id == $selectedStudType ? 'selected' : '' }}>{{ $data->studentTypeName }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>

                                                                        <div class="col-md-3">
                                                                            <label>Transferee/Shiftee: <span class="text-danger">*</span></label>
                                                                            <select class="form-control form-control-sm" name="transferee">
                                                                                <option disabled selected> --Select--</option>
                                                                                @foreach ($shiftrans as $data)
                                                                                    <option value="{{ $data->id }}" {{ $data->id == $selectedStudTransferee ? 'selected' : '' }}>{{ $data->studentShiftTransDesc }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>

                                                                        <div class="col-md-3">
                                                                            <label>4P's Beneficiaries: <span class="text-danger">*</span></label>
                                                                            <select class="form-control form-control-sm" name="fourPs">
                                                                                <option disabled selected> --Select--</option>
                                                                                <option value="0" {{ $selectedStudFourPs == 0 ? 'selected' : '' }}>NO</option>
                                                                                <option value="1" {{ $selectedStudFourPs == 1 ? 'selected' : '' }}>YES</option>
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
                                                                    <thead style="background-color: #c9c9c9">
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
                                                                        @foreach($subjectsEn as $dataen)
                                                                        <tr>
                                                                            <td>{{ $dataen->subjID }}</td>
                                                                            <td>{{ $dataen->subCode }}</td>
                                                                            <td>{{ $dataen->sub_name }} - {{ $dataen->subSec }}</td>
                                                                            <td>{{ $dataen->sub_title }}</td>
                                                                            <td>{{ $dataen->subUnit }}</td>
                                                                            <td>{{ $dataen->lecFee }}</td>
                                                                            <td>{{ $dataen->labFee }}</td>
                                                                            <td>{{ $dataen->devFee }}</td>
                                                                            <td>{{ $dataen->itfee }}</td>
                                                                            <td>
                                                                                <button class="btn btn-outline-danger btn-sm delete-row">
                                                                                    <i class="fas fa-trash"></i>
                                                                                </button>
                                                                            </td>
                                                                        </tr>
                                                                        @endforeach
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
                                                            <a href="#" class="col-md-12 btn btn-success btn-sm mt-2 btnprim" id="addSubjectModalBtn" data-bs-toggle="modal" data-bs-target="#modal-addSub">Add Subject</a>
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
                                                            <input type="hidden" id="subjIDsInput" name="subjIDs" class="form-control form-control-sm" readonly value="{{ $subOfferedIds }}">
                                                            <input type="hidden" id="subjprimIDsInput" name="id" class="form-control form-control-sm" readonly value="{{ $studsubenrollIds }}">
                                                            <input type="hidden" id="primaryIDsInput" name="id" class="form-control form-control-sm" readonly value="{{ $studsubenrollIdsprimID }}">
                                                            <input type="hidden" id="itsubjInput" class="form-control form-control-sm" readonly value="{{ $studsubenrollIdsprimIDitfee }}">
                                                            <br>
                                                            <input type="hidden" id="subjIDsInputlog" name="subjIDs" class="form-control form-control-sm" readonly value="{{ $subOfferedIdslog }}">
                                                            <input type="hidden" id="subjprimIDsInputlog" name="id" class="form-control form-control-sm" readonly value="{{ $studsubenrollIdslog }}">
                                                            <input type="hidden" id="primaryIDsInputlog" name="id" class="form-control form-control-sm" readonly value="{{ $studsubenrollIdsprimIDlog }}">
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
        <div class="modal-dialog modal-dialog-centered modal-md">
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
        var fetchTemplateRoute  = "{{ route('fetchSubjectsOffered') }}";
        var getfetchSubjectRoute  = "{{ route('coursefetchSubjectsSelect') }}";
        var fetchFeeDataRoute  = "{{ route('fetchFeeSubjectsSelect') }}";
        var saveEvalEnrollmentRoute  = "{{ route('studFacEvalEnrollmentCreate') }}";
        var checkEnrollmentRoute  = "{{ route('faccheckEnrollment') }}";

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
