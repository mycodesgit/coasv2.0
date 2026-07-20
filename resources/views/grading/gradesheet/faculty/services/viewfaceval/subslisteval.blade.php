@extends('layouts.master_faculty')

@section('title')
CISS V.1.0 || Faculty Services
@endsection

@section('workspace')
    <div class="row">
        <div class="col-12">
            <div class="mb-6">
                <h1 class="fs-5 mb-4 d-none d-md-block">
                    <a href="{{ route('index.services') }}">
                        <i class="ti ti-arrow-left"></i> Services 
                    </a>
                    <span class="text-muted">/ Faculty Evaluation</span>
                </h1>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-chalkboard-teacher"></i> Faculty Evaluation
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
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
                                        @forelse($sections as $section)
                                            @if($section['title'] !== 'Division Chair')
                                                <div class="page-header" style="border-bottom: 1px solid #04401f;"></div>
                                            @endif
                                            <h4>
                                                @if(isset($section['icon']))
                                                    <i class="{{ $section['icon'] }} me-2"></i>
                                                @endif
                                                {{ $section['title'] }}
                                                @if(isset($section['role']))
                                                    <small class="text-muted">(Evaluating as: {{ $section['role'] }})</small>
                                                @endif
                                                <small class="text-sm" style="font-size: 12pt"><span class="badge bg-secondary">{{ $section['data']->count() }}</span></small>
                                            </h4>
                                            <div class="row g-3">
                                                @foreach($section['data'] as $faculty)
                                                    @include('grading.gradesheet.faculty.services.viewfaceval.partials.facultycards', [
                                                        'faculty' => $faculty,
                                                        'evaluator' => $section['evaluator'],
                                                        'disabledsubj' => $section['disabled'],
                                                        'disabledsubjdean' => $section['disabled'],
                                                        'disabledsubjdivchair' => $section['disabled'],
                                                        'disabledsubjcampusdeaninstruction' => $section['disabled'],
                                                        'sy' => $sy
                                                    ])
                                                @endforeach
                                            </div>
                                        @empty
                                            <div class="col-12">
                                                <div class="alert alert-info d-flex align-items-center" role="alert">
                                                    <i class="ti ti-info-circle fs-3 me-3"></i>
                                                    <div>Waiting for Supervisor Evaluation</div>
                                                </div>
                                            </div>
                                        @endforelse
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
