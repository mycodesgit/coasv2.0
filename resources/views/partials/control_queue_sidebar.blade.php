@php
    $curr_route = request()->route()->getName();

    $queueDashActive = in_array($curr_route, ['queue-dash']) ? 'active' : '';
    $countersActive = in_array($curr_route, ['queue-index']) ? 'active' : '';
    $numbersActive = in_array($curr_route, ['numberRead']) ? 'active' : '';
    $queueActive = in_array($curr_route, ['queueonoff']) ? 'active' : '';

@endphp

<ul class="nav flex-column">

    <li class="px-4 py-2"><small class="nav-text text-muted">Main Navigation</small></li>
    <li>
        <a class="nav-link {{ $queueDashActive }}" href="{{ route('queue-dash') }}">
            <i class="ti ti-table"></i><span class="nav-text">Dashboard</span>
        </a>
    </li>

    <li>
        <a class="nav-link {{ $countersActive }}" href="{{ route('queue-index') }}">
            <i class="ti ti-window"></i><span class="nav-text">Counters</span>
        </a>
    </li>

    <li>
        <a class="nav-link {{ $numbersActive }}" href="{{ route('numberRead') }}">
            <i class="ti ti-numbers"></i><span class="nav-text">Numbers</span>
        </a>
    </li>

    <li>
        <a class="nav-link {{ $queueActive }}" href="{{ route('queueonoff') }}">
            <i class="ti ti-settings"></i><span class="nav-text">Settings</span>
        </a>
    </li>
</ul>
