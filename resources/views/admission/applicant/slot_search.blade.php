@extends('layouts.master_admission')

@section('title')
CISS V.1.0 || Admission
@endsection
@php
use App\Models\AdmissionDB\Time;
use App\Models\AdmissionDB\Applicant;
use App\Models\AdmissionDB\Venue;
use App\Models\AdmissionDB\AdmissionDate;
@endphp

@section('workspace')
    <style>
        @keyframes pulse-blink {
            0% { opacity: 1; }
            50% { opacity: 0.2; }
            100% { opacity: 1; }
        }

        .blink-dot {
            animation: pulse-blink 1.2s infinite ease-in-out;
        }
    </style>
    <div class="row">
        <div class="col-12">
            <div class="mb-6">
                {{-- <h1 class="fs-5 mb-4 d-none d-md-block">Dashboard</h1> --}}
                <div class="card mb-3" style=" background-color: #e9ecef; margin-top: -10px">
                    <div class="card-body">
                        <ol class="breadcrumb" style="margin-bottom: -3px;">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}" class="btn btn-success btn-sm text-light">
                                    <i class="fas fa-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item mt-1">Admission</li>
                            <li class="breadcrumb-item active mt-1">Slots</li>
                        </ol>
                    </div>
                </div>
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                    <div>
                        <h1 class="h4 fw-bold mb-1" style="letter-spacing: -0.02em;">Availability Slots</h1>
                        <p class="text-muted small mb-0">(Select Year and Campus to view available slots)</p>
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-12">
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-search"></i> Search to show data
                                </h6>
                            </div>
                            <div class="card-body">
                                <form method="GET" action="{{ route('slots_search') }}">
                                    @csrf

                                    <div class="form-group">
                                        <div class="row g-3">
                                            <div class="col-md-2">
                                                <label class="form-label fw-semibold">Year: <span class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm" id="year" name="date">
                                                    @foreach($curryear as $datacurryear)
                                                        <option>{{ $datacurryear->adyear }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label fw-semibold">Campus: <span class="text-danger">*</span></label>
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
                                                    @else
                                                    @endif
                                                </select>
                                            </div>

                                            {{-- <div class="col-md-2">
                                                <label>&nbsp;</label>
                                                <button type="submit" class="form-control form-control-sm btn btn-success btn-sm">Search</button>
                                            </div> --}}
                                        </div>
                                    </div>
                                </form>

                                <div class="page-header mt-3" style="border-bottom: 1px solid #04401f;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="card border-0 card-animate">
                            <div id="slot-container" class="row g-3">
                                <!-- AJAX content -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
