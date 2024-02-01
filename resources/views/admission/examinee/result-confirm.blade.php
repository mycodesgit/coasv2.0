@extends('layouts.master_admission')

@section('title')
COAS - V1.0 || Confirm Examinee 
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
            <li class="breadcrumb-item mt-1">{{ $confirmresult->admission_id }}</li>
            <li class="breadcrumb-item mt-1" style="text-transform: uppercase;">{{$confirmresult->fname}} 
                @if($confirmresult->mname == null) 
                    @else {{ substr($confirmresult->mname,0,1) }}.
                @endif {{$confirmresult->lname}} 
                 
                @if($confirmresult->ext == 'N/A') 
                    @else{{$confirmresult->ext}}
                @endif</a></li>
            <li class="breadcrumb-item active mt-1">Confirm Examinee</li>
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
            <form method="POST" action="" class="mt-3">
                @csrf
                <center>
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-5">
                                <a href="{{ route('confirmPreEnrolment', $confirmresult->id) }}" type="button" class="btn btn-primary btn-lg">
                                    <i class="fas fa-check"></i> 
                                </a> <span style="font-size: 20pt" class="mt-2">Push to Confirmed Applicants</span>
                            </div>
                        </div>
                    </div>
                </center>
            </form>
        </div>
    </div>
</div>



@endsection

@section('script')