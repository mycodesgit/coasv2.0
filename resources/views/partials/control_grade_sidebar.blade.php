@php
    $curr_route = request()->route()->getName();

    $dashSchActive = in_array($curr_route, ['homefaculty']) ? 'active' : '';
    $attendSchActive = in_array($curr_route, ['attendancefac', 'attendance_searchfac', 'attendance_searchfacpdfpage']) ? 'active' : '';
    $semesterSchActive = in_array($curr_route, ['semesterfac', 'virtualfaculty_class', 'virtual_facultysubjectclass']) ? 'active' : '';
    $gradeActive = in_array($curr_route, ['grades', 'gradesstud', 'gradesstud_search']) ? 'active' : '';
    
@endphp

<div class="ml-2 mr-2 mt-3 mb-3">
    <ul class="list-group">
        <a href="{{ route('homefaculty') }}" class="list-group-item {{ $dashSchActive }}">Dashboard</a>
    </ul>
    <ul class="list-group mt-1">
        <a href="{{ route('attendancefac') }}" class="list-group-item {{ $attendSchActive }}">Student Attendance</a>  
        <a href="{{ route('semesterfac') }}" class="list-group-item {{ $semesterSchActive }}">Grade Sheet</a> 
    </ul>
    <ul class="list-group mt-1">
        <a href="{{ route('logout') }}" class="list-group-item">Sign Out</a>
    </ul>
</div>