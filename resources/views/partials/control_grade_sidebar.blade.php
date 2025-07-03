@php
    $curr_route = request()->route()->getName();

    $dashSchActive = in_array($curr_route, ['homefaculty']) ? 'active-link' : '';
    $attendSchActive = in_array($curr_route, ['attendancefac', 'attendance_searchfac', 'attendance_searchfacpdfpage']) ? 'active-link' : '';
    $facSchActive = in_array($curr_route, ['schedulefac', 'schedulefac_searchview']) ? 'active-link' : '';
    $semesterSchActive = in_array($curr_route, ['semesterfac', 'virtualfaculty_class', 'virtual_facultysubjectclass']) ? 'active-link' : '';
    $gradeActive = in_array($curr_route, ['grades', 'gradesstud', 'gradesstud_search']) ? 'active-link' : '';
    
@endphp

<!-- <h3 class="sidebar__title">MANAGE</h3> -->

<div class="sidebar__list">
    <a href="{{ route('homefaculty') }}" class="sidebar__link {{ $dashSchActive }}">
        <i class="fas fa-grip"></i>
        <span>Dashboard</span>
    </a>

    <a href="{{ route('attendancefac') }}" class="sidebar__link {{ $attendSchActive }}">
        <i class="fas fa-file-pdf"></i>
        <span>Student Attendance</span>
    </a>

    <a href="{{ route('schedulefac') }}" class="sidebar__link  {{ $facSchActive }}">
        <i class="fas fa-calendar-alt"></i>
        <span>Teaching Schedule</span>
    </a>

    <a href="{{ route('semesterfac') }}" class="sidebar__link {{ $semesterSchActive }}">
        <i class="fas fa-list-ol"></i>
        <span>Gradesheet</span>
    </a>
</div>

{{-- <div class="ml-2 mr-2 mt-3 mb-3">
    <ul class="list-group">
        <a href="{{ route('homefaculty') }}" class="list-group-item {{ $dashSchActive }}">Dashboard</a>
    </ul>
    <ul class="list-group mt-1">
        <a href="{{ route('attendancefac') }}" class="list-group-item {{ $attendSchActive }}">Student Attendance</a>  
        <a href="{{ route('schedulefac') }}" class="list-group-item {{ $facSchActive }}">My Teaching Schedule</a>  
        <a href="{{ route('semesterfac') }}" class="list-group-item {{ $semesterSchActive }}">Grade Sheet</a> 
    </ul>
    <ul class="list-group mt-1">
        <a href="{{ route('logoutfac') }}" class="list-group-item">Sign Out</a>
    </ul>
</div> --}}