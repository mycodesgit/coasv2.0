@php
    $curr_route = request()->route()->getName();

    $dashSchActive = in_array($curr_route, ['homefaculty']) ? 'active' : '';
    $attendSchActive = in_array($curr_route, ['attendancefac', 'attendance_searchfac', 'attendance_searchfacpdfpage']) ? 'active' : '';
    $facSchActive = in_array($curr_route, ['schedulefac', 'schedulefac_searchview']) ? 'active' : '';
    $semesterSchActive = in_array($curr_route, ['semesterfac', 'virtualfaculty_class', 'virtual_facultysubjectclass']) ? 'active' : '';
    $gradeActive = in_array($curr_route, ['grades', 'gradesstud', 'gradesstud_search']) ? 'active' : '';
    
@endphp

<ul class="sidebar-menu">
    <li class="menu-header">Main Navigation</li>
    <li class="{{ $dashSchActive }}">
        <a class="nav-link" href="{{ route('homefaculty') }}">
            <i class="fas fa-square"></i> <span>Dashboard</span>
        </a>
    </li>
    <li class="{{ $attendSchActive }}">
        <a class="nav-link" href="{{ route('attendancefac') }}">
            <i class="fas fa-file-pdf"></i> <span>Attendance</span>
        </a>
    </li>
    <li class="{{ $facSchActive }}">
        <a class="nav-link" href="{{ route('schedulefac') }}">
            <i class="fas fa-calendar-alt"></i> <span>Schedule</span>
        </a>
    </li>
    <li class="{{ $semesterSchActive }}">
        <a class="nav-link" href="{{ route('semesterfac') }}">
            <i class="fas fa-list-ol"></i> <span>Gradesheet</span>
        </a>
    </li>
</ul>