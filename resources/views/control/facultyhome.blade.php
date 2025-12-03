@extends('layouts.master_faculty')

@section('title')
    CISS V.1.0 || Faculty Dashboard
@endsection

@section('workspace')
    <section class="section mt-4">
        <!-- <div class="section-header" style="border-radius: 20px !important;">
            <h1>Blank Page</h1>
        </div> -->

        <div class="section-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card" style="border-radius: 20px !important; background-color: #4dc591; border: #f0eff1;">
                                <div class="card-body">
                                    <h4 class="text-white mt-2">
                                        Hi 
                                        @auth('faculty')
                                            @if(Auth::guard('faculty')->user()->role == '943')
                                                {{ Auth::guard('faculty')->user()->fname }} {{ Auth::guard('faculty')->user()->lname }}
                                            @endif
                                        @endauth 
                                    </h4>
                                    <p class="text-white">
                                        Welcome, here you can quickly access your class attendance, class schedules, and stay updated with important
                                        announcements. Everything you need to streamline your academic tasks is right here.
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 d-flex">
                            <div class="vision-mission-goal card p-4 flex-fill text-left" style="border-radius: 20px;">
                                <h3>Vision <i class="fas fa-eye" style="color: #198754; font-size: 16pt;"></i></h3>
                                <p>CPSU as the leading technology-driven multi-disciplinary University by 2030.</p>
                            </div>
                        </div>

                        <div class="col-md-4 d-flex">
                            <div class="vision-mission-goal card p-4 flex-fill text-left" style="border-radius: 20px;">
                                <h3>Mission <i class="fas fa-lightbulb" style="color: #198754; font-size: 16pt;"></i></h3>
                                <p>CPSU is committed to produce competent graduates who can generate and extend technologies in multi-disciplinary areas beneficial to the community.</p>
                            </div>
                        </div>

                        <div class="col-md-4 d-flex">
                            <div class="vision-mission-goal card p-4 flex-fill text-left" style="border-radius: 20px;">
                                <h3>Goal <i class="fas fa-bullseye" style="color: #198754; font-size: 16pt;"></i></h3>
                                <p>To provide efficient, quality, technology-driven and Gender-sensitive Products and Services.</p>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card p-4" style="border-radius: 20px !important;">
                        <div class="calendar-header calendar-widget text-center mb-2">
                            <button id="prevMonth" class="btn btn-light btn-sm">&lt;</button>
                            <span id="monthYear" class="fw-semibold text-success"></span>
                            <button id="nextMonth" class="btn btn-light btn-sm">&gt;</button>
                        </div>
                        <div id="calendarDays" class="calendar-grid text-center"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection