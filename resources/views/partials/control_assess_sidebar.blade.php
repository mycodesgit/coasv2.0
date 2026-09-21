@php
    $curr_route = request()->route()->getName();

    $dashAssessActive = in_array($curr_route, ['assessment-index']) ? 'active' : '';
    $fundActive = in_array($curr_route, ['fundsRead']) ? 'active' : '';
    $coaActive = in_array($curr_route, ['accountCOARead']) ? 'active' : '';
    $accntAppraisalActive = in_array($curr_route, ['accountAppraisalRead']) ? 'active' : '';
    $studFeeActive = in_array($curr_route, ['searchStudfee', 'list_searchStudfee']) ? 'active' : '';

    $studFeeTemplateActive = in_array($curr_route, ['searchStudfeeTemplate', 'list_searchStudfeetemplate']) ? 'active' : '';

    $studcheckappActive = in_array($curr_route, ['studcheckappraisal.index', 'studcheckappraisal.store']) ? 'active' : '';

    $studStateAccntActive = in_array($curr_route, ['stateaccntpersem', 'stateaccntpersem_search']) ? 'active' : '';
    $studStateAccntStudActive = in_array($curr_route, ['stateaccntperstudent', 'stateaccntperstudentid_search', 'stateaccntperstudentname_search']) ? 'active' : '';
    $studStateAccntSumActive = in_array($curr_route, ['stateaccntpersum', 'stateaccntpersum_search']) ? 'active' : '';

    $hebillingActive = in_array($curr_route, ['hebillingRead', 'hebillingRead_search']) ? 'active' : '';

@endphp

<ul class="nav flex-column">

    <li class="px-4 py-2"><small class="nav-text text-muted">Main Navigation</small></li>
    <li>
        <a class="nav-link {{ $dashAssessActive }}" href="{{ route('assessment-index') }}">
            <i class="ti ti-home"></i><span class="nav-text">Dashboard</span>
        </a>
    </li>

    @if(Auth::guard('web')->user()->role == 0)
        <li>
            <a class="nav-link {{ $fundActive }}" href="{{ route('fundsRead') }}">
                <i class="ti ti-credit-card-refund"></i><span class="nav-text">Funds</span>
            </a>
        </li>

        <li>
            <a class="nav-link {{ $coaActive }}" href="{{ route('accountCOARead') }}">
                <i class="ti ti-bookmark"></i><span class="nav-text">COA Accounts</span>
            </a>
        </li>

        <li>
            <a class="nav-link {{ $accntAppraisalActive }}" href="{{ route('accountAppraisalRead') }}">
                <i class="ti ti-address-book"></i><span class="nav-text">School Funds</span>
            </a>
        </li>
    @endif

    <li>
        <a class="nav-link {{ $studFeeActive }}" href="{{ route('searchStudfee') }}">
            <i class="ti ti-receipt"></i><span class="nav-text">Student Fee</span>
        </a>
    </li>

    @if(Auth::guard('web')->user()->role == 0)
        <li>
            <a class="nav-link {{ $studFeeTemplateActive }}" href="{{ route('searchStudfeeTemplate') }}">
                <i class="ti ti-receipt-pound"></i><span class="nav-text">Student Fee Template</span>
            </a>
        </li>
    @endif

    <li class="px-4 py-2"><small class="nav-text text-muted">Checking</small></li>
    <li>
        <a class="nav-link {{ $studcheckappActive }}" href="{{ route('studcheckappraisal.index') }}">
            <i class="ti ti-device-laptop"></i>
            <span class="nav-text">Stud. Appraisal</span>
            <span class="badge bg-warning ms-1" id="pendingAppraisalCount">{{ $data['pendCount'] }}</span>
        </a>
    </li>

    <li class="nav-text-space"><small class="nav-text"></small></li>
    <li class="px-4 py-2"><small class="nav-text text-muted">Reports</small></li>

    <li>
        <a class="nav-link {{ $studStateAccntActive }}" href="{{ route('stateaccntpersem') }}">
            <i class="ti ti-user-cog"></i><span class="nav-text">Accounts Per Semester</span>
        </a>
    </li>

    <li>
        <a class="nav-link {{ $studStateAccntStudActive }}" href="{{ route('stateaccntperstudent') }}">
            <i class="ti ti-user-bolt"></i><span class="nav-text">Accounts Per Student</span>
        </a>
    </li>

    <li>
        <a class="nav-link {{ $studStateAccntSumActive }}" href="{{ route('stateaccntpersum') }}">
            <i class="ti ti-checklist"></i><span class="nav-text">Accounts Summary</span>
        </a>
    </li>

    <li>
        <a class="nav-link {{ $hebillingActive }}" href="{{ route('hebillingRead') }}">
            <i class="ti ti-book"></i><span class="nav-text">HE Billing Details</span>
        </a>
    </li>
</ul>
