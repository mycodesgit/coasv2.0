@php
    $curr_route = request()->route()->getName();

    $dashfacActive = in_array($curr_route, ['homefaculty']) ? 'active' : '';
    $attendfacActive = in_array($curr_route, ['attendancefac', 'attendance_searchfac', 'attendance_searchfacpdfpage']) ? 'active' : '';
    $servicesfacActive = in_array($curr_route, ['index.services', 'schedulefac', 'schedulefac_searchview']) ? 'active' : '';
    $schedfacActive = in_array($curr_route, ['schedulefac', 'schedulefac_searchview']) ? 'active' : '';
    $semesterSchActive = in_array($curr_route, ['semesterfac', 'virtualfaculty_class', 'virtual_facultysubjectclass']) ? 'active' : '';
    $gradeActive = in_array($curr_route, ['grades', 'gradesstud', 'gradesstud_search']) ? 'active' : '';
    
@endphp

<ul class="nav flex-column">
    <li class="px-4 py-2">
        <small class="nav-text text-muted">Main</small>
    </li>
    <li>
        <a class="nav-link {{$dashfacActive}}" href="{{ route('homefaculty') }}">
            <i class="ti ti-layout-grid"></i><span class="nav-text">Dashboard</span>
        </a>
    </li>
    <li>
        <a class="nav-link {{$attendfacActive}}" href="{{ route('attendancefac') }}">
            <i class="ti ti-file"></i><span class="nav-text">Attendance</span>
        </a>
    </li>
    <li>
        <a class="nav-link {{$servicesfacActive}}" href="{{ route('index.services') }}">
            <i class="ti ti-server"></i><span class="nav-text">Services</span>
        </a>
    </li>
    <li>
        <a class="nav-link" href="#">
            <i class="ti ti-user"></i><span class="nav-text">Profile</span>
        </a>
    </li>
</ul>