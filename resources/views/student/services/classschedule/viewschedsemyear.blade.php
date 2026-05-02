@extends('layouts.master_student')

@section('title')
    CISS V.1.0 || Services
@endsection

@section('body')
    <div class="row ">
        <div class="col-12">
            <div class="mb-6">
                <h1 class="fs-5 mb-4 d-none d-md-block">
                    <a href="{{ route('show.services') }}">
                        <i class="ti ti-arrow-left"></i> Services 
                    </a>
                    <span class="text-muted">/ Class Schedule</span> 
                    <span class="text-muted">
                        /   {{ request('schlyear') }},
                            @if(request('semester') == 1)
                                1st Sem
                            @elseif(request('semester') == 2)
                                2nd Sem
                            @elseif(request('semester') == 3)
                                Summer
                            @else
                                Unknown Semester
                            @endif    
                    </span> 
                </h1>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-server"></i> Class Schedule
                                    <span class="text-muted">/
                                        {{ request('schlyear') }},
                                        @if(request('semester') == 1)
                                            1st Sem
                                        @elseif(request('semester') == 2)
                                            2nd Sem
                                        @elseif(request('semester') == 3)
                                            Summer
                                        @else
                                            Unknown Semester
                                        @endif
                                    </span>
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <div class="" id="schedule-grid" style="font-size: 10pt;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection