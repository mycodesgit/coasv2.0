@extends('layouts.master_faculty')

@section('title')
CISS V.1.0 || Home
@endsection

{{-- @section('sideheader')
<h4>Grading</h4>
@endsection --}}

@yield('sidemenu')

@section('workspace')
    <div class="card">
        <div class="card-body">
            @if(Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success')}}</div>
            @elseif (Session::has('fail'))
                <div class="alert alert-danger">{{Session::get('fail')}}</div>
            @endif
            <div class="workspace-top" style="text-align: center;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="chart-responsive pt-1">
                            <canvas id="currSemesterunderprogBarChart" style="height:330px; min-height:330px"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection