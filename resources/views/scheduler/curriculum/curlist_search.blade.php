@extends('layouts.master_classScheduler')

@section('title')
CISS V.1.0 || Curriculumn
@endsection

@section('sideheader')
<h4>Option</h4>
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
            <li class="breadcrumb-item mt-1">Scheduler</li>
            <li class="breadcrumb-item active mt-1">Option</li>
            <li class="breadcrumb-item active mt-1">Curriculumn</li>
        </ol>

        <p>
            @if(Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success')}}</div>
            @elseif (Session::has('fail'))
                <div class="alert alert-danger">{{Session::get('fail')}}</div>
            @endif
        </p>

        <div class="page-header">
            <form method="GET" action="{{ route('curRead_search') }}" id="curriculumSearch">
                @csrf

                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                    <h4>Curriculumn</h4>
                </div>

                <div class="mt-1">
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-4">
                                <label><span class="badge badge-secondary">Course</span></label>
                                <select class="form-control form-control-sm select2bs4" name="progCod" id="progCod">
                                    <option disabled selected>Select a course</option>
                                    @foreach ($program as $programs)
                                        <option value="{{ $programs->progCod }}">
                                            {{ $programs->progAcronym }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label>&nbsp;</label>
                                <button type="submit" class="form-control form-control-sm btn btn-success btn-sm">OK</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <hr>

        <div class="row">
            <div class="col-md-12">
                <div class="card-outline-tabs">
                    <div class="card-header p-0 border-bottom-0">
                        <ul class="nav nav-tabs" id="custom-tabs-five-tab" role="tablist">
                            <li class="nav-item ml-1">
                                <a class="nav-link active text-dark text-bold" id="custom-tabs-one-tab" data-toggle="pill" href="#custom-tabs-one" role="tab" aria-controls="custom-tabs-one" aria-selected="true">Add Subject Offer Per Sem</a>
                            </li>
                            <li class="nav-item ml-1">
                                <a class="nav-link text-dark text-bold" id="custom-tabs-two-tab" data-toggle="pill" href="#custom-tabs-two" role="tab" aria-controls="custom-tabs-two" aria-selected="false">List of Curriculumn</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-four-tabContent">
                            <div class="tab-pane fade show active" id="custom-tabs-one" role="tabpanel" aria-labelledby="custom-tabs-one-tab">
                                <div class="row">
                                    <div class="col-md-3">
                                        <form method="post" action="{{ route('classEnrollCreate') }}"  id="classEnrollAdd">
                                            @csrf
                                            <div class="page-header mt-3" style="border-bottom: 1px solid #04401f;">
                                                <h5>Add</h5>
                                            </div>

                                            <input type="hidden" name="campus" class="form-control  form-control-sm" value="{{ Auth::guard('web')->user()->campus; }}">

                                            <div class="form-group mt-2">
                                                <div class="form-row">
                                                    <div class="mt-2 col-md-12">
                                                        <label><span class="badge badge-secondary">Programs</span></label>
                                                        <select class="form-control form-control-sm" name="progCode" id="">
                                                            <option disabled selected>Select</option>
                                                            @foreach ($program as $programs)
                                                                <option value="{{ $programs->progCod }}">
                                                                    {{ $programs->progAcronym }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    
                                                    <div class="mt-2 col-md-12">
                                                        <label><span class="badge badge-secondary">Year & Section</span></label>
                                                        <input type="text" name="classSection" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()" placeholder="e.g., 1-A">
                                                    </div>

                                                    <div class="mt-2 col-md-12">
                                                        <label><span class="badge badge-secondary">Est. Number of Student</span></label>
                                                        <input type="number" name="classno" class="form-control form-control-sm" min="0">
                                                    </div>

                                                    @if(Auth::guard('web')->user()->lname == 'Gargoles' || Auth::guard('web')->user()->lname == 'Level')
                                                        <div class="mt-2 col-md-12">
                                                            <label><span class="badge badge-secondary">Add On</span></label>
                                                            <select class="form-control form-control-sm" name="progType" id="">
                                                                <option disabled selected>Select</option>
                                                                <option value="SIKAT - CAMI">SIKAT - CAMI</option>
                                                            </select>
                                                        </div>
                                                    @endif

                                                    <div class="col-md-12">
                                                        <label>&nbsp;</label>
                                                        <button type="submit" class="form-control form-control-sm btn btn-primary btn-sm">Save</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection
