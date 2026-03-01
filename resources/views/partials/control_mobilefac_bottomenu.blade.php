@php
    $curr_route = request()->route()->getName();

    $dashSchActive = in_array($curr_route, ['homefaculty']) ? 'active' : '';
    $attendSchActive = in_array($curr_route, ['attendancefac', 'attendance_searchfac', 'attendance_searchfacpdfpage']) ? 'active' : '';
    $facSchActive = in_array($curr_route, ['schedulefac', 'schedulefac_searchview']) ? 'active' : '';
    $semesterSchActive = in_array($curr_route, ['semesterfac', 'virtualfaculty_class', 'virtual_facultysubjectclass']) ? 'active' : '';
    $gradeActive = in_array($curr_route, ['grades', 'gradesstud', 'gradesstud_search']) ? 'active' : '';
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