@extends('layouts.master_admission')

@section('title')
CISS V.1.0 || Higher Education Billing
@endsection

@section('sideheader')
<h4>Admission</h4>
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
            <li class="breadcrumb-item mt-1">Admission</li>
            <li class="breadcrumb-item active mt-1">Higher Education Billing</li>
        </ol>

        <p>
            @if(Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success')}}</div>
            @elseif (Session::has('fail'))
                <div class="alert alert-danger">{{Session::get('fail')}}</div>
            @endif
        </p>

        <div class="page-header" style="border-bottom: 1px solid #04401f;">
            <h4>Higher Education Billing</h4>
        </div>

        <div class="mt-2 row">
            <div class="col-md-12">
                <form method="GET" action="{{ route('adbillingRead_search') }}" id="adAppAd">
                    @csrf

                    <div class="">
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-2">
                                    <label><span class="badge badge-secondary">Academic Year</span></label>
                                    <select class="form-control form-control-sm" id="year" name="year"></select>
                                </div>

                                <div class="col-md-2">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="form-control form-control-sm btn btn-success btn-sm">Search</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="page-header" style="border-bottom: 1px solid #04401f;"></div>

        <div class="mt-3 row">
            <div class="col-md-12">
                <table id="adbilltable" class="table table-hover">
                    <thead>
                        <tr>
                            <th>Sequence #</th>
                            <th>Lastname</th>
                            <th>Givenname</th>
                            <th>Initial</th>
                            <th>Gender</th>
                            <th>Birthdate</th>
                            <th>Degree</th>
                            <th>Year Lv.</th>
                            <th>Contact</th>
                            <th>Admission Fee</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    var adbillReadRoute = "{{ route('getadbillingRead_search') }}";
</script>
@endsection
