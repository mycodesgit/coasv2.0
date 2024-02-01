@extends('layouts.master_grading')

@section('title')
COAS - V1.0 || Grading Student
@endsection

@section('sideheader')
<h4>Grading</h4>
@endsection

@section('sideheaderlegend')
<h4>Legend</h4>
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
            <li class="breadcrumb-item mt-1">Grading</li>
            <li class="breadcrumb-item mt-1">@if($gradeviewData)<strong>{{ $gradeviewData->first()->sub_name }} {{ $gradeviewData->first()->subSec }}</strong> @endif</li>
            <li class="breadcrumb-item active mt-1">GradeSheet</li>
        </ol>

        <p>
            @if(Session::has('success'))
                <div class="alert alert-success" id="alert">{{ Session::get('success')}}</div>
            @elseif (Session::has('fail'))
                <div class="alert alert-danger" id="alert">{{Session::get('fail')}}</div>
            @endif
        </p>

        <div>
            <div class="page-header" style="border-bottom: 1px solid #04401f;">
                <h4>Grade Sheet</h4>
            </div>
        </div>

        <div class="mt-2 row">
            <div class="col-md-10">
                <h5>
                    <small>
                        <i>School Year-<b>{{ $cursttngs->syear; }}</b>,
                            Semester-<b>{{ $cursttngs->semester; }}</b>,
                            No. of Students: <b>{{ $totalSearchResults }}</b>
                        </i>
                    </small>
                </h5>
            </div>
        </div>

        <div class="card-header p-0 border-bottom-0 mt-3">
            <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
                <li class="nav-item ml-1">
                    <a class="nav-link active" id="custom-tabs-one-tab" data-toggle="pill" href="#custom-tabs-one" role="tab" aria-controls="custom-tabs-one" aria-selected="true" style="color: #000">Grading</a>
                </li>
                <li class="nav-item ml-1">
                    <a class="nav-link" id="custom-tabs-two-tab" data-toggle="pill" href="#custom-tabs-two" role="tab" aria-controls="custom-tabs-two" aria-selected="false" style="color: #000">Gradesheet PDF</a>
                </li>
            </ul>
        </div>
        <div class="tab-content" id="custom-tabs-two-tabContent">
            <div class="tab-pane fade show active" id="custom-tabs-one" role="tabpanel" aria-labelledby="custom-tabs-one-tab">
                <div class="col-md-2 float-right mt-1 mb-3">
                    <form method="POST" action="{{ route('updateStatus_gradessubmit', ['subjID' => $gradeviewData->first()->subjID]) }}" id="confirmationForm">
                        @csrf
                        <input type="hidden" name="subjID[]" value="{{ $gradeviewData->first()->subjID }}">
                        <button type="button" class="btn btn-primary btn-sm btn-block" id="submitgradeid" data-toggle="modal" data-target="#submitgrades" @if($grade == 0) disabled @endif>Submit Grades</button>
                    </form>
                </div>

                @include('modal.submitgrades')

                <div class="col-md-12 mt-2">
                    <div class="card-body table-responsive p-0" style="height: 400px;">
                        <table id="" class="table table-bordered table-striped table-head-fixed text-nowrap">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Student ID</th>
                                    <th>Name</th>
                                    <th>Final Grade</th>
                                    <th>Completion</th>
                                    <th>Unit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @if($gradeviewData)
                                    @foreach($gradeviewData as $studgrade)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td><input type="hidden" name="id[]" value="{{ $studgrade->sgid }}">{{ $studgrade->studID }}</td>
                                        <td><strong>{{ $studgrade->lname }}, {{ $studgrade->fname }} {{ strtoupper(substr($studgrade->mname, 0, 1)) }}. </strong></td>
                                        <td>
                                            @if ($studgrade->gstat == 1 || empty($studgrade->subjFgrade))
                                                @if (!empty($studgrade->subjFgrade))
                                                    <select class="form-control form-control-sm" name="subjFgrade" id="{{ $studgrade->sgid }}" onchange="updateGrade(this.id, this.value)">
                                                        <option></option>
                                                        @foreach ($grdCode as $grdCodes)
                                                            <option value="{{ $grdCodes->grade }}" {{ $grdCodes->grade == $studgrade->subjFgrade ? 'selected' : '' }}>
                                                                {{ $grdCodes->grade }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                @endif
                                                @if (empty($studgrade->subjFgrade))
                                                    <select class="form-control form-control-sm" name="subjFgrade" id="{{ $studgrade->sgid }}" onchange="updateGrade(this.id, this.value)">
                                                        <option></option>
                                                        @foreach ($grdCode as $grdCodes)
                                                            <option value="{{ $grdCodes->grade }}">
                                                                {{ $grdCodes->grade }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                @endif
                                            @elseif ($studgrade->gstat == 2)
                                                <strong>{{ $studgrade->subjFgrade }}</strong>
                                            @endif

                                            
                                        </td>
                                        <td><strong>{{ $studgrade->subjComp }}</strong></td>
                                        <td><strong>{{ $studgrade->creditEarned }}</strong></td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="2">No data available</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="custom-tabs-two" role="tabpanel" aria-labelledby="custom-tabs-two-tab">
                <iframe src="{{ route('PDFgradesheetnew', ['subjID' => $gradeviewData->first()->subjID]) }} #toolbar=0" width="100%" height="500"></iframe>
            </div>
        </div>
    </div>
</div>

@endsection

