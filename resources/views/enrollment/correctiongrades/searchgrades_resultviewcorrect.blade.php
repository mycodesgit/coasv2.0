@extends('layouts.master_enrollment')

@section('title')
CISS V.1.0 || Grading
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
            <li class="breadcrumb-item mt-1">Correction of Grades</li>
            <li class="breadcrumb-item mt-1">
                <a href="{{ url()->previous() }}"> 
                    @if($genstud && $genstud->isNotEmpty())
                        <strong>{{ $genstud->first()->sub_name }} {{ $genstud->first()->subSec }}</strong>
                    @else
                        <strong>No subjects or no students</strong>
                    @endif
                </a>
            </li>
            <li class="breadcrumb-item active mt-1">Grade Sheet</li>
        </ol>

        {{-- <p>
            @if(Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success')}}</div>
            @elseif (Session::has('fail'))
                <div class="alert alert-danger">{{Session::get('fail')}}</div>
            @endif
        </p> --}}

        <div style="border-bottom: 1px solid #04401f;">
            <form method="GET" action="{{ route('studgradecorrection_resultsearch') }}" enctype="multipart/form-data" id="gradeSht">
                @csrf

                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                    <h4>Correction of Grades</h4>
                </div>

                <div class="form-group mt-2">
                    <div class="form-row">
                        <div class="col-md-2">
                            <label><span class="badge badge-secondary">School Year</span></label>
                            <select class="form-control form-control-sm" name="schlyear">
                                @foreach($sy as $datasy)
                                    <option value="{{ $datasy->schlyear }}">{{ $datasy->schlyear }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label><span class="badge badge-secondary">Semester</span></label>
                            <select class="form-control form-control-sm" name="semester">
                                <option disabled selected>Select</option>
                                <option value="1" @if (old('type') == 1) {{ 'selected' }} @endif>First Semester</option>
                                <option value="2" @if (old('type') == 2) {{ 'selected' }} @endif>Second Semester</option>
                                <option value="3" @if (old('type') == 3) {{ 'selected' }} @endif>Summer</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label>&nbsp;</label>
                            <button type="submit" class="form-control form-control-sm btn btn-success btn-sm">OK</button>
                        </div>
                    </div>
                </div>
            </form>
            <h5>Search Results: {{ $totalSearchResults }} 
                <small>
                    <i>Year-<b>{{ request('syear') }}</b>,
                        Semester-<b>{{ request('semester') }}</b>,
                    </i>
                </small>
            </h5>
        </div>

        <div class="col-md-2 float-right mt-3 mb-2">
            @if($genstud && $genstud->isNotEmpty())
            <form method="POST" action="{{ route('registrarupdateStatus_gradessubmit', ['subjID' => $genstud->first()->subjID]) }}" id="confirmationForm">
                @csrf
                <input type="hidden" name="subjID[]" value="{{ $genstud->first()->subjID }}">
                <button type="button" class="btn btn-primary btn-sm btn-block" id="submitgradeid" data-toggle="modal" data-target="#submitgrades" @if($gradereg == 0) disabled @endif>Submit Grades</button>
            </form>
            @else
                <strong>No Students in this subject</strong>
            @endif
        </div>
        @include('modal.submitgrades')
        <div class="mt-3 table-responsive p-0" style="height: 400px;">
            <table id="" class="table table-bordered table-head-fixed text-nowrap">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Final Grade</th>
                        <th>Completion</th>
                        <th>Equivalent</th>
                        <th>Unit</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php 
                        if (!function_exists('getEquivalentGrade')) {
                        function getEquivalentGrade($grade) {
                            if ($grade === 'INC') {
                                return ['gpa' => 'INC', 'status' => 'Incomplete'];
                            } elseif ($grade === 'NN') {
                                return ['gpa' => 'NN', 'status' => 'No Name'];
                            } elseif ($grade === 'NG') {
                                return ['gpa' => 'NG', 'status' => 'No Grade'];
                            } elseif ($grade === 'Drp..') {
                                return ['gpa' => 'Drp.', 'status' => 'Drop'];
                            } elseif ($grade >= 97 || $grade == 1) {
                                return ['gpa' => '1.00', 'status' => 'Passed'];
                            } elseif ($grade >= 94) {
                                return ['gpa' => '1.25', 'status' => 'Passed'];
                            } elseif ($grade >= 91) {
                                return ['gpa' => '1.50', 'status' => 'Passed'];
                            } elseif ($grade >= 88) {
                                return ['gpa' => '1.75', 'status' => 'Passed'];
                            } elseif ($grade >= 85 || $grade == 2) {
                                return ['gpa' => '2.00', 'status' => 'Passed'];
                            } elseif ($grade >= 82) {
                                return ['gpa' => '2.25', 'status' => 'Passed'];
                            } elseif ($grade >= 79) {
                                return ['gpa' => '2.50', 'status' => 'Passed'];
                            } elseif ($grade >= 76) {
                                return ['gpa' => '2.75', 'status' => 'Passed'];
                            } elseif ($grade >= 75 || $grade == 3) {
                                return ['gpa' => '3.00', 'status' => 'Passed'];
                            } elseif ($grade >= 70) {
                                return ['gpa' => '4.00', 'status' => 'Conditional'];
                            } else {
                                return ['gpa' => '5.00', 'status' => 'Failure'];
                            }
                        }

                        function displayGrade($grade) {
                            if (is_numeric($grade) && strpos($grade, '.') === false) {
                                $equivalent = getEquivalentGrade($grade);
                                return $equivalent['gpa'];
                            }
                            return $grade;
                        }}
                    @endphp
                    @php $no = 1; @endphp
                    @foreach($genstud as $datagenstud)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $datagenstud->studID }}</td>
                        <td><strong>{{ $datagenstud->lname }}, {{ $datagenstud->fname }} {{ strtoupper(substr($datagenstud->mname, 0, 1)) }}. </strong></td>
                        <td>
                            @if ($datagenstud->gstat == 1 || empty($datagenstud->subjFgrade))
                                @if (!empty($datagenstud->subjFgrade))
                                    <select class="form-control form-control-sm" name="subjFgrade" id="{{ $datagenstud->sgid }}" onchange="updateGrade(this.id, this.value)">
                                        <option></option>
                                        @foreach ($grdCode as $grdCodes)
                                            <option value="{{ $grdCodes->grade }}" {{ $grdCodes->grade == $datagenstud->subjFgrade ? 'selected' : '' }}>
                                                {{ $grdCodes->grade }}
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                                @if (empty($datagenstud->subjFgrade))
                                    <select class="form-control form-control-sm" name="subjFgrade" id="{{ $datagenstud->sgid }}" onchange="updateGrade(this.id, this.value)">
                                        <option></option>
                                        @foreach ($grdCode as $grdCodes)
                                            <option value="{{ $grdCodes->grade }}">
                                                {{ $grdCodes->grade }}
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                            @elseif ($datagenstud->gstat == 2)
                                <strong style="{{ ($datagenstud->subjFgrade == 'INC' || $datagenstud->subjFgrade == 'Inc.' || $datagenstud->subjFgrade == 'inc' || $datagenstud->subjFgrade == 'NN' || $datagenstud->subjFgrade == 'nn') ? 'color: red;' : '' }}">{{ $datagenstud->subjFgrade }}</strong>
                            @endif
                        </td>
                        <td>
                            @if ($datagenstud->subjFgrade == 'FAILURE' || $datagenstud->subjFgrade == 'INC' || $datagenstud->subjFgrade == 'Inc.' || $datagenstud->subjFgrade == 'inc')
                                @if ($datagenstud->compstat == 1 || empty($datagenstud->subjComp) || empty($datagenstud->compstat) && $datagenstud->status == '2')
                                    @if (!empty($datagenstud->subjComp))
                                        <select class="form-control form-control-sm" name="subjComp" id="{{ $datagenstud->sgid }}" onchange="updateGradeComp(this.id, this.value)">
                                            <option></option>
                                            @foreach ($grdCode as $grdCodes)
                                                <option value="{{ $grdCodes->grade }}" {{ $grdCodes->grade == $datagenstud->subjComp ? 'selected' : '' }}>
                                                    {{ $grdCodes->grade }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @endif
                                    @if (empty($datagenstud->subjComp))
                                        <select class="form-control form-control-sm" name="subjComp" id="{{ $datagenstud->sgid }}" onchange="updateGradeComp(this.id, this.value)">
                                            <option></option>
                                            @foreach ($grdCode as $grdCodes)
                                                <option value="{{ $grdCodes->grade }}">
                                                    {{ $grdCodes->grade }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @endif
                                @elseif ($datagenstud->compstat == 2)
                                    <strong>{{ $datagenstud->subjComp }}</strong>
                                @endif
                            @endif
                        </td>
                        <td>
                            <strong style="{{ $datagenstud->subjComp ? '' : ($datagenstud->subjFgrade == 'INC' ? 'color: red;' : '') }}">
                                {{ $datagenstud->subjComp ? displayGrade($datagenstud->subjComp) : displayGrade($datagenstud->subjFgrade) }}
                            </strong>
                        </td>
                        <td><strong>{{ $datagenstud->creditEarned }}</strong></td>
                        <td style="text-align:center;">
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu" role="menu">
                                    <a class="dropdown-item" data-toggle="modal" data-target="#editgrades{{ $datagenstud->sgid }}">
                                        <i class="fas fa-pen"></i> Edit Grades
                                    </a>
                                    <a class="dropdown-item" id="editgradecompletionid" data-toggle="modal" data-target="#editCompletiongrades{{ $datagenstud->sgid }}">
                                        <i class="fa-solid fa-envelopes-bulk"></i> Completion
                                    </a>
                                </div>
                            </div>
                        </td>

                        <!-- Edit Grades Modal -->
                        <div class="modal fade" id="editgrades{{ $datagenstud->sgid }}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h6 class="modal-title">
                                            <i class="fas fa-info-circle"></i> Confirmation
                                        </h6>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form method="POST" action="{{ route('editGrade', ['id' => $datagenstud->sgid]) }}" id="editConfirmForm{{ $datagenstud->sgid }}">
                                        @csrf
                                        <div class="modal-body">
                                            <input type="hidden" name="id" value="{{ $datagenstud->sgid }}">
                                            <div class="form-group">
                                                <input type="hidden" class="form-control" name="status" value="1">
                                            </div>
                                            Are you sure you want to Edit the Grades?
                                            <div class="form-group">
                                                <div class="form-row">
                                                    <div class="mt-2 col-md-12">
                                                        <label><span class="badge badge-warning">Enter the password here</span></label>
                                                        <input type="password" id="gradeauthpass{{ $datagenstud->sgid }}" name="gradeauthpass" class="form-control form-control-sm">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-between">
                                            <div>
                                                <button type="submit" class="btn btn-primary" id="editBtn{{ $datagenstud->sgid }}" disabled>Yes</button>
                                            </div>
                                            <button type="button" class="btn btn-danger float-right" data-dismiss="modal">No</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Completion Grades Modal -->
                        <div class="modal fade" id="editCompletiongrades{{ $datagenstud->sgid }}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h6 class="modal-title">
                                            <i class="fas fa-info-circle"></i> Confirmation
                                        </h6>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form method="POST" action="{{ route('editCompletion', ['id' => $datagenstud->sgid]) }}" id="editCompletionForm{{ $datagenstud->sgid }}">
                                        @csrf
                                        <div class="modal-body">
                                            <input type="hidden" name="id" value="{{ $datagenstud->sgid }}">
                                            <div class="form-group">
                                                <input type="hidden" class="form-control" name="status" value="1">
                                            </div>
                                            <center>
                                                <h4>Are you sure you want to Edit the Completion Grades?</h4>
                                            </center>
                                            <div class="form-group">
                                                <div class="form-row">
                                                    <div class="mt-2 col-md-12">
                                                        <label><span class="badge badge-warning">Enter the password Password here</span></label>
                                                        <input type="password" id="gradeauthpassCompletion{{ $datagenstud->sgid }}" name="gradeauthpass" class="form-control form-control-sm">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-between">
                                            <div>
                                                <button type="submit" class="btn btn-primary" id="editCompletionBtn{{ $datagenstud->sgid }}" disabled>Yes</button>
                                            </div>
                                            <button type="button" class="btn btn-danger float-right" data-dismiss="modal">No</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
    </div>
</div>


<script>
    var passgradeRoute = "{{ route('checkPassword') }}";
    var passgradeTokenRoute = "{{ csrf_token() }}";
</script>

@endsection
