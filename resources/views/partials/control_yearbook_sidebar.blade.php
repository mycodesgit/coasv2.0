@php
    $curr_route = request()->route()->getName();

    $dashyearbookActive = in_array($curr_route, ['yearbook-index']) ? 'active' : '';
    $studlistyearbookActive = in_array($curr_route, ['showStudent', 'showStudentResult']) ? 'active' : '';
    $studreleaseyearbookActive = in_array($curr_route, ['showRelease', 'showReleaseResult']) ? 'active' : '';
    $yearbookActive = in_array($curr_route, ['yerbokshipment.index']) ? 'active' : '';
    $shipmentActive = in_array($curr_route, ['shipment.index']) ? 'active' : '';
@endphp

<ul class="nav flex-column">

    <li class="px-4 py-2"><small class="nav-text text-muted">Main Navigation</small></li>
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
        <a class="nav-link {{ $studreleaseyearbookActive }}" href="{{ route('showRelease') }}">
            <i class="ti ti-book"></i><span class="nav-text">Releasing</span>
        </a>
    </li>

    <li class="px-4 py-2"><small class="nav-text text-muted">Inventory</small></li>
    <li>
        <a class="nav-link {{ $yearbookActive }}" href="{{ route('yerbokshipment.index') }}">
            <i class="ti ti-books"></i><span class="nav-text">Yearbooks</span>
        </a>
    </li>
    <li>
        <a class="nav-link {{ $shipmentActive }}" href="{{ route('shipment.index') }}">
            <i class="ti ti-truck"></i><span class="nav-text">Shipments</span>
        </a>
    </li>
</ul>
