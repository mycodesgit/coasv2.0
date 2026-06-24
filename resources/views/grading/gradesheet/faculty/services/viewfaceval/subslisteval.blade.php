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
                                <div class="row g-3 justify-content-center align-items-center min-vh-50">
                                    <!-- Content Section - Full width on mobile -->
                                    <div class="col-lg-5 col-md-6 col-sm-12 col-12">
                                        <div class="p-4 p-md-5 w-100" 
                                            style="background: linear-gradient(135deg, #04401f, #066a32); border-radius: 12px; min-height: 300px;">
                                            <div class="text-light text-center d-flex flex-column justify-content-center align-items-center h-100">
                                                <!-- Server Icon -->
                                                <div class="mb-3">
                                                    <i class="fas fa-server" style="font-size: 4rem; opacity: 0.9;"></i>
                                                </div>
                                                
                                                <!-- Heading -->
                                                <h2 class="fw-bold mb-2">We'll be back soon!</h2>
                                                
                                                <!-- Divider -->
                                                <div class="divider-custom my-3">
                                                    <div class="divider-custom-line" style="width: 60px; height: 2px; background: rgba(255,255,255,0.3); margin: 0 auto;"></div>
                                                </div>
                                                
                                                <!-- Message -->
                                                <p class="mb-0 px-2" style="font-size: 1rem; line-height: 1.6; max-width: 400px;">
                                                    Sorry for the inconvenience but we're performing some maintenance at the moment. We'll be back online shortly!
                                                </p>
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
