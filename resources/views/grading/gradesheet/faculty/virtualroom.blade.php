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
            <li class="breadcrumb-item mt-1">Grade Sheet</li>
            <li class="breadcrumb-item mt-1"><a href="{{ route('semesterfac') }}">Semester</a></li>
            <li class="breadcrumb-item active mt-1">
                @php
                    $semester = request('semester');
                    $semesterName = '';

                    if ($semester == 1) {
                        $semesterName = '1st Semester';
                    } elseif ($semester == 2) {
                        $semesterName = '2nd Semester';
                    } elseif ($semester == 3) {
                        $semesterName = 'Summer';
                    }
                @endphp

                {{ $semesterName }} - {{ request('schlyear') }}
            </li>
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
                <h4>Grade Sheet</h4>
            </div>
        </div>

        <div class="mt-5 row">
            @foreach($facsubprogen as $datafacsubprogen)
                <div class="col-lg-3 col-6">
                    <div class="card card-widget widget-user">
                        <div class="widget-user-header" style="background: url('{{ asset('template/img/img_bookclub.jpg') }}')no-repeat; background-position: center; background-size: cover;">
                            <h5 class="widget-user-username text-light" style="text-align: left; font-weight: bold;">{{ $datafacsubprogen->sub_name }}</h5>
                            <h6 class="widget-user-desc text-light" style="text-align: left;">{{ $datafacsubprogen->subSec }}</h6>
                        </div>
                        <div class="widget-user-image">
                            <img class="img-circle elevation-2" src="{{ asset('template/img/user.png') }}" alt="User Avatar">
                        </div>
                        <div class="modal-footer justify-content-between">
                            <h6 class="widget-user-desc text-dark">
                                @if(isset($datafacsubprogen->fname) && isset($datafacsubprogen->lname))
                                    {{ substr($datafacsubprogen->fname, 0, 1) }}. {{ $datafacsubprogen->lname }}
                                @else
                                    No Instructor
                                @endif
                            </h6>

                            @auth('faculty')
                                @if(Auth::guard('faculty')->user()->role == '943') 
                                    <a href="{{ route('virtual_facultysubjectclass', ['id' => $datafacsubprogen->subjID, 'schlyear'  => request('schlyear'), 'semester'  => request('semester')]) }}" class="btn btn-outline-success btn-sm">
                                        <i class="fas fa-folder-open"></i>
                                    </a>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
    </div>
</div>



@endsection
