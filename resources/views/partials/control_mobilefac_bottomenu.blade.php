@php
    $curr_route = request()->route()->getName();

    $dashfacActive = in_array($curr_route, ['homefaculty']) ? 'active' : '';
    $attendfacActive = in_array($curr_route, ['attendancefac', 'attendance_searchfac', 'attendance_searchfacpdfpage']) ? 'active' : '';
    $servicesfacActive = in_array($curr_route, ['index.services', 'schedulefac', 'schedulefac_searchview', 'semesterfac', 'virtualfaculty_class', 'virtual_facultysubjectclass']) ? 'active' : '';
@endphp

@if (request()->routeIs('schedulefac', 'semesterfac', 'index.assessmentstudentfees'))
    <a href="{{ route('index.services') }}">
        <div class="bottom-nav">
            <div class="nav-item" data-label="Dashboard">
                <i class="fas fa-arrow-left icon"></i>
                <span>Go Back</span>
            </div>
        </div>
    </a>
@elseif (request()->routeIs('schedulefac_searchview'))
    <a href="{{ route('schedulefac') }}">
        <div class="bottom-nav">
            <div class="nav-item" data-label="Dashboard">
                <i class="fas fa-arrow-left icon"></i>
                <span>Go Back</span>
            </div>
        </div>
    </a>
@elseif (request()->routeIs('virtualfaculty_class'))
    <a href="{{ route('semesterfac') }}">
        <div class="bottom-nav">
            <div class="nav-item" data-label="Dashboard">
                <i class="fas fa-arrow-left icon"></i>
                <span>Go Back</span>
            </div>
        </div>
    </a>
@elseif (request()->routeIs('virtual_facultysubjectclass'))
    <a href="javascript:history.back()">
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
            <div class="nav-item {{ $dashfacActive }}" data-label="Dashboard">
                <i class="fas fa-th icon"></i>
                <span>Home</span>
            </div>
        </a>

        <a href="{{ route('attendancefac') }}">
            <div class="nav-item {{ $attendfacActive }}" data-label="Attendance">
                <i class="fas fa-file-pdf icon"></i>
                <span>Attendance</span>
            </div>
        </a>

        <a href="{{ route('index.services') }}">
            <div class="nav-item {{ $servicesfacActive }}" data-label="Services">
                <i class="fas fa-server icon"></i>
                <span>Services</span>
            </div>
        </a>

        <a href="">
            <div class="nav-item" data-label="Profile">
                <i class="fas fa-user icon"></i>
                <span>Profile</span>
            </div>
        </a>
    </div>
@endif