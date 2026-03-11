@php
    $curr_route = request()->route()->getName();

    $dashfacActive = in_array($curr_route, ['homefaculty']) ? 'active' : '';
    $attendfacActive = in_array($curr_route, ['attendancefac', 'attendance_searchfac', 'attendance_searchfacpdfpage']) ? 'active' : '';
    $servicesfacActive = in_array($curr_route, ['index.services', 'schedulefac', 'schedulefac_searchview', 'semesterfac', 'virtualfaculty_class', 'virtual_facultysubjectclass', 'supfaceval', 'supfacevalrate']) ? 'active' : '';
@endphp

<ul class="nav flex-column">
    
    <li class="px-4 py-2"><small class="nav-text"></small></li>
    <li>
        <a class="nav-link active" href="#">
            <i class="ti ti-home"></i><span class="nav-text">Dashboard</span>
        </a>
    </li>

    <li>
        <a class="nav-link" href="#">
            <i class="ti ti-users"></i><span class="nav-text">Students</span>
        </a>
    </li>

    <li>
        <a class="nav-link" href="#">
            <i class="ti ti-book"></i><span class="nav-text">Releasing</span>
        </a>
    </li>
</ul>