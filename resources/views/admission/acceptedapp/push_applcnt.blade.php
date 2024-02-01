@extends('layouts.master_admission')

@section('title')
COAS - V1.0 || Accept Examinee 
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
            <li class="breadcrumb-item mt-1">{{ $applicant->admission_id }}</li>
            <li class="breadcrumb-item mt-1" style="text-transform: uppercase;">{{$applicant->fname}} 
                @if($applicant->mname == null) 
                    @else {{ substr($applicant->mname,0,1) }}.
                @endif {{$applicant->lname}} 
                 
                @if($applicant->ext == 'N/A') 
                    @else{{$applicant->ext}}
                @endif</a></li>
            <li class="breadcrumb-item active mt-1">Push to Enrollment</li>
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
                                @if($applicant->en_status == 1)
                                    <a href="{{ route('save_enroll_applicant', $applicant->id) }}" type="button" class="btn btn-primary btn-lg">
                                        <i class="fas fa-check"></i> 
                                    </a> <span style="font-size: 20pt" class="mt-2">Push to Enrollment</span>
                                @else
                                    <a href="#" type="button" class="btn btn-danger btn-md" disabled>
                                        <i class="fas fa-check"></i> 
                                    </a> <span style="font-size: 20pt" class="mt-2">Already Done Push to Enrollment</span>
                                @endif
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