@extends('layouts.master_faculty')

@section('title')
CISS V.1.0 || Faculty Services
@endsection

@section('workspace')
    <div class="row">
        <div class="col-12">
            <div class="mb-6">
                <h1 class="fs-5 mb-4 d-none d-md-block">Services</h1>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-server"></i> List of Services
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3 mb-4">
                                    <!-- Class Schedule -->
                                    <div class="col-12 col-sm-6 col-xl-3 d-flex">
                                        <div class="card card-hover w-100 h-100">
                                            <div class="card-body p-4 d-flex flex-column">
                                            
                                                <div class="d-flex justify-content-between align-items-start border-bottom pb-3 mb-3">
                                                    <div class="pe-2">
                                                        <h3 class="fw-bold h5 mb-2">Class Schedule</h3>
                                                        <p class="small text-muted mb-2">
                                                            View your class schedule and stay updated with upcoming sessions.
                                                        </p>
                                                        <div class="small text-muted">
                                                            <span class="text-success fw-semibold">All Schedule</span>, A.Y.
                                                        </div>
                                                    </div>
                                                    <i class="ti ti-calendar fs-1 text-success"></i>
                                                </div>
                                                <div class="mt-auto">
                                                    <a href="{{ route('schedulefac') }}"
                                                    class="small text-decoration-underline text-dark">
                                                        View Schedule
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Grades Submission -->
                                    <div class="col-12 col-sm-6 col-xl-3 d-flex">
                                        <div class="card card-hover w-100 h-100">
                                            <div class="card-body p-4 d-flex flex-column">
                                                <div class="d-flex justify-content-between align-items-start border-bottom pb-3 mb-3">
                                                    <div class="pe-2">
                                                        <h3 class="fw-bold h5 mb-2">Submit Grades</h3>
                                                        <p class="small text-muted mb-2">
                                                            Submit student grades for academic evaluation and official records.
                                                        </p>
                                                        <div class="small text-muted">
                                                            <span class="text-success fw-semibold">All Semester</span>, A.Y.
                                                        </div>
                                                    </div>
                                                    <i class="ti ti-receipt fs-1 text-success"></i>
                                                </div>
                                                <div class="mt-auto">
                                                    <a href="{{ route('semesterfac') }}"
                                                    class="small text-decoration-underline text-dark">
                                                        View Submission
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Faculty Evaluation -->
                                    <div class="col-12 col-sm-6 col-xl-3 d-flex">
                                        <div class="card card-hover w-100 h-100">
                                            <div class="card-body p-4 d-flex flex-column">
                                                <div class="d-flex justify-content-between align-items-start border-bottom pb-3 mb-3">
                                                    <div class="pe-2">
                                                        <h3 class="fw-bold h5 mb-2">Faculty Evaluation</h3>
                                                        <p class="small text-muted mb-2">
                                                            Evaluate your teachers and share feedback to improve learning quality.
                                                        </p>
                                                        <div class="small text-muted">
                                                            <span class="text-success fw-semibold">2024-2025</span>, 2nd Sem
                                                        </div>
                                                    </div>
                                                    <i class="ti ti-chalkboard-teacher fs-1 text-success"></i>
                                                </div>
                                                <div class="mt-auto">
                                                    <a href="{{ route('supfaceval') }}"
                                                    class="small text-decoration-underline text-dark">
                                                        Start Evaluation
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Pre-enrollment -->
                                    @if($authfacdesig->contains(Auth::guard('faculty')->user()->id))
                                    <div class="col-12 col-sm-6 col-xl-3 d-flex">
                                        <div class="card card-hover w-100 h-100">
                                            <div class="card-body p-4 d-flex flex-column">
                                                <div class="d-flex justify-content-between align-items-start border-bottom pb-3 mb-3">
                                                    <div class="pe-2">
                                                        <h3 class="fw-bold h5 mb-2">Pre-enrollment</h3>
                                                        <p class="small text-muted mb-2">
                                                            Evaluate your students for this semester and monitor their academic progress.
                                                        </p>
                                                        <div class="small text-muted">
                                                            <span class="text-success fw-semibold">2025-2026</span>, Summer
                                                        </div>
                                                    </div>
                                                    <i class="ti ti-device-laptop fs-1 text-success"></i>
                                                </div>
                                                <div class="mt-auto">
                                                    <a href="{{ route('prelist.index') }}"
                                                    class="small text-decoration-underline text-dark">
                                                        Start Enrollment
                                                    </a>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
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
