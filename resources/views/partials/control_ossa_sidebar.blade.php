@php
    $curr_route = request()->route()->getName();

    $dashossaActive = in_array($curr_route, ['ossa-index']) ? 'active' : '';
    $studrfidregossaActive = in_array($curr_route, ['rfid.store']) ? 'active' : '';
    $studrfidverifyossaActive = in_array($curr_route, ['verifyStudentIDrfid']) ? 'active' : '';
@endphp

<ul class="nav flex-column">
    
    <li class="px-4 py-2"><small class="nav-text"></small></li>
    <li>
        <a class="nav-link {{ $dashossaActive }}" href="{{ route('ossa-index') }}">
            <i class="ti ti-home"></i><span class="nav-text">Dashboard</span>
        </a>
    </li>

    <li>
        <a class="nav-link {{ $studrfidregossaActive }}" href="{{ route('rfid.store') }}">
            <i class="ti ti-grid-scan"></i><span class="nav-text">Student RFID Reg</span>
        </a>
    </li>

    <li>
        <a class="nav-link {{ $studrfidverifyossaActive }}" href="{{ route('verifyStudentIDrfid') }}">
            <i class="ti ti-line-scan"></i><span class="nav-text">Check Stud RFID</span>
        </a>
    </li>

    <li>
        <a class="nav-link" href="#">
            <i class="ti ti-users"></i><span class="nav-text">Students Enrolled</span>
        </a>
    </li>
</ul>