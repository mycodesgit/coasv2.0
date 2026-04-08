@extends('layouts.master')

@section('title')
    CISS V.1.0 || Home
@endsection

@section('sideheader')
    <h4></h4>
@endsection

@section('sidemenu')
    <div class="row g-4 mb-5" style="padding-left: 20px; padding-right: 20px">
        <div class="col-lg-4 col-12">
            <div class="card card-animate mb-3">
                <div class="card-body p-6">
                    <div class="d-flex justify-content-between pb-2">
                        <div>
                            <h3 class="fw-bold h4">VISION</h3>
                            <span>CPSU as the leading technology-driven multi-disciplinary University by 2030.</span>
                        </div>
                        <div>
                            <i class="ti ti-eye fs-1 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card card-animate mb-3">
                <div class="card-body p-6">
                    <div class="d-flex justify-content-between pb-2">
                        <div>
                            <h3 class="fw-bold h4">MISSION</h3>
                            <span>CPSU is committed to produce competent graduates who can generate and extend leading technologies in multi-disciplinary areas beneficial to the community.</span>
                        </div>
                        <div>
                            <i class="ti ti-target fs-1 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card card-animate mb-3">
                <div class="card-body p-6">
                    <div class="d-flex justify-content-between pb-2">
                        <div>
                            <h3 class="fw-bold h4">GOAL</h3>
                            <span>To provide efficient, Quality, Technology-driven and Gender-Sensitive Products and Services.</span>
                        </div>
                        <div>
                            <i class="ti ti-clipboard fs-1 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card card-animate">
                <div class="card-header bg-white d-flex justify-content-between align-items-center px-4 py-3">
                    <h4 class="mb-0 h5">
                        Current Enrollment A.Y. 2025-2026, 2nd Semester -
                        @if (Auth::guard('web')->user()->campus == 'MC') Main Campus
                            @elseif(Auth::guard('web')->user()->campus == 'VC') Victorias Campus
                            @elseif(Auth::guard('web')->user()->campus == 'SCC') San Carlos Campus
                            @elseif(Auth::guard('web')->user()->campus == 'HC') Hinigaran Campus
                            @elseif(Auth::guard('web')->user()->campus == 'MP') Moises Padilla Campus
                            @elseif(Auth::guard('web')->user()->campus == 'IC') Ilog Campus
                            @elseif(Auth::guard('web')->user()->campus == 'CA') Candoni Campus
                            @elseif(Auth::guard('web')->user()->campus == 'CC') Cauayan Campus
                            @elseif(Auth::guard('web')->user()->campus == 'SC') Sipalay  Campus
                            @elseif(Auth::guard('web')->user()->campus == 'HinC') Hinobaan Campus
                            @elseif(Auth::guard('web')->user()->campus == 'VE') Valladolid Campus
                        @endif
                    </h4>
                </div>
                <div class="card-body">
                    <canvas id="currentSemesterBarChart" style="height:330px; min-height:330px"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection
