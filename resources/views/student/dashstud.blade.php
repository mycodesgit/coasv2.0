@extends('layouts.master_student')

@section('title')
    CISS V.1.0 || Student Dashboard
@endsection

@section('body')
    <div class="row">
        <div class="col-12">
            <div class="mb-6">
                <h1 class="fs-3 mb-4 d-none d-md-block">Dashboard</h1>

                <div class="card bg-success bg-opacity-10 border border-success border-opacity-25 rounded-2 mb-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="icon-shape icon-md bg-success text-white rounded-2 d-none d-md-block">
                                <i class="ti ti-user fs-4"></i>
                            </div>
                            <div>
                                <h1 class="mb-0 fs-2">Welcome back,
                                    {{ ucwords(strtolower(trim($studauth->fname))) }} 
                                    {{ ucfirst(strtolower(trim(substr($studauth->mname, 0, 1)))) }}. 
                                    {{ ucfirst(strtolower(trim($studauth->lname))) }} !
                                </h1>
                                <p class="text-secondary mb-0 small">Manage your academic life with ease — view your grades, update your account, check your class schedule, and prepare for pre-enrollment. Stay organized and take charge of your success.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4 mb-5">
                    <div class="col-lg-4 col-12">
                        <div class="card p-4 bg-secondary bg-opacity-10 border border-secondary border-opacity-25 rounded-2 h-100">
                            <div class="d-flex gap-3">
                                <div class="icon-shape icon-md bg-success text-white rounded-2 p-2">
                                    <i class="ti ti-eye fs-4"></i>
                                </div>
                                <div>
                                    <h1 class="mb-2 fs-3">Vision</h1>
                                    <h6 class="fw-normal mb-0">CPSU as the leading technology-driven multi-disciplinary University by 2030.</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-12">
                        <div class="card p-4 bg-secondary bg-opacity-10 border border-secondary border-opacity-25 rounded-2 h-100">
                            <div class="d-flex gap-3">
                                <div class="icon-shape icon-md bg-success text-white rounded-2 p-2">
                                    <i class="ti ti-eye fs-4"></i>
                                </div>
                                <div>
                                    <h1 class="mb-2 fs-3">Mission</h1>
                                    <h6 class="fw-normal mb-0">CPSU is committed to produce competent graduates who can generate and extend technologies in multi-disciplinary areas beneficial to the community.</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-12">
                        <div class="card p-4 bg-secondary bg-opacity-10 border border-secondary border-opacity-25 rounded-2 h-100">
                            <div class="d-flex gap-3">
                                <div class="icon-shape icon-md bg-success text-white rounded-2 p-2">
                                    <i class="ti ti-eye fs-4"></i>
                                </div>
                                <div>
                                    <h1 class="mb-2 fs-3">Goal</h1>
                                    <h6 class="fw-normal mb-0">To provide efficient, quality, technology-driven and Gender-sensitive Products and Services.</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection