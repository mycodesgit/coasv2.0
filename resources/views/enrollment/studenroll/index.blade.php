@extends('layouts.master_enrollment')

@section('title')
COAS - V1.0 || Enroll Student
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
            <li class="breadcrumb-item active mt-1">Enroll Student</li>
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
                <h4>Enroll Student</h4>
            </div> 
        </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mt-2 card" style="padding: 10px">
                        <div class="form-row">
                            <div class="col-md-12">
                                <label><span class="badge badge-secondary">Student Type</span></label>
                                <select class="form-control form-control-sm" name="en_status" id="studentType">
                                    <option value="1">New</option>
                                    <option value="2">Old</option>
                                </select>
                            </div>
                            <div class="col-md-12 mt-2">
                                <label><span class="badge badge-secondary">Search Student here.</span></label>
                                <input type="text" class="form-control" id="liveSearchInput" placeholder="Search for a student">
                            </div>
                            <div class="col-md-12">
                                <div id="liveSearchResultsContainer" class="mt-2"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <form method="GET" action="{{ route('searchStudEnroll') }}" enctype="multipart/form-data" id="enrollStud">
                        @csrf   

                        <div class="form-group mt-2 card" style="padding: 10px">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <label><span class="badge badge-secondary">Selected Student</span></label>
                                    <select class="form-control form-control-sm" id="selectedStudentInfo" name="stud_id" style="pointer-events: none;">
                                        
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-12">
                                    <label><span class="badge badge-secondary">School Year</span></label>
                                    <select class="form-control form-control-sm" id="schlyear" name="schlyear"></select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-12">
                                    <label><span class="badge badge-secondary">Semester</span></label>
                                    <select class="form-control form-control-sm" name="semester">
                                        <option disabled selected>Select</option>
                                        <option value="1" @if (old('type') == 1) {{ 'selected' }} @endif>First Semester</option>
                                        <option value="2" @if (old('type') == 2) {{ 'selected' }} @endif>Second Semester</option>
                                        <option value="3" @if (old('type') == 3) {{ 'selected' }} @endif>Summer</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-12">
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
</div>



@endsection
