@extends('layouts.master_admission')

@section('title')
CISS V.1.0 || Admission
@endsection

@yield('sidemenu')

@section('workspace')
    <div class="row">
        <div class="col-12">
            <div class="mb-6">
                {{-- <h1 class="fs-5 mb-4 d-none d-md-block">Dashboard</h1> --}}
                <div class="card" style=" background-color: #e9ecef; margin-top: -10px">
                    <div class="card-body">
                        <ol class="breadcrumb" style="margin-bottom: -3px;">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}" class="btn btn-success btn-sm text-light">
                                    <i class="fas fa-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item mt-1">Admission</li>
                            <li class="breadcrumb-item active mt-1">Accepted Applicants</li>
                        </ol>
                    </div>
                </div>
                <div class="row g-3 mb-3 mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                    <h4>Accepted Applicants</h4>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <form method="GET" action="{{ route('srchacceptedList') }}">
                                            @csrf

                                            <div class="form-group mt-3">
                                                <div class="row g-3">
                                                    <div class="col-md-2">
                                                        <label>Year: <span class="text-danger">*</span></label>
                                                        <select class="form-control form-control-sm" id="year" name="year">
                                                            @foreach($curryear as $datacurryear)
                                                                <option>{{ $datacurryear->adyear }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label>Campus: <span class="text-danger">*</span></label>
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
                                                            @if(Auth::user()->role == 0 || (Auth::user()->campus == 'MC' && Auth::user()->role == 1))
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
                                                        <label>Strand: <span class="text-danger">*</span></label>
                                                        <select class="form-control  form-control-sm" name="strand">
                                                            <option value=""> --Select-- </option>
                                                            @foreach($strand as $datastrand)
                                                                <option value="{{ $datastrand->code }}">{{ $datastrand->strand }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label>&nbsp;</label>
                                                        <button type="submit" class="form-control form-control-sm btn btn-success btn-sm">Search</button>
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
@endsection
