@extends('layouts.master_assessment')

@section('title')
CISS V.1.0 || Higher Education Billing
@endsection

@section('sideheader')
<h4>Assessment</h4>
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
            <li class="breadcrumb-item mt-1">Assessment</li>
            <li class="breadcrumb-item active mt-1">Higher Education Billing</li>
        </ol>

        <p>
            @if(Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success')}}</div>
            @elseif (Session::has('fail'))
                <div class="alert alert-danger">{{Session::get('fail')}}</div>
            @endif
        </p>

        <div class="page-header" style="border-bottom: 1px solid #04401f;">
            <h4>Higher Education Billing</h4>
        </div>

        <div class="mt-2 row">
            <div class="col-md-12">
                <form method="GET" action="{{ route('hebillingRead_search') }}" id="studstatesum">
                    @csrf

                    <div class="">
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-2">
                                    <label><span class="badge badge-secondary">Academic Year</span></label>
                                    <select class="form-control form-control-sm" name="schlyear">
                                        @foreach($sy as $datasy)
                                            <option value="{{ $datasy->schlyear }}">{{ $datasy->schlyear }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label><span class="badge badge-secondary">Campus</span></label>
                                    <select class="form-control form-control-sm" name="campus" id="campus">
                                        <option value="{{Auth::user()->campus}}">
                                            @if (Auth::user()->campus == 'MC') Main 
                                                @elseif(Auth::user()->campus == 'VC') Victorias 
                                                @elseif(Auth::user()->campus == 'SCC') San Carlos 
                                                @elseif(Auth::user()->campus == 'HC') Hinigaran 
                                                @elseif(Auth::user()->campus == 'MP') Moises Padilla 
                                                @elseif(Auth::user()->campus == 'IC') Ilog 
                                                @elseif(Auth::user()->campus == 'CA') Candoni 
                                                @elseif(Auth::user()->campus == 'CC') Cauayan 
                                                @elseif(Auth::user()->campus == 'SC') Sipalay 
                                                @elseif(Auth::user()->campus == 'HinC') Hinobaan 
                                                @elseif(Auth::user()->campus == 'VE') Valladolid 
                                            @endif
                                        </option>
                                        @if(Auth::user()->role == 0 && Auth::user()->role == 23)
                                            <option value="MC">Main</option>
                                            <option value="VC">Victorias</option>
                                            <option value="SCC">San Carlos</option>
                                            <option value="HC">Hinigaran</option>
                                            <option value="MP">Moises Padilla</option>
                                            <option value="IC">Ilog</option>
                                            <option value="CA">Candoni</option>
                                            <option value="CC">Cauayan</option>
                                            <option value="SC">Sipalay</option>
                                            <option value="HinC">Hinobaan</option>
                                            <option value="VE">Valladolid</option>
                                        @else
                                        @endif
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label><span class="badge badge-secondary">Semester</span></label>
                                    <select class="form-control  form-control-sm" name="semester">
                                        <option disabled selected>---Select---</option>
                                        <option value="1">First Semester</option>
                                        <option value="2">Second Semester</option>
                                        <option value="3">Summer</option>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="form-control form-control-sm btn btn-success btn-sm">Search</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
