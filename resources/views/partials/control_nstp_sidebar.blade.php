@php
    $curr_route = request()->route()->getName();

    $dashnstplistActive = in_array($curr_route, ['nstp-index']) ? 'active' : '';
    $cwtslistActive = in_array($curr_route, ['cwts_nstp', 'cwts_nstpresult']) ? 'active' : '';
    $ltslistActive = in_array($curr_route, ['lts_nstp', 'lts_nstpresult']) ? 'active' : '';
    $rotclistActive = in_array($curr_route, ['rotc_nstp', 'rotc_nstpresult']) ? 'active' : '';
    $gradenstpActive = in_array($curr_route, ['gradenstp', 'gradenstp_searchlist', 'gradenstpview']) ? 'active' : '';
@endphp

<ul class="nav flex-column">
    
    <li class="px-4 py-2"><small class="nav-text text-muted">Main Navigation</small></li>
    <li>
        <a class="nav-link {{ $dashnstplistActive }}" href="{{ route('nstp-index') }}">
            <i class="ti ti-home"></i><span class="nav-text">Dashboard</span>
        </a>
    </li>
    
    <li>
        <a class="nav-link {{ $cwtslistActive }}" href="{{ route('cwts_nstp') }}">
            <i class="ti ti-users"></i><span class="nav-text">CWTS Students</span>
        </a>
    </li>

    <li>
        <a class="nav-link {{ $ltslistActive }}" href="{{ route('lts_nstp') }}">
            <i class="ti ti-user-cog"></i><span class="nav-text">LTS Students</span>
        </a>
    </li>
    
    <li>
        <a class="nav-link {{ $rotclistActive }}" href="{{ route('rotc_nstp') }}">
            <i class="ti ti-user-bolt"></i><span class="nav-text">ROTC Students</span>
        </a>
    </li>
    
    <li>
        <a class="nav-link {{ $gradenstpActive }}" href="{{ route('gradenstp') }}">
            <i class="ti ti-numbers"></i><span class="nav-text">Grade Sheet</span>
        </a>
    </li>
</ul>
