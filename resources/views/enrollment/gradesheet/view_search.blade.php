@extends('layouts.master_enrollment')

@section('title')
COAS - V1.0 || Grading
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
            <li class="breadcrumb-item mt-1">Grading</li>
            <li class="breadcrumb-item mt-1"><a href="{{ url()->previous() }}"> @if($genstud)<strong>{{ $genstud->first()->sub_name }} {{ $genstud->first()->subSec }}</strong> @endif </a></li>
            <li class="breadcrumb-item active mt-1">Grade Sheet</li>
        </ol>

        <p>
            @if(Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success')}}</div>
            @elseif (Session::has('fail'))
                <div class="alert alert-danger">{{Session::get('fail')}}</div>
            @endif
        </p>

        <div style="border-bottom: 1px solid #04401f;">
            <form method="GET" action="{{ route('studgrade_searchlist') }}" enctype="multipart/form-data" id="gradeSht">
                @csrf

                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                    <h4>Grade Sheet</h4>
                </div>

                <div class="form-group mt-2">
                    <div class="form-row">
                        <div class="col-md-2">
                            <label><span class="badge badge-secondary">School Year</span></label>
                            <select class="form-control form-control-sm" id="schlyear" name="syear"></select>
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
            <form method="POST" action="{{ route('updateStatus_gradessubmit', ['subjID' => $genstud->first()->subjID]) }}" id="confirmationForm">
                @csrf
                <input type="hidden" name="subjID[]" value="{{ $genstud->first()->subjID }}">
                <button type="button" class="btn btn-primary btn-sm btn-block" id="submitgradeid" data-toggle="modal" data-target="#submitgrades" @if($gradereg == 0) disabled @endif>Submit Grades</button>
            </form>
        </div>

        <div class="mt-3 table-responsive p-0" style="height: 400px;">
            <table id="" class="table table-bordered table-head-fixed text-nowrap">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Final Grade</th>
                        <th>Completion</th>
                        <th>Unit</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @foreach($genstud as $data)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $data->studID }}</td>
                        <td><strong>{{ $data->lname }}, {{ $data->fname }} {{ strtoupper(substr($data->mname, 0, 1)) }}. </strong></td>
                        <td>
                            @if ($data->gstat == 1 || empty($data->subjFgrade))
                                    @if (!empty($data->subjFgrade))
                                        <select class="form-control form-control-sm" name="subjFgrade" id="{{ $data->sgid }}" onchange="updateGrade(this.id, this.value)">
                                            <option></option>
                                            @foreach ($grdCode as $grdCodes)
                                                <option value="{{ $grdCodes->grade }}" {{ $grdCodes->grade == $data->subjFgrade ? 'selected' : '' }}>
                                                    {{ $grdCodes->grade }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @endif
                                    @if (empty($data->subjFgrade))
                                        <select class="form-control form-control-sm" name="subjFgrade" id="{{ $data->sgid }}" onchange="updateGrade(this.id, this.value)">
                                            <option></option>
                                            @foreach ($grdCode as $grdCodes)
                                                <option value="{{ $grdCodes->grade }}">
                                                    {{ $grdCodes->grade }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @endif
                                @elseif ($data->gstat == 2)
                                    <strong>{{ $data->subjFgrade }}</strong>
                                @endif
                        </td>
                        <td>{{ $data->subjComp }}</td>
                        <td><strong>{{ $data->creditEarned }}</strong></td>
                        <td style="text-align:center;">
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                                <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu" role="menu" style="">
                                    <a class="dropdown-item" href="">
                                        <i class="fas fa-list-ol"></i> Edit Grades
                                    </a>
                                    @if ($data->subjFgrade >= 3)
                                    <a class="dropdown-item" href="">
                                        <i class="fa-solid fa-envelopes-bulk"></i> Completion
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
    </div>
</div>



@endsection
