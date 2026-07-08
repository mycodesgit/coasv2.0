@extends('layouts.master_student')

@section('title')
    CISS V.1.0 || Services
@endsection

@section('body')
    <div class="row ">
        <div class="col-12">
            <div class="mb-6">
                <h1 class="fs-5 mb-4 d-none d-md-block">
                    <a href="{{ route('show.services') }}">
                        <i class="ti ti-arrow-left"></i> Services 
                    </a>
                    <span class="text-muted">/ Class Schedule</span> 
                </h1>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-server"></i> Class Schedule
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3 mb-2">
                                    @foreach ($enrollmentHistory as $history)
                                        <div class="col-lg-3 col-12">
                                            <a href="{{ route('show.scheduleclass', ['schlyear' => $history->schlyear, 'semester' => $history->semester]) }}">
                                                <div class="card card-hover h-100" style="background: url('{{ asset('template/img/img_bookclub.jpg') }}')no-repeat; background-position: center; background-size: cover;">
                                                    <div class="card-body p-4">
                                                        <div class="d-flex justify-content-between border-bottom pb-5 mb-3">
                                                            <div class="text-light">
                                                                <h3 class="fw-bold h4">{{ $history->progAcronym }} {{ $history->studYear }}-{{ $history->studSec }}</h3>
                                                                <span>Class Schedule</span><br>
                                                                <span style="font-size: 9pt;">
                                                                    
                                                                </span>
                                                            </div>
                                                            <div>
                                                                <i class="ti ti-book fs-1 text-warning"></i>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex justify-content-between align-items-center small">
                                                            <div class="text-muted">
                                                                <span class="text-white">
                                                                    <span class="text-white">{{ $history->schlyear }}</span>, {{ $history->semester == 1 ? '1st Sem' : ($history->semester == 2 ? '2nd Sem' : ($history->semester == 3 ? 'Summer' : $history->semester)) }}
                                                                </span>
                                                            </div>
                                                            <div><span class="badge bg-light textbold text-dark"><i class="ti ti-circle-filled text-success"></i> Click to View</span></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection