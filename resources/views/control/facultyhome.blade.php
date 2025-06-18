@extends('layouts.master_faculty')

@section('title')
CISS V.1.0 || Home
@endsection

@section('sideheader')
<h4>Grading</h4>
@endsection

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
                    @if(request()->routeIs('homefaculty'))
                        {{-- <div class="col-lg-3 col-6">
                            <div class="small-box bg-info d-flex align-items-center justify-content-between pl-3 pr-3 pb-3 pt-3 card-curve" style="background-color: #00bc8c !important">
                                <div class="text-left">
                                    <div class="inner">
                                        <h3>1</h3>
                                        <p>No. of Stud</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="icon">
                                        <i class="fa fa-users"></i>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
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
                                        <a href="{{ route('virtual_facultysubjectclass', ['id' => $datafacsubprogen->subjID, 'schlyear'  => request('schlyear'), 'semester'  => request('semester')]) }}">
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
                                        </a>
                                    @elseif (Auth::guard('faculty')->user()->role == '943')
                                        No
                                    @endif
                                @endauth
                            @endforeach
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection