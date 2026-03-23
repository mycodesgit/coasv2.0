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
                <div class="card" style=" background-color: #e9ecef; margin-top: -10px">
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
                <div class="row g-3 mb-3 mt-3">
                    @if(Auth::guard('web')->user()->role == '0')
                        <div class="col-lg-3 col-12">
                            <div class="card mb-3">
                                <div class="card-body p-6">
                                    <div class="d-flex justify-content-between pb-2">
                                        <div>
                                            <h3 class="fw-bold h1">{{ $userCounts }}</h3>
                                            <span>Total Users</span>
                                        </div>
                                        <div>
                                            <i class="ti ti-users fs-1 text-info"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-12">
                            <div class="card mb-3">
                                <div class="card-body p-6">
                                    <div class="d-flex justify-content-between pb-2">
                                        <div>
                                            <h3 class="fw-bold h1">{{ $userActiveCounts }}</h3>
                                            <span>Total Active Users</span>
                                        </div>
                                        <div>
                                            <i class="ti ti-users fs-1 text-success"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-12">
                            <div class="card mb-3">
                                <div class="card-body p-6">
                                    <div class="d-flex justify-content-between pb-2">
                                        <div>
                                            <h3 class="fw-bold h1">{{ $userUnActiveCounts }}</h3>
                                            <span>Total Unactive Users</span>
                                        </div>
                                        <div>
                                            <i class="ti ti-users fs-1 text-danger"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-12">
                            <div class="card mb-3">
                                <div class="card-body p-6">
                                    <div class="d-flex justify-content-between pb-2">
                                        <div>
                                            <h3 class="fw-bold h1">{{ $userAddedTodayCounts }}</h3>
                                            <span>Added User's Today</span>
                                        </div>
                                        <div>
                                            <i class="ti ti-user-plus fs-1 text-success"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-12">
                            <div class="card mb-3">
                                <div class="card-body p-6">
                                    <div class="d-flex justify-content-between pb-2">
                                        <div>
                                            <h3 class="fw-bold h1">Off</h3>
                                            <span>Admission Status</span>
                                        </div>
                                        <div>
                                            <i class="ti ti-calendar-user fs-1 text-success"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-12">
                            <div class="card mb-3">
                                <div class="card-body p-6">
                                    <div class="d-flex justify-content-between pb-2">
                                        <div>
                                            <h3 class="fw-bold h1">On</h3>
                                            <span>Enrollment Status</span>
                                        </div>
                                        <div>
                                            <i class="ti ti-device-laptop fs-1 text-success"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-12">
                            <div class="card mb-3">
                                <div class="card-body p-6">
                                    <div class="d-flex justify-content-between pb-2">
                                        <div>
                                            <h3 class="fw-bold h1">{{ $queueStatus->statusqueue }}</h3>
                                            <span>Queueing Status</span>
                                        </div>
                                        <div>
                                            <i class="ti ti-line fs-1 text-success"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-12">
                            <div class="card mb-3">
                                <div class="card-body p-6">
                                    <div class="d-flex justify-content-between pb-2">
                                        <div>
                                            <h3 class="fw-bold h1">Off</h3>
                                            <span>Server Maintenance Status</span>
                                        </div>
                                        <div>
                                            <i class="ti ti-server fs-1 text-success"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
