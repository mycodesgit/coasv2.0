@extends('layouts.master_admission')

@section('title')
COAS - V1.0 || Examinee Results
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
            <li class="breadcrumb-item mt-1">{{ $examinee->admission_id }}</li>
            <li class="breadcrumb-item mt-1" style="text-transform: uppercase;">{{$examinee->fname}} 
                @if($examinee->mname == null) 
                    @else {{ substr($examinee->mname,0,1) }}.
                @endif {{$examinee->lname}} 
                 
                @if($examinee->ext == 'N/A') 
                    @else{{$examinee->ext}}
                @endif</a></li>
            <li class="breadcrumb-item active mt-1">Assign Results</li>
        </ol>

        <div class="page-header" style="border-bottom: 1px solid #04401f;"></div>

        <div class="mt-5">
            <p>
                @if(Session::has('success'))
                    <div class="alert alert-success" id="alert">{{ Session::get('success')}}</div>
                @elseif (Session::has('fail'))
                    <div class="alert alert-danger" id="alert">{{Session::get('fail')}}</div>
                @endif
            </p>
            <iframe src="{{ route('genPreEnrolment', $examinee->id) }}" width="100%" height="800"></iframe>
        </div>
    </div>
</div>



@endsection

@section('script')