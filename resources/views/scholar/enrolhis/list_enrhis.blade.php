@extends('layouts.master_scholarship')

@section('title')
COAS - V2.0 || Student Enrollment History
@endsection

@section('sideheader')
<h4>Scholarship</h4>
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
            <li class="breadcrumb-item mt-1">Scholarship</li>
            <li class="breadcrumb-item active mt-1">Student Enrollment History</li>
        </ol>

        <div class="page-header" style="border-bottom: 1px solid #04401f;">
            <form method="GET" action="{{ route('viewsearchStudHistory') }}" id="studscholar">
                @csrf

                <div class="">
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">Type</span></label>
                                <select class="form-control form-control-sm" name="type">
                                    <option disabled selected>Select</option>
                                    <option value="lname">Last Name</option>
                                    <option value="studentID">Student ID Number</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label><span class="badge badge-secondary">Search</span></label>
                                <input type="text" name="query" class="form-control form-control-sm">
                            </div>

                            <div class="col-md-2">
                                <label>&nbsp;</label>
                                <button type="submit" class="form-control form-control-sm btn btn-success btn-sm">OK</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var historyReadRoute = "{{ route('searchStudHistory') }}";
</script>

@endsection

@section('script')
