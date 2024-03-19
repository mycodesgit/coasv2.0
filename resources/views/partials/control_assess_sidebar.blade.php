@php
    $curr_route = request()->route()->getName();

    $fundActive = in_array($curr_route, ['fundsRead']) ? 'active' : '';
    $coaActive = in_array($curr_route, ['accountCOARead']) ? 'active' : '';
    $accntAppraisalActive = in_array($curr_route, ['accountAppraisalRead']) ? 'active' : '';
    $studFeeActive = in_array($curr_route, ['searchStudfee', 'list_searchStudfee']) ? 'active' : '';
    $listScholarActive = in_array($curr_route, ['chedscholarRead', 'chedscholarSearch']) ? 'active' : '';
    

@endphp

<div class="ml-2 mr-2 mt-3 mb-3">
    <ul class="list-group">
        <a href="{{ route('fundsRead') }}" class="list-group-item {{ $fundActive }}">Funds</a> 
        <a href="{{ route('accountCOARead') }}" class="list-group-item {{ $coaActive }}">COA Accounts</a> 
        <a href="{{ route('accountAppraisalRead') }}" class="list-group-item {{ $accntAppraisalActive }}">Accounts</a> 
        <a href="{{ route('searchStudfee') }}" class="list-group-item {{ $studFeeActive }}">Student Fee</a>  
        <a href="{{ route('chedscholarRead') }}" class="list-group-item {{ $listScholarActive }}">Scholarship List</a>
        <a href="" class="list-group-item">Student Scholarship</a>
    </ul>
</div>


<div class="page-header ml-2 mr-2 mt-3" style="border-bottom: 1px solid #04401f;">
    <h5>Reports</h5>
</div>
<div class="ml-2 mr-2 mt-3 mb-3">
    <ul class="list-group">
        <a href="" class="list-group-item">Students Scholar Reports</a>
    </ul>
</div>