@php
    $curr_route = request()->route()->getName();

    $dashfacActive = in_array($curr_route, ['homefaculty']) ? 'active' : '';
    $attendfacActive = in_array($curr_route, ['attendancefac', 'attendance_searchfac', 'attendance_searchfacpdfpage']) ? 'active' : '';
    $servicesfacActive = in_array($curr_route, [
                                'index.services', 
                                'schedulefac', 
                                'schedulefac_searchview', 
                                'semesterfac', 
                                'virtualfaculty_class', 
                                'virtual_facultysubjectclass', 
                                'supfaceval', 
                                'supfacevalrate',
                                'prelist.index',
                                'prelist.store',
                                'prelist.storeshift',
                                'storeprenrolview.store'
                            ]) ? 'active' : '';
    $confirmfacActive = in_array($curr_route, ['confirm.index', 'confirm.store']) ? 'active' : '';
    $aceptedfacActive = in_array($curr_route, ['accepted.index', 'accepted.store']) ? 'active' : '';
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

    @if($authfacdesig->contains(Auth::guard('faculty')->user()->id))
        <li class="px-4 py-2">
            <small class="nav-text text-muted">Admission</small>
        </li>
        <li>
            <a class="nav-link {{ $confirmfacActive }}" href="{{ route('confirm.index') }}">
                <i class="ti ti-user-bolt"></i><span class="nav-text">Confirmed Applicants</span>
            </a>
        </li>
        <li>
            <a class="nav-link {{ $aceptedfacActive }}" href="{{ route('accepted.index') }}">
                <i class="ti ti-user-check"></i><span class="nav-text">Accepted Applicants</span>
            </a>
        </li>
    @endif
</ul>