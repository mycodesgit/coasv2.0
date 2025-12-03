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
    <section class="section mt-4">
        <!-- <div class="section-header" style="border-radius: 20px !important;">
            <h1>Blank Page</h1>
        </div> -->

        <div class="section-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="card" style="border-radius: 20px">
                        <div class="card-body">
                            <h6>
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
                            </h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card" style="border-radius: 20px">
                        <div class="card-body">
                            <div class="mt-5 row">
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
                                                </a>
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
    </section>
@endsection
