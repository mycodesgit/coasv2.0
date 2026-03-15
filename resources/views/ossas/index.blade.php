@extends('layouts.master_ossa')

@section('title')
    CISS V.1.0 || Ossa Dashboard
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
                                <a href="{{ route('home') }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item mt-1">Ossa</li>
                            <li class="breadcrumb-item active mt-1">Dashboard</li>
                        </ol>
                    </div>
                </div>
                <div class="row g-3 mb-3 mt-3">
                    <div class="col-lg-3 col-12">
                        <div class="card p-4 bg-success bg-opacity-10 border border-success border-opacity-25 rounded-2">
                            <div class="d-flex gap-3 ">
                                <div class="icon-shape icon-md bg-success text-white rounded-2">
                                    <i class="ti ti-circle-dashed-number-1 fs-4"></i>
                                </div>
                                <div>
                                    <h2 class="mb-3 fs-6">1st Stud Enrolled this Sem</h2>
                                    <h3 class="fw-bold mb-0">{{ $enrlstudcountfirst }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-12">
                        <div class="card p-4 bg-success bg-opacity-10 border border-success border-opacity-25 rounded-2">
                            <div class="d-flex gap-3 ">
                                <div class="icon-shape icon-md bg-success text-white rounded-2">
                                    <i class="ti ti-circle-dashed-number-2 fs-4"></i>
                                </div>
                                <div>
                                    <h2 class="mb-3 fs-6">2nd Stud Enrolled this Sem</h2>
                                    <h3 class="fw-bold mb-0">{{ $enrlstudcountsecond }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-12">
                        <div class="card p-4 bg-success bg-opacity-10 border border-success border-opacity-25 rounded-2">
                            <div class="d-flex gap-3 ">
                                <div class="icon-shape icon-md bg-success text-white rounded-2">
                                    <i class="ti ti-circle-dashed-number-3 fs-4"></i>
                                </div>
                                <div>
                                    <h2 class="mb-3 fs-6">3rd Stud Enrolled this Sem</h2>
                                    <h3 class="fw-bold mb-0">{{ $enrlstudcountthird }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-12">
                        <div class="card p-4 bg-success bg-opacity-10 border border-success border-opacity-25 rounded-2">
                            <div class="d-flex gap-3 ">
                                <div class="icon-shape icon-md bg-success text-white rounded-2">
                                    <i class="ti ti-circle-dashed-number-4 fs-4"></i>
                                </div>
                                <div>
                                    <h2 class="mb-3 fs-6">4th Stud Enrolled this Sem</h2>
                                    <h3 class="fw-bold mb-0">{{ $enrlstudcountfourth }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
