@php
    $current_route=request()->route()->getName();

    $studentdashActive = in_array($current_route, ['index.student']) ? 'active' : '';
    $studentgradesActive = in_array($current_route, ['show.grades']) ? 'active' : '';
    $studentaccntsActive = in_array($current_route, ['show.services', 'index.scheduleclass', 'index.evaluation', 'show.evaluation.rate', 'index.assessmentstudentfees']) ? 'active' : '';
    $preenrolviewActive = in_array($current_route, ['pre.index', 'pre.show']) ? 'active' : '';
@endphp

<ul class="nav flex-column">
    <li class="px-4 py-2">
        <small class="nav-text text-muted">Main</small>
    </li>
    <li>
        <a class="nav-link {{$studentdashActive}}" href="{{ route('index.student') }}">
            <i class="ti ti-layout-grid"></i><span class="nav-text">Dashboard</span>
        </a>
    </li>
    <li>
        <a class="nav-link {{$studentgradesActive}}" href="{{ route('show.grades') }}">
            <i class="ti ti-numbers"></i><span class="nav-text">Grades</span>
        </a>
    </li>
    <li>
        <a class="nav-link {{$studentaccntsActive}}" href="{{ route('show.services') }}">
            <i class="ti ti-server"></i><span class="nav-text">Services</span>
        </a>
    </li>
    <li>
        <a class="nav-link" href="#">
            <i class="ti ti-user"></i><span class="nav-text">Profile</span>
        </a>
    </li>
</ul>