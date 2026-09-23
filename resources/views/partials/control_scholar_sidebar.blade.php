@php
    $curr_route = request()->route()->getName();

    $dashSchActive = in_array($curr_route, ['scholarship-index']) ? 'active' : '';
    $chedScholarActive = in_array($curr_route, ['chedscholarlist']) ? 'active' : '';
    $uniScholarActive = in_array($curr_route, ['unischolarlist']) ? 'active' : '';
    $allScholarActive = in_array($curr_route, ['allscholarlist']) ? 'active' : '';
    $listStudScholarActive = in_array($curr_route, ['chedstudscholarRead', 'studscholar_searchRead']) ? 'active' : '';

    $enreportSchActive = in_array($curr_route, ['studenscholarreportRead', 'studenscholarreport_searchRead']) ? 'active' : '';
    $enhistoryActive = in_array($curr_route, ['studEnHistory', 'viewsearchStudHistory']) ? 'active' : '';
    $countnumEnrollActive = in_array($curr_route, ['countstudnoenrollee']) ? 'active' : '';
    $regformActive = in_array($curr_route, ['studregformRead', 'listsearch_studregformRead']) ? 'active' : '';
    $gradscholarviewActive = in_array($curr_route, ['scholarstudgradeview', 'scholarstudgradeviewSearch']) ? 'active' : '';


@endphp

<ul class="nav flex-column">

    <li class="px-4 py-2"><small class="nav-text text-muted">Main Navigation</small></li>
    <li>
        <a class="nav-link {{ $dashSchActive }}" href="{{ route('scholarship-index') }}">
            <i class="ti ti-box"></i><span class="nav-text">Dashboard</span>
        </a>
    </li>

    <li>
        <a class="nav-link {{ $chedScholarActive }}" href="{{ route('chedscholarlist') }}">
            <i class="ti ti-app-window"></i><span class="nav-text">CHED Scholarship</span>
        </a>
    </li>

    <li>
        <a class="nav-link {{ $uniScholarActive }}" href="{{ route('unischolarlist') }}">
            <i class="ti ti-building"></i><span class="nav-text">CPSU Scholarship</span>
        </a>
    </li>

    <li>
        <a class="nav-link {{ $allScholarActive }}" href="{{ route('allscholarlist') }}">
            <i class="ti ti-school"></i><span class="nav-text">Scholarship</span>
        </a>
    </li>

    <li>
        <a class="nav-link {{ $listStudScholarActive }}" href="{{ route('chedstudscholarRead') }}">
            <i class="ti ti-users"></i><span class="nav-text">Students Scholarship</span>
        </a>
    </li>

    <li class="nav-text-space"><small class="nav-text"></small></li>
    <li class="px-4 py-2"><small class="nav-text text-muted">Reports</small></li>

    <li>
        <a class="nav-link {{ $enreportSchActive }}" href="{{ route('studenscholarreportRead') }}">
            <i class="ti ti-file"></i><span class="nav-text">Scholar Reports</span>
        </a>
    </li>

    <li>
        <a class="nav-link {{ $enhistoryActive }}" href="{{ route('studEnHistory') }}">
            <i class="ti ti-history"></i><span class="nav-text">Enrollment History</span>
        </a>
    </li>

    <li>
        <a class="nav-link {{ $countnumEnrollActive }}" href="{{ route('countstudnoenrollee') }}">
            <i class="ti ti-checklist"></i><span class="nav-text">Number of Enrollees</span>
        </a>
    </li>

    <li>
        <a class="nav-link {{ $regformActive }}" href="{{ route('studregformRead') }}">
            <i class="ti ti-file-type-pdf"></i><span class="nav-text">Registration Form</span>
        </a>
    </li>

    <li>
        <a class="nav-link {{ $gradscholarviewActive }}" href="{{ route('scholarstudgradeview') }}">
            <i class="ti ti-numbers"></i><span class="nav-text">View Grades</span>
        </a>
    </li>
</ul>
