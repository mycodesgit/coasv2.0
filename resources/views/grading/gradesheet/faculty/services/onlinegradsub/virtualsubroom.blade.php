@extends('layouts.master_faculty')

@section('title')
CISS V.1.0 || Grading
@endsection

@section('sideheader')
<h4>Grading</h4>
@endsection

@section('sideheaderlegend')
<h4>Legend</h4>
@endsection

@yield('sidemenu')

@section('workspace')
    <div class="row">
        <div class="col-12">
            <div class="mb-6">
                <h1 class="fs-5 mb-4 d-none d-md-block">
                    <a href="{{ route('index.services') }}">
                        <i class="ti ti-arrow-left"></i> Services 
                    </a>
                    <span class="text-muted">/</span>
                    <a href="{{ route('semesterfac') }}">
                        Semester 
                    </a>
                    <span class="text-muted">/ Final Grade Submission</span>
                </h1>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-receipt"></i> List of Students in this Subject
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="card-outline-tabs">
                                    <div class="card-header p-0 border-bottom-0">
                                        <ul class="nav nav-tabs" id="custom-tabs-five-tab" role="tablist">
                                            <li class="nav-item ml-1">
                                                <a class="nav-link active text-dark text-bold" id="custom-tabs-one-tab" data-toggle="pill" href="#custom-tabs-one" role="tab" aria-controls="custom-tabs-one" aria-selected="true">Input Grades</a>
                                            </li>
                                            {{-- <li class="nav-item ml-1">
                                                <a class="nav-link text-dark text-bold" id="custom-tabs-two-tab" data-toggle="pill" href="#custom-tabs-two" role="tab" aria-controls="custom-tabs-two" aria-selected="false">Gradesheet PDF</a>
                                            </li> --}}
                                        </ul>
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-content" id="custom-tabs-four-tabContent">
                                            <div class="tab-pane fade show active" id="custom-tabs-one" role="tabpanel" aria-labelledby="custom-tabs-one-tab">
                                                <div class="alert alert-default alert-dismissible" style="background: url('{{ asset('template/img/img_code.jpg') }}')no-repeat; background-position: center; background-size: cover; border-radius: 5px;">
                                                    <h2 style="color: #fff"><i class="icon fas fa-file-lines"></i> <strong>{{ $sub->first()->sub_name }} - {{ $sub->first()->sub_title }}</strong></h2>
                                                    <span style="color: #fff; font-size: 12pt; margin-left: 45px">{{ $sub->first()->subSec }}</span>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="col-md-2 float-right mt-1 mb-3">
                                                            <form method="POST" action="{{ route('updateStatus_gradessubmit', ['subjID' => $sub->first()->subjID]) }}" id="confirmationForm">
                                                                @csrf
                                                                <input type="hidden" name="subjID[]" value="{{ $sub->first()->subjID }}">
                                                                <input type="hidden" name="semester" value="{{ request('semester') }}">
                                                                <input type="hidden" name="schlyear" value="{{ request('schlyear') }}">
                                                                <button type="button" class="btn btn-success btn-sm btn-block" id="submitgradeid" data-bs-toggle="modal" data-bs-target="#submitgrades" @if($grade == 0) disabled @endif>Submit Grades</button>
                                                            </form>
                                                        </div>
                                                        
                                                        <div class="card-body table-responsive p-0 text-dark" style="height: 500px;">
                                                            <table id="" class="table table-bordered table-striped table-head-fixed text-nowrap">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Student ID</th>
                                                                        <th>Name</th>
                                                                        <th>Final Grade</th>
                                                                        {{-- <th>Completion</th> --}}
                                                                        <th>Unit</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @php $no = 1; @endphp
                                                                    @if($sub)
                                                                        @foreach($sub as $studgrade)
                                                                        <tr>
                                                                            <td>{{ $no++ }}</td>
                                                                            <td><input type="hidden" value="{{ $studgrade->sgid }}"><strong>{{ $studgrade->studID }}</strong></td>
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
                                                                                    <strong style="{{ (is_numeric($studgrade->subjFgrade) && $studgrade->subjFgrade <= 69) || in_array($studgrade->subjFgrade, ['INC', 'Inc.', 'inc', 'NN', 'nn']) ? 'color: red;' : '' }}">{{ $studgrade->subjFgrade }}</strong>
                                                                                @endif

                                                                                
                                                                            </td>
                                                                            {{-- <td>
                                                                                @if ($studgrade->gstat == 2 && $studgrade->compstat != 2 && $studgrade->subjFgrade === 'INC')
                                                                                    <select class="form-control form-control-sm" name="subjComp" id="{{ $studgrade->sgid }}" onchange="updateGradeComp(this.id, this.value)">
                                                                                        <option></option>
                                                                                        @foreach ($grdCodeComp as $grdCodesc)
                                                                                            <option value="{{ $grdCodesc->grade }}" {{ $grdCodesc->grade == $studgrade->subjComp ? 'selected' : '' }}>
                                                                                                {{ $grdCodesc->grade }}
                                                                                            </option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                @elseif ($studgrade->compstat == 2 && $studgrade->subjFgrade === 'INC')
                                                                                    <strong>{{ $studgrade->subjComp }}</strong>
                                                                                @endif
                                                                            </td> --}}

                                                                            <td>
                                                                                <strong>
                                                                                    @if($studgrade->gstat == 2 && !empty($studgrade->subjFgrade))
                                                                                        @if(is_numeric($studgrade->subjFgrade) && $studgrade->subjFgrade <= 69)
                                                                                            <span style="color: red">0</span>
                                                                                        @elseif(in_array($studgrade->subjFgrade, ['INC', 'NN', 'NG']))
                                                                                            <span style="color: red">0</span>
                                                                                        @else
                                                                                            <span>{{ $studgrade->creditEarned }}</span>
                                                                                        @endif
                                                                                    @endif    
                                                                                </strong>
                                                                            </td>
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
                                            </div>
                                            <div class="tab-pane fade" id="custom-tabs-two" role="tabpanel" aria-labelledby="custom-tabs-two-tab">
                                                <iframe src="{{ route('PDFgradesheetnew', ['subjID' => $sub->first()->subjID]) }}" width="100%" height="500"></iframe>
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

    @include('modal.submitgrades')

@endsection
