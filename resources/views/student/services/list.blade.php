@extends('layouts.master_student')

@section('title')
    CISS V.1.0 || Services
@endsection

@section('body')
    <div class="row ">
        <div class="col-12">
            <div class="mb-6">
                <h1 class="fs-3 mb-4 d-none d-md-block">Services</h1>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card border-0">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-server"></i> Services
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3 mb-4">
                                    <div class="col-lg-3 col-12">
                                        <div class="card h-100">
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
                                                    <div class="text-muted"><span class="text-success">2025-2026</span>, <span class="text-dark">2nd Sem</span></div>
                                                    <div><a href="#" class="link-primary text-decoration-underline">View Schedule</a></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-12">
                                        <div class="card h-100">
                                            <div class="card-body p-6">
                                                <div class="d-flex justify-content-between border-bottom pb-5 mb-3">
                                                    <div>
                                                        <h3 class="fw-bold h4">Pre-Enrollment</h3>
                                                        <span>Secure your spot by enrolling in your courses for next semester.</span>
                                                    </div>
                                                    <div>
                                                        <i class="ti ti-device-laptop fs-1 text-success"></i>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center small">
                                                    <div class="text-muted"><span class="text-success">Upcoming</span>, <span class="text-dark">1st Sem</span></div>
                                                    <div><a href="#" class="link-primary text-decoration-underline">Pre-Enrol Now</a></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-12">
                                        <div class="card h-100">
                                            <div class="card-body p-6">
                                                <div class="d-flex justify-content-between border-bottom pb-5 mb-3">
                                                    <div>
                                                        <h3 class="fw-bold h4">Faculty Evaluation</h3>
                                                        <span>Help improve teaching by sharing your experience.</span>
                                                    </div>
                                                    <div>
                                                        <i class="ti ti-chalkboard-teacher fs-1 text-success"></i>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center small">
                                                    <div class="text-muted"><span class="text-success">2025-2026</span>, <span class="text-dark">2nd Sem</span></div>
                                                    <div><a href="{{ route('show.evaluation') }}" class="link-primary text-decoration-underline">Start Evaluation</a></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-12">
                                        <div class="card h-100">
                                            <div class="card-body p-6">
                                                <div class="d-flex justify-content-between border-bottom pb-5 mb-3">
                                                    <div>
                                                        <h3 class="fw-bold h4">Assessment Fee</h3>
                                                        <span>Covers academic evaluation and student assessment services.</span>
                                                    </div>
                                                    <div>
                                                        <i class="ti ti-receipt fs-1 text-success"></i>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center small">
                                                    <div class="text-muted"><span class="text-success">2025-2026</span>, <span class="text-dark">2nd Sem</span></div>
                                                    <div><a href="#" class="link-primary text-decoration-underline">View Fees</a></div>
                                                </div>
                                            </div>
                                        </div>
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