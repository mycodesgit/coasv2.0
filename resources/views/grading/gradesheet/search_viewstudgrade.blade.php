@extends('layouts.master_grading')

@section('title')
CISS V.1.0 || Grading
@endsection

@section('sideheader')
<h4>Grading</h4>
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

        <div>
            <form method="GET" action="{{ route('gradesstud_search') }}" enctype="multipart/form-data" id="admissionApply">
                @csrf

                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                    <h4>Grade Sheet</h4>
                </div>

                <div class="form-group mt-2">
                    <div class="form-row">
                        <div class="col-md-2">
                            <label><span class="badge badge-secondary">School Year</span></label>
                            <input type="hidden" name="facID" value="{{ Auth::guard($guard)->user()->id }}">
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
        </div>

        <div class="mt-5 row">
            <h5>Total Search: {{ $totalSearchResults }},
                <small>
                    <i>School Year-<b>{{ request('syear') }}</b>,
                        Semester-<b>{{ request('semester') }}</b>
                    </i>
                </small>
            </h5>
            <div class="col-md-12">
                <table id="" class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Subject</th>
                            <th>Desc</th>
                            <th>Course&Section</th>
                            <th>Units</th>
                            <th>No. of Stud</th>
                            <th width="60">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach($datargrades1 as $dataItem)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $dataItem->sub_name }}</td>
                            <td>{{ $dataItem->sub_desc }}</td> 
                            <td>{{ $dataItem->subSec }}</td>
                            <td>{{ $dataItem->subUnit }}</td>
                            <td>{{ count($grades[$dataItem->subjectID]) }}</td>
                            <td>
                                <a href="{{ route('gradesstud', ['subjID' => $dataItem->subjectID]) }}" type="button" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>
</div>



@endsection
