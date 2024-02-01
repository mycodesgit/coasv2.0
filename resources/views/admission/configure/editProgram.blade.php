@extends('layouts.master_admission')

@section('title')
COAS - V1.0 || Edit Program
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
            <li class="breadcrumb-item mt-1">Configure</li>
            <li class="breadcrumb-item active mt-1">Edit Program</li>
        </ol>

        <div class="page-header" style="border-bottom: 1px solid #04401f;">

        </div>
        <div class="mt-3">

            <p>
                @if(Session::has('success'))
                    <div class="alert alert-success" id="alert">{{ Session::get('success')}}</div>
                @elseif (Session::has('fail'))
                    <div class="alert alert-danger" id="alert">{{Session::get('fail')}}</div>
                @endif
            </p>

            <form method="post" action="{{ route('programEdit')}}" enctype="multipart/form-data" id="admissionApply">
                @csrf
                <input type="hidden" name="id" value="{{ $program->id }}">
                <div class="form-group">
                    <div class="form-row">
                        <div class="col-md-2">
                            <label><span class="badge badge-secondary">Code</span></label>
                            <input type="text" name="code" class="form-control form-control-sm" value="{{$program->code}}" oninput="this.value = this.value.toUpperCase()">
                        </div>

                        <div class="col-md-10">
                            <label><span class="badge badge-secondary">Program</span></label>
                            <input type="text" name="program" class="form-control form-control-sm" value="{{$program->program}}" oninput="var words = this.value.split(' '); for(var i = 0; i < words.length; i++){ words[i] = words[i].substr(0,1).toUpperCase() + words[i].substr(1); } this.value = words.join(' ');">
                        </div>
                    </div>
                </div>

                <div class="row mt-5 mb-3">
                    <div class="col-md-12 col-6">
                        <button type="submit" class="btn btn-primary btn-md">
                            <i class="fas fa-check"></i> Update
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>



@endsection

@section('script')