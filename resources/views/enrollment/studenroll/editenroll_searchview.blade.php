@extends('layouts.master_enrollment')

@section('title')
COAS - V2.0 || Edit Student Enrollment
@endsection

@section('sideheader')
<h4>Enrollment</h4>
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
            <li class="breadcrumb-item mt-1">Enrollment</li>
            <li class="breadcrumb-item active mt-1">Edit Student Enrollment</li>
        </ol>

        <p>
            @if(Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success')}}</div>
            @elseif (Session::has('fail'))
                <div class="alert alert-danger">{{Session::get('fail')}}</div>
            @endif
        </p>

        <div>
            <div class="page-header" style="border-bottom: 1px solid #04401f;">
                <div class="row">
                    <div class="col-md-9">
                        <h4>Edit Student Enrollment</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-10 scrolling-column">
                    <div class="card mt-2" style="background-color: #e9ecef">
                        <div class="body pr-2 pl-2 pt-2">
                            <div class="form-group">
                                <div class="form-row">
                                    <div class="col-md-2">
                                        <label><span class="badge badge-secondary">Student ID</span></label>
                                        <input type="text" name="" class="form-control form-control-sm" value="{{ $student->stud_id }}" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <label><span class="badge badge-secondary">Last Name</span></label>
                                        <input type="text" name="" class="form-control form-control-sm" value="{{ $student->lname }}" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <label><span class="badge badge-secondary">First Name</span></label>
                                        <input type="text" name="" class="form-control form-control-sm" value="{{ $student->fname }}" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <label><span class="badge badge-secondary">Middle Name</span></label>
                                        <input type="text" name="" class="form-control form-control-sm" value="{{ $student->mname }}" readonly>
                                    </div>
                                    <div class="col-md-1">
                                        <label><span class="badge badge-secondary">Ext. Name</span></label>
                                        <input type="text" name="" class="form-control form-control-sm" value="{{ $student->ext }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card" style="background-color: #e9ecef">
                        <div class="body pr-2 pl-2 pt-2">
                            <form method="POST" action="{{ route('studEnrollmentCreate') }}" id="AddenrollStud">
                                @csrf
                                
                                <input type="hidden" value="{{ request('schlyear') }}" name="schlyear" id="schlyearInput" readonly>
                                <input type="hidden" value="{{ request('semester') }}" name="semester" id="semesterInput" readonly>
                                <input type="hidden" value="{{ $student->stud_id }}" name="studentID" id="studentID" readonly>
                                <input type="hidden" value="{{ $student->campus }}" name="campus" id="campusInput" readonly>
                                <input type="hidden" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" name="postedDate" readonly>
                                <input type="hidden" value="{{ Auth::guard('web')->user()->id }}" name="postedBy" readonly>

                                <div class="container">
                                    <div class="form-group">
                                        <div class="form-row">
                                            <div class="col-md-3">
                                                <label><span class="badge badge-secondary">Course Year&Section</span></label>
                                                <select class="form-control form-control-sm" name="course" id="programNameSelect">
                                                    <option> --Select --</option>
                                                    @foreach ($classEnrolls as $class)
                                                    @php
                                                        $yearsection = preg_replace('/\D/', '', $class->classSection);

                                                        $combinedValue = $class->progCode . ' ' . $class->classSection;
                                                        $selected = ($combinedValue == $selectedProgValue) ? 'selected' : '';
                                                    @endphp
                                                    <option value="{{ $combinedValue }}" data-pkey="{{ $class->subjID}}" data-section="{{ $class->classSection }}"  data-program-code="{{ $class->progCode }}" data-program-classid="{{ $class->clid }}" data-program-name="{{ $class->progName }}" data-year-section="{{ $class->yearleveldesc }}" {{ $selected }}>
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
                                                <label><span class="badge badge-secondary">Program Name</span></label>
                                                <input type="text" id="programNameInput" name="" class="form-control form-control-sm" readonly>
                                            </div>

                                            <div class="col-md-2">
                                                <label><span class="badge badge-secondary">Total Units</span></label>
                                                <input type="text" id="totalunitInput" name="studUnit" class="form-control form-control-sm" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="form-row">
                                            <div class="col-md-3">
                                                <label><span class="badge badge-secondary">Student Level</span></label>
                                                <select class="form-control form-control-sm" name="studLevel">
                                                    <option disabled selected> --Select-- </option>
                                                    @foreach ($studlvl as $data)
                                                    <option value="{{ $data->id }}">{{ $data->studLevel }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-9">
                                                <label><span class="badge badge-secondary">Year Level</span></label>
                                                <input type="text" id="yearsectionInput" name="" class="form-control form-control-sm" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <label><span class="badge badge-secondary">Scholarship</span></label>
                                                <select class="form-control form-control-sm" name="studSch">
                                                    <option value="0">NO SCHOLARSHIP</option>
                                                    @foreach ($studscholar as $data)
                                                        <option value="{{ $data->scholar_name }}">{{ $data->scholar_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label><span class="badge badge-secondary">Major</span></label>
                                                <select class="form-control form-control-sm" name="studMajor">
                                                    <option disabled selected> --Select--</option>
                                                    @foreach ($mamisub as $mamisubjects)
                                                        <option value="{{ $mamisubjects->submamiID }}">{{ $mamisubjects->submamiName }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label><span class="badge badge-secondary">Minor</span></label>
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
                                        <div class="form-row">
                                            <div class="col-md-3">
                                                <label><span class="badge badge-secondary">Status</span></label>
                                                <select class="form-control form-control-sm" name="studStatus">
                                                    @foreach ($studstat as $data)
                                                        <option value="{{ $data->id }}">{{ $data->studentStatName }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label><span class="badge badge-secondary">Type</span></label>
                                                <select class="form-control form-control-sm" name="studType">
                                                    <option disabled selected> --Select--</option>
                                                    @foreach ($studtype as $data)
                                                        <option value="{{ $data->id }}">{{ $data->studentTypeName }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label><span class="badge badge-secondary">Transferee/Shiftee</span></label>
                                                <select class="form-control form-control-sm" name="transferee">
                                                    <option disabled selected> --Select--</option>
                                                    @foreach ($shiftrans as $data)
                                                        <option value="{{ $data->id }}">{{ $data->studentShiftTransDesc }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label><span class="badge badge-secondary">4P's Beneficiaries</span></label>
                                                <select class="form-control form-control-sm" name="fourPs">
                                                    <option disabled selected> --Select--</option>
                                                    <option value="0">NO</option>
                                                    <option value="1">YES</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card" style="background-color: #e9ecef">
                        <div class="body pr-2 pl-2 pt-2">
                            <table id="subjectTable" class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Subject Code</th>
                                        <th>Subject Name</th>
                                        <th>Descriptive Title</th>
                                        <th>Credit</th>
                                        <th>Lec Fee</th>
                                        <th>Lab Fee</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card" style="background-color: #e9ecef">
                        <div class="body pr-2 pl-2 pt-2">
                            <table id="studFeeTable" class="table">
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

                <div class="col-md-2 sticky-column mt-6">
                    <div class="card mt-2" style="background-color: #e9ecef">
                        <div class="card-body">
                            <a href="{{ route('searchStud') }}" class="form-control form-control-sm btn btn-success btn-sm">Enroll New</a>
                            <a href="" class="form-control form-control-sm btn btn-success btn-sm mt-2 btnprim" id="addSubjectModalBtn" data-toggle="modal" data-target="#modal-addSub">Add Subject</a>
                            <button type="button" class="form-control form-control-sm btn btn-success btn-sm mt-2 btnprim" id="assessButton">Assess</button>
                            <button type="button" class="form-control form-control-sm btn btn-success btn-sm mt-2 btnprim" id="submitButton">Save</button>
                            <form action="{{ route('studrfprint') }}" method="get" target="_blank">
                                @csrf
                                <input type="hidden" name="stud_id" value="{{ request('stud_id') }}">
                                <input type="hidden" name="schlyear" value="{{ request('schlyear') }}">
                                <input type="hidden" name="semester" value="{{ request('semester') }}">
                            <button type="submit" class="form-control form-control-sm btn btn-success btn-sm mt-2 btnprim" target="_blank">
                                Print RF
                            </button>
                            </form>
                            {{-- <a href="" class="form-control form-control-sm btn btn-success btn-sm mt-2 btnprim">Check Conflict</a>
                            <a href="" class="form-control form-control-sm btn btn-success btn-sm mt-2 btnprim">Est. No. of Stud.</a> --}}
                        </div>
                    </div>

                    <div class="card mt-2" style="background-color: #e9ecef">
                        <div class="card-body">
                            <div class="form-group">
                                <div class="form-row">
                                    <div class="col-md-6">
                                        Tuition: <input type="text" id="totalLecFeeInput" class="form-control form-control-sm" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        Lab Fee: <input type="text" id="totalLabFeeInput" class="form-control form-control-sm" readonly>
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

<div class="modal fade" id="modal-addSub">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">
                    <i class="fas fa-plus"></i> Add New
                </h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-body">
                <div class="form-group">
                    <div class="form-row">
                        <div class="col-md-12">
                            <label>Subjects</label><br>
                            <select class="form-control form-control-sm select2bs4" name="dd" id="subjectSelect">
                                <option disabled selected> --Select-- </option>
                                @foreach($subjOffer as $subs)
                                    <option value="{{ $subs->sub_name }} {{ $subs->subSec }}"
                                            data-subp-sid="{{ $subs->id }}" 
                                            data-sub-code="{{ $subs->sub_code }}"
                                            data-sub-title="{{ $subs->sub_title }}" 
                                            data-sub-unit="{{ $subs->subUnit }}" 
                                            data-lec-fee="{{ $subs->lecFee }}" 
                                            data-lab-fee="{{ $subs->labFee }}">
                                        {{ $subs->sub_name }} - {{ $subs->subSec }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>  
                
                <div class="form-group">
                    <input type="text" class="form-control form-control-sm" id="subjecID" readonly>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control form-control-sm" id="sub_code" readonly>
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
                
                <button type="button" class="btn btn-primary" id="addSubjectBtn">
                    <i class="fas fa-save"></i> Add
                </button>
            </div>
            <div class="modal-footer justify-content-between">
                <span class="float-right">Total of {{ $subjectCount }} subjects offered this semester</span>
            </div>
        </div>
    </div>
</div>


{{-- @include('enrollment.studenroll.modalrf') --}}

<script>
    var fetchTemplateRoute  = "{{ route('fetchSubjects') }}";
    var getfetchSubjectRoute  = "{{ route('coursefetchSubjects') }}";
    var fetchFeeDataRoute  = "{{ route('fetchFeeSubjects') }}";
    var saveEnrollmentRoute  = "{{ route('studEnrollmentCreate') }}";

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
