@extends('layouts.master_yearbook')

@section('title')
CISS V.1.0 || YearBook
@endsection

@yield('sidemenu')

@section('workspace')
    <div class="row">
        <div class="col-12">
            <div class="mb-6">
                {{-- <h1 class="fs-5 mb-4 d-none d-md-block">Dashboard</h1> --}}
                <div class="card mb-3" style=" background-color: #e9ecef; margin-top: -10px">
                    <div class="card-body">
                        <ol class="breadcrumb" style="margin-bottom: -3px;">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}" class="btn btn-success btn-sm text-light">
                                    <i class="fas fa-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item mt-1">Yearbook</li>
                            <li class="breadcrumb-item active mt-1">Dashboard</li>
                        </ol>
                    </div>
                </div>
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                    <div>
                        <h1 class="h4 fw-bold mb-1" style="letter-spacing: -0.02em;">Yearbook Dashboard Overview</h1>
                        <p class="text-muted small mb-0">Yearbook Inventory, student payment, releasing and daily activity logs.</p>
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-xl-3 col-sm-6">
                        <div class="card card-animate p-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted small fw-medium">1st Year</span>
                                <i class="ti ti-users text-muted fs-5"></i>
                            </div>
                            <div class="h2 fw-bold mb-1">{{ number_format($enrlstudcountfirst ?? 0) }}</div>
                            <small class="text-muted"><span class="text-success fw-semibold"><i class="ti ti-arrow-up-right"></i> Active</span> 1st Stud Enrolled this Sem</small>
                        </div>
                    </div>

                    <div class="col-xl-3 col-sm-6">
                        <div class="card card-animate p-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted small fw-medium">2nd Year</span>
                                <i class="ti ti-users text-muted fs-5"></i>
                            </div>
                            <div class="h2 fw-bold mb-1">{{ number_format($enrlstudcountsecond ?? 0) }}</div>
                            <small class="text-muted"><span class="text-success fw-semibold"><i class="ti ti-arrow-up-right"></i> Active</span> 2nd Stud Enrolled this Sem</small>
                        </div>
                    </div>

                    <div class="col-xl-3 col-sm-6">
                        <div class="card card-animate p-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted small fw-medium">3rd Year</span>
                                <i class="ti ti-users text-muted fs-5"></i>
                            </div>
                            <div class="h2 fw-bold mb-1">{{ number_format($enrlstudcountthird ?? 0) }}</div>
                            <small class="text-muted"><span class="text-success fw-semibold"><i class="ti ti-arrow-up-right"></i> Active</span> 3rd Stud Enrolled this Sem</small>
                        </div>
                    </div>

                    <div class="col-xl-3 col-sm-6">
                        <div class="card card-animate p-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted small fw-medium">4th Year</span>
                                <i class="ti ti-users text-muted fs-5"></i>
                            </div>
                            <div class="h2 fw-bold mb-1">{{ number_format($enrlstudcountfourth ?? 0) }}</div>
                            <small class="text-muted"><span class="text-success fw-semibold"><i class="ti ti-arrow-up-right"></i> Active</span> 1st Stud Enrolled this Sem</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
