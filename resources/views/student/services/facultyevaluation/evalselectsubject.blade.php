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
                        <div class="card">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-server"></i> Subjects for Evaluation
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3 mb-4">
                                    @if($setevalmode->statuseval === 'Off')
                                        <div class="col-12">
                                            <div class="alert alert-warning d-flex align-items-center" role="alert">
                                                <i class="ti ti-alert-triangle fs-3 me-3"></i>
                                                <div>
                                                    Faculty Evaluation is currently unavailable. Please check back later.
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        @foreach($mysubj as $datafacsubprogen)
                                            @if($disabledsubj->contains('subjidrate', $datafacsubprogen->subjID))
                                                <div class="col-lg-3 col-12">
                                                    <a href="#" disabled>
                                                        <div class="card h-100">
                                                            <div class="card-body p-4">
                                                                <div class="d-flex justify-content-between border-bottom pb-5 mb-3">
                                                                    <div>
                                                                        <h3 class="fw-bold h4">{{ $datafacsubprogen->sub_name }}</h3>
                                                                        <span>{{ $datafacsubprogen->subSec }}</span><br>
                                                                        <span style="font-size: 9pt;">
                                                                            <span class="text-success">{{ $datafacsubprogen->schlyear }}</span>, {{ $datafacsubprogen->semester == 1 ? '1st Sem' : ($datafacsubprogen->semester == 2 ? '2nd Sem' : ($datafacsubprogen->semester == 3 ? 'Summer' : $datafacsubprogen->semester)) }}
                                                                        </span>
                                                                    </div>
                                                                    <div>
                                                                        <i class="ti ti-book fs-1 text-secondary"></i>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex justify-content-between align-items-center small">
                                                                    <div class="text-muted">
                                                                        <span class="text-dark">
                                                                            @if(isset($datafacsubprogen->fname) && isset($datafacsubprogen->lname))
                                                                                {{ substr($datafacsubprogen->fname, 0, 1) }}. {{ $datafacsubprogen->lname }}
                                                                            @else
                                                                                No Instructor
                                                                            @endif
                                                                        </span>
                                                                    </div>
                                                                    <div><span class="badge bg-success textbold"><i class="ti ti-check"></i> Done Evaluate</span></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                            @else  
                                                <div class="col-lg-3 col-12">
                                                    <a href="{{ route('show.evaluation.rate', ['id' => $datafacsubprogen->subjID, 'qcefacID'  => $datafacsubprogen->id, 'qcefacname'  => $datafacsubprogen->fname . ' ' . $datafacsubprogen->lname]) }}">
                                                        <div class="card card-hover h-100">
                                                            <div class="card-body p-4">
                                                                <div class="d-flex justify-content-between border-bottom pb-5 mb-3">
                                                                    <div>
                                                                        <h3 class="fw-bold h4">{{ $datafacsubprogen->sub_name }}</h3>
                                                                        <span>{{ $datafacsubprogen->subSec }}</span><br>
                                                                        <span style="font-size: 9pt;">
                                                                            <span class="text-success">{{ $datafacsubprogen->schlyear }}</span>, {{ $datafacsubprogen->semester == 1 ? '1st Sem' : ($datafacsubprogen->semester == 2 ? '2nd Sem' : ($datafacsubprogen->semester == 3 ? 'Summer' : $datafacsubprogen->semester)) }}
                                                                        </span>
                                                                    </div>
                                                                    <div>
                                                                        <i class="ti ti-book fs-1 text-success"></i>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex justify-content-between align-items-center small">
                                                                    <div class="text-muted">
                                                                        <span class="text-dark">
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
                                            @endif
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