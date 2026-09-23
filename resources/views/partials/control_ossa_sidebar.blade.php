@php
    $curr_route = request()->route()->getName();

    $dashossaActive = in_array($curr_route, ['ossa-index']) ? 'active' : '';
    $studrfidregossaActive = in_array($curr_route, ['rfid.store']) ? 'active' : '';
    $studrfidverifyossaActive = in_array($curr_route, ['verifyStudentIDrfid']) ? 'active' : '';

    $studinfosActive = in_array($curr_route, ['studsinfos.index']) ? 'active' : '';
    $idIssuanceLogActive = in_array($curr_route, ['id-issuance-log.index', 'id-issuance-log.store']) ? 'active' : '';
@endphp

<ul class="nav flex-column">

    <li class="px-4 py-2"><small class="nav-text text-muted">Main Navigation</small></li>
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
        <a class="nav-link {{ $studrfidverifyossaActive }}" href="#">
            <i class="ti ti-line-scan"></i><span class="nav-text">Check Stud RFID</span>
        </a>
    </li>

    {{-- <li>
        <a class="nav-link" href="#">
            <i class="ti ti-users"></i><span class="nav-text">Students Enrolled</span>
        </a>
    </li> --}}

    {{-- <li>
        <a class="nav-link" href="#">
            <i class="ti ti-file"></i><span class="nav-text">Student Good Moral</span>
        </a>
    </li> --}}

    <li class="nav-text-space"><small class="nav-text"></small></li>
    <li class="px-4 py-2"><small class="nav-text text-muted">Reports</small></li>
    <li>
        <a class="nav-link {{ $studinfosActive }}" href="{{ route('studsinfos.index') }}">
            <i class="ti ti-users"></i><span class="nav-text">Students Info</span>
        </a>
    </li>
    <li>
        <a class="nav-link {{ $idIssuanceLogActive }}" href="{{ route('id-issuance-log.index') }}">
            <i class="ti ti-id-badge"></i><span class="nav-text">ID Issuance Log</span>
        </a>
    </li>
    {{-- <li>
        <a class="nav-link" href="#">
            <i class="ti ti-file"></i><span class="nav-text">Event Attendance</span>
        </a>
    </li> --}}
</ul>
