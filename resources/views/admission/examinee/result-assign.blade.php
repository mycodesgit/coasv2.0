@extends('layouts.master_admission')

@section('title')
CISS v.1.0 || Examinee Results
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
            <li class="breadcrumb-item mt-1">{{ $assignresult->admission_id }}</li>
            <li class="breadcrumb-item mt-1" style="text-transform: uppercase;">{{$assignresult->fname}} 
                @if($assignresult->mname == null) 
                    @else {{ substr($assignresult->mname,0,1) }}.
                @endif {{$assignresult->lname}} 
                 
                @if($assignresult->ext == 'N/A') 
                    @else{{$assignresult->ext}}
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
            <form method="post" action="{{ route('examinee_result_save_nd', $assignresult->id) }}" enctype="multipart/form-data" id="admissionApply">
                @csrf
                @method('PUT')

                <div class="page-header mt-3" style="border-bottom: 1px solid #04401f;">
                    <h4>Assign Result</h4>
                </div>

                <div class="form-group mt-2">
                    <div class="form-row">
                        <div class="col-md-6">
                            <label><span class="badge badge-secondary">Raw Score</span></label>
                            <input type="text" class="form-control" name="raw_score" value="{{$assignresult->result->raw_score}}">
                        </div>

                        <div class="col-md-6">
                            <label><span class="badge badge-secondary">Percentile</span></label>
                            <input type="text" class="form-control" name="percentile" value="{{$assignresult->result->percentile}}">
                        </div>
                    </div>
                </div>

                <div class="row mt-5 mb-3">
                    <div class="col-md-12 col-6 d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary btn-md">
                            <i class="fas fa-check"></i>
                        </button>
                        &nbsp;&nbsp;
                        <button type="reset" class="btn btn-danger btn-md">
                            <i class="fas fa-refresh"></i>
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>



@endsection

@section('script')