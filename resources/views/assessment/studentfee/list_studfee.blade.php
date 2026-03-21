@extends('layouts.master_assessment')

@section('title')
CISS V.1.0 || Assessment
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
                            <li class="breadcrumb-item mt-1">Assessment</li>
                            <li class="breadcrumb-item active mt-1">Student Fee</li>
                        </ol>
                    </div>
                </div>
                <div class="row g-3 mb-3 mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                    <h4>Student Fee</h4>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <form method="GET" action="{{ route('list_searchStudfee') }}" id="studFee">
                                            @csrf

                                            <div class="mt-2">
                                                <div class="form-group">
                                                    <div class="row g-3">
                                                        <div class="col-md-12">
                                                            <label>Course: <span class="text-danger">*</span></label>
                                                            <select class="form-control form-control-sm select2bs4" name="prog_Code">
                                                                <option disabled selected> ---Select---</option>
                                                                @foreach($programsEn as $prog)
                                                                    <option value="{{ $prog->progCod }}">{{ $prog->progAcronym }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group mt-3">
                                                    <div class="row g-3">
                                                        <div class="col-md-3">
                                                            <label>Year Level: <span class="text-danger">*</span></label>
                                                            <select class="form-control form-control-sm" name="yrlevel">
                                                                <option disabled selected>---Select---</option>
                                                                <option value="1">1</option>
                                                                <option value="2">2</option>
                                                                <option value="3">3</option>
                                                                <option value="4">4</option>
                                                                <option value="5">5</option>
                                                            </select>
                                                        </div>
                                                        
                                                        <div class="col-md-3">
                                                            <label>Academic Year: <span class="text-danger">*</span></label>
                                                            <select class="form-control form-control-sm" name="schlyear">
                                                                @foreach($sy as $datasy)
                                                                    <option value="{{ $datasy->schlyear }}">{{ $datasy->schlyear }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <label>Semester: <span class="text-danger">*</span></label>
                                                            <select class="form-control  form-control-sm" name="semester">
                                                                <option disabled selected>---Select---</option>
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
                                            </div>
                                        </form>
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
