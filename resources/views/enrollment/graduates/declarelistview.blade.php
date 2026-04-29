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
                            <li class="breadcrumb-item active mt-1">Graduates</li>
                        </ol>
                    </div>
                </div>
                <div class="row g-3 mb-3 mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                    <h4>Graduates</h4>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mt-2">
                                        <form method="GET" action="{{ route('gradstud.store') }}" id="enrollStud">
                                            @csrf   

                                            <div class="form-group mt-2">
                                                <div class="row g-3">
                                                    <div class="col-md-3">
                                                        <label>School Year: <span class="text-danger">*</span></label>
                                                        <select class="form-control form-control-sm" name="schlyear" id="schlyear1">
                                                            @foreach($sy as $datasy)
                                                                <option value="{{ $datasy->schlyear }}">{{ $datasy->schlyear }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label>Semester: <span class="text-danger">*</span></label>
                                                        <select class="form-control form-control-sm" name="semester" id="semester">
                                                            <option disabled selected>Select</option>
                                                            <option value="1">First Semester</option>
                                                            <option value="2">Second Semester</option>
                                                            <option value="3">Summer</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label>&nbsp;</label>
                                                        <button type="submit" class="form-control form-control-sm btn btn-success btn-sm">Search</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>

                                        <div class="page-header mt-3" style="border-bottom: 1px solid #04401f;"></div>

                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
