@extends('layouts.master_enrollment')

@section('title')
CISS V.1.0 || Student List per Subjects
@endsection

@section('sideheader')
<h4>Enrollment</h4>
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
            <li class="breadcrumb-item mt-1">Enrollment</li>
            <li class="breadcrumb-item active mt-1">Student List per Subjects</li>
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
                <h4>Student List per Subjects</h4>
            </div> 
        </div>
            <div class="row">
                <div class="col-md-12 mt-5">
                    <div>
                        @php
                            $id = request('id');
                        @endphp
                        <iframe src="{{ route('studsubjectsReadPDF', ['id' => $id, 'schlyear' => request('schlyear'), 'semester' => request('semester')]) }}" style="width: 100%; height: 600px;" frameborder="0" class="mt-3"></iframe>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>


@endsection
