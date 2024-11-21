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
<div class="card">
    <div class="card-body">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('homefaculty') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-home"></i>
                </a>
            </li>
            <li class="breadcrumb-item mt-1">Attendance Sheet</li>
            <li class="breadcrumb-item active mt-1">Subjects</li>
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
                <h4>Attendance Sheet</h4>
            </div>
        </div>

        <div class="mt-1 row">
            <div class="col-md-12">
                @php
                    $colors = ['red', 'green', 'lightblue', 'yellow', 'gray'];  // Colors for the folder icons
                    $fixedColors = ['skyblue', 'green', 'orange', 'yellow', 'gray'];  // Set fixed colors for folders 1 to 10
                @endphp

                <div class="row mt-2" style="border-bottom: 1px solid #04401f;">
                    @foreach($datafacsubprogen as $index => $attendfac)
                        @php
                            $randomColor = ($index < 10) ? $fixedColors[$index % count($fixedColors)] : $colors[array_rand($colors)];
                        @endphp
                        <div class="col-6 col-sm-2 col-md-2 text-center mb-3">
                            <h2>
                                <a href="{{ route('attendance_searchfacpdfpage', ['id' => $attendfac->subjID, 'schlyear'  => request('schlyear'), 'semester'  => request('semester')]) }}" class="text-dark">
                                    <i class="fa-regular fa-file-lines folder-icon" aria-hidden="true" style="color: {{ $randomColor }}; font-size: 80px;"></i>
                                    <br>
                                    <span style="color: {{ $randomColor }}; font-size: 14px; display: inline-block; margin-top: 5px;"></span>
                                    <span style="font-size: 12px; font-weight: bold;">{{ $attendfac->sub_name }} - {{ $attendfac->subSec }}</span>
                                </a>
                            </h2>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-12">
                @php
                    $id = request('id');
                @endphp
                <iframe src="{{ route('studsubjectsReadPDFfacattendance', ['id' => $id, 'schlyear' => request('schlyear'), 'semester' => request('semester')]) }}" style="width: 100%; height: 600px;" frameborder="0" class="mt-3"></iframe>
            </div>
        </div>
    </div>
</div>



@endsection
