@php
    $curr_route = request()->route()->getName();

    $dashActive = in_array($curr_route, ['settings-index']) ? 'active' : '';
    $usersActive = in_array($curr_route, ['usersRead', 'edit_user']) ? 'active' : '';
    $curconfActive = in_array($curr_route, ['setconfigure']) ? 'active' : '';
    $gradeconfActive = in_array($curr_route, ['setgradeAllpassconfigure']) ? 'active' : '';
    $admissionconfActive = in_array($curr_route, ['setAdmissionConf']) ? 'active' : '';
    $enrollconfActive = in_array($curr_route, ['setEnrollConf']) ? 'active' : '';
    $queueconfActive = in_array($curr_route, ['setQueueConf']) ? 'active' : '';
    $faculevalconfActive = in_array($curr_route, ['setFaculEvalConf']) ? 'active' : '';
    $campusesconfActive = in_array($curr_route, ['setCampusesConf']) ? 'active' : '';

    $facultyActive = in_array($curr_route, ['facultiesRead']) ? 'active' : '';
    $usersAccntActive = in_array($curr_route, ['accountRead']) ? 'active' : '';
    $serverActive = in_array($curr_route, ['serverMaintenance']) ? 'active' : '';


    $addressActive = in_array($curr_route, ['regionsRead']) ? 'active' : '';
    $signatoryActive = in_array($curr_route, ['signatory.index', 'signatory.store']) ? 'active' : '';
@endphp

<ul class="nav flex-column">
    
    <li class="px-4 py-2"><small class="nav-text text-muted">Main Navigation</small></li>
    @if(Auth::guard('web')->user()->role == '0')
        <li>
            <a class="nav-link {{ $dashActive }}" href="{{ route('settings-index') }}">
                <i class="ti ti-box"></i><span class="nav-text">Dashboard</span>
            </a>
        </li>

        <li>
            <a class="nav-link {{ $usersActive }}" href="{{ route('usersRead') }}">
                <i class="ti ti-users"></i><span class="nav-text">User's Management</span>
            </a>
        </li>
        
        <li>
            <a class="nav-link {{ $curconfActive }}" href="{{ route('setconfigure') }}">
                <i class="ti ti-calendar-cog"></i><span class="nav-text">A.Y. & Semester</span>
            </a>
        </li>

        <li>
            <a class="nav-link {{ $gradeconfActive }}" href="{{ route('setgradeAllpassconfigure') }}">
                <i class="ti ti-lock-password"></i><span class="nav-text">Grades Password</span>
            </a>
        </li>

        <li>
            <a class="nav-link {{ $admissionconfActive }}" href="{{ route('setAdmissionConf') }}">
                <i class="ti ti-calendar-check"></i><span class="nav-text">Admission Status</span>
            </a>
        </li>

        <li>
            <a class="nav-link {{ $enrollconfActive }}" href="{{ route('setEnrollConf') }}">
                <i class="ti ti-device-laptop"></i><span class="nav-text">Enrollment Status</span>
            </a>
        </li>

        <li>
            <a class="nav-link {{ $queueconfActive }}" href="{{ route('setQueueConf') }}">
                <i class="ti ti-line"></i><span class="nav-text">Queueing Status</span>
            </a>
        </li>
        
        <li>
            <a class="nav-link {{ $faculevalconfActive }}" href="{{ route('setFaculEvalConf') }}">
                <i class="ti ti-file-analytics"></i><span class="nav-text">Faculty Eval. Status</span>
            </a>
        </li>

        <li>
            <a class="nav-link {{ $campusesconfActive }}" href="{{ route('setCampusesConf') }}">
                <i class="ti ti-building"></i><span class="nav-text">Campuses Status</span>
            </a>
        </li>
        
        <li>
            <a class="nav-link" href="#">
                <i class="ti ti-server"></i><span class="nav-text">Server Maintenance</span>
            </a>
        </li>

        <li class="nav-text-space"><small class="nav-text"></small></li>
        <li class="px-4 py-2"><small class="nav-text text-muted">Others</small></li>

        <li>
            <a class="nav-link" href="#">
                <i class="ti ti-address-book"></i><span class="nav-text">Addresses</span>
            </a>
        </li>
        
        <li>
            <a class="nav-link {{ $signatoryActive }}" href="{{ route('signatory.index') }}">
                <i class="ti ti-signature"></i><span class="nav-text">Signatories</span>
            </a>
        </li>
    @endif
</ul>