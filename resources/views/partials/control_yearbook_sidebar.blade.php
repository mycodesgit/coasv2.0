@php
    $curr_route = request()->route()->getName();

    $dashyearbookActive = in_array($curr_route, ['yearbook-index']) ? 'active' : '';
    $studlistyearbookActive = in_array($curr_route, ['showStudent', 'showStudentResult']) ? 'active' : '';
@endphp

<ul class="nav flex-column">
    
    <li class="px-4 py-2"><small class="nav-text"></small></li>
    <li>
        <a class="nav-link {{ $dashyearbookActive }}" href="{{ route('yearbook-index') }}">
            <i class="ti ti-home"></i><span class="nav-text">Dashboard</span>
        </a>
    </li>

    <li>
        <a class="nav-link {{ $studlistyearbookActive }}" href="{{ route('showStudent') }}">
            <i class="ti ti-users"></i><span class="nav-text">Students</span>
        </a>
    </li>

    <li>
        <a class="nav-link" href="#">
            <i class="ti ti-book"></i><span class="nav-text">Releasing</span>
        </a>
    </li>
</ul>