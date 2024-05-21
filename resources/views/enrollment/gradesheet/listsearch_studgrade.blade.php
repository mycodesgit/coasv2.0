@extends('layouts.master_enrollment')

@section('title')
COAS - V2.0 || Grading
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
                            <select class="form-control form-control-sm" id="schlyear" name="schlyear"></select>
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

        {{-- <div style="border-bottom: 1px solid #04401f;">
            <form method="GET" action="" enctype="multipart/form-data" id="admissionApply">
                @csrf

                <div class="form-group mt-2">
                    <div class="form-row">
                        <div class="col-md-3">
                            <label><span class="badge badge-secondary">Subjects</span></label>
                            <select class="form-control form-control-sm select2bs4" onchange="subJect(this.value)" name="subid" id="subjectDropdown">
                                <option disabled selected>Select</option>
                                @foreach($data as $subjects)
                                    <option value="{{ $subjects->sid }}" data-desc="{{ $subjects->sub_desc }}" data-unit="{{ $subjects->sub_unit }}">
                                        {{ $subjects->sub_name }} - {{ $subjects->subSec }} {{ $subjects->sid }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label><span class="badge badge-secondary">Descriptive Title</span></label>
                            <input type="text" name="sub_desc" class="form-control form-control-sm" id="descriptiveTitle" readonly>
                        </div>

                        <div class="col-md-2">
                            <label><span class="badge badge-secondary">Unit</span></label>
                            <input type="text" name="sub_unit" class="form-control form-control-sm" id="unit" readonly>
                        </div>
                    </div>
                </div>
            </form>
        </div> --}}

        <div class="mt-3">
            <table id="studgradeid" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Subject Code</th>
                        <th>Subject Name</th>
                        <th>Descriptive Title</th>
                        <th>Year&Section</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $subjects)
                    <tr>
                        <td>{{ $subjects->sid }}</td>
                        <td>{{ $subjects->sub_name }}</td>
                        <td>{{ $subjects->sub_title }}</td>
                        <td>{{ $subjects->subSec }}</td>
                        <td>
                            <a href="{{ route('geneStudent1', ['id' => $subjects->sid, 'schlyear'  => request('schlyear'), 'semester'  => request('semester')]) }}" class="btn btn-primary btn-sm">
                                <i class="fa fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
    </div>
</div>



@endsection
