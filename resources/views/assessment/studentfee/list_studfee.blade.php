@extends('layouts.master_assessment')

@section('title')
COAS - V1.0 || Student Fee
@endsection

@section('sideheader')
<h4>Assessment</h4>
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
            <li class="breadcrumb-item mt-1">Assessment</li>
            <li class="breadcrumb-item active mt-1">Student Fee</li>
        </ol>

        <p>
            @if(Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success')}}</div>
            @elseif (Session::has('fail'))
                <div class="alert alert-danger">{{Session::get('fail')}}</div>
            @endif
        </p>

        <div class="page-header" style="border-bottom: 1px solid #04401f;"></div>

        <div class="mt-3 row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="{{route('list_searchStudfee')}}" id="">
                            @csrf
                            <div class="page-header mt-1" style="border-bottom: 1px solid #04401f;">
                                <h5>Student Fee</h5>
                            </div>

                            <input type="hidden" name="campus" value="{{ Auth::guard('web')->user()->campus }}">

                            <div class="form-group">
                                <div class="form-row">
                                    <div class="mt-2 col-md-12">
                                        <label><span class="badge badge-secondary">Course</span></label>
                                        <select class="form-control  form-control-sm" name="prog_Code">
                                            <option disabled selected> ---Select---</option>
                                            @foreach($programsEn as $prog)
                                                <option value="{{ $prog->progCod }}">{{ $prog->progAcronym }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mt-2 col-md-12">
                                        <label><span class="badge badge-secondary">Year Level</span></label>
                                        <select class="form-control form-control-sm" name="yrlevel">
                                            <option disabled selected>---Select---</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                    </div>

                                    <div class="mt-2 col-md-12">
                                        <label><span class="badge badge-secondary">School Year</span></label>
                                        <select class="form-control form-control-sm" id="schlyear" name="schlyear"></select>
                                    </div>

                                    <div class="mt-2 col-md-12">
                                        <label><span class="badge badge-secondary">Semester</span></label>
                                        <select class="form-control form-control-sm" name="semester">
                                            <option disabled selected>---Select---</option>
                                            <option value="1">First Semester</option>
                                            <option value="2">Second Semester</option>
                                            <option value="3">Summer</option>
                                        </select>
                                    </div>

                                    <div class="col-md-12">
                                        <label>&nbsp;</label>
                                        <button type="submit" class="form-control form-control-sm btn btn-primary btn-sm">Search</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <table id="coa" class="table table-hover">
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



@endsection
