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
    <div class="row">
        <div class="col-12">
            <div class="mb-6">
                <h1 class="fs-5 mb-4 d-none d-md-block">
                    <a href="{{ route('index.services') }}">
                        <i class="ti ti-arrow-left"></i> Services 
                    </a>
                    <span class="text-muted">/ Final Grade Submission</span>
                </h1>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-receipt"></i> List of Subjects 
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    @if($facsubprogen->isEmpty())
                                        <div class="col-12">
                                            <div class="alert alert-secondary alert-dismissible">
                                                <div class="form-group">
                                                    <div class="form-row">
                                                        <div class="col-12 mt-3">
                                                            <div class="icheck-warning">
                                                                <label for="maintenance">
                                                                    <h3 style="margin-top: -5px"><i class="icon fas fa-exclamation-triangle text-warning"></i>No subject plotted or loaded in this academic year and semester!</h3>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        @foreach($facsubprogen as $datafacsubprogen)
                                            @auth('faculty')
                                                @if(Auth::guard('faculty')->user()->role == '943') 
                                                    <div class="col-lg-3 col-12">
                                                        <a href="{{ route('virtual_facultysubjectclass', ['id' => $datafacsubprogen->subjID, 'schlyear'  => request('schlyear'), 'semester'  => request('semester')]) }}">
                                                            <div class="card card-hover h-100" style="background: url('{{ asset('template/img/img_bookclub.jpg') }}')no-repeat; background-position: center; background-size: cover;">
                                                                <div class="card-body p-4">
                                                                    <div class="d-flex justify-content-between border-bottom pb-5 mb-3">
                                                                        <div class="text-light">
                                                                            <h3 class="fw-bold h4">{{ $datafacsubprogen->sub_name }}</h3>
                                                                            <span>{{ $datafacsubprogen->subSec }}</span><br>
                                                                            <span style="font-size: 9pt;">
                                                                                <span class="text-white">{{ $datafacsubprogen->schlyear }}</span>, {{ $datafacsubprogen->semester == 1 ? '1st Sem' : ($datafacsubprogen->semester == 2 ? '2nd Sem' : ($datafacsubprogen->semester == 3 ? 'Summer' : $datafacsubprogen->semester)) }}
                                                                            </span>
                                                                        </div>
                                                                        <div>
                                                                            <i class="ti ti-book fs-1 text-warning"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between align-items-center small">
                                                                        <div class="text-muted">
                                                                            <span class="text-white">
                                                                                @if(isset($datafacsubprogen->fname) && isset($datafacsubprogen->lname))
                                                                                    {{ substr($datafacsubprogen->fname, 0, 1) }}. {{ $datafacsubprogen->lname }}
                                                                                @else
                                                                                    No Instructor
                                                                                @endif
                                                                            </span>
                                                                        </div>
                                                                        <div><span class="badge bg-info textbold"><i class="ti ti-x"></i> Not Done</span></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>

                                                    {{-- <a href="{{ route('virtual_facultysubjectclass', ['id' => $datafacsubprogen->subjID, 'schlyear'  => request('schlyear'), 'semester'  => request('semester')]) }}">
                                                        <div class="col-lg-3 col-6">
                                                            <div class="card card-widget widget-user" style="border-radius: 20px !important;">
                                                                <div class="widget-user-header" style="background: url('{{ asset('template/img/img_bookclub.jpg') }}')no-repeat; background-position: center; background-size: cover;">
                                                                    <h5 class="widget-user-username text-light" style="text-align: left; font-weight: bold;">{{ $datafacsubprogen->sub_name }}</h5>
                                                                    <h6 class="widget-user-desc text-light" style="text-align: left;">{{ $datafacsubprogen->subSec }}</h6>
                                                                </div>
                                                                <div class="widget-user-image">
                                                                    <img class="img-circle elevation-2" src="{{ asset('template/img/user.png') }}" alt="User Avatar">
                                                                </div>
                                                                <div class="modal-footer justify-content-between" style="background-color: antiquewhite">
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
                                                    </a> --}}
                                                @elseif (Auth::guard('faculty')->user()->role == '943')
                                                    No
                                                @endif
                                            @endauth
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
