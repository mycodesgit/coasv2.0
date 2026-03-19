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
                            <li class="breadcrumb-item active mt-1">Gradesheet</li>
                        </ol>
                    </div>
                </div>
                <div class="row g-3 mb-3 mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                    <h4>Gradesheet</h4>
                                </div>
                                <div class="row">
                                    <div class="col-md-12"> 
                                        <form method="GET" action="{{ route('studgrade_searchlist') }}" enctype="multipart/form-data" id="gradeSht">
                                            @csrf

                                            <div class="form-group mt-2">
                                                <div class="row g-3">
                                                    <div class="col-md-2">
                                                        <label>School Year: <span class="text-danger">*</span></label>
                                                        <select class="form-control form-control-sm" name="schlyear">
                                                            @foreach($sy as $datasy)
                                                                <option value="{{ $datasy->schlyear }}">{{ $datasy->schlyear }}</option>
                                                            @endforeach
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

                                                    <div class="col-md-2">
                                                        <label>&nbsp;</label>
                                                        <button type="submit" class="form-control form-control-sm btn btn-success btn-sm">OK</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>

                                        <div class="page-header mt-3 mb-3" style="border-bottom: 1px solid #04401f;"></div>

                                        <div class="row">
                                            <div class="col-md-10"></div>
                                            <div class="col-md-2">
                                                <div style="text-align: right">
                                                    @if($genstud && $genstud->isNotEmpty())
                                                        <form method="POST" action="{{ route('registrarupdateStatus_gradessubmit', ['subjID' => $genstud->first()->subjID]) }}" id="confirmationForm">
                                                            @csrf
                                                            <input type="hidden" name="subjID[]" value="{{ $genstud->first()->subjID }}">
                                                            <button type="button" class="btn btn-success btn-sm btn-block" id="submitgradeid" data-bs-toggle="modal" data-bs-target="#submitgrades" @if($gradereg == 0) disabled @endif>Submit Grades</button>
                                                        </form>
                                                    @else
                                                        <strong>No Students in this subject</strong>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-3 table-responsive" style="height: 400px">
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
                                                            function getEquivalentGrade($grade, $isOldSystem) {
                                                                if ($grade === 'INC') return ['gpa' => 'INC', 'status' => 'Incomplete'];
                                                                if ($grade === 'NN') return ['gpa' => 'NN', 'status' => 'No Name'];
                                                                if ($grade === 'NG') return ['gpa' => 'NG', 'status' => 'No Grade'];
                                                                if ($grade === 'Drp.') return ['gpa' => 'Drp.', 'status' => 'Drop'];

                                                                if ($isOldSystem) {
                                                                    // 4-point scale logic
                                                                    if ($grade >= 95 || $grade == 1) return ['gpa' => '1.0', 'status' => 'Passed'];
                                                                    if ($grade >= 94) return ['gpa' => '1.1', 'status' => 'Passed'];
                                                                    if ($grade >= 93) return ['gpa' => '1.2', 'status' => 'Passed'];
                                                                    if ($grade >= 92) return ['gpa' => '1.3', 'status' => 'Passed'];
                                                                    if ($grade >= 91) return ['gpa' => '1.4', 'status' => 'Passed'];
                                                                    if ($grade >= 90) return ['gpa' => '1.5', 'status' => 'Passed'];
                                                                    if ($grade >= 89) return ['gpa' => '1.6', 'status' => 'Passed'];
                                                                    if ($grade >= 88) return ['gpa' => '1.7', 'status' => 'Passed'];
                                                                    if ($grade >= 87) return ['gpa' => '1.8', 'status' => 'Passed'];
                                                                    if ($grade >= 86) return ['gpa' => '1.9', 'status' => 'Passed'];
                                                                    if ($grade >= 85 || $grade == 2) return ['gpa' => '2.0', 'status' => 'Passed'];
                                                                    if ($grade >= 84) return ['gpa' => '2.1', 'status' => 'Passed'];
                                                                    if ($grade >= 83) return ['gpa' => '2.2', 'status' => 'Passed'];
                                                                    if ($grade >= 82) return ['gpa' => '2.3', 'status' => 'Passed'];
                                                                    if ($grade >= 81) return ['gpa' => '2.4', 'status' => 'Passed'];
                                                                    if ($grade >= 80) return ['gpa' => '2.5', 'status' => 'Passed'];
                                                                    if ($grade >= 79) return ['gpa' => '2.6', 'status' => 'Passed'];
                                                                    if ($grade >= 78) return ['gpa' => '2.7', 'status' => 'Passed'];
                                                                    if ($grade >= 77) return ['gpa' => '2.8', 'status' => 'Passed'];
                                                                    if ($grade >= 76) return ['gpa' => '2.9', 'status' => 'Passed'];
                                                                    if ($grade >= 75 || $grade == 3) return ['gpa' => '3.0', 'status' => 'Passed'];
                                                                    if ($grade >= 74) return ['gpa' => '4.0', 'status' => 'Conditional'];
                                                                    if ($grade >= 73) return ['gpa' => '4.0', 'status' => 'Conditional'];
                                                                    if ($grade >= 72) return ['gpa' => '4.0', 'status' => 'Conditional'];
                                                                    if ($grade >= 71) return ['gpa' => '4.0', 'status' => 'Conditional'];
                                                                    if ($grade >= 70) return ['gpa' => '4.0', 'status' => 'Conditional'];
                                                                    return ['gpa' => '5.0', 'status' => 'Failure'];
                                                                } else {
                                                                    if ($grade >= 97 || $grade == 1) return ['gpa' => '1.00', 'status' => 'Passed'];
                                                                    if ($grade >= 94) return ['gpa' => '1.25', 'status' => 'Passed'];
                                                                    if ($grade >= 91) return ['gpa' => '1.50', 'status' => 'Passed'];
                                                                    if ($grade >= 88) return ['gpa' => '1.75', 'status' => 'Passed'];
                                                                    if ($grade >= 85 || $grade == 2) return ['gpa' => '2.00', 'status' => 'Passed'];
                                                                    if ($grade >= 82) return ['gpa' => '2.25', 'status' => 'Passed'];
                                                                    if ($grade >= 79) return ['gpa' => '2.50', 'status' => 'Passed'];
                                                                    if ($grade >= 76) return ['gpa' => '2.75', 'status' => 'Passed'];
                                                                    if ($grade >= 75 || $grade == 3) return ['gpa' => '3.00', 'status' => 'Passed'];
                                                                    if ($grade >= 70) return ['gpa' => '4.00', 'status' => 'Conditional'];
                                                                    return ['gpa' => '5.00', 'status' => 'Failure'];
                                                                }
                                                            }

                                                            function displayGrade($grade, $isOldSystem = false) {
                                                                if (is_numeric($grade) && strpos($grade, '.') === false) {
                                                                    $equivalent = getEquivalentGrade($grade, $isOldSystem);
                                                                    return $equivalent['gpa'];
                                                                }
                                                                return $grade;
                                                            }
                                                        }
                                                    @endphp
                                                    @php $no = 1; @endphp
                                                    @foreach($genstud as $datagenstud)
                                                        @php
                                                            $entryYear = (int)substr($datagenstud->studID, 0, 4);
                                                            $isOldSystem = $entryYear <= 2021;
                                                        @endphp
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
                                                                    {{ $datagenstud->subjComp ? displayGrade($datagenstud->subjComp, $isOldSystem) : displayGrade($datagenstud->subjFgrade, $isOldSystem) }}
                                                                </strong>
                                                            </td>
                                                            <td><strong>{{ $datagenstud->creditEarned }}</strong></td>
                                                            <td style="text-align:center;">
                                                                <div class="btn-group">
                                                                    <button type="button" class="btn btn-success dropdown-toggle dropdown-icon" data-bs-toggle="dropdown" aria-expanded="false">
                                                                        <span class="sr-only">Toggle Dropdown</span>
                                                                    </button>
                                                                    <div class="dropdown-menu" role="menu">
                                                                        @if ($datagenstud->subjFgrade != 'FAILURE' && $datagenstud->subjFgrade != 'INC' && $datagenstud->subjFgrade != 'Inc.' && $datagenstud->subjFgrade != 'inc')
                                                                            <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editgrades{{ $datagenstud->sgid }}">
                                                                                <i class="fas fa-pen"></i> Edit Grades
                                                                            </a>
                                                                        @endif
                                                                        @if ($datagenstud->compstat == 2 || empty($datagenstud->compstat))
                                                                            <a class="dropdown-item" id="editgradecompletionid" data-bs-toggle="modal" data-bs-target="#editCompletiongrades{{ $datagenstud->sgid }}">
                                                                                <i class="fa-solid fa-envelopes-bulk"></i> Completion
                                                                            </a>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </td>

                                                            <!-- Edit Grades Modal -->
                                                            <div class="modal fade mt-6" id="editgrades{{ $datagenstud->sgid }}" tabindex="-1" role="dialog" aria-hidden="true">
                                                                <div class="modal-dialog modal-sm">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h6 class="modal-title">
                                                                                <i class="fas fa-info-circle"></i> Confirmation
                                                                            </h6>
                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                                                                    <button type="submit" class="btn btn-success" id="editBtn{{ $datagenstud->sgid }}" disabled>Yes</button>
                                                                                </div>
                                                                                <button type="button" class="btn btn-danger float-right" data-bs-dismiss="modal">No</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Completion Grades Modal -->
                                                            <div class="modal fade mt-6" id="editCompletiongrades{{ $datagenstud->sgid }}" tabindex="-1" role="dialog" aria-hidden="true">
                                                                <div class="modal-dialog modal-sm">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h6 class="modal-title">
                                                                                <i class="fas fa-info-circle"></i> Confirmation
                                                                            </h6>
                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                                                                    <button type="submit" class="btn btn-success" id="editCompletionBtn{{ $datagenstud->sgid }}" disabled>Yes</button>
                                                                                </div>
                                                                                <button type="button" class="btn btn-danger float-right" data-bs-dismiss="modal">No</button>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('modal.submitgrades')

    <script>
        var passgradeRoute = "{{ route('checkPassword') }}";
        var passgradeTokenRoute = "{{ csrf_token() }}";
    </script>
@endsection
