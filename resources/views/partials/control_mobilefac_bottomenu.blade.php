@php
    $current_route=request()->route()->getName();

    $studentdashActive = in_array($current_route, ['index.student']) ? 'active' : '';
    $studentgradesActive = in_array($current_route, ['show.grades']) ? 'active' : '';
    $studentaccntsActive = in_array($current_route, ['show.services', 'index.assessmentstudentfees']) ? 'active' : '';
    $preenrolviewActive = in_array($current_route, ['pre.index', 'pre.show']) ? 'active' : '';
@endphp

@if (request()->routeIs('index.scheduleclass', 'index.evaluation', 'index.assessmentstudentfees'))
    <a href="{{ route('show.services') }}">
        <div class="bottom-nav">
            <div class="nav-item" data-label="Dashboard">
                <i class="fas fa-arrow-left icon"></i>
                <span>Go Back</span>
            </div>
        </div>
    </a>
@elseif (request()->routeIs('show.scheduleclass'))
    <a href="{{ route('index.scheduleclass') }}">
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
        <a href="{{ route('homefaculty') }}">
            <div class="nav-item {{ $dashSchActive }}" data-label="Dashboard">
                <i class="fas fa-th icon"></i>
                <span>Dashboard</span>
            </div>
        </a>

        <a href="{{ route('attendancefac') }}">
            <div class="nav-item {{ $attendSchActive }}" data-label="Attendance">
                <i class="fas fa-file-pdf icon"></i>
                <span>Attendance</span>
            </div>
        </a>

        <a href="{{ route('schedulefac') }}">
            <div class="nav-item {{ $facSchActive }}" data-label="Schedule">
                <i class="fas fa-calendar-alt icon"></i>
                <span>Schedule</span>
            </div>
        </a>

        <a href="{{ route('semesterfac') }}">
            <div class="nav-item {{ $semesterSchActive }}" data-label="Grade Sheet">
                <i class="fas fa-list-ol icon"></i>
                <span>Grade Sheet</span>
            </div>
        </a>
    </div>
@endif