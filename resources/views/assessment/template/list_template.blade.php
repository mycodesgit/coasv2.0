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
                            <li class="breadcrumb-item active mt-1">Student Fees Template</li>
                        </ol>
                    </div>
                </div>
                <div class="row g-3 mb-3 mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                    <h4>Student Fees Template</h4>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row mt-3">
                                            <div class="col-md-3">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <form method="GET" action="{{route('list_searchStudfeetemplate')}}" id="studFee">
                                                            @csrf
                                                            <div class="page-header mt-1" style="border-bottom: 1px solid #04401f;">
                                                                <h5>Student Fee</h5>
                                                            </div>

                                                            <div class="form-group">
                                                                <div class="form-row">
                                                                    <div class="mt-2 col-md-12">
                                                                        <label>Type: <span class="text-danger">*</span></label>
                                                                        <select class="form-control form-control-sm" name="temptype">
                                                                            <option disabled selected>---Select---</option>
                                                                            <option value="UGS">Under Graduate</option>
                                                                            <option value="GSS">Graduate</option>
                                                                        </select>
                                                                    </div>

                                                                    <div class="mt-2 col-md-12">
                                                                        <label>Semester: <span class="text-danger">*</span></label>
                                                                        <select class="form-control form-control-sm" name="semester">
                                                                            <option disabled selected>---Select---</option>
                                                                            <option value="1">First Semester</option>
                                                                            <option value="2">Second Semester</option>
                                                                            <option value="3">Summer</option>
                                                                        </select>
                                                                    </div>

                                                                    <div class="mt-2 col-md-12">
                                                                        <label>Year Level: <span class="text-danger">*</span></label>
                                                                        <select class="form-control form-control-sm" name="yrlevel">
                                                                            <option disabled selected>---Select---</option>
                                                                            <option value="New">New</option>
                                                                            <option value="Old">Old</option>
                                                                        </select>
                                                                    </div>

                                                                    <div class="col-md-12">
                                                                        <label>&nbsp;</label>
                                                                        <button type="submit" class="btn btn-success btn-sm btn-block">Search</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-9 mt-3">
                                                <table class="table table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Fund</th>
                                                            <th>Account Name</th>
                                                            <th>Amount</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        
                                                    </tbody>
                                                </table>
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
@endsection
