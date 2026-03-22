@php
    $curr_route = request()->route()->getName();

    $kioskadminSchActive = in_array($curr_route, ['adminkioskRead']) ? 'active' : '';
    $kioskbulkSchActive = in_array($curr_route, ['adminbulkkioskRead', 'adminbulkkioskShow']) ? 'active' : '';
    $kioskreportschActive = in_array($curr_route, ['kioskReport']) ? 'active' : '';
@endphp

<ul class="nav flex-column">
    
    <li class="px-4 py-2"><small class="nav-text text-muted">Main Navigation</small></li>
    <li>
        <a class="nav-link {{ $kioskadminSchActive }}" href="{{ route('adminkioskRead') }}">
            <i class="ti ti-users"></i><span class="nav-text">Kiosk User</span>
        </a>
    </li>
    
    <li>
        <a class="nav-link {{ $kioskbulkSchActive }}" href="{{ route('adminbulkkioskRead') }}">
            <i class="ti ti-user-cog"></i><span class="nav-text">Bulk Generation</span>
        </a>
    </li>
    
    <li class="nav-text-space"><small class="nav-text"></small></li>
    <li class="px-4 py-2"><small class="nav-text text-muted">Reports</small></li>

    <li>
        <a class="nav-link {{ $kioskreportschActive }}" href="{{ route('kioskReport') }}">
            <i class="ti ti-chart"></i><span class="nav-text">Kiosk Reports</span>
        </a>
    </li>
</ul>