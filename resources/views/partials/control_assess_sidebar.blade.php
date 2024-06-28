@php
    $curr_route = request()->route()->getName();

    $dashAssessActive = in_array($curr_route, ['assessment-index']) ? 'active' : '';
    $fundActive = in_array($curr_route, ['fundsRead']) ? 'active' : '';
    $coaActive = in_array($curr_route, ['accountCOARead']) ? 'active' : '';
    $accntAppraisalActive = in_array($curr_route, ['accountAppraisalRead']) ? 'active' : '';
    $studFeeActive = in_array($curr_route, ['searchStudfee', 'list_searchStudfee']) ? 'active' : '';

    $studStateAccntActive = in_array($curr_route, ['stateaccntpersem', 'stateaccntpersem_search']) ? 'active' : '';
    $studStateAccntSumActive = in_array($curr_route, ['stateaccntpersum', 'stateaccntpersum_search']) ? 'active' : '';

@endphp

<div class="ml-2 mr-2 mt-3 mb-3">
    <ul class="list-group">
        <a href="{{ route('assessment-index') }}" class="list-group-item {{ $dashAssessActive }}">Dashboard</a>
    </ul>
    <ul class="list-group mt-1">
        <a href="{{ route('fundsRead') }}" class="list-group-item {{ $fundActive }}">Funds</a> 
        <a href="{{ route('accountCOARead') }}" class="list-group-item {{ $coaActive }}">COA Accounts</a> 
        <a href="{{ route('accountAppraisalRead') }}" class="list-group-item {{ $accntAppraisalActive }}">Accounts</a> 
        <a href="{{ route('searchStudfee') }}" class="list-group-item {{ $studFeeActive }}">Student Fee</a>  
    </ul>
</div>


<div class="page-header ml-2 mr-2 mt-3" style="border-bottom: 1px solid #04401f;">
    <h5>Reports <span class="nav-header text-dark text-left" style="font-size: 12pt">of Statement of</span></h5>
</div>
<div class="ml-2 mr-2 mt-3 mb-3">
    <ul class="list-group">
        <a href="{{ route('stateaccntpersem') }}" class="list-group-item {{ $studStateAccntActive }}">Accounts Per Semester</a>
        <a href="" class="list-group-item ">Accounts Per Date</a>
        <a href="{{ route('stateaccntpersum') }}" class="list-group-item {{ $studStateAccntSumActive }}">Accounts Summary</a>
    </ul>
</div>