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
                            <li class="breadcrumb-item active mt-1">Dashboard</li>
                        </ol>
                    </div>
                </div>
                <div class="row g-3 mb-3 mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header pt-3">
                                <h6 class="card-title">No. of Applicants in every campus in this year {{ $currentYear }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="chart">
                                    <div class="position-relative mb-4">
                                        <canvas id="sales-chart"
                                                data-main="{!! $MainRegCount !!}"
                                                data-main-sched="{!! $MainSchedCount !!}"
                                                data-ilog="{!! $IlogRegCount !!}"
                                                data-ilog-sched="{!! $IlogSchedCount !!}" 
                                                data-cauayan="{!! $CauayanRegCount !!}"
                                                data-cauayan-sched="{!! $CauayanSchedCount !!}" 
                                                data-candoni="{!! $CandoniRegCount !!}"
                                                data-candoni-sched="{!! $CandoniSchedCount !!}" 
                                                data-siplay="{!! $SipalayRegCount !!}"
                                                data-siplay-sched="{!! $SipalaySchedCount !!}" 
                                                data-hinobaan="{!! $HinobaanRegCount !!}"
                                                data-hinobaan-sched="{!! $HinobaanSchedCount !!}" 
                                                data-hinigaran="{!! $HinigaranRegCount !!}"
                                                data-hinigaran-sched="{!! $HinigaranSchedCount !!}" 
                                                data-moises="{!! $MoisesRegCount !!}"
                                                data-moises-sched="{!! $MoisesSchedCount !!}" 
                                                data-sancarlos="{!! $SancarlosRegCount !!}"
                                                data-sancarlos-sched="{!! $SancarlosSchedCount !!}" 
                                                data-victorias="{!! $VictoriasRegCount !!}"
                                                data-victorias-sched="{!! $VictoriasSchedCount !!}" 
                                                height="350">
                                        </canvas>
                                    </div>
                                    <div class="d-flex flex-row justify-content-end">
                                        <span class="mr-5">
                                            <i class="fas fa-square" style="color: #90ee90"></i> Registered Applicant: No schedule yet
                                        </span>
                                        &nbsp;&nbsp;
                                        <span class="mr-2">
                                            <i class="fas fa-square" style="color: #00a65a"></i> Registered Applicant: Scheduled
                                        </span>
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
