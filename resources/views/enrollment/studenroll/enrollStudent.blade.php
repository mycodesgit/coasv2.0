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
                <div class="row">
                    <div class="col-md-9">
                        <h4>Enroll Student</h4>
                    </div>
                </div>
            </div>

            {{-- <div class="col-md-2 mt-2 float-right">
                
            </div> --}}

            <div class="row">
                <div class="col-md-10">
                    <div class="card mt-2" style="background-color: #e9ecef">
                        <div class="body pr-2 pl-2 pt-2">
                            <div class="form-group">
                                <div class="form-row">
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
                                    <div class="col-md-3">
                                        <label><span class="badge badge-secondary">Ext. Name</span></label>
                                        <input type="text" name="" class="form-control form-control-sm" value="{{ $student->ext }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card" style="background-color: #e9ecef">
                        <div class="body pr-2 pl-2 pt-2">
                            <form method="GET" action="{{ route('courseEnroll_list_search') }}" id="classEnroll">
                                <div class="container">
                                    <div class="form-group">
                                        <div class="form-row">
                                            <div class="col-md-3">
                                                <label><span class="badge badge-secondary">Course</span></label>
                                                <select class="form-control form-control-sm" name="course">
                                                    <option disabled selected>Select</option>
                                                    @foreach ($program as $programsItem)
                                                    <option value="{{ $programsItem->code }}" {{ $programsItem->code == $selectedProgram ? 'selected' : '' }}>{{ $programsItem->code }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label><span class="badge badge-secondary">Year&Section</span></label>
                                                <select class="form-control form-control-sm" name="yrsection">
                                                    <option disabled selected>Select</option>
                                                    @foreach ($yrSections as $yrSection)
                                                        <option value="{{ $yrSection }}">{{ $yrSection }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label><span class="badge badge-secondary">Student Level</span></label>
                                                <select class="form-control form-control-sm" name="yrsection">
                                                    <option disabled selected>Select</option>
                                                    @foreach ($studlvl as $data)
                                                    <option value="{{ $data->studLevel }}">{{ $data->studLevel }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <label><span class="badge badge-secondary">Student Level</span></label>
                                                <select class="form-control form-control-sm" name="studscholar">
                                                    <option>NO SCHOLARSHIP</option>
                                                    @foreach ($studscholar as $data)
                                                    <option value="{{ $data->scholar_name }}">{{ $data->scholar_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label><span class="badge badge-secondary">Major</span></label>
                                                <select class="form-control form-control-sm" name="major">
                                                    <option>NONE</option>
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label><span class="badge badge-secondary">Minor</span></label>
                                                <select class="form-control form-control-sm" name="minor">
                                                    <option>NONE</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card mt-2" style="background-color: #e9ecef">
                        <div class="card-body">
                            <a href="{{ route('searchStud') }}" class="form-control form-control-sm btn btn-success btn-sm">Enroll New</a>
                            <a href="" class="form-control form-control-sm btn btn-success btn-sm mt-2 btnprim">Print RF</a>
                            <a href="" class="form-control form-control-sm btn btn-success btn-sm mt-2 btnprim">Check Conflict</a>
                            <a href="" class="form-control form-control-sm btn btn-success btn-sm mt-2 btnprim">Est. No. of Stud.</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>

@endsection
