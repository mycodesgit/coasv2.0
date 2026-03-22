@php
    $curr_route = request()->route()->getName();

    $dashCashActive = in_array($curr_route, ['cashiering-index']) ? 'active' : '';
    $orActive = in_array($curr_route, ['list_orRead', 'listsearch_orRead']) ? 'active' : '';
    $orEditActive = in_array($curr_route, ['listedit_orRead', 'listsearchedit_orRead']) ? 'active' : '';
    $orperdayActive = in_array($curr_route, ['listorperdayRead', 'listsearch_orperdayRead']) ? 'active' : '';
    $orpermonthActive = in_array($curr_route, ['listorpermonthRead', 'listsearch_orpermonthRead']) ? 'active' : '';

@endphp

<ul class="nav flex-column">
    
    <li class="px-4 py-2"><small class="nav-text text-muted">Main Navigation</small></li>

    <li>
        <a class="nav-link {{ $dashCashActive }}" href="{{ route('cashiering-index') }}">
            <i class="ti ti-home"></i><span class="nav-text">Dashboard</span>
        </a>
    </li>
    
    <li>
        <a class="nav-link {{ $orActive }}" href="{{ route('list_orRead') }}">
            <i class="ti ti-receipt"></i><span class="nav-text">Official Receipt</span>
        </a>
    </li>
    
    <li>
        <a class="nav-link {{ $orEditActive }}" href="{{ route('listedit_orRead') }}">
            <i class="ti ti-receipt-pound"></i><span class="nav-text">Edit Official Receipt</span>
        </a>
    </li>
    
    <li class="nav-text-space"><small class="nav-text"></small></li>
    <li class="px-4 py-2"><small class="nav-text text-muted">Reports</small></li>

    <li>
        <a class="nav-link {{ $orperdayActive }}" href="{{ route('listorperdayRead') }}">
            <i class="ti ti-calendar-check"></i><span class="nav-text">OR Per Date</span>
        </a>
    </li>
    
    <li>
        <a class="nav-link {{ $orpermonthActive }}" href="{{ route('listorpermonthRead') }}">
            <i class="ti ti-calendar-month"></i><span class="nav-text">OR Per Month</span>
        </a>
    </li>

</ul>