@php
    $current_route=request()->route()->getName();

    $studentdashActive = in_array($current_route, ['index.student']) ? 'active' : '';
    $studentgradesActive = in_array($current_route, ['show.grades']) ? 'active' : '';
    $studentaccntsActive = in_array($current_route, ['show.services', 'index.assessmentstudentfees']) ? 'active' : '';
    $preenrolviewActive = in_array($current_route, ['pre.index', 'pre.show']) ? 'active' : '';
@endphp

@if (request()->routeIs('index.scheduleclass', 'show.scheduleclass', 'index.evaluation', 'index.assessmentstudentfees'))
    <a href="{{ route('show.services') }}">
        <div class="bottom-nav">
            <div class="nav-item" data-label="Dashboard">
                <i class="fas fa-arrow-left icon"></i>
                <span>Go Back</span>
            </div>
        </div>
    </a>
@elseif (request()->routeIs('show.evaluation.rate'))
    <a href="{{ route('index.evaluation') }}">
        <div class="bottom-nav">
            <div class="nav-item" data-label="Dashboard">
                <i class="fas fa-arrow-left icon"></i>
                <span>Go Back</span>
            </div>
        </div>
    </a>
@else
    <div class="bottom-nav">
        <a href="{{ route('index.student') }}">
            <div class="nav-item {{$studentdashActive}}" data-label="Dashboard">
                <i class="fas fa-th icon"></i>
                <span>Home</span>
            </div>
        </a>

        <a href="{{ route('show.grades') }}">
            <div class="nav-item {{$studentgradesActive}}" data-label="Attendance">
                <i class="fas fa-graduation-cap icon"></i>
                <span>Grades</span>
            </div>
        </a>

        <a href="{{ route('show.services') }}">
            <div class="nav-item {{$studentaccntsActive}}" data-label="Schedule">
                <i class="fas fa-server icon"></i>
                <span>Services</span>
            </div>
        </a>

        <a href="#">
            <div class="nav-item" data-label="Grade Sheet">
                <i class="fas fa-user icon"></i>
                <span>Profile</span>
            </div>
        </a>
    </div>
@endif