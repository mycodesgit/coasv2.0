@extends('layouts.master_settings')

@section('title')
CISS V.1.0 || Settings
@endsection

@yield('sidemenu')

@section('workspace')
    <div class="row">
        <div class="col-12">
            <div class="mb-6">
                {{-- <h1 class="fs-5 mb-4 d-none d-md-block">Dashboard</h1> --}}
                <div class="card mb-4" style=" background-color: #e9ecef; margin-top: -10px">
                    <div class="card-body">
                        <ol class="breadcrumb" style="margin-bottom: -3px;">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}" class="btn btn-success btn-sm text-light">
                                    <i class="fas fa-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item mt-1">Settings</li>
                            <li class="breadcrumb-item active mt-1">Dashboard</li>
                        </ol>
                    </div>
                </div>
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h1 class="h4 fw-bold mb-1" style="letter-spacing: -0.02em;">System Dashboard Overview</h1>
                        <p class="text-muted small mb-0">System metrics, configuration, and daily activity logs.</p>
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    @if(Auth::guard('web')->user()->role == '0')
                        {{-- Total Users --}}
                        <div class="col-xl-3 col-sm-6">
                            <div class="card card-animate p-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted small fw-medium">Total Users</span>
                                    <i class="ti ti-users text-info fs-5"></i>
                                </div>
                                <div class="h2 fw-bold mb-1">{{ number_format($userCounts ?? 0) }}</div>
                                <small class="text-muted"><span class="text-info fw-semibold"><i class="ti ti-users"></i> System</span> total registered users</small>
                            </div>
                        </div>

                        {{-- Total Active Users --}}
                        <div class="col-xl-3 col-sm-6">
                            <div class="card card-animate p-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted small fw-medium">Total Active Users</span>
                                    <i class="ti ti-users text-success fs-5"></i>
                                </div>
                                <div class="h2 fw-bold mb-1">{{ number_format($userActiveCounts ?? 0) }}</div>
                                <small class="text-muted"><span class="text-success fw-semibold"><i class="ti ti-check"></i> Active</span> currently active accounts</small>
                            </div>
                        </div>

                        {{-- Total Unactive Users --}}
                        <div class="col-xl-3 col-sm-6">
                            <div class="card card-animate p-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted small fw-medium">Total Unactive Users</span>
                                    <i class="ti ti-users text-danger fs-5"></i>
                                </div>
                                <div class="h2 fw-bold mb-1">{{ number_format($userUnActiveCounts ?? 0) }}</div>
                                <small class="text-muted"><span class="text-danger fw-semibold"><i class="ti ti-x"></i> Inactive</span> disabled or idle accounts</small>
                            </div>
                        </div>

                        {{-- Added Users Today --}}
                        <div class="col-xl-3 col-sm-6">
                            <div class="card card-animate p-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted small fw-medium">Added User's Today</span>
                                    <i class="ti ti-user-plus text-success fs-5"></i>
                                </div>
                                <div class="h2 fw-bold mb-1">{{ number_format($userAddedTodayCounts ?? 0) }}</div>
                                <small class="text-muted"><span class="text-success fw-semibold"><i class="ti ti-arrow-up-right"></i> Today</span> new registrations</small>
                            </div>
                        </div>

                        {{-- Admission Status --}}
                        <div class="col-xl-3 col-sm-6">
                            <div class="card card-animate p-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted small fw-medium">Admission Status</span>
                                    <i class="ti ti-calendar-user text-success fs-5"></i>
                                </div>
                                <div class="h2 fw-bold mb-1">{{ $admissionStatus->statusadmission ?? 'N/A' }}</div>
                                <small class="text-muted"><span class="text-success fw-semibold"><i class="ti ti-info-circle"></i> Status</span> current admission mode</small>
                            </div>
                        </div>

                        {{-- Enrollment Status --}}
                        <div class="col-xl-3 col-sm-6">
                            <div class="card card-animate p-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted small fw-medium">Enrollment Status</span>
                                    <i class="ti ti-device-laptop text-success fs-5"></i>
                                </div>
                                <div class="h2 fw-bold mb-1">{{ $enrolledStatus->statusenroll ?? 'N/A' }}</div>
                                <small class="text-muted"><span class="text-success fw-semibold"><i class="ti ti-info-circle"></i> Status</span> current enrollment mode</small>
                            </div>
                        </div>

                        {{-- Queueing Status --}}
                        <div class="col-xl-3 col-sm-6">
                            <div class="card card-animate p-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted small fw-medium">Queueing Status</span>
                                    <i class="ti ti-line text-success fs-5"></i>
                                </div>
                                <div class="h2 fw-bold mb-1">{{ $queueStatus->statusqueue ?? 'N/A' }}</div>
                                <small class="text-muted"><span class="text-success fw-semibold"><i class="ti ti-info-circle"></i> Status</span> queue system operational state</small>
                            </div>
                        </div>

                        {{-- Faculty Eval. Status --}}
                        <div class="col-xl-3 col-sm-6">
                            <div class="card card-animate p-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted small fw-medium">Faculty Eval. Status</span>
                                    <i class="ti ti-file-analytics text-success fs-5"></i>
                                </div>
                                <div class="h2 fw-bold mb-1">{{ $faculevalStatus->statuseval ?? 'N/A' }}</div>
                                <small class="text-muted"><span class="text-success fw-semibold"><i class="ti ti-info-circle"></i> Status</span> faculty evaluation system state</small>
                            </div>
                        </div>

                        {{-- Server Maintenance Status --}}
                        <div class="col-xl-3 col-sm-6">
                            <div class="card card-animate p-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted small fw-medium">Server Maintenance Status</span>
                                    <i class="ti ti-server text-success fs-5"></i>
                                </div>
                                <div class="h2 fw-bold mb-1">Off</div>
                                <small class="text-muted"><span class="text-success fw-semibold"><i class="ti ti-check"></i> Normal</span> server status operating</small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
