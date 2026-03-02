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
                    <span class="text-muted">/ Schedule</span>
                </h1>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-file"></i> View Schedule
                                </h6>
                            </div>
                            <div class="card-body">
                                <form method="GET" action="{{ route('schedulefac_searchview') }}" id="attendancegrading">
                                    @csrf   

                                    <div class="">
                                        <div class="row">
                                            <div class="col-md-3">
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
            </div>
        </div>
    </div>
@endsection
