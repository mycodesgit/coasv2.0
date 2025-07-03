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
<div class="card text-sm">
    <div class="card-body">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('homefaculty') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-home"></i>
                </a>
            </li>
            <li class="breadcrumb-item mt-1">Attendance Sheet</li>
            <li class="breadcrumb-item active mt-1">Subjects</li>
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
                <h4>Attendance Sheet</h4>
            </div>
        </div>

        <div class="mt-1 row">
            <div class="col-md-12">
                <form method="GET" action="{{ route('attendance_searchfac') }}" id="attendancegrading">
                    @csrf   

                    <div class="form-group mt-2" style="padding: 10px">
                        <div class="form-row">
                            <div class="col-md-3">
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
                                    <option value="1">First Semester</option>
                                    <option value="2">Second Semester</option>
                                    <option value="3">Summer</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label>&nbsp;</label>
                                <button type="submit" class="form-control form-control-sm btn btn-success btn-sm">OK</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
    </div>
</div>



@endsection
