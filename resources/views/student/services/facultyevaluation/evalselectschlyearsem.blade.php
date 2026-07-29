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
                    <span class="text-muted">/ Faculty Evaluation</span> 
                </h1>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-server"></i> Students Evaluation for Teachers
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3 mb-4">
                                    @foreach ($enrollmentHistory as $history)
                                        <div class="col-lg-3 col-12">
                                            <a href="{{ route('index.evaluation', ['schlyear' => $history->schlyear, 'semester' => $history->semester]) }}">
                                                <div class="card card-hover h-100" style="background: url('{{ asset('template/img/img_code.jpg') }}')no-repeat; background-position: center; background-size: cover;">
                                                    <div class="card-body p-4">
                                                        <div class="d-flex justify-content-between border-bottom pb-5 mb-3">
                                                            <div class="text-light">
                                                                <span>Academic</span><br>
                                                                <h3 class="fw-bold h4">{{ $history->schlyear }}</span>, {{ $history->semester == 1 ? '1st Sem' : ($history->semester == 2 ? '2nd Sem' : ($history->semester == 3 ? 'Summer' : $history->semester)) }}</h3>
                                                                <span style="font-size: 9pt;">
                                                                    
                                                                </span>
                                                            </div>
                                                            <div>
                                                                <i class="ti ti-book fs-1 text-success"></i>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex justify-content-between align-items-center small">
                                                            <div class="text-muted">
                                                                <span class="text-white">
                                                                    <span class="text-white"> {{ $history->progAcronym }} {{ $history->studYear }}-{{ $history->studSec }}
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