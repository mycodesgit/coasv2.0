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
                                    <div class="col-lg-3 col-12">
                                        <div class="card card-hover h-100">
                                            <div class="card-body p-6">
                                                <div class="d-flex justify-content-between border-bottom pb-5 mb-3">
                                                    <div>
                                                        <h3 class="fw-bold h4">Class Schedule</h3>
                                                        <span>View your class schedule and stay up to date with all upcoming sessions.</span>
                                                    </div>
                                                    <div>
                                                        <i class="ti ti-calendar fs-1 text-success"></i>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center small">
                                                    <div class="text-muted"><span class="text-success">All Schedule</span>, <span class="text-dark">A.Y.</span></div>
                                                    <div><a href="{{ route('schedulefac') }}" class="link-defalt text-decoration-underline">View Schedule</a></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-12">
                                        <div class="card card-hover h-100">
                                            <div class="card-body p-6">
                                                <div class="d-flex justify-content-between border-bottom pb-5 mb-3">
                                                    <div>
                                                        <h3 class="fw-bold h4">Final Grade Submission</h3>
                                                        <span>Submit student grades for academic evaluation and official records.</span>
                                                    </div>
                                                    <div>
                                                        <i class="ti ti-receipt fs-1 text-success"></i>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center small">
                                                    <div class="text-muted"><span class="text-success">All Semester</span>, <span class="text-dark">A.Y.</span></div>
                                                    <div><a href="{{ route('semesterfac') }}" class="link-defalt text-decoration-underline">View Submission</a></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-12">
                                        <div class="card card-hover h-100">
                                            <div class="card-body p-6">
                                                <div class="d-flex justify-content-between border-bottom pb-5 mb-3">
                                                    <div>
                                                        <h3 class="fw-bold h4">Faculty Evaluation</h3>
                                                        <span>Evaluate your teachers and share feedback to improve learning quality.</span>
                                                    </div>
                                                    <div>
                                                        <i class="ti ti-chalkboard-teacher fs-1 text-success"></i>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center small">
                                                    <div class="text-muted"><span class="text-success">2025-2026</span>, <span class="text-dark">2nd Sem</span></div>
                                                    <div><a href="{{ route('supfaceval') }}" class="link-defalt text-decoration-underline">Start Evaluation</a></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if($authfacdesig->contains(Auth::guard('faculty')->user()->id))
                                        <div class="col-lg-3 col-12">
                                            <div class="card card-hover h-100">
                                                <div class="card-body p-6">
                                                    <div class="d-flex justify-content-between border-bottom pb-5 mb-3">
                                                        <div>
                                                            <h3 class="fw-bold h4">Pre-enrollment</h3>
                                                            <span>Evaluate your students for the upcoming semester.</span>
                                                        </div>
                                                        <div>
                                                            <i class="ti ti-device-laptop fs-1 text-success"></i>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-between align-items-center small">
                                                        <div class="text-muted"><span class="text-success">2026-2027</span>, <span class="text-dark">1st Sem</span></div>
                                                        <div><a href="{{ route('prelist.index') }}" class="link-defalt text-decoration-underline">Start Enrollment</a></div>
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
